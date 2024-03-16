<?php

try {
    $dsn = "pgsql:host=mohit-kumar-eet222095-products-server.postgres.database.azure.com port=5432 dbname=mohit-kumar-eet222095-products-database sslmode=require user=pqlsbayksr password=Mohit@1999";
    $conn = new PDO($dsn);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// CREATE operation
if (isset($_POST['create'])) {
    $name = $_POST['name'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];
    $sql = "INSERT INTO products (name, amount, description) VALUES (:name, :amount, :description)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':amount', $amount);
    $stmt->bindParam(':description', $description);
    if ($stmt->execute()) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// READ operation
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

// UPDATE operation
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];
    $sql = "UPDATE products SET name=:name, amount=:amount, description=:description WHERE id=:id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':amount', $amount);
    $stmt->bindParam(':description', $description);
    if ($stmt->execute()) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// DELETE operation
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM products WHERE id=:id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    if ($stmt->execute()) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>CRUD Operations</title>
</head>
<body>

<h2>Create Product</h2>
<form method="post" action="">
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
<table border="1">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Amount</th>
        <th>Description</th>
        <th>Action</th>
    </tr>
    <?php
    if ($result->rowCount() > 0) {
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['amount'] . "</td>";
            echo "<td>" . $row['description'] . "</td>";
            echo "<td>";
            echo "<form method='post' action=''>";
            echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
            echo "<input type='text' name='name' value='" . $row['name'] . "'>";
            echo "<input type='text' name='amount' value='" . $row['amount'] . "'>";
            echo "<textarea name='description'>" . $row['description'] . "</textarea>";
            echo "<input type='submit' name='update' value='Update'>";
            echo "<input type='submit' name='delete' value='Delete'>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No products found</td></tr>";
    }
    ?>
</table>

</body>
</html>

<?php
// Close connection
$conn = null;
?>

