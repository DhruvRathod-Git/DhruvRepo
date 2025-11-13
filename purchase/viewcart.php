<?php
session_start();
include('../database.php');

if (empty($_SESSION['cart'])) {
    header("Location: ../invoice/index.php");
    exit;
}

$cart = $_SESSION['cart'];
$total = array_sum(array_column($cart, 'price'));

if (isset($_POST['submit'])) {
    try {
        $conn->begin_transaction();

        $purchase_sql = "INSERT INTO purchase (customer_id, product_id, price) VALUES (?, ?, ?)";
        $purchase_stmt = $conn->prepare($purchase_sql);

        foreach ($cart as $item) {
            $purchase_stmt->bind_param("iid", $item['customer_id'], $item['product_id'], $item['price']);
            $purchase_stmt->execute();
        }

        $invoice_id = uniqid('INV-');
        $product_id = json_encode(array_column($cart, 'product_id'));
        $customer_id = $cart[0]['customer_id'];

        $invoice_sql = "INSERT INTO invoices (invoice_id, customer_id, product_id, total_amount, created_at)
                        VALUES (?, ?, ?, ?, NOW())";
        $invoice_stmt = $conn->prepare($invoice_sql);
        $invoice_stmt->bind_param("sssd", $invoice_id, $customer_id, $product_id, $total);
        $invoice_stmt->execute();

        $conn->commit();

        unset($_SESSION['cart']);
        header("Location: ../invoice/index.php?invoice_id=$invoice_id");
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        die("Transaction failed: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Confirmation Cart</title>
    <link rel="icon" type="image/x-icon" href="vnnovate.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h1 class="text-center mb-4">Cart Confirmation</h1>

        <form method="post">
            <table class="table table-bordered text-center bg-white align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Product Name</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; foreach ($cart as $item): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= htmlspecialchars($item['product_name']) ?></td>
                        <td>₹<?= number_format($item['price'], 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="2" class="text-end fw-bold">Total Price:</td>
                        <td class="fw-bold">₹<?= number_format($total, 2) ?></td>
                    </tr>
                </tbody>
            </table>

            <div class="d-flex justify-content-between mt-4">
                <a href="index.php" class="btn btn-secondary">Back</a>
                <button type="submit" name="submit" class="btn btn-primary">Buy Items</button>
            </div>
        </form>
    </div>
</body>

</html>
