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

    <title>cryptomania</title>
</head>

<body>
    <?php navbar() ?>

    <div id="walletContainer" class="wallet-container" style="display:none;">
    <h2 class="wallet-title">Your Wallet</h2>
    <div id="walletNotification" style="display:none;"></div>
    <table id="walletTable" class="wallet-table">
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

    <script id="walletTemplate" type="x-tmpl-mustache">
        {{#coins}}
            <tr data-coin="{{coin_id}}">
                <td>{{coin_name}}</td>
                <td>{{coin_id}}</td>
                <td>{{amount}}</td>
                <td>${{price}}</td>
                <td>${{total}}</td>
                <td>
                    <button class="btn updateCoin">Update</button>
                    <button class="btn btn-danger deleteCoin">Delete</button>
                </td>
            </tr>
        {{/coins}}
    </script>
</div>

<div id="walletEditModal" style="display:none;">
    <div class="wallet-edit-modal-content">
        <span class="wallet-edit-close">&times;</span>
        <h2 id="walletEditTitle">Edit Coin</h2>
        <div class="wallet-edit-section">
            <label for="walletEditAmount">Amount:</label>
            <input type="number" id="walletEditAmount" placeholder="Enter amount">
            <p id="walletEditTotal" class="wallet-edit-total">Total: $0.00</p>
            <button id="walletSaveEditButton">Save</button>
            <button id="walletRemoveButton" style="background-color:#f44336;color:white;">Remove</button>
        </div>
    </div>
</div>

</body>
<script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/mustache@4.2.0/mustache.min.js"></script>

<script defer src="js/wallet.js"></script>

</html>