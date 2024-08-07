<?php
session_start();

// Check if the user is logged in, if not then redirect to login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Include the database connection file
require_once "connection.php";

// Define variables and initialize with empty values
$student_id = $amount = $payment_date = "";
$student_id_err = $amount_err = $payment_date_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate student_id
    if (empty(trim($_POST["student_id"]))) {
        $student_id_err = "Please select a student.";
    } else {
        $student_id = trim($_POST["student_id"]);
    }

    // Validate amount
    if (empty(trim($_POST["amount"]))) {
        $amount_err = "Please enter the payment amount.";
    } else {
        $amount = trim($_POST["amount"]);
    }

    // Validate payment_date
    if (empty(trim($_POST["payment_date"]))) {
        $payment_date_err = "Please enter the payment date.";
    } else {
        $payment_date = trim($_POST["payment_date"]);
    }

    // Check input errors before inserting in database
    if (empty($student_id_err) && empty($amount_err) && empty($payment_date_err)) {
        $sql = "INSERT INTO payments (student_id, amount, payment_date) VALUES (?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sss", $student_id, $amount, $payment_date);

            if ($stmt->execute()) {
                // Redirect to payment page with success message
                header("Location: payment.php?success=1");
                exit;
            } else {
                echo "Something went wrong. Please try again later.";
            }

            $stmt->close();
        }
    }

    $conn->close();
}

// Fetch students for the dropdown
$students = [];
$result = $conn->query("SELECT id, full_name FROM students");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Management</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="navbar">
    <a href="dashboard.php">Home</a>
    <a href="student.php">Student</a>
    <a href="attendnce.php">Attendnce</a>
    <a href="payment.php">Payment</a>
    <a href="logout.php" class="logout-button">Back</a>
</div>
<main>
    <section class="actions">
        <div class="card">
            <img src="icons/add_icon.png" alt="Add Payment">
            <h2>Add Payment</h2>
            <button onclick="location.href='add_payment.php'">Add</button>
        </div>
        <div class="card">
            <img src="icons/view_icon.png" alt="View Payments">
            <h2>View Payments</h2>
            <button onclick="location.href='view_payments.php'">Access Now</button>
        </div>
    </section>
  </main>
</body>
</html>
