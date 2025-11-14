<?php
include('../database.php');
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_id = $_POST['customer_id'];
    $product_id = $_POST['product_id'];

    $_SESSION['cart'][] = [
        'customer_id' => $customer_id,
        'product_id' => $product_id
    ];

    header("Location: viewcart.php");
    exit;
}

// Fetch customers and products
$customers = $conn->query("SELECT id, c_name FROM customers");
$products = $conn->query("SELECT id, product_name FROM products");
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add To Cart</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="vnnovate.png">
</head>

<body>
    <div class="container">
        <h1 style="text-align:center; color:green;">Add To Cart</h1>

        <form method="post" action="" enctype="multipart/form-data">

            <div class="mb-3">
                <label class="form-label">Select Customer</label>
                <select name="customer_id" class="form-select" required>
                    <option value="">-- Choose Customer --</option>
                    <?php while($c = $customers->fetch_assoc()): ?>
                    <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['c_name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <label for="product_id" style="margin-top:10px;">Product:</label>
            <select name="product_id" class="form-select" required>
                    <option value="">-- Choose Product --</option>
                    <?php while($p = $products->fetch_assoc()): ?>
                    <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['product_name']) ?></option>
                    <?php endwhile; ?>
                </select>
            <?php if (!empty($errors['product_id'])): ?>
            <p style="color:red;"><?php echo $errors['product_id']; ?></p>
            <?php endif; ?>
            <br><br>

            <input type="submit" value="Add To Cart"
                style="padding: 10px 20px; border: none; cursor: pointer; width: 200px; font-weight:bold; background:green; color:white;">

            <input type="button" onclick="window.location.href='index.php';" value="Back"
                style="padding: 10px; border: none; cursor: pointer; width: 200px; font-weight:bold; background:gray; color:white;">
        </form>
    </div>
</body>

</html>
