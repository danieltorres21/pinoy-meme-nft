<?php
session_start();
require_once 'config.php';
require_once 'nft_functions.php';

if (!isLoggedIn()) {
    $_SESSION['message'] = "Please log in to see your NFTs.";
    $_SESSION['message_type'] = "fail";
    header("Location: portal.php");
    exit;
}

$userId = $_SESSION['user_id'];

// Get NFTs owned by this user
$stmt = $pdo->prepare("
    SELECT nfts.nft_id, nfts.title, nfts.image_path, nfts.price
    FROM nfts
    JOIN user_nfts ON nfts.nft_id = user_nfts.nft_id
    WHERE user_nfts.user_id = ?
");
$stmt->execute([$userId]);
$ownedNFTs = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>My NFT Collection</title>
    <link rel="stylesheet" href="css/style.css" />
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        header { background: #222; color: white; padding: 15px; }
        nav a { color: white; margin-right: 15px; text-decoration: none; }
        h1, h2 { text-align: center; }
        .nft-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            padding: 20px;
            justify-items: center;
        }
        .nft-card {
            background: #f9f9f9;
            border: 1px solid #ccc;
            padding: 15px;
            width: 220px;
            text-align: center;
            border-radius: 10px;
            transition: transform 0.2s;
        }
        .nft-card:hover {
            transform: scale(1.03);
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }
        .nft-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 5px;
        }
        .price {
            color: black;
            font-weight: bold;
            margin: 10px 0;
        }
        .message {
            text-align: center;
            margin: 10px;
            font-weight: bold;
        }
        .success { color: green; }
        .fail { color: red; }
    </style>
</head>
<body>
<header>
    <h1>Pinoy Meme NFT - My Collection</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="nfts.php">NFTs</a>
        <a href="my_nfts.php">My NFTs</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<?php if (isset($_SESSION['message'])): ?>
    <div class="message <?= htmlspecialchars($_SESSION['message_type'] ?? '') ?>">
        <?= htmlspecialchars($_SESSION['message']) ?>
    </div>
    <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
<?php endif; ?>

<main>
    <h2>Your Owned NFTs</h2>
    <?php if (empty($ownedNFTs)): ?>
        <p style="text-align:center;">You do not own any NFTs yet.</p>
    <?php else: ?>
        <div class="nft-cards">
            <?php foreach ($ownedNFTs as $nft): ?>
                <div class="nft-card">
                    <img src="<?= htmlspecialchars($nft['image_path']) ?>" alt="<?= htmlspecialchars($nft['title']) ?>" />
                    <h3><?= htmlspecialchars($nft['title']) ?></h3>
                    <p class="price">Price: ₱<?= number_format($nft['price'], 2) ?></p>
                    <span class="owned-label">Owned</span>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>
</body>
</html>
