<?php
// Database connection
$host = "mohit-kumar-eet222092-products-crud-server.mysql.database.azure.com";
$port = "3306"; // MySQL default port
$dbname = "mohit-kumar-eet222092-products-crud-database";
$user = "jircpobeas";
$password = "";

try {

    $conn = mysqli_init();
    mysqli_ssl_set($conn,NULL,NULL, "DigiCertGlobalRootCA.crt.pem", NULL, NULL);
    mysqli_real_connect($conn, "mohit-kumar-eet222092-products-crud-server.mysql.database.azure.com", "jircpobeas", "1710BZTOR3P0P4C7$", "mohit-kumar-eet222092-products-crud-database", 3306, MYSQLI_CLIENT_SSL);

    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Run the migration SQL file
    $migrationSQL = file_get_contents('migration.sql');
    $conn->exec($migrationSQL);

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Static record if database connection fails
$products = [
    [
        'id' => 1,
        'name' => 'Static Product',
        'amount' => 10.00,
        'description' => 'This is a static product record.',
    ]
];

// CRUD operations
// Implementation remains the same as before
?>

<!DOCTYPE html>
<html>
<head>
    <title>CRUD Operations</title>
</head>
<body>

<h2>Create Product</h2>
<!-- Form for creating product remains the same -->

<hr>

<h2>Products</h2>
<table border="1">
    <!-- Table for displaying products remains the same -->
</table>

</body>
</html>

<?php
// Close connection
if (isset($conn)) {
    $conn = null;
}
?>

