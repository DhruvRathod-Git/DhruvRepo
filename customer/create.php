<?php
include '../database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $errors = [];

    $c_name = trim($_POST['c_name'] ?? '');
    $email  = trim($_POST['email'] ?? '');
    $number = trim($_POST['number'] ?? '');
    $city   = trim($_POST['city'] ?? '');

    if (empty($c_name)) {
        $errors['c_name'] = "Customer Name is required";
    } elseif (strlen($c_name) < 2) {
        $errors['c_name'] = "Customer Name must be at least 2 characters";
    }

    if (empty($email)) {
        $errors['email'] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format";
    }

    if (empty($number)) {
        $errors['number'] = "Phone Number is required";
    } elseif (strlen($number) < 10) {
        $errors['number'] = "Phone Number must be at least 10 characters";
    }

    if (empty($city)) {
        $errors['city'] = "City is required";
    } elseif (strlen($city) < 2) {
        $errors['city'] = "City must be at least 2 characters";
    }

    if (empty($errors)) {
        $sql = "INSERT INTO customers (c_name, email, number, city)
                VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        $stmt->bind_param("ssss", $c_name, $email, $number, $city);

        if ($stmt->execute()) {
            header('Location: index.php');
            exit();
        } else {
            echo "Database Error: " . htmlspecialchars($stmt->error);
        }
    }
}


    $sql = "SELECT * FROM customers";
        $result = $conn->query($sql);
        $products = $result->fetch_all(MYSQLI_ASSOC);   
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Customer</title>
    <link rel="icon" type="image/x-icon" href="vnnovate.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container py-5">

        <div class="text-center mb-4">
            <h1 class="fw-bold text-success">
                <i class="bi bi-person-plus-fill me-2"></i>Add Customer
            </h1>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form method="post" action="" enctype="multipart/form-data" novalidate>

                    <div class="mb-3">
                        <label for="c_name" class="form-label fw-semibold">Customer Name</label>
                        <input type="text" class="form-control" id="c_name" name="c_name"
                            placeholder="Enter customer name" value="<?php echo htmlspecialchars($c_name ?? ''); ?>">
                        <?php if (isset($errors['c_name'])): ?>
                        <div class="text-danger fw-semibold small mt-1">
                            <?php echo $errors['c_name']; ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                            placeholder="Enter customer email" value="<?php echo htmlspecialchars($email ?? ''); ?>">
                        <?php if (isset($errors['email'])): ?>
                        <div class="text-danger fw-semibold small mt-1">
                            <?php echo $errors['email']; ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="number" class="form-label fw-semibold">Phone Number</label>
                        <input type="number" class="form-control" id="number" name="number"
                            placeholder="Enter customer phone number"
                            value="<?php echo htmlspecialchars($number ?? ''); ?>">
                        <?php if (isset($errors['number'])): ?>
                        <div class="text-danger fw-semibold small mt-1">
                            <?php echo $errors['number']; ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-4">
                        <label for="city" class="form-label fw-semibold">City</label>
                        <input type="text" class="form-control" id="city" name="city" placeholder="Enter customer city"
                            value="<?php echo htmlspecialchars($city ?? ''); ?>">
                        <?php if (isset($errors['city'])): ?>
                        <div class="text-danger fw-semibold small mt-1">
                            <?php echo $errors['city']; ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="d-flex justify-start gap-2">
                        <button type="submit" class="btn btn-success px-4 fw-semibold">
                            <i class="bi bi-check-circle me-1"></i> Create Customer
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
