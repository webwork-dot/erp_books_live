import argparse
import os
import shutil
import mysql.connector

def main():
    p = argparse.ArgumentParser()
    p.add_argument("--db-host", default="localhost")
    p.add_argument("--db-user", default="root")
    p.add_argument("--db-pass", default="")
    p.add_argument("--db-name", required=True)
    p.add_argument("--source-root", required=True, help="Path root that contains 'uploads/school' from old system")
    p.add_argument("--vendor-id", type=int, default=1)
    p.add_argument("--dest-root", default=r"d:\xampp\htdocs\erp_books_live\assets\uploads\vendors")
    args = p.parse_args()

    conn = mysql.connector.connect(host=args.db_host, user=args.db_user, password=args.db_pass, database=args.db_name)
    cur = conn.cursor()
    cur.execute("SELECT i.id, i.school_id, i.image_path FROM erp_school_images i JOIN erp_schools s ON s.id=i.school_id WHERE s.vendor_id=%s", (args.vendor_id,))
    rows = cur.fetchall()
    cur.close()
    conn.close()

    copied = 0
    missing = 0
    dest_base = os.path.join(args.dest_root, str(args.vendor_id), "schools")
    os.makedirs(dest_base, exist_ok=True)

    for _id, school_id, path in rows:
        if not path:
            continue
        # If path already points to vendor dir, just verify file exists
        if path.replace("\\", "/").startswith(f"assets/uploads/vendors/{args.vendor_id}/schools/"):
            filename = os.path.basename(path)
            dest = os.path.join(dest_base, filename)
            if not os.path.isfile(dest):
                missing += 1
            continue
        # Expect old style path 'uploads/school/<filename>'
        rel = path.replace("\\", "/")
        if rel.startswith("uploads/school/"):
            filename = os.path.basename(rel)
            src = os.path.join(args.source_root, "uploads", "school", filename)
        else:
            # fallback: try direct join
            filename = os.path.basename(rel)
            src = os.path.join(args.source_root, rel)
        dest = os.path.join(dest_base, filename)
        if os.path.isfile(src):
            shutil.copy2(src, dest)
            copied += 1
        else:
            missing += 1

    print({"copied": copied, "missing": missing, "dest": dest_base})

if __name__ == "__main__":
    main()
