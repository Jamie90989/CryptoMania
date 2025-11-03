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
             <div>
                <button><a href="exchanges.php">Exchanges</a></button>
            </div>
             <div>
                <button><a href="news.php">News</a></button>
            </div>
        </div>
        <div>
            <button><a href="login.php">Login</a></button>
            <button><a href="logout.php">Logout</a></button>
        </div>
    </header>

<?php
}

function registerUser($username, $password)
{
    $response = ['success' => false, 'message' => ''];

    $pdo = connectDB(); // make sure connectDB() returns a PDO object

    $username = trim($username);
    $password = trim($password);

    if (empty($username) || empty($password)) {
        $response['message'] = "All fields are required.";
        return $response;
    }

    try {
        // Check if username already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);

        if ($stmt->rowCount() > 0) {
            $response['message'] = "Username is already taken.";
            return $response;
        }

        // Hash password and insert new user
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->execute([$username, $hashedPassword]);

        $response['success'] = true;
        $response['message'] = "✅ Registration successful! You can now log in.";
    } catch (PDOException $e) {
        $response['message'] = "❌ Something went wrong: " . $e->getMessage();
    }

    return $response;
}


function loginUser($username, $password)
{
    $response = ['success' => false, 'message' => ''];
    $pdo = connectDB();

    $username = trim($username);
    $password = trim($password);

    if (empty($username) || empty($password)) {
        $response['message'] = "All fields are required.";
        return $response;
    }

    $stmt = $pdo->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($password, $user['password'])) {
        $response['message'] = "Username or password is incorrect.";
        return $response;
    }

    if (session_status() === PHP_SESSION_NONE) session_start();
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $username;

    $response['success'] = true;
    $response['message'] = "✅ Login successful!";
    return $response;
}

function getWallet($user_id, $pdo)
{
    $stmt = $pdo->prepare("SELECT * FROM wallets WHERE user_id=?");
    $stmt->execute([$user_id]);
    $wallet = $stmt->fetchAll();
    // DEBUG
    error_log("Wallet for user $user_id: " . print_r($wallet, true));
    return $wallet;
}

// Add or update coin in wallets
function addOrUpdateWallet($user_id, $coin_id, $coin_name, $amount, $price, $pdo)
{
    // Check if coin exists
    $stmt = $pdo->prepare("SELECT id, amount FROM wallets WHERE user_id=? AND coin_id=?");
    $stmt->execute([$user_id, $coin_id]);
    $existing = $stmt->fetch();

    if ($existing) {
        $newAmount = $existing['amount'] + $amount;

        $updateStmt = $pdo->prepare("UPDATE wallets SET amount = ?, price = ?, updated_at = NOW() WHERE id = ?");
        $updateStmt->execute([$newAmount, $price, $existing['id']]);
    } else {
        // Coin does not exist, insert new row
        $insertStmt = $pdo->prepare("INSERT INTO wallets (user_id, coin_id, coin_name, amount, price, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())");
        $insertStmt->execute([$user_id, $coin_id, $coin_name, $amount, $price]);
    }
}

// Delete coin
function deleteCoin($user_id, $coin_id, $pdo)
{
    $stmt = $pdo->prepare("DELETE FROM wallets WHERE user_id=? AND coin_id=?");
    $stmt->execute([$user_id, $coin_id]);
}

// Update coin amount
function updateCoinAmount($user_id, $coin_id, $amount, $pdo)
{
     // Check if coin exists
    $stmt = $pdo->prepare("SELECT id, amount FROM wallets WHERE user_id=? AND coin_id=?");
    $stmt->execute([$user_id, $coin_id]);
    $existing = $stmt->fetch();

        // Add to existing amount
        $newAmount = $existing['amount'] + $amount;
        $updateStmt = $pdo->prepare("UPDATE wallets SET amount = ?, updated_at = NOW() WHERE id = ?");
        $updateStmt->execute([$newAmount, $existing['id']]);
}



?>