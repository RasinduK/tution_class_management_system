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
$student_id = $amount = $payment_date = $payment_method = $payment_month = "";
$student_id_err = $amount_err = $payment_date_err = $payment_method_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate student ID
    if (empty(trim($_POST["student_id"]))) {
        $student_id_err = "Please select a student.";
    } else {
        $student_id = trim($_POST["student_id"]);
    }

    // Validate amount
    if (empty(trim($_POST["amount"]))) {
        $amount_err = "Please enter the amount.";
    } elseif (!is_numeric($_POST["amount"])) {
        $amount_err = "Please enter a valid amount.";
    } else {
        $amount = trim($_POST["amount"]);
    }

    // Validate payment date
    if (empty(trim($_POST["payment_date"]))) {
        $payment_date_err = "Please enter the payment date.";
    } else {
        $payment_date = trim($_POST["payment_date"]);
    }

    // Validate payment method
    if (empty(trim($_POST["payment_method"]))) {
        $payment_method_err = "Please select a payment method.";
    } else {
        $payment_method = trim($_POST["payment_method"]);
    }

    // Validate payment month
    if (empty(trim($_POST["payment_month"]))) {
        $payment_month = ""; // Optional field
    } else {
        $payment_month = trim($_POST["payment_month"]);
    }

    // Check input errors before inserting in database
    if (empty($student_id_err) && empty($amount_err) && empty($payment_date_err) && empty($payment_method_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO payments (student_id, amount, payment_date, payment_method, payment_month) VALUES (?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("iisss", $param_student_id, $param_amount, $param_payment_date, $param_payment_method, $param_payment_month);

            // Set parameters
            $param_student_id = $student_id;
            $param_amount = $amount;
            $param_payment_date = $payment_date;
            $param_payment_method = $payment_method;
            $param_payment_month = $payment_month;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Records created successfully. Redirect to landing page
                header("Location: payment.php");
                exit();
            } else {
                echo "Something went wrong. Please try again later.";
                // Debugging
                echo "<pre>";
                print_r($stmt->error_list);
                echo "</pre>";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Payment</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
    <h2>Add Payment</h2>
    <form id="payment-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label>Student</label>
            <select name="student_id" id="student_id" class="form-control <?php echo (!empty($student_id_err)) ? 'is-invalid' : ''; ?>">
                <option value="">Select a student</option>
                <?php
                // Fetch students from the database
                $result = $conn->query("SELECT id, full_name FROM students");
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . $row['id'] . '">' . $row['full_name'] . '</option>';
                }
                ?>
            </select>
            <span class="invalid-feedback"><?php echo $student_id_err; ?></span>
        </div>
        <div class="form-group">
            <label>Amount</label>
            <input type="text" name="amount" class="form-control <?php echo (!empty($amount_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $amount; ?>">
            <span class="invalid-feedback"><?php echo $amount_err; ?></span>
        </div>
        <div class="form-group">
            <label>Payment Date</label>
            <input type="date" name="payment_date" class="form-control <?php echo (!empty($payment_date_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $payment_date; ?>">
            <span class="invalid-feedback"><?php echo $payment_date_err; ?></span>
        </div>
        <div class="form-group">
            <label>Payment Method</label>
            <select name="payment_method" class="form-control <?php echo (!empty($payment_method_err)) ? 'is-invalid' : ''; ?>">
                <option value="">Select a payment method</option>
                <option value="Cash" <?php echo ($payment_method == "Cash") ? 'selected' : ''; ?>>Cash</option>
                <option value="Credit Card" <?php echo ($payment_method == "Credit Card") ? 'selected' : ''; ?>>Credit Card</option>
                <option value="Bank Transfer" <?php echo ($payment_method == "Bank Transfer") ? 'selected' : ''; ?>>Bank Transfer</option>
            </select>
            <span class="invalid-feedback"><?php echo $payment_method_err; ?></span>
        </div>
        <div class="form-group">
            <label>Payment Month</label>
            <input type="date" name="payment_month" class="form-control" value="<?php echo $payment_month; ?>">
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit">
        </div>
    </form>
    <button onclick="printPayment()">Print</button>
</main>

<script>
    $(document).ready(function() {
        $('#student_id').change(function() {
            var student_id = $(this).val();
            if(student_id) {
                $.ajax({
                    type: 'POST',
                    url: 'fetch_student_name.php',
                    data: { student_id: student_id },
                    success: function(response) {
                        $('#student_name').val(response);
                    }
                });
            } else {
                $('#student_name').val('');
            }
        });
    });

    function printPayment() {
        var printContents = document.getElementById("payment-form").innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>

</body>
</html>
