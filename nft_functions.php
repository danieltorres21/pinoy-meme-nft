<?php
require_once 'config.php';


// nft_functions.php

function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function getAllNFTs() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM nfts");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getNFTById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM nfts WHERE nft_id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function userOwnsNFT($user_id, $nft_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM user_nfts WHERE user_id = ? AND nft_id = ?");
    $stmt->execute([$user_id, $nft_id]);
    return $stmt->fetch() ? true : false;
}

function buyNFT($buyer_id, $nft_id) {
    global $pdo;
    $pdo->beginTransaction();

    try {
        // Get NFT price
        $stmt = $pdo->prepare("SELECT price FROM nfts WHERE nft_id = ?");
        $stmt->execute([$nft_id]);
        $nft = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$nft) {
            $pdo->rollBack();
            return false;
        }

        // Insert into transactions
        $stmt = $pdo->prepare("INSERT INTO transactions (nft_id, buyer_id, amount) VALUES (?, ?, ?)");
        $stmt->execute([$nft_id, $buyer_id, $nft['price']]);

        // Insert into user_nfts
        $stmt = $pdo->prepare("INSERT INTO user_nfts (user_id, nft_id) VALUES (?, ?)");
        $stmt->execute([$buyer_id, $nft_id]);

        $pdo->commit();
        return true;

    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("buyNFT error: " . $e->getMessage());
        return false;
    }
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}
