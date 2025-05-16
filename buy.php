<?php
session_start();
require_once 'config.php';     // Your PDO $pdo connection
require_once 'nft_functions.php'; // contains isLoggedIn() etc.

// Check login
if (!isLoggedIn()) {
    $_SESSION['message'] = "Please log in to buy NFTs.";
    $_SESSION['message_type'] = "fail";
    header("Location: portal.php");
    exit;
}

$userId = $_SESSION['user_id'];
$nftId = $_POST['nft_id'] ?? null;

if (!$nftId || !is_numeric($nftId)) {
    $_SESSION['message'] = "Invalid NFT selected.";
    $_SESSION['message_type'] = "fail";
    header("Location: nfts.php");
    exit;
}

// Check if NFT exists and get price
$stmt = $pdo->prepare("SELECT price FROM nfts WHERE nft_id = ?");
$stmt->execute([$nftId]);
$nft = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$nft) {
    $_SESSION['message'] = "NFT not found.";
    $_SESSION['message_type'] = "fail";
    header("Location: nfts.php");
    exit;
}

// Check if user already owns the NFT
$stmt = $pdo->prepare("SELECT 1 FROM user_nfts WHERE user_id = ? AND nft_id = ?");
$stmt->execute([$userId, $nftId]);
if ($stmt->fetch()) {
    $_SESSION['message'] = "You already own this NFT.";
    $_SESSION['message_type'] = "fail";
    header("Location: nfts.php");
    exit;
}

// Insert ownership record
$stmt = $pdo->prepare("INSERT INTO user_nfts (user_id, nft_id) VALUES (?, ?)");
$successOwnership = $stmt->execute([$userId, $nftId]);

// Insert transaction record
$stmt = $pdo->prepare("INSERT INTO transactions (nft_id, buyer_id, amount) VALUES (?, ?, ?)");
$successTransaction = $stmt->execute([$nftId, $userId, $nft['price']]);

if ($successOwnership && $successTransaction) {
    $_SESSION['message'] = "Purchase successful! You now own this NFT.";
    $_SESSION['message_type'] = "success";
} else {
    $_SESSION['message'] = "Error processing purchase. Please try again.";
    $_SESSION['message_type'] = "fail";
}

header("Location: nfts.php");
exit;
