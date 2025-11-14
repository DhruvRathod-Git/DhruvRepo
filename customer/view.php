<?php

include('../database.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM customers WHERE id = $id";
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
    <title>View Customer</title>
    <link rel="icon" type="image/x-icon" href="vnnovate.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="container py-5">

        <div class="text-center mb-4">
            <h1 class="fw-bold text-primary">
                <i class="bi bi-person-vcard me-2"></i>Customer Details
            </h1>
        </div>

            <div class="card-body p-4">
                <table class="table table-borderless align-middle mb-0">
                    <tbody>
                        <tr>
                            <th class="text-secondary" width="40%">Customer Name:</th>
                            <td class="fw-semibold">
                                <?php echo htmlentities($row['c_name'] ?? ''); ?>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-secondary">Email:</th>
                            <td class="fw-semibold">
                                <?php echo htmlentities($row['email'] ?? ''); ?>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-secondary">Phone Number:</th>
                            <td class="fw-semibold">
                                <?php echo htmlentities($row['number'] ?? ''); ?>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-secondary">City:</th>
                            <td class="fw-semibold">
                                <?php echo htmlentities($row['city'] ?? ''); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <a href="index.php" class="btn btn-secondary px-4 fw-semibold mt-2">
                    <i class="bi bi-arrow-left-circle me-1"></i> Back
                </a>

            </div>
    </div>

</body>

</html>
