<?php
require_once "php/db.php";
include 'php/functions.php';

session_start();
$responseMessage = '';
$responseColor = 'red';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $username = $_POST['userName'] ?? '';
    $password = $_POST['password'] ?? '';

    $response = loginUser($username, $password);
    $responseMessage = $response['message'];
    $responseColor = $response['success'] ? 'green' : 'red';

    // Redirect if login successful (optional)
    if ($response['success']) {
        header("Location: index.php"); // create dashboard.php or home page
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="css/style.css">
<title>Login - Cryptomania</title>
</head>
<body>
<?php navbar(); ?>

<div class="login-container">
    <h1 class="login-title">Login to Cryptomania</h1>

    <form action="" method="post" class="login-form">
        <div class="form-group">
            <input type="text" name="userName" placeholder="Username" class="form-input" required>
        </div>
        <div class="form-group">
            <input type="password" name="password" placeholder="Password" class="form-input" required>
        </div>
        <div class="form-group">
            <input type="submit" value="Login" class="btn-submit">
        </div>
    </form>

    <?php if (!empty($responseMessage)): ?>
        <p class="form-message" style="color: <?= $responseColor ?>"><?= htmlspecialchars($responseMessage) ?></p>
    <?php endif; ?>

    <p class="register-link">Don't have an account? <a href="register.php">Register here</a></p>
</div>
</body>
</html>
