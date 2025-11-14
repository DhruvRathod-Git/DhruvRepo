<?php

include('../database.php');

$id = $_GET['id'];
$errors = [];

$sql = "SELECT * FROM customers WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$customers = $result->fetch_assoc();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $c_name = trim($_POST['c_name'] ?? '');
    if (is_null($c_name) || empty($c_name) ) {
        $errors['c_name'] = "Customer Name is required";
    }

    $email = trim($_POST['email'] ?? '');
    if (is_null($email) || empty($email) ) {
        $errors['email'] = "Email is required";
    }

    $number = trim($_POST['number'] ?? '');
    if (is_null($number) || empty($number) ) {
        $errors['number'] = "Phone Number is required";
    }

    $city = trim($_POST['city'] ?? '');
    if (is_null($city) || empty($city) ) {
        $errors['city'] = "City is required";
    }

    if (empty($errors)) {
        
        $sql = "UPDATE customers
        SET c_name = ?, email = ?, number = ?, city = ? WHERE id = ?;";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssisi", 
        $c_name,
        $email,
        $number,
        $city,
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
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Customer</title>
    <link rel="icon" type="image/x-icon" href="vnnovate.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container py-5">

        <div class="text-center mb-4">
            <h1 class="fw-bold text-success">
                <i class="bi bi-pencil-square me-2"></i>Edit Customer
            </h1>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">

                <form method="post" enctype="multipart/form-data">

                    <div class="mb-3">
                        <label for="c_name" class="form-label fw-semibold">Customer Name</label>
                        <input type="text" id="c_name" name="c_name" class="form-control"
                            placeholder="Enter customer name"
                            value="<?php echo isset($customers['c_name']) ? htmlspecialchars($customers['c_name']) : ''; ?>">
                        <?php if (!empty($errors['c_name'])): ?>
                        <div class="text-danger fw-semibold small mt-1">
                            <?php echo htmlspecialchars($errors['c_name']); ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <input type="email" id="email" name="email" class="form-control"
                            placeholder="Enter customer email"
                            value="<?php echo isset($customers['email']) ? htmlspecialchars($customers['email']) : ''; ?>">
                        <?php if (!empty($errors['email'])): ?>
                        <div class="text-danger fw-semibold small mt-1">
                            <?php echo htmlspecialchars($errors['email']); ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="number" class="form-label fw-semibold">Phone Number</label>
                        <input type="number" id="number" name="number" class="form-control"
                            placeholder="Enter customer phone number"
                            value="<?php echo isset($customers['number']) ? htmlspecialchars($customers['number']) : ''; ?>">
                        <?php if (!empty($errors['number'])): ?>
                        <div class="text-danger fw-semibold small mt-1">
                            <?php echo htmlspecialchars($errors['number']); ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-4">
                        <label for="city" class="form-label fw-semibold">City</label>
                        <input type="text" id="city" name="city" class="form-control" placeholder="Enter customer city"
                            value="<?php echo isset($customers['city']) ? htmlspecialchars($customers['city']) : ''; ?>">
                        <?php if (!empty($errors['city'])): ?>
                        <div class="text-danger fw-semibold small mt-1">
                            <?php echo htmlspecialchars($errors['city']); ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="d-flex justify-content-start gap-2">
                        <button type="submit" class="btn btn-success px-4 fw-semibold">
                            <i class="bi bi-pencil-square me-1"></i> Update
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
