<?php
mysqli_report(MYSQLI_REPORT_OFF);

// Connect to MySQL
$conn = mysqli_connect('localhost', 'root', '');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error() . "\n");
}

// 1. Alter in erp_master if it has erp_uniforms
mysqli_select_db($conn, 'erp_master');
$res = mysqli_query($conn, "SHOW TABLES LIKE 'erp_uniforms'");
if (mysqli_num_rows($res) > 0) {
    echo "Altering erp_uniforms.gender in erp_master...\n";
    $alter = mysqli_query($conn, "ALTER TABLE erp_uniforms MODIFY COLUMN gender VARCHAR(255) NOT NULL");
    if ($alter) {
        echo "Successfully altered erp_uniforms.gender in erp_master!\n";
    } else {
        echo "Failed to alter erp_uniforms.gender in erp_master: " . mysqli_error($conn) . "\n";
    }
}

// 2. Query erp_clients to get all databases
$clients_res = mysqli_query($conn, "SELECT id, database_name FROM erp_clients");
if ($clients_res) {
    while ($client = mysqli_fetch_assoc($clients_res)) {
        $db = $client['database_name'];
        if (!empty($db)) {
            // Select the database and see if erp_uniforms exists
            if (@mysqli_select_db($conn, $db)) {
                $table_res = mysqli_query($conn, "SHOW TABLES LIKE 'erp_uniforms'");
                if ($table_res && mysqli_num_rows($table_res) > 0) {
                    echo "Altering erp_uniforms.gender in {$db}...\n";
                    $alter = mysqli_query($conn, "ALTER TABLE erp_uniforms MODIFY COLUMN gender VARCHAR(255) NOT NULL");
                    if ($alter) {
                        echo "Successfully altered erp_uniforms.gender in {$db}!\n";
                    } else {
                        echo "Failed to alter erp_uniforms.gender in {$db}: " . mysqli_error($conn) . "\n";
                    }
                }
            } else {
                echo "Database {$db} not found locally or unable to select.\n";
            }
        }
    }
}

mysqli_close($conn);
