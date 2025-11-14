<?php

include('../database.php');

$id = $_GET['id'];
$errors = [];

$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$products = $result->fetch_assoc();


if ($_SERVER['REQUEST_METHOD'] == 'POST') { 

    $product_name = trim($_POST['product_name'] ?? '');
    if (is_null($product_name) || empty($product_name) ) {
        $errors['product_name'] = "Product Name is required";
    }

    $quantity = trim($_POST['quantity'] ?? '');
    if (is_null($quantity) || empty($quantity) ) {
        $errors['quantity'] = "Quantity is required";
    } else {
        if (!is_numeric($quantity)) {
            $errors['quantity'] = "Quantity must be a number";
        }
    }

    $category = trim($_POST['category'] ?? '');
    if (is_null($category) || empty($category) ) {
        $errors['category'] = "Category is required";
    }


    $price = trim($_POST['price'] ?? '');
    if (is_null($price) || empty($price) ) {
        $errors['price'] = "Price is required";
    } else {
        if (!is_numeric($price)) {
            $errors['price'] = "Price must be a number";
        }
    }

    $description = trim($_POST['description'] ?? '');
    if (is_null($description) || empty($description) ) {
        $errors['description'] = "Product Description is required";
    } elseif (strlen($description) > 100) {
        $errors['description'] = "Product Description must be less than 100 characters";
    }

        $sql = "UPDATE products
        SET product_name = ?, description = ?, price = ?, category = ?, quantity = ? WHERE id = ?;";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssisii", 
        $product_name,
        $description,
        $price,
        $category,
        $quantity,
        $id
    );
        if ($stmt->execute()) {
            header('Location: index.php');
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }
    }
// $result = $conn->query($sql);

?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Products</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="vnnovate.png">
</head>

<body>
    <div class="container">
        <h1 style="text-align:center; color:green;">
            Edit Products</span>
        </h1>
        <form method="post" enctype="multipart/form-data">

            <label for="product_name">Product Name:</label><br>
            <input type="text" id="product_name" name="product_name"
                value="<?php echo isset($products['product_name']) ? htmlspecialchars($products['product_name']) : ''; ?>"><br><br>
            <?php
                 if (!empty($errors['product_name'])) {
                 echo "<p style='color: red; font-weight:bold;'>" . htmlspecialchars($errors['product_name']) . "</p>";
                 }
                ?>

            <label for="description">Product Description:</label><br>
            <input type="textarea" id="description" name="description"
                value="<?php echo isset($products['description']) ? htmlspecialchars($products['description']) : ''; ?>"><br><br>
            <?php
                     if (!empty($errors['description'])) {
                     echo "<p style='color: red; font-weight:bold;'>" . htmlspecialchars($errors['description']) . "</p>";
                     }
                    ?>

            <label for="price">Price:</label><br>
            <input type="text" id="price" name="price"
                value="<?php echo isset($products['price']) ? htmlspecialchars($products['price']) : ''; ?>"><br><br>
            <?php
                     if (!empty($errors['price'])) {
                     echo "<p style='color: red; font-weight:bold;'>" . htmlspecialchars($errors['price']) . "</p>";
                     }
                    ?>

            <label for="category">Category:</label><br>
            <input type="text" id="category" name="category"
                value="<?php echo isset($products['category']) ? htmlspecialchars($products['category']) : ''; ?>"><br><br>
            <?php
                     if (!empty($errors['category'])) {
                     echo "<p style='color: red; font-weight:bold;'>" . htmlspecialchars($errors['category']) . "</p>";
                     }
                    ?>

            <label for="quantity">Qty:</label><br>
            <input type="number" id="quantity" name="quantity"
                value="<?php echo isset($products['quantity']) ? htmlspecialchars($products['quantity']) : ''; ?>"><br><br>
            <?php
                 if (!empty($errors['quantity'])) {
                 echo "<p style='color: red; font-weight:bold;'>" . htmlspecialchars($errors['quantity']) . "</p>";
                 }
                ?>

            <input type="submit" value="Update"
                style="padding: 10px 20px; border: none; cursor: pointer; width: 200px; font-weight:bold; background:green; color:white;">

            <input type="button" onclick="window.location.href = 'index.php';" value="Back"
                style="padding: 10px; border: none; cursor: pointer; width: 200px; font-weight:bold;">
        </form>
    </div>
</body>

</html>