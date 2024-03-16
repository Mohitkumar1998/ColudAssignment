<?php
// Database connection
$host = "mohit-kumar-eet222092-products-crud-server.mysql.database.azure.com";
$port = "3306";
$dbname = "mohit-kumar-eet222092-products-crud-database";
$user = "jircpobeas";
$password = "1710BZTOR3P0P4C7$";

$conn = mysqli_init();
mysqli_ssl_set($conn, NULL, NULL, "DigiCertGlobalRootCA.crt.pem", NULL, NULL);
mysqli_real_connect($conn, "mohit-kumar-eet222092-products-crud-server.mysql.database.azure.com", "jircpobeas", "1710BZTOR3P0P4C7$", "mohit-kumar-eet222092-products-crud-database", 3306, MYSQLI_CLIENT_SSL);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Run the migration SQL file
$migrationSQL = file_get_contents('migration.sql');
if (mysqli_multi_query($conn, $migrationSQL)) {
    do {
        // Consume all results
        if ($result = mysqli_store_result($conn)) {
            mysqli_free_result($result);
        }
    } while (mysqli_next_result($conn));
} else {
    echo "Error executing migration SQL: " . mysqli_error($conn);
}


if (isset($_POST['create'])) {
    $name = $_POST['name'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];

    if (!empty($name) && !empty($amount) && !empty($description)) {
        if (insertProduct($name, $amount, $description, $conn)) {
            header("Location: /");
            exit;
        } else {
            echo "<script>alert('Failed to insert product');</script>";
        }
    } else {
        echo "<script>alert('Please fill all fields');</script>";
    }
}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];

    if (!empty($id) && !empty($name) && !empty($amount) && !empty($description)) {
        if (updateProduct($id, $name, $amount, $description, $conn)) {
            header("Location: /");
            exit;
        } else {
            echo "<script>alert('Failed to update product');</script>";
        }
    } else {
        echo "<script>alert('Please fill all fields');</script>";
    }
}

if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    if (deleteProduct($id, $conn)) {
        header("Location: /");
        exit;
    } else {
        echo "<script>alert('Failed to delete product');</script>";
    }
}

// Function to insert product
function insertProduct($name, $amount, $description, $conn) {
    $sql = "INSERT INTO products (name, amount, description) VALUES ('$name', '$amount', '$description')";
    return mysqli_query($conn, $sql);
}

// Function to update product
function updateProduct($id, $name, $amount, $description, $conn) {
    $sql = "UPDATE products SET name='$name', amount='$amount', description='$description' WHERE id='$id'";
    return mysqli_query($conn, $sql);
}

// Function to delete product
function deleteProduct($id, $conn) {
    $sql = "DELETE FROM products WHERE id='$id'";
    return mysqli_query($conn, $sql);
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Product CRUD</title>
</head>
<body>
    <h2>Create Product</h2>
    <form method="post" action="index.php">
        <label for="name">Product Name:</label><br>
        <input type="text" id="name" name="name" required><br>
        <label for="amount">Amount:</label><br>
        <input type="text" id="amount" name="amount" required><br>
        <label for="description">Description:</label><br>
        <textarea id="description" name="description"></textarea><br><br>
        <input type="submit" name="create" value="Create">
    </form>

    <hr>

    <h2>Products</h2>
    <?php
    $sql = "SELECT * FROM products";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<p>ID: " . $row['id'] . " - Name: " . $row['name'] . " - Amount: " . $row['amount'] . " - Description: " . $row['description'] . "</p>";
            echo "<button><a href='?delete_id=" . $row['id'] . "'>Delete</a></button>";
            echo "<form method='post' action='index.php'>";
            echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
            echo "<input type='text' name='name' value='" . $row['name'] . "'>";
            echo "<input type='text' name='amount' value='" . $row['amount'] . "'>";
            echo "<textarea name='description'>" . $row['description'] . "</textarea>";
            echo "<input type='submit' name='update' value='Update'>";
            echo "</form>";
            echo "<hr>";
        }
    } else {
        echo "<p>No products found</p>";
    }
    ?>

</body>
</html>

<?php
mysqli_close($conn);
?>

