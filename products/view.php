<?php

include('../database.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM products WHERE id = $id";
}            
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
    } else {
        echo "<h2 style='color:red; text-align:center;'>Record not found!</h2>";
        // exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Product</title>
  <link rel="icon" type="image/x-icon" href="vnnovate.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link rel="stylesheet" href="style.css">
</head>

<body class="bg-light" style="font-weight:bold;">

    <div class="container" style="text-align:center;">
        <h1 style="color:blue;"><i class="bi bi-box-seam me-2"></i> Product Details</h1>

        <table style="font-weight:semibold;">
            <tr>
                <td>Product Name:</td>
                <td><?php echo htmlentities($row['product_name'] ?? ''); ?></td>
            </tr>
            <tr>
                <td>Description:</td>
                <td><?php echo htmlentities($row['description'] ?? ''); ?></td>
            </tr>
            <tr>
                <td>Product Price:</td>
                <td><?php echo '$' . htmlentities($row['price'] ?? ''); ?></td>
            </tr>
            <tr>
                <td>Product Category:</td>
                <td><?php echo '$' . htmlentities($row['category'] ?? ''); ?></td>
            </tr>
            <tr>
                <td>Product Qty:</td>
                <td><?php echo htmlentities($row['quantity'] ?? ''); ?></td>
            </tr>
        </table>

        <input type="button" onclick="window.location.href = 'index.php';" value="Back" 
            style="padding: 10px; border: none; cursor: pointer; width: 200px; font-weight:bold; margin-top:20px; margin-right:1000px; background-color:gray; color: white;">
    </div>

</body>

</html>