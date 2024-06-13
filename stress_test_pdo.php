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
