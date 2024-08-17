<?php
session_start();

// Check if the user is logged in, if not then redirect to login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Include the database connection file
require_once "connection.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Payments</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .receipt {
            width: 300px;
            border: 1px solid #000;
            padding: 10px;
            margin: 0 auto;
            font-family: 'Courier New', Courier, monospace;
        }
        .receipt .header,
        .receipt .footer {
            text-align: center;
        }
        .receipt .item {
            display: flex;
            justify-content: space-between;
        }
        .receipt .item:not(:last-child) {
            margin-bottom: 5px;
        }
        .receipt .total {
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="navbar">
    <a href="dashboard.php">Home</a>
    <a href="student.php">Student</a>
    <a href="attendance.php">Attendance</a>
    <a href="payment.php">Payment</a>
    <a href="dashboard.php" class="logout-button">Back</a>
</div>
<main>
    <h2>View Payments</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Student ID</th>
                <th>Student Name</th>
                <th>Amount</th>
                <th>Payment Date</th>
                <th>Payment Method</th>
                <th>Payment Month</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch payments from the database
            $sql = "SELECT payments.id, payments.student_id, students.full_name, payments.amount, payments.payment_date, payments.payment_method, payments.payment_month 
                    FROM payments 
                    JOIN students ON payments.student_id = students.id";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr data-payment-id='" . $row['id'] . "'>";
                    echo "<td class='id'>" . $row['id'] . "</td>";
                    echo "<td class='student-id'>" . $row['student_id'] . "</td>";
                    echo "<td class='student-name'>" . $row['full_name'] . "</td>";
                    echo "<td class='amount'>" . $row['amount'] . "</td>";
                    echo "<td class='payment-date'>" . $row['payment_date'] . "</td>";
                    echo "<td class='payment-method'>" . $row['payment_method'] . "</td>";
                    echo "<td class='payment-month'>" . $row['payment_month'] . "</td>";
                    echo "<td><button onclick='printPayment(" . $row['id'] . ")'>Print</button></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No payments found</td></tr>";
            }

            $conn->close();
            ?>
        </tbody>
    </table>
</main>

<script>
    function printPayment(paymentId) {
        var paymentRow = document.querySelector('tr[data-payment-id="' + paymentId + '"]');
        if (!paymentRow) {
            alert('Payment ID not found.');
            return;
        }

        var paymentDetails = {
            id: paymentRow.querySelector('.id').textContent.trim(),
            studentId: paymentRow.querySelector('.student-id').textContent.trim(),
            studentName: paymentRow.querySelector('.student-name').textContent.trim(),
            amount: paymentRow.querySelector('.amount').textContent.trim(),
            paymentDate: paymentRow.querySelector('.payment-date').textContent.trim(),
            paymentMethod: paymentRow.querySelector('.payment-method').textContent.trim(),
            paymentMonth: paymentRow.querySelector('.payment-month').textContent.trim()
        };

        console.log(paymentDetails); // Debugging line to check what is being captured

        var printWindow = window.open('', '_blank');
        printWindow.document.write('<html><head><title>Print Payment</title>');
        printWindow.document.write('<style>');
        printWindow.document.write('body { font-family: "Courier New", Courier, monospace; margin: 20px; }');
        printWindow.document.write('.receipt { width: 300px; border: 1px solid #000; padding: 10px; margin: 0 auto; font-family: "Courier New", Courier, monospace; }');
        printWindow.document.write('.receipt .header, .receipt .footer { text-align: center; }');
        printWindow.document.write('.receipt .item { display: flex; justify-content: space-between; }');
        printWindow.document.write('.receipt .item:not(:last-child) { margin-bottom: 5px; }');
        printWindow.document.write('.receipt .total { font-weight: bold; }');
        printWindow.document.write('</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write('<div class="receipt">');
        printWindow.document.write('<div class="header">');
        printWindow.document.write('<h2>Payment Receipt</h2>');
        printWindow.document.write('</div>');
        printWindow.document.write('<div class="item"><span>ID</span><span>' + paymentDetails.id + '</span></div>');
        printWindow.document.write('<div class="item"><span>Student ID</span><span>' + paymentDetails.studentId + '</span></div>');
        printWindow.document.write('<div class="item"><span>Student Name</span><span>' + paymentDetails.studentName + '</span></div>');
        printWindow.document.write('<div class="item"><span>Amount</span><span>' + paymentDetails.amount + '</span></div>');
        printWindow.document.write('<div class="item"><span>Payment Date</span><span>' + paymentDetails.paymentDate + '</span></div>');
        printWindow.document.write('<div class="item"><span>Payment Method</span><span>' + paymentDetails.paymentMethod + '</span></div>');
        printWindow.document.write('<div class="item"><span>Payment Month</span><span>' + paymentDetails.paymentMonth + '</span></div>');
        printWindow.document.write('<div class="footer">');
        printWindow.document.write('<p>Thank you!</p>');
        printWindow.document.write('</div>');
        printWindow.document.write('</div>');
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    }
</script>


</body>
</html>
