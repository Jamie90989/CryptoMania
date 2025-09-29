<?php
session_start();
include 'functions.php';
include 'db.php'; // adjust path if needed

header('Content-Type: application/json');

if(!isset($_SESSION['user_id'])){
    echo json_encode(['success'=>false,'error'=>'Not logged in']);
    exit;
}


$pdo = connectDB();
$user_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';

switch($action) {
    case 'get':
        echo json_encode(getWallet($user_id, $pdo));
        break;

    case 'add':
        $coin_id = $_POST['coin_id'];
        $coin_name = $_POST['coin_name'];
        $amount = floatval($_POST['amount']);
        $price = floatval($_POST['price']);
        addOrUpdateWallet($user_id, $coin_id, $coin_name, $amount, $price, $pdo);
        echo json_encode(['success'=>true]);
        break;

    case 'update':
        $coin_id = $_POST['coin_id'];
        $amount = floatval($_POST['amount']);
        updateCoinAmount($user_id, $coin_id, $amount, $pdo);
        echo json_encode(['success'=>true]);
        break;

    case 'delete':
        $coin_id = $_POST['coin_id'];
        deleteCoin($user_id, $coin_id, $pdo);
        echo json_encode(['success'=>true]);
        break;

    default:
        echo json_encode(['error'=>'Invalid action']);
}
