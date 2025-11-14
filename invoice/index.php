<?php
include('../database.php');

$invoice_id = $_GET['invoice_id'] ?? $_POST['invoice_id'] ?? '';
if (empty($invoice_id)) {
    echo "Error: Missing or invalid invoice ID";
    exit;
}

$invoice = $conn->prepare("SELECT * FROM invoices
    JOIN customers c ON invoices.customer_id = c.id
    WHERE invoices.invoice_id = ?
    LIMIT 1
");

$invoice->bind_param("s", $invoice_id);
$invoice->execute();
$invoice = $invoice->get_result()->fetch_assoc();

// if (!$invoice) {
//     echo "Invoice not found for code" . htmlspecialchars($invoice_id);
//     exit;
// }

$product_id = json_decode($invoice['product_id'], true);
if (empty($product_id)) {
    echo "No products found for this invoice";
    exit;
}

$placeholders = implode(',', array_fill(0, count($product_id), '?'));
$types = str_repeat('i', count($product_id));

$sql = "SELECT id, product_name, price, quantity FROM products WHERE id IN ($placeholders)";
$stmt = $conn->prepare($sql);

$stmt->bind_param($types, ...$product_id);
$stmt->execute();
$products = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice <?= htmlspecialchars($invoice_id) ?></title>
    <link rel="icon" type="image/x-icon" href="vnnovate.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0"><?= htmlspecialchars($invoice_id) ?></h4>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <h5 style="margin-bottom: 20px; font-weight: bold;">Customer's Information</h5>
                    <p>
                        <strong>Name:</strong> <?= htmlspecialchars($invoice['c_name'] ?? 'N/A') ?><br>
                        <strong>Email:</strong> <?= htmlspecialchars($invoice['email'] ?? 'N/A') ?><br>
                        <strong>Number:</strong> <?= htmlspecialchars($invoice['number'] ?? 'N/A') ?><br>
                        <strong>City:</strong> <?= htmlspecialchars($invoice['city'] ?? 'N/A') ?><br>
                        <strong>Date:</strong>
                        <?= htmlspecialchars(date('Y-m-d', strtotime($invoice['created_at']))) ?><br>
                        <strong>Time:</strong> <?= htmlspecialchars(date('H:i:s', strtotime($invoice['created_at']))) ?>
                    </p>

                </div>

                <table class="table table-bordered mt-4">
                    <thead class="table-dark">
                        <tr style="text-align: center">
                            <th>#</th>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i = 1;
                        foreach ($products as $row): ?>
                        <tr style="text-align: center">
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($row['product_name']) ?></td>
                            <td><?= $row['quantity'] ?></td>
                            <td>₹<?= number_format($row['price'], 2) ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr class="fw-bold" style="text-align: center">
                            <td colspan="3" class="text-end">Total:</td>
                            <td>₹<?= number_format($invoice['total_amount'], 2) ?></td>
                        </tr>
                    </tbody>
                </table>

                <div class="text-end mt-3">
                    <a href="../customer/index.php" class="btn btn-secondary">Back to Home</a>
                    <!-- <button class="btn btn-success" onclick="window.print()">Print Invoice</button> -->
                </div>
            </div>
        </div>
    </div>
</body>

</html>
