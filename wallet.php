<?php
session_start();
include 'php/functions.php';
include 'php/db.php'; // or wherever you connect to DB

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
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
    <div id="walletContainer" style="display:none;">
    <h2>Your Wallet</h2>
    <table id="walletTable">
        <thead>
            <tr>
                <th>Coin</th>
                <th>Symbol</th>
                <th>Amount</th>
                <th>Current Price</th>
                <th>Total Value</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="walletBody"></tbody>
    </table>
</div>
    <script defer src="js/main.js"></script>
</body>

</html>