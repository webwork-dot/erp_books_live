# Schema migrations

Laravel-style DDL migrations applied to `erp_master` and every `erp_clients` database.

## Add a migration

1. Create `database/migrations/YYYY_MM_DD_HHMMSS_description.php`
2. Class name must be `Migration_` + filename (without `.php`)
3. Implement `up($mysqli, $migrator)` and always check table/column existence first
4. Return `array('status' => 'done'|'skipped', 'message' => '...')`

## Run

**Admin URLs (logged in as ERP admin):**
- `/erp-admin/schema-migrations` — status
- `/erp-admin/schema-migrations/run` — run all pending
- `/erp-admin/schema-migrations/run/{migration_name}` — run one

**CLI:**
```bash
php index.php Erp_admin/Schema_migrations/status
php index.php Erp_admin/Schema_migrations/run
php index.php Erp_admin/Schema_migrations/run/2026_09_11_191500_add_school_short_code
```

Status is stored in `erp_master.sys_schema_migrations` (`pending` / `done` / `failed` / `skipped`).
