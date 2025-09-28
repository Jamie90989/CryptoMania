<?php
require_once "php/db.php";
include 'php/functions.php';

$response = ['success' => false, 'message' => ''];
$responseMessage = '';
$responseColor = 'red';

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $username = $_POST['userName'] ?? '';
    $password = $_POST['password'] ?? '';

    $response = registerUser($username, $password);

    // Redirect if successful
    if ($response['success']) {
        header("Location: login.php");
        exit;
    }

    $responseMessage = $response['message'];
    $responseColor = $response['success'] ? 'green' : 'red';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/mustache@4.2.0/mustache.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>

    <title>cryptomania</title>
</head>

<body>
    <?php navbar() ?>
   <div class="login-container">
    <h1 class="login-title">Register for Cryptomania</h1>

    <form action="" method="post" class="login-form">
        <div class="form-group">
            <input type="text" name="userName" placeholder="Username" class="form-input" required>
        </div>
        <div class="form-group">
            <input type="password" name="password" placeholder="Password" class="form-input" required>
        </div>
        <div class="form-group">
            <input type="submit" value="Register" class="btn-submit">
        </div>
    </form>

    <?php if (!empty($responseMessage)): ?>
        <p class="form-message" style="color: <?= $responseColor ?>"><?= htmlspecialchars($responseMessage) ?></p>
    <?php endif; ?>

    <p class="register-link">Already have an account? <a href="login.php">Login here</a></p>
</div>

    <script defer src="js/main.js"></script>
</body>

</html>