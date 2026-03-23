import argparse
import datetime
import json
import os
import re
import sys
from typing import List, Dict, Any, Tuple, Optional

def read_file(path: str) -> str:
    with open(path, "r", encoding="utf-8", errors="ignore") as f:
        return f.read()

def normalize(s: Optional[str]) -> str:
    if s is None:
        return ""
    x = s.strip().lower()
    x = re.sub(r"[\s\.\-_,]+", " ", x)
    x = re.sub(r"[^a-z0-9 ]+", "", x)
    x = re.sub(r"\s+", " ", x)
    return x.strip()

STATE_ALIASES = {
    "up": "uttar pradesh",
    "u p": "uttar pradesh",
    "uttarpradesh": "uttar pradesh",
    "mh": "maharashtra",
    "mp": "madhya pradesh",
    "tn": "tamil nadu",
    "tamilnadu": "tamil nadu",
    "wb": "west bengal",
    "odisha": "odisha",
    "orissa": "odisha",
    "ap": "andhra pradesh",
    "ts": "telangana",
    "gj": "gujarat",
    "gujrat": "gujarat",
    "rj": "rajasthan",
    "pn": "punjab",
    "uk": "uttarakhand",
    "ua": "uttarakhand",
    "hp": "himachal pradesh",
    "jk": "jammu and kashmir",
    "jammu kashmir": "jammu and kashmir",
}

def alias_state(n: str) -> str:
    k = normalize(n)
    m = STATE_ALIASES.get(k)
    return m if m else k

def split_values_block(values_block: str) -> List[str]:
    s = values_block.strip()
    out = []
    i = 0
    n = len(s)
    while i < n:
        while i < n and s[i].isspace():
            i += 1
        if i >= n:
            break
        if s[i] != "(":
            i += 1
            continue
        depth = 0
        start = i
        while i < n:
            c = s[i]
            if c == "(":
                depth += 1
            elif c == ")":
                depth -= 1
                if depth == 0:
                    row = s[start:i+1]
                    out.append(row)
                    i += 1
                    while i < n and s[i] in [",", "\n", "\r", " "]:
                        i += 1
                    break
            elif c == "'":
                i += 1
                while i < n:
                    if s[i] == "\\":
                        i += 2
                        continue
                    if s[i] == "'":
                        i += 1
                        break
                    i += 1
                continue
            i += 1
    return out

def parse_row_values(row: str) -> List[Any]:
    assert row[0] == "(" and row[-1] == ")"
    s = row[1:-1]
    out = []
    i = 0
    n = len(s)
    token = ""
    in_string = False
    while i < n:
        c = s[i]
        if in_string:
            if c == "\\":
                if i + 1 < n:
                    token += s[i+1]
                    i += 2
                    continue
            if c == "'":
                out.append(token)
                token = ""
                in_string = False
                i += 1
                while i < n and s[i] in [" ", "\t"]:
                    i += 1
                if i < n and s[i] == ",":
                    i += 1
                continue
            token += c
            i += 1
            continue
        if c == "'":
            in_string = True
            i += 1
            continue
        if c == ",":
            v = token.strip()
            if v.upper() == "NULL":
                out.append(None)
            elif re.fullmatch(r"-?\d+", v or ""):
                out.append(int(v))
            elif re.fullmatch(r"-?\d+\.\d+", v or ""):
                out.append(float(v))
            else:
                out.append(v)
            token = ""
            i += 1
            continue
        token += c
        i += 1
    v = token.strip()
    if v != "" or token != "":
        if v.upper() == "NULL":
            out.append(None)
        elif re.fullmatch(r"-?\d+", v or ""):
            out.append(int(v))
        elif re.fullmatch(r"-?\d+\.\d+", v or ""):
            out.append(float(v))
        else:
            out.append(v)
    return out

def parse_inserts(sql: str, table_names: List[str]) -> Dict[str, List[Dict[str, Any]]]:
    out: Dict[str, List[Dict[str, Any]]] = {t: [] for t in table_names}
    pattern = re.compile(r"INSERT\s+INTO\s+`?([A-Za-z0-9_]+)`?\s*\((.*?)\)\s*VALUES\s*(.*?);", re.IGNORECASE | re.DOTALL)
    for m in pattern.finditer(sql):
        t = m.group(1)
        cols_str = m.group(2)
        vals_block = m.group(3)
        if t not in out:
            continue
        cols = [c.strip().strip("`") for c in cols_str.split(",")]
        rows = split_values_block(vals_block)
        for r in rows:
            vals = parse_row_values(r)
            if len(vals) != len(cols):
                continue
            row = {}
            for i, c in enumerate(cols):
                row[c] = vals[i]
            out[t].append(row)
    return out

def parse_all_inserts(sql: str) -> List[Dict[str, Any]]:
    out: List[Dict[str, Any]] = []
    pattern = re.compile(r"INSERT\s+INTO\s+`?([A-Za-z0-9_]+)`?\s*\((.*?)\)\s*VALUES\s*(.*?);", re.IGNORECASE | re.DOTALL)
    for m in pattern.finditer(sql):
        t = m.group(1)
        cols_str = m.group(2)
        vals_block = m.group(3)
        cols = [c.strip().strip("`") for c in cols_str.split(",")]
        rows_dicts = []
        rows = split_values_block(vals_block)
        for r in rows:
            vals = parse_row_values(r)
            if len(vals) != len(cols):
                continue
            row = {}
            for i, c in enumerate(cols):
                row[c] = vals[i]
            rows_dicts.append(row)
        out.append({"table": t, "columns": cols, "rows": rows_dicts})
    return out

def build_state_maps(rows: List[Dict[str, Any]]) -> Dict[int, Dict[str, Any]]:
    out = {}
    for r in rows:
        rid = r.get("id")
        name = r.get("name") or r.get("state_name") or r.get("name_en")
        out[int(rid)] = {"id": int(rid), "name": str(name) if name is not None else ""}
    return out

def build_city_maps(rows: List[Dict[str, Any]]) -> Dict[int, Dict[str, Any]]:
    out = {}
    for r in rows:
        rid = r.get("id")
        name = r.get("name") or r.get("city_name") or r.get("name_en")
        state_id = r.get("state_id") or r.get("stateid") or r.get("stateId")
        out[int(rid)] = {"id": int(rid), "name": str(name) if name is not None else "", "state_id": int(state_id) if state_id is not None else None}
    return out

def build_board_maps(rows: List[Dict[str, Any]]) -> Dict[int, Dict[str, Any]]:
    out = {}
    for r in rows:
        rid = r.get("id")
        name = r.get("board_name") or r.get("name")
        out[int(rid)] = {"id": int(rid), "name": str(name) if name is not None else ""}
    return out

def build_school_rows(rows: List[Dict[str, Any]], states: Dict[int, Dict[str, Any]], cities: Dict[int, Dict[str, Any]]) -> Dict[int, Dict[str, Any]]:
    out = {}
    for r in rows:
        rid = int(r.get("id"))
        name = r.get("school_name") or r.get("name")
        addr = r.get("address") or ""
        phone = r.get("phone") or r.get("contact") or ""
        email = r.get("email") or ""
        sid = r.get("state_id") or r.get("stateid") or r.get("stateId")
        cid = r.get("city_id") or r.get("cityid") or r.get("cityId")
        sname = states.get(int(sid))["name"] if sid and int(sid) in states else r.get("state_name") or ""
        cname = cities.get(int(cid))["name"] if cid and int(cid) in cities else r.get("city_name") or ""
        out[rid] = {"id": rid, "school_name": str(name) if name is not None else "", "address": str(addr), "phone": str(phone), "email": str(email), "state_name": str(sname), "city_name": str(cname)}
    return out

def build_school_images(rows: List[Dict[str, Any]]) -> Dict[int, List[Dict[str, Any]]]:
    out: Dict[int, List[Dict[str, Any]]] = {}
    for r in rows:
        sid = r.get("school_id") or r.get("schoolid")
        if sid is None:
            continue
        p = r.get("image_path") or r.get("path") or r.get("image") or r.get("filename")
        primary = r.get("is_primary") or r.get("is_primary_image") or 0
        sid = int(sid)
        if sid not in out:
            out[sid] = []
        out[sid].append({"image_path": str(p) if p is not None else "", "is_primary": 1 if str(primary) == "1" else 0})
    return out

def build_school_board_mapping(rows: List[Dict[str, Any]]) -> List[Tuple[int, int]]:
    out = []
    for r in rows:
        sid = r.get("school_id") or r.get("schoolid")
        bid = r.get("board_id") or r.get("boardid")
        if sid is None or bid is None:
            continue
        out.append((int(sid), int(bid)))
    return out

def vendor_states_from_sql(sql: str) -> Dict[str, int]:
    tables = parse_inserts(sql, ["states"])
    st = tables.get("states", [])
    out = {}
    for r in st:
        name = r.get("name") or r.get("state_name")
        if name is None:
            continue
        out[normalize(name)] = int(r.get("id"))
    return out

def vendor_cities_from_sql(sql: str) -> Dict[Tuple[str, Optional[int]], int]:
    tables = parse_inserts(sql, ["cities"])
    ct = tables.get("cities", [])
    out = {}
    for r in ct:
        name = r.get("name") or r.get("city_name")
        sid = r.get("state_id")
        k = (normalize(name) if name else "", int(sid) if sid is not None else None)
        out[k] = int(r.get("id"))
    return out

def vendor_boards_from_db(conn, vendor_id: int) -> Dict[str, int]:
    cur = conn.cursor()
    cur.execute("SELECT id, board_name FROM erp_school_boards WHERE vendor_id=%s OR vendor_id IS NULL", (vendor_id,))
    out = {}
    for rid, name in cur.fetchall():
        out[normalize(name)] = int(rid)
    cur.close()
    return out

def vendor_states_from_db(conn) -> Dict[str, int]:
    cur = conn.cursor()
    cur.execute("SELECT id, name FROM states")
    out = {}
    for rid, name in cur.fetchall():
        out[normalize(name)] = int(rid)
    cur.close()
    return out

def vendor_cities_from_db(conn) -> Dict[Tuple[str, Optional[int]], int]:
    cur = conn.cursor()
    cur.execute("SELECT id, name, state_id FROM cities")
    out = {}
    for rid, name, sid in cur.fetchall():
        out[(normalize(name), int(sid) if sid is not None else None)] = int(rid)
    cur.close()
    return out

def ensure_board(conn, vendor_id: int, name: str, boards_map: Dict[str, int]) -> int:
    key = normalize(name)
    if key in boards_map:
        return boards_map[key]
    cur = conn.cursor()
    cols = get_columns(conn, "erp_school_boards")
    fields = []
    values = []
    if "vendor_id" in cols:
        fields.append("vendor_id"); values.append(vendor_id)
    if "board_name" in cols:
        fields.append("board_name"); values.append(name)
    elif "name" in cols:
        fields.append("name"); values.append(name)
    if "status" in cols:
        fields.append("status"); values.append("active")
    if "created_at" in cols:
        fields.append("created_at"); values.append(datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S"))
    if "updated_at" in cols:
        fields.append("updated_at"); values.append(datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S"))
    sql = "INSERT INTO erp_school_boards (" + ",".join(fields) + ") VALUES (" + ",".join(["%s"]*len(fields)) + ")"
    cur.execute(sql, tuple(values))
    bid = cur.lastrowid
    conn.commit()
    cur.close()
    boards_map[key] = bid
    return bid

def resolve_state_id(states_map: Dict[str, int], name: str) -> Optional[int]:
    n = alias_state(name)
    return states_map.get(n)

def resolve_city_id(cities_map: Dict[Tuple[str, Optional[int]], int], state_id: Optional[int], name: str) -> Optional[int]:
    n = normalize(name)
    if state_id is not None:
        cid = cities_map.get((n, state_id))
        if cid:
            return cid
    for (cn, sid), cid in cities_map.items():
        if cn == n:
            return cid
    return None

def insert_school(conn, vendor_id: int, s: Dict[str, Any], state_id: Optional[int], city_id: Optional[int]) -> int:
    now = datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    cur = conn.cursor()
    cols = get_columns(conn, "erp_schools")
    if "state_id" in cols and state_id is None:
        state_id = get_fallback_state_id(conn)
    if "city_id" in cols and city_id is None:
        city_id = get_fallback_city_id(conn, state_id)
    fields = []
    values = []
    if "vendor_id" in cols:
        fields.append("vendor_id"); values.append(vendor_id)
    if "school_name" in cols:
        fields.append("school_name"); values.append(s.get("school_name"))
    elif "name" in cols:
        fields.append("name"); values.append(s.get("school_name") or s.get("name"))
    if "address" in cols:
        fields.append("address"); values.append(s.get("address"))
    if "phone" in cols:
        fields.append("phone"); values.append(s.get("phone"))
    if "email" in cols:
        fields.append("email"); values.append(s.get("email"))
    if "state_id" in cols:
        fields.append("state_id"); values.append(state_id)
    if "city_id" in cols:
        fields.append("city_id"); values.append(city_id)
    if "status" in cols:
        fields.append("status"); values.append("active")
    if "created_at" in cols:
        fields.append("created_at"); values.append(now)
    if "updated_at" in cols:
        fields.append("updated_at"); values.append(now)
    sql = "INSERT INTO erp_schools (" + ",".join(fields) + ") VALUES (" + ",".join(["%s"]*len(fields)) + ")"
    cur.execute(sql, tuple(values))
    sid = cur.lastrowid
    conn.commit()
    cur.close()
    return sid

def insert_school_image(conn, school_id: int, image_path: str, is_primary: int) -> None:
    cur = conn.cursor()
    cols = get_columns(conn, "erp_school_images")
    fields = []
    values = []
    if "school_id" in cols:
        fields.append("school_id"); values.append(school_id)
    if "image_path" in cols:
        fields.append("image_path"); values.append(image_path)
    elif "image" in cols:
        fields.append("image"); values.append(image_path)
    elif "path" in cols:
        fields.append("path"); values.append(image_path)
    if "is_primary" in cols:
        fields.append("is_primary"); values.append(is_primary)
    sql = "INSERT INTO erp_school_images (" + ",".join(fields) + ") VALUES (" + ",".join(["%s"]*len(fields)) + ")"
    cur.execute(sql, tuple(values))
    conn.commit()
    cur.close()

def insert_school_board_mapping(conn, school_id: int, board_id: int) -> None:
    cur = conn.cursor()
    cols = get_columns(conn, "erp_school_boards_mapping")
    fields = []
    values = []
    if "school_id" in cols:
        fields.append("school_id"); values.append(school_id)
    if "board_id" in cols:
        fields.append("board_id"); values.append(board_id)
    sql = "INSERT INTO erp_school_boards_mapping (" + ",".join(fields) + ") VALUES (" + ",".join(["%s"]*len(fields)) + ")"
    cur.execute(sql, tuple(values))
    conn.commit()
    cur.close()

def get_columns(conn, table: str) -> List[str]:
    cur = conn.cursor()
    cur.execute("SELECT COLUMN_NAME FROM information_schema.columns WHERE table_schema=%s AND table_name=%s", (conn.database, table))
    cols = [r[0] for r in cur.fetchall()]
    cur.close()
    return cols

def get_fallback_state_id(conn) -> Optional[int]:
    cur = conn.cursor()
    cur.execute("SELECT id FROM states ORDER BY name ASC LIMIT 1")
    row = cur.fetchone()
    cur.close()
    return int(row[0]) if row else None

def get_fallback_city_id(conn, state_id: Optional[int]) -> Optional[int]:
    cur = conn.cursor()
    if state_id is not None:
        cur.execute("SELECT id FROM cities WHERE state_id=%s ORDER BY name ASC LIMIT 1", (state_id,))
        row = cur.fetchone()
        if row:
            cur.close()
            return int(row[0])
    cur.execute("SELECT id FROM cities ORDER BY name ASC LIMIT 1")
    row = cur.fetchone()
    cur.close()
    return int(row[0]) if row else None

def main():
    p = argparse.ArgumentParser()
    p.add_argument("--source-sql", default=r"C:\Users\Anas\Documents\IPMsg\AutoSave\shivambo_livedbs.sql")
    p.add_argument("--vendor-mode", choices=["db", "sql"], default="db")
    p.add_argument("--vendor-sql", default=r"d:\xampp\htdocs\erp_books_live\erp_client_shivambooksin.sql")
    p.add_argument("--db-host", default="localhost")
    p.add_argument("--db-user", default="root")
    p.add_argument("--db-pass", default="")
    p.add_argument("--db-name", required=False)
    p.add_argument("--vendor-id", type=int, required=True)
    args = p.parse_args()

    src_text = read_file(args.source_sql)
    tables_to_parse = ["erp_schools","erp_school_boards","erp_school_images","erp_school_boards_mapping","schools","school_boards","school_images","school_boards_mapping","states","cities"]
    parsed = parse_inserts(src_text, tables_to_parse)
    all_inserts = parse_all_inserts(src_text)

    src_states_rows = parsed.get("states") or []
    src_cities_rows = parsed.get("cities") or []
    src_states = build_state_maps(src_states_rows)
    src_cities = build_city_maps(src_cities_rows)

    src_boards_rows = parsed.get("erp_school_boards") or parsed.get("school_boards") or []
    src_boards = build_board_maps(src_boards_rows)

    src_schools_rows = parsed.get("erp_schools") or parsed.get("schools") or []
    src_schools = build_school_rows(src_schools_rows, src_states, src_cities)

    src_images_rows = parsed.get("erp_school_images") or parsed.get("school_images") or []
    src_images = build_school_images(src_images_rows)

    src_map_rows = parsed.get("erp_school_boards_mapping") or parsed.get("school_boards_mapping") or []
    src_map = build_school_board_mapping(src_map_rows)

    if len(src_schools_rows) == 0 or len(src_boards_rows) == 0:
        schools_candidates = []
        boards_candidates = []
        images_candidates = []
        mapping_candidates = []
        for ins in all_inserts:
            cols = set(ins["columns"])
            tname = ins["table"]
            has_school_name = "school_name" in cols or "name" in cols
            has_state = "state_id" in cols or "stateid" in cols or "stateId" in cols or "state_name" in cols
            has_city = "city_id" in cols or "cityid" in cols or "cityId" in cols or "city_name" in cols
            if has_school_name and (has_state or has_city):
                schools_candidates.append(ins)
            has_board_name = "board_name" in cols or ("name" in cols and "board" in normalize(tname))
            if has_board_name:
                boards_candidates.append(ins)
            has_image_path = "image_path" in cols or "image" in cols or "path" in cols or "filename" in cols
            has_school_id = "school_id" in cols or "schoolid" in cols
            if has_image_path and has_school_id:
                images_candidates.append(ins)
            has_board_id = "board_id" in cols or "boardid" in cols
            if has_school_id and has_board_id:
                mapping_candidates.append(ins)
        if len(src_schools_rows) == 0 and len(schools_candidates) > 0:
            src_schools_rows = schools_candidates[0]["rows"]
            src_schools = build_school_rows(src_schools_rows, src_states, src_cities)
        if len(src_boards_rows) == 0 and len(boards_candidates) > 0:
            src_boards_rows = boards_candidates[0]["rows"]
            src_boards = build_board_maps(src_boards_rows)
        if len(src_images_rows) == 0 and len(images_candidates) > 0:
            src_images_rows = images_candidates[0]["rows"]
            src_images = build_school_images(src_images_rows)
        if len(src_map_rows) == 0 and len(mapping_candidates) > 0:
            src_map_rows = mapping_candidates[0]["rows"]
            src_map = build_school_board_mapping(src_map_rows)

    if args.vendor_mode == "sql":
        vend_text = read_file(args.vendor_sql)
        vend_states = vendor_states_from_sql(vend_text)
        vend_cities = vendor_cities_from_sql(vend_text)
        print(json.dumps({"states": len(vend_states), "cities": len(vend_cities)}, ensure_ascii=False))
        print("SQL mode does not write into database. Use --vendor-mode db to import.")
        return

    try:
        import mysql.connector
    except Exception:
        print("Missing mysql-connector-python. Install with: pip install mysql-connector-python")
        sys.exit(1)

    if not args.db_name:
        print("Provide --db-name for vendor database.")
        sys.exit(1)

    conn = mysql.connector.connect(host=args.db_host, user=args.db_user, password=args.db_pass, database=args.db_name)
    vend_states = vendor_states_from_db(conn)
    vend_cities = vendor_cities_from_db(conn)
    vend_boards = vendor_boards_from_db(conn, args.vendor_id)

    school_id_map: Dict[int, int] = {}
    board_id_map: Dict[int, int] = {}

    for sbid, sb in src_boards.items():
        bid = ensure_board(conn, args.vendor_id, sb["name"], vend_boards)
        board_id_map[sbid] = bid

    for src_sid, s in src_schools.items():
        st_id = resolve_state_id(vend_states, s.get("state_name"))
        ct_id = resolve_city_id(vend_cities, st_id, s.get("city_name"))
        sid = insert_school(conn, args.vendor_id, s, st_id, ct_id)
        school_id_map[src_sid] = sid
        imgs = src_images.get(src_sid, [])
        is_primary_set = False
        for img in imgs:
            ip = img.get("is_primary", 0)
            if not is_primary_set and ip != 1:
                ip = 1
                is_primary_set = True
            insert_school_image(conn, sid, img.get("image_path", ""), ip)

    for sm in src_map:
        s_src, b_src = sm
        s_v = school_id_map.get(s_src)
        b_v = board_id_map.get(b_src)
        if s_v and b_v:
            insert_school_board_mapping(conn, s_v, b_v)

    print(json.dumps({"imported_schools": len(school_id_map), "imported_boards": len(board_id_map), "images": sum(len(v) for v in src_images.values())}, ensure_ascii=False))

if __name__ == "__main__":
    main()
