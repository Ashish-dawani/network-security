<?php
include 'db_connect.php'; // Include the file containing database connection details

// Check if the connection is successful
if ($conn) {
    echo "Connected successfully to the database.";
} else {
    echo "Failed to connect to the database.";
}
?>
