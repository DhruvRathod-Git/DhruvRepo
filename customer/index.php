<?php 
include('../database.php');
$result = $conn->query("SELECT * FROM customers");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Management</title>
    <link rel="icon" type="image/x-icon" href="vnnovate.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container py-5">

        <div class="text-center mb-4">
            <h1 class="fw-bold text-primary">
                <i class="bi bi-people-fill me-2"></i>Customer Management
            </h1>
        </div>

        <div class="d-flex justify-content-center gap-3 mb-4">
            <a href="../products/index.php" class="btn btn-primary fw-semibold">
                <i class="bi bi-shop-window"></i> Products
            </a>
            <a href="../purchase/index.php" class="btn btn-primary fw-semibold">
                <i class="bi bi-bag-plus"></i> Purchase
            </a>
        </div>

        <hr class="my-5">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold mb-0">Customer List</h3>
            <a href="create.php" class="btn btn-success fw-semibold">
                <i class="bi bi-plus-lg"></i> Add Customer
            </a>
        </div>

        <div class="table-responsive shadow-sm rounded">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-primary text-center">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>City</th>
                        <th>Actions</th>
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
                        <td><?php echo htmlentities($row['c_name'] ?? ''); ?></td>
                        <td><?php echo $row['email'] ?? ''; ?></td>
                        <td><?php echo $row['number'] ?? ''; ?></td>
                        <td><?php echo $row['city'] ?? ''; ?></td>
                        <td class="text-center">
                            <a href="view.php?id=<?php echo $row['id']; ?>"
                                class="btn btn-sm btn-warning text-white me-1 mb-1">
                                <i class="bi bi-eye"></i> View
                            </a>
                            <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-success me-1 mb-1">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>
                            <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger mb-1"
                                onclick="return confirm('Are you sure you want to delete this customer?');">
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
                        <td colspan="6" class="text-center text-danger fw-bold">No Records Found</td>
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
