<?php
/**
 * Database Migration Script
 * Alters erp_uniform_size_prices table to support class-specific pricing
 * and replicates existing size prices for each class assigned to the uniform.
 */

// Database credentials
$host = 'localhost';
$user = 'root';
$pass = '';

$conn = mysqli_connect($host, $user, $pass);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error() . "\n");
}

// Find all tenant databases
$db_res = mysqli_query($conn, "SHOW DATABASES LIKE 'erp_client_%'");
$databases = array();
while ($row = mysqli_fetch_row($db_res)) {
    $databases[] = $row[0];
}

echo "Found " . count($databases) . " client databases to process.\n\n";

foreach ($databases as $db) {
    echo "========================================\n";
    echo "Processing database: $db\n";
    echo "========================================\n";
    
    // Select database
    if (!mysqli_select_db($conn, $db)) {
        echo "Error: Could not select database $db. Skipping.\n";
        continue;
    }
    
    // Check if table erp_uniform_size_prices exists
    $table_check = mysqli_query($conn, "SHOW TABLES LIKE 'erp_uniform_size_prices'");
    if (mysqli_num_rows($table_check) == 0) {
        echo "Table erp_uniform_size_prices does not exist in $db. Skipping.\n";
        continue;
    }
    
    // Check if class_id column exists
    $col_check = mysqli_query($conn, "SHOW COLUMNS FROM erp_uniform_size_prices LIKE 'class_id'");
    $class_id_exists = mysqli_num_rows($col_check) > 0;
    
    if (!$class_id_exists) {
        echo "Adding class_id column to erp_uniform_size_prices...\n";
        $alter_query = "ALTER TABLE erp_uniform_size_prices ADD COLUMN class_id INT(11) UNSIGNED NOT NULL DEFAULT 0 AFTER size_id";
        if (mysqli_query($conn, $alter_query)) {
            echo "Successfully added class_id column.\n";
        } else {
            echo "Error adding class_id: " . mysqli_error($conn) . "\n";
            continue;
        }
    } else {
        echo "class_id column already exists in erp_uniform_size_prices.\n";
    }
    
    // Manage indexes - drop old unique index first to avoid duplicate key errors during replication
    // Check if idx_uniform_size exists
    $idx_check = mysqli_query($conn, "SHOW INDEX FROM erp_uniform_size_prices WHERE Key_name = 'idx_uniform_size'");
    if (mysqli_num_rows($idx_check) > 0) {
        echo "Dropping index idx_uniform_size...\n";
        if (mysqli_query($conn, "ALTER TABLE erp_uniform_size_prices DROP INDEX idx_uniform_size")) {
            echo "Dropped index idx_uniform_size.\n";
        } else {
            echo "Error dropping index idx_uniform_size: " . mysqli_error($conn) . "\n";
        }
    }
    
    // Perform data migration for existing rows (those with class_id = 0)
    echo "Checking for existing size prices to migrate...\n";
    $prices_res = mysqli_query($conn, "SELECT * FROM erp_uniform_size_prices WHERE class_id = 0");
    $prices_to_migrate = array();
    while ($row = mysqli_fetch_assoc($prices_res)) {
        $prices_to_migrate[] = $row;
    }
    
    echo "Found " . count($prices_to_migrate) . " price entries with class_id = 0.\n";
    
    if (count($prices_to_migrate) > 0) {
        $migrated_count = 0;
        $inserted_count = 0;
        
        foreach ($prices_to_migrate as $price) {
            $uniform_id = $price['uniform_id'];
            $price_id = $price['id'];
            
            // Get classes for this uniform
            $uniform_res = mysqli_query($conn, "SELECT class_id FROM erp_uniforms WHERE id = " . intval($uniform_id));
            if ($uniform_res && mysqli_num_rows($uniform_res) > 0) {
                $uniform = mysqli_fetch_assoc($uniform_res);
                $class_ids_str = trim($uniform['class_id']);
                
                if (!empty($class_ids_str)) {
                    $class_ids = array_filter(array_map('intval', explode(',', $class_ids_str)));
                    
                    if (!empty($class_ids)) {
                        // Use the first class ID to update the existing row
                        $first_class_id = array_shift($class_ids);
                        mysqli_query($conn, "UPDATE erp_uniform_size_prices SET class_id = $first_class_id WHERE id = $price_id");
                        $migrated_count++;
                        
                        // For the remaining classes, insert new duplicate price rows
                        foreach ($class_ids as $c_id) {
                            $mrp = floatval($price['mrp']);
                            $selling_price = floatval($price['selling_price']);
                            $school_margin = is_null($price['school_margin']) ? "NULL" : floatval($price['school_margin']);
                            
                            $insert_sql = "INSERT INTO erp_uniform_size_prices (uniform_id, size_id, class_id, mrp, selling_price, school_margin, created_at) 
                                           VALUES ($uniform_id, {$price['size_id']}, $c_id, $mrp, $selling_price, $school_margin, NOW())";
                            
                            if (mysqli_query($conn, $insert_sql)) {
                                $inserted_count++;
                            } else {
                                echo "Error copying price row for uniform $uniform_id, class $c_id: " . mysqli_error($conn) . "\n";
                            }
                        }
                    }
                }
            }
        }
        echo "Migrated $migrated_count existing rows to their first class; inserted $inserted_count new rows for remaining classes.\n";
    }
    
    // Check if idx_uniform_class_size exists
    $new_idx_check = mysqli_query($conn, "SHOW INDEX FROM erp_uniform_size_prices WHERE Key_name = 'idx_uniform_class_size'");
    if (mysqli_num_rows($new_idx_check) == 0) {
        echo "Adding unique index idx_uniform_class_size...\n";
        $index_query = "ALTER TABLE erp_uniform_size_prices ADD UNIQUE KEY idx_uniform_class_size (uniform_id, class_id, size_id)";
        if (mysqli_query($conn, $index_query)) {
            echo "Successfully added unique index idx_uniform_class_size.\n";
        } else {
            echo "Error adding index idx_uniform_class_size: " . mysqli_error($conn) . "\n";
        }
    } else {
        echo "Unique index idx_uniform_class_size already exists.\n";
    }
    
    echo "Database $db processing completed.\n\n";
}

mysqli_close($conn);
echo "All migrations completed successfully!\n";
