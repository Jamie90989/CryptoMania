<?php

function navbar()
{
    ?>
    <header>
        <div class="headerRight">
            <div>
                <button><a href="index.php">Home</a></button>
            </div>
            <div>
                <button><a href="wallet.php">Wallet</a></button>
            </div>
        </div>
        <div>
            <button><a href="login.php">Login</a></button>
            <button><a href="logout.php">Logout</a></button>
        </div>
    </header>

    <?php
}

function registerUser($username, $password) {
    $response = ['success' => false, 'message' => ''];

    $mysqli = connectDB();

    $username = trim($username);
    $password = trim($password);

    if (empty($username) || empty($password)) {
        $response['message'] = "All fields are required.";
        return $response;
    }

    // Check if username already exists
    $stmt = $mysqli->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $response['message'] = "Username is already taken.";
        $stmt->close();
        $mysqli->close();
        return $response;
    }
    $stmt->close();

    // Hash password and insert new user
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $mysqli->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashedPassword);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = "✅ Registration successful! You can now log in.";
    } else {
        $response['message'] = "❌ Something went wrong. Please try again.";
    }

    $stmt->close();
    $mysqli->close();

    return $response;
}

function loginUser($username, $password) {
    $response = ['success' => false, 'message' => ''];
    $mysqli = connectDB();

    $username = trim($username);
    $password = trim($password);

    if (empty($username) || empty($password)) {
        $response['message'] = "All fields are required.";
        return $response;
    }

    $stmt = $mysqli->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        $response['message'] = "Username or password is incorrect.";
        $stmt->close();
        $mysqli->close();
        return $response;
    }

    $stmt->bind_result($userId, $hashedPassword);
    $stmt->fetch();

    if (password_verify($password, $hashedPassword)) {
        // Start session if not started yet
        if (session_status() === PHP_SESSION_NONE) session_start();

        // Save user info in session
        $_SESSION['user_id'] = $userId;
        $_SESSION['username'] = $username;

        $response['success'] = true;
        $response['message'] = "✅ Login successful!";
    } else {
        $response['message'] = "Username or password is incorrect.";
    }

    $stmt->close();
    $mysqli->close();

    return $response;
}

?>