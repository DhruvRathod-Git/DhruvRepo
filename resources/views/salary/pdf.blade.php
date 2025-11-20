<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Salary Slip</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .company-logo {
            width: 60px;
            height: auto;
            margin-bottom: 10px;
        }

        .salary-box {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 22px;
            background: #ffffff;
        }

        .salary-table th {
            width: 40%;
            background: #f1f1f1;
        }

        h3 {
            font-weight: bold;
        }
    </style>
</head>

<body>

<div class="container my-4">

    <div class="text-center mb-3">
        <img src="{{ public_path('vnnovate.png') }}" alt="Company Logo" class="company-logo">
        <h1 class="mb-0">Vnnovate Solution Pvt Ltd</h1>
    </div>

    <div class="salary-box mb-4 shadow-lg">
        <h2 class="mb-3">Employee Information</h2>

        <table class="table table-bordered salary-table">
            <tbody>
                <tr>
                    <th>Name:</th>
                    <td>{{ $data['name'] }}</td>
                </tr>
                <tr>
                    <th>Email:</th>
                    <td>{{ $data['email'] }}</td>
                </tr>
                <tr>
                    <th>Phone:</th>
                    <td>{{ $data['phone'] }}</td>
                </tr>
                <tr>
                    <th>Address:</th>
                    <td>{{ $data['address'] }}</td>
                </tr>
                <tr>
                    <th>Month:</th>
                    <td>{{ $data['month'] }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="salary-box shadow-lg">
        <h2 class="mb-3">Salary Details</h2>

        <table class="table table-bordered salary-table">
            <tbody>
                <tr>
                    <th>Gross Salary:</th>
                    <td>{{ number_format($data['salary']) }}/-</td>
                </tr>

                <tr>
                    <th>Provident Fund (PF):</th>
                    <td>0.{{ $data['pf'] }}%</td>
                </tr>

                <tr>
                    <th>Leave Deduction:</th>
                    <td>{{ number_format($data['leave_deduction']) }}</td>
                </tr>

                <tr class="table-success">
                    <th><strong>Net Salary:</strong></th>
                    <td><strong>{{ number_format($data['net_salary']) }}/-</strong></td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>