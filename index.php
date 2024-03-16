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
            echo "<script>alert('Product Inserted');</script>";
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
            echo "<script>alert('Product Updated');</script>";
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
        echo "<script>alert('Product Deleted');</script>";
    } else {
        echo "<script>alert('Failed to delete product');</script>";
    }
}

// Function to insert product
function insertProduct($name, $amount, $description, $conn) {
    $sql = "INSERT INTO products (name, amount, description) VALUES ('$name', '$amount', '$description')";
    mysqli_query($conn, $sql);
    header("Location: /");
    exit;
}

// Function to update product
function updateProduct($id, $name, $amount, $description, $conn) {
    $sql = "UPDATE products SET name='$name', amount='$amount', description='$description' WHERE id='$id'";
    mysqli_query($conn, $sql);
    header("Location: /");
    exit;
}

// Function to delete product
function deleteProduct($id, $conn) {
    $sql = "DELETE FROM products WHERE id='$id'";
    mysqli_query($conn, $sql);
    header("Location: /");
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Product CRUD</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Additional CSS styles */
        .product-container {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mt-5">Create Product</h2>
        <form class="product-container" method="post" action="index.php">
            <div class="form-group">
                <label for="name">Product Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="amount">Amount:</label>
                <input type="text" class="form-control" id="amount" name="amount" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description"></textarea>
            </div>
            <button type="submit" class="btn btn-primary" name="create">Create</button>
        </form>

        <hr>

        <h2 class="mt-5">Products</h2>
        <?php
        $sql = "SELECT * FROM products";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='product-container'>";
                echo "<p>ID: " . $row['id'] . " - Name: " . $row['name'] . " - Amount: " . $row['amount'] . " - Description: " . $row['description'] . "</p>";
                echo "<button class='btn btn-danger'><a href='?delete_id=" . $row['id'] . "'>Delete</a></button>";
                echo "<button class='btn btn-primary mt-2' id='updateBtn" . $row['id'] . "'>Update</button>";
                echo "<form method='post' action='index.php' class='update-form' id='updateForm" . $row['id'] . "' style='display:none;'>";
                echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
                echo "<input type='text' name='name' value='" . $row['name'] . "' class='form-control'>";
                echo "<input type='text' name='amount' value='" . $row['amount'] . "' class='form-control'>";
                echo "<textarea name='description' class='form-control'>" . $row['description'] . "</textarea>";
                echo "<button type='submit' name='update' class='btn btn-success mt-2'>Update</button>";
                echo "</form>";
                echo "</div>";
                echo "<hr>";
            }
        } else {
            echo "<p>No products found</p>";
        }
        ?>

        <script>
            $(document).ready(function() {
                // Show update form when update button is clicked
                $("button[id^='updateBtn']").click(function() {
                    var productId = $(this).attr('id').replace('updateBtn', '');
                    $("#updateForm" + productId).toggle();
                });
            });
        </script>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
