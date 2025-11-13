<?php
include('../database.php');
session_start();

// Initialize cart session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Fetch customers and products
$customers = $conn->query("SELECT id, c_name FROM customers");
$products = $conn->query("SELECT id, product_name, price FROM products");

// Handle product selection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['selected_products'])) {
    $selected_products = $_POST['selected_products'];
    $customer_id = intval($_POST['customer_id']);

    $_SESSION['cart'] = [];

    foreach ($selected_products as $product_id) {
        $stmt = $conn->prepare("SELECT product_name, price FROM products WHERE id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $product = $res->fetch_assoc();

        if ($product) {
            $_SESSION['cart'][] = [
                'product_id'   => $product_id,
                'product_name' => $product['product_name'],
                'price'        => $product['price'],
                'customer_id'  => $customer_id
            ];
        }
    }

    header("Location: viewcart.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Products List</title>
    <link rel="icon" type="image/x-icon" href="vnnovate.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h1 class="text-center mb-4">Available Products</h1>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label fw-bold">Select Customer</label>
                <select name="customer_id" class="form-select mb-3" style="width: 250px;" required>
                    <option value="">-- Select Customer --</option>
                    <?php while ($c = $customers->fetch_assoc()): ?>
                    <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['c_name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <table class="table table-bordered text-center bg-white align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Select</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($products && $products->num_rows > 0): $cnt = 1; ?>
                    <?php while ($row = $products->fetch_assoc()): ?>
                    <tr>
                        <td><?= $cnt++ ?></td>
                        <td><?= htmlspecialchars($row['product_name']) ?></td>
                        <td><?= number_format($row['price'], 2) ?></td>
                        <td>
                            <input type="checkbox" name="selected_products[]" value="<?= $row['id'] ?>"
                                class="product-checkbox" data-price="<?= $row['price'] ?>">
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-danger fw-bold">No Products Found</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <h4>Total: ₹<span id="totalPrice">0.00</span></h4>
                <div>
                    <a href="../customer/index.php" class="btn btn-danger fw-bold me-2">
                        <i class="bi bi-arrow-left"></i> Go Back
                    </a>
                    <button type="submit" class="btn btn-primary fw-bold">
                        <i class="bi bi-cart-check me-1"></i> Add To Cart
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const checkboxes = document.querySelectorAll(".product-checkbox");
            const totalPriceEl = document.getElementById("totalPrice");
            let total = 0;

            checkboxes.forEach(chk => {
                chk.addEventListener("change", () => {
                    const price = parseFloat(chk.dataset.price);
                    total += chk.checked ? price : -price;
                    totalPriceEl.textContent = total.toFixed(2);
                });
            });
        });
    </script>
</body>

</html>
