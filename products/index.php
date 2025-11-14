<?php 

include('../database.php');

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'id'; 
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'Quantity'; 
$sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'DESC'; 

$sort_by = in_array($sort_by, ['id','product_name', 'price', 'quantity']) ? $sort_by : 'id';
$sort_order = in_array(strtoupper($sort_order), ['ASC', 'DESC']) ? strtoupper($sort_order) : 'DESC';


// for searching
$where_clauses = [];
if (!empty($search)) {
    $escaped_search = $conn->real_escape_string($search);
    $where_clauses[] = "product_name LIKE '%$escaped_search%' OR
    price LIKE '%$escaped_search%' OR quantity LIKE '%$escaped_search%'";
}

// for filters
$where_sql = '';
if (!empty($where_clauses)) {
    $where_sql = " WHERE " . implode(" OR ", $where_clauses);
}

$where_sql = '';
if (!empty($where_clauses)) {
    $where_sql = " WHERE " . implode(" AND ", $where_clauses); // Use AND to combine all filters
}
// $result = $conn->query($sql);

$order_by_sql = " ORDER BY " . $conn->real_escape_string($sort_by) . " " . $conn->real_escape_string($sort_order);
$sql = "SELECT * FROM products" . $where_sql . $order_by_sql;
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <link rel="icon" type="image/x-icon" href="vnnovate.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>

<body>
    <div class="container py-5">

        <div class="text-center mb-4">
            <h1 class="fw-bold text-primary">
                <i class="bi bi-box-seam me-2"></i> Product Management
            </h1>
        </div>

        <div class="d-flex justify-content-center gap-3 mb-4">
            <a href="../customer/index.php" class="btn btn-primary fw-semibold">
                <i class="bi bi-people-fill me-1"></i> Customers
            </a>
            <a href="../purchase/index.php" class="btn btn-primary fw-semibold">
                <i class="bi bi-bag-plus me-1"></i> Purchases
            </a>
        </div>

        <hr class="my-5">

        <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
            <h3 class="fw-bold mb-3 mb-md-0">Products List</h3>
            <a href="create.php" class="btn btn-success fw-semibold">
                <i class="bi bi-plus-lg me-1"></i> Add New Product
            </a>
        </div>

        <form action="index.php" method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search products..."
                    value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
            </div>
            <div class="col-md-3">
                <select name="sort_by" id="sort_by" class="form-select">
                    <option value="" disabled selected>Select Field</option>
                    <option value="id" <?php echo ($sort_by == 'id') ? 'selected' : ''; ?>>Product ID</option>
                    <option value="name" <?php echo ($sort_by == 'name') ? 'selected' : ''; ?>>Product Name</option>
                    <option value="price" <?php echo ($sort_by == 'price') ? 'selected' : ''; ?>>Product Price</option>
                    <option value="quantity" <?php echo ($sort_by == 'quantity') ? 'selected' : ''; ?>>Product Quantity
                    </option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="sort_order" class="form-select">
                    <option value="" disabled selected>Select Order</option>
                    <option value="ASC" <?php echo ($sort_order == 'ASC') ? 'selected' : ''; ?>>Ascending</option>
                    <option value="DESC" <?php echo ($sort_order == 'DESC') ? 'selected' : ''; ?>>Descending</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-outline-primary w-100 fw-semibold">
                    <i class="bi bi-sort-alpha-down me-1"></i> Apply
                </button>
                <a href="index.php" class="btn btn-outline-secondary w-100 fw-semibold">
                    <i class="bi bi-arrow-clockwise me-1"></i> Refresh
                </a>
            </div>
        </form>

        <div class="table-responsive shadow-sm rounded">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-primary text-center">
                    <tr>
                        <th>#</th>
                        <th>Product Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Category</th>
                        <th>Quantity</th>
                        <th style="width: 140px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        $cnt = 1;
                        while ($row = $result->fetch_assoc()) {
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $cnt; ?></td>
                        <td><?php echo htmlentities($row['product_name'] ?? ''); ?></td>
                        <td><?php echo htmlentities($row['description'] ?? ''); ?></td>
                        <td><?php echo '$' . htmlentities($row['price'] ?? ''); ?></td>
                        <td><?php echo htmlentities($row['category'] ?? ''); ?></td>
                        <td><?php echo htmlentities($row['quantity'] ?? ''); ?></td>
                        <td class="text-center">
                            <a href="view.php?id=<?php echo $row['id']; ?>"
                                class="btn btn-sm btn-warning text-white me-1 mb-1">
                                <i class="bi bi-eye"></i> View
                            </a>
                            <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-success me-1 mb-1">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>
                            <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger mb-1"
                                onclick="return confirm('Are you sure you want to delete this product?');">
                                <i class="bi bi-trash3"></i> Delete
                            </a>
                        </td>
                    </tr>
                    <?php
                            $cnt++;
                        }
                    } else {
                    ?>
                    <tr>
                        <td colspan="7" class="text-center text-danger fw-bold">No Records Found</td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>
</body>

</html>
