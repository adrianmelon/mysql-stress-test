
# Stress Test Script for MySQL Database

## Overview

This PHP script is designed to stress test a MySQL database by opening multiple simultaneous connections and executing a `SLEEP` command. It helps to evaluate how the database handles a high number of concurrent connections and maintains a state of load to observe the system's response under heavy conditions.

## Features

- **Multiple Concurrent Connections**: Opens multiple connections to the database simultaneously.
- **Simulates Load**: Executes `SLEEP` queries to simulate load and keep connections active.
- **Continuous Testing**: Continues to retry connections even after reaching the maximum connection limit, useful for maintaining stress on the database.
- **Configurable**: Adjust the number of connections and sleep duration as needed.

## Prerequisites

- PHP 8.3 or higher
- MySQL database
- Access to the server where the script will be executed

## Script Details

### `stress_test_pdo.php`

This script uses PHP's PDO to manage multiple database connections and execute the `SLEEP` query to keep them active.

### Configuration Parameters

- **Database Host**: The hostname or IP address of the MySQL server.
- **Database Name**: The name of the MySQL database to connect to.
- **User**: The MySQL user with permissions to connect to the database.
- **Password**: The password for the MySQL user.
- **Number of Connections**: The number of simultaneous connections to open.
- **Sleep Duration**: The duration (in seconds) for the `SLEEP` command to keep the connection active.

### Script

```php
<?php

// Database connection configuration
$host = 'localhost';
$db = 'db';
$user = 'user';
$password = 'password';

// SQL query for the SLEEP command in milliseconds
$query = "SELECT SLEEP(0.2);"; // SLEEP for 200 milliseconds

// Number of simultaneous connections desired
$numRequests = 200; // Change this value to the number of connections you want to simulate

while (true) {
    $pdoConnections = [];

    try {
        for ($i = 0; $i < $numRequests; $i++) {
            $dsn = "mysql:host=$host;dbname=$db";
            $pdo = new PDO($dsn, $user, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Execute the query on each connection
            $stmt = $pdo->query($query);

            // Store the PDO connection
            $pdoConnections[] = $pdo;

            echo "Connection $i executing SLEEP(0.2)...
";
        }

        // Sleep for a moment before trying more connections
        sleep(1);

    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage() . "
";
        
        // Wait a bit before retrying to open connections
        sleep(1);
    }

    // Close all PDO connections
    foreach ($pdoConnections as $pdo) {
        $pdo = null;
    }

    // Try to open connections again
    echo "Trying to open connections again...
";
}
```

## How to Use

### Step 1: Upload the Script

Upload the `stress_test_pdo.php` script to your server where you can execute PHP scripts from the command line.

### Step 2: Set Permissions (Optional)

Ensure the script has the necessary permissions to be executed.

```bash
chmod +x stress_test_pdo.php
```

### Step 3: Execute the Script

Run the script from the command line to start the stress test.

```bash
php stress_test_pdo.php
```

### Monitoring the Process

While the script is running, you can monitor your system to observe how it handles the load:

- **CPU and Memory Usage**:
  - Use tools like `htop` or `top` on Linux to monitor the CPU and memory usage.

- **Active Connections in MySQL**:
  - Use the following MySQL command to view active connections:

    ```sql
    SHOW FULL PROCESSLIST;
    ```

- **System Status**:
  - Watch for any impact on system performance and services that depend on MySQL.

## Considerations

- **Test Environment**: Perform these tests in a development or test environment to avoid impacting production.
- **Control the Test**: Be prepared to manually stop the script as it runs indefinitely to maintain the load.
- **Impact on Services**: The test may prevent MySQL from accepting new legitimate connections, affecting services that rely on the database.

## Troubleshooting

- **Too Many Connections**: If you encounter "Too many connections" errors, ensure that the `max_connections` setting in MySQL is appropriately configured for your testing needs.
- **Script Timeout**: Adjust PHP’s execution time settings if the script times out prematurely.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Contributions

Feel free to submit pull requests or issues if you find any bugs or have suggestions for improvements.
