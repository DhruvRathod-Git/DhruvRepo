<?php
include '../database.php';

$errors = [];
$product_name = $description = $price = $category = $quantity = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $product_name = trim($_POST['product_name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $quantity = trim($_POST['quantity'] ?? '');


    if (empty($product_name)) {
        $errors['product_name'] = "Product Name is required.";
    } elseif (strlen($product_name) < 2) {
        $errors['product_name'] = "Product Name must be at least 2 characters.";
    }

    if ($quantity === '' || !filter_var($quantity, FILTER_VALIDATE_INT, ["options" => ["min_range" => 0]])) {
        $errors['quantity'] = "Quantity must be a valid non-negative integer.";
    }

    if ($price === '' || !filter_var($price, FILTER_VALIDATE_FLOAT) || $price <= 0) {
        $errors['price'] = "Price must be a valid positive number.";
    }

    if (empty($description)) {
        $errors['description'] = "Product Description is required.";
    } elseif (strlen($description) > 100) {
        $errors['description'] = "Product Description must be under 100 characters.";
    }

    if (empty($category)) {
        $errors['category'] = "Category is required.";
    }

    if (empty($errors)) {
        $sql = "INSERT INTO products (product_name, description, price, category, quantity)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Database Prepare Error: " . htmlspecialchars($conn->error));
        }

        $stmt->bind_param(
            "ssdsi",
            $product_name,
            $description,
            $price,
            $category,
            $quantity
        );

        if ($stmt->execute()) {
            header('Location: index.php');
            exit();
        } else {
            echo "Database Error: " . htmlspecialchars($stmt->error);
        }

        $stmt->close();
    }
}

$sql = "SELECT * FROM products";
$result = $conn->query($sql);
$products = $result->fetch_all(MYSQLI_ASSOC);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="icon" type="image/x-icon" href="vnnovate.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container py-5">

        <div class="text-center mb-4">
            <h1 class="fw-bold text-success">
                <i class="bi bi-box-seam me-2"></i>Add Product
            </h1>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form method="post" action="" enctype="multipart/form-data">

                    <div class="mb-3">
                        <label for="product_name" class="form-label fw-semibold">Product Name</label>
                        <input type="text" class="form-control" id="product_name" name="product_name"
                            placeholder="Enter product name"
                            value="<?php echo htmlspecialchars($product_name ?? ''); ?>">
                        <?php if (isset($errors['product_name'])): ?>
                        <div class="text-danger fw-semibold small mt-1">
                            <?php echo $errors['product_name']; ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label fw-semibold">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"
                            placeholder="Enter product description"><?php echo htmlspecialchars($description ?? ''); ?></textarea>
                        <?php if (isset($errors['description'])): ?>
                        <div class="text-danger fw-semibold small mt-1">
                            <?php echo $errors['description']; ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label fw-semibold">Price</label>
                        <input type="number" step="0.01" class="form-control" id="price" name="price"
                            placeholder="Enter product price" value="<?php echo htmlspecialchars($price ?? ''); ?>">
                        <?php if (isset($errors['price'])): ?>
                        <div class="text-danger fw-semibold small mt-1">
                            <?php echo $errors['price']; ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="category" class="form-label fw-semibold">Category</label>
                        <input type="text" class="form-control" id="category" name="category"
                            placeholder="Enter product category"
                            value="<?php echo htmlspecialchars($category ?? ''); ?>">
                        <?php if (isset($errors['category'])): ?>
                        <div class="text-danger fw-semibold small mt-1">
                            <?php echo $errors['category']; ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-4">
                        <label for="quantity" class="form-label fw-semibold">Quantity</label>
                        <input type="number" class="form-control" id="quantity" name="quantity"
                            placeholder="Enter product quantity"
                            value="<?php echo htmlspecialchars($quantity ?? ''); ?>">
                        <?php if (isset($errors['quantity'])): ?>
                        <div class="text-danger fw-semibold small mt-1">
                            <?php echo $errors['quantity']; ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="d-flex justify-content-start gap-3">
                        <button type="submit" class="btn btn-success px-4 fw-semibold">
                            <i class="bi bi-plus-lg me-1"></i> Create Product
                        </button>
                        <a href="index.php" class="btn btn-secondary px-4 fw-semibold">
                            <i class="bi bi-arrow-left-circle me-1"></i> Back
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</body>

</html>
