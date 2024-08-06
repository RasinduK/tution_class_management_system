<?php
session_start();

// Check if user is already logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: dashboard.php");
    exit;
}

// Include the database connection file
require_once "connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['login_uname'];
    $password = $_POST['login_pwd'];

    // Validate login credentials (check against database)
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id, $username, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            // Login successful, set session variables and redirect to dashboard
            $_SESSION['loggedin'] = true;
            $_SESSION['id'] = $id;
            $_SESSION['username'] = $username;
            header("Location: dashboard.php");
            exit;
        } else {
            $error_message = "Invalid username or password.";
        }
    } else {
        $error_message = "Invalid username or password.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="navbar">
        <a href="loging.php">Home</a>
        <!--<a href="student.php">Student</a>
        <a href="attendnce.php">Attendnce</a>
        <a href="Payment.php">Payment</a>
        <a href="study_material.php">Study Material</a>
        <a href="exam.php">Exam</a>
        <a href="result.php">Result</a>-->
        <a href="logout.php" class="logout-button">Exit</a>
    </div>
    <div>
        <h1>Login</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="login_uname">User Name:</label>
            <input type="text" id="login_uname" name="login_uname" required>
            
            <label for="login_pwd">Password:</label>
            <input type="password" id="login_pwd" name="login_pwd" required>
            
            <button type="submit">Sign in</button>
        </form>
        <?php if (isset($error_message)) { echo '<p class="error">' . $error_message . '</p>'; } ?>
        <p>Don't have an account? <a href="register.php">Create one here</a>.</p>
    </div>
</body>
</html>
