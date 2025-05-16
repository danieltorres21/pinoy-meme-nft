<?php
session_start();
require_once 'config.php';       // Your DB connection
require_once 'nft_functions.php'; // Optional, if you have helper functions

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: portal.php");
    exit;
}

$userId = $_SESSION['user_id'];

// Fetch NFTs owned by this user
$stmt = $pdo->prepare("
    SELECT n.*
    FROM nfts n
    INNER JOIN user_nfts un ON n.nft_id = un.nft_id
    WHERE un.user_id = ?
    ORDER BY un.purchase_date DESC
");
$stmt->execute([$userId]);
$ownedNFTs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>My NFT Collection</title>
    <link rel="stylesheet" href="css/style.css" />
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0; padding: 0;
            background: #f0f0f0;
        }
        header {
            background: #222;
            color: white;
            padding: 15px;
            text-align: center;
        }
        nav a {
            color: white;
            margin: 0 10px;
            text-decoration: none;
        }
        h1 {
            margin-bottom: 10px;
        }
        main {
            padding: 20px;
            max-width: 960px;
            margin: 0 auto;
        }
        .nft-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill,minmax(220px,1fr));
            gap: 20px;
        }
        .nft-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
            padding: 15px;
            text-align: center;
            transition: transform 0.2s ease;
        }
        .nft-card:hover {
            transform: scale(1.05);
        }
        .nft-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 6px;
        }
        .nft-card h3 {
            margin: 10px 0 5px;
            font-size: 1.1rem;
        }
        .nft-card p {
            margin: 0;
            color: #333;
            font-weight: bold;
        }
        .no-nfts {
            text-align: center;
            font-size: 1.2rem;
            color: #555;
            margin-top: 50px;
        }
        footer {
            text-align: center;
            padding: 15px;
            margin-top: 50px;
            color: #777;
        }
    </style>
</head>
<body>
    <header>
        <h1>My NFT Collection</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="nfts.php">All NFTs</a>
            <a href="mynfts_collection.php">My NFTs</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <?php if (count($ownedNFTs) > 0): ?>
            <div class="nft-cards">
                <?php foreach ($ownedNFTs as $nft): ?>
                    <div class="nft-card">
                        <img src="<?= htmlspecialchars($nft['image_path']) ?>" alt="<?= htmlspecialchars($nft['title']) ?>" />
                        <h3><?= htmlspecialchars($nft['title']) ?></h3>
                        <p>Price: ₱<?= number_format($nft['price'], 2) ?></p>
                        <p><small>Purchased on: <?= date('F j, Y', strtotime($nft['mint_date'])) ?></small></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="no-nfts">You don't own any NFTs yet.</p>
        <?php endif; ?>
    </main>

    <footer>
        &copy; <?= date('Y') ?> Pinoy Meme NFT
    </footer>
</body>
</html>
