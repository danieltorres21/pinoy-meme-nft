<?php
session_start();
require_once 'config.php';
require_once 'nft_functions.php';

$nfts = getAllNFTs();

$ownedNFTs = [];
if (isLoggedIn()) {
    $stmt = $pdo->prepare("SELECT nft_id FROM user_nfts WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $ownedNFTs = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'nft_id');
}

$message = $_SESSION['message'] ?? null;
$message_type = $_SESSION['message_type'] ?? '';
unset($_SESSION['message'], $_SESSION['message_type']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>NFTs</title>
    <link rel="stylesheet" href="css/style.css" />
    <style>
    /* existing styles here, omitted for brevity */
    </style>
</head>
<body>
<header>
    <h1>Pinoy Meme NFT</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="about.php">About</a>
        <a href="nfts.php">NFTs</a>
        <a href="my_nfts.php">My NFTs</a> <!-- Add this link -->
        <a href="contact.php">Contact</a>
        <?php if (isLoggedIn()): ?>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="portal.php">Login</a>
        <?php endif; ?>
    </nav>
</header>

<?php if ($message): ?>
    <div class="message <?= htmlspecialchars($message_type) ?>">
        <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>

<main>
    <h2>Available NFT Collection</h2>
    <div class="nft-cards">
        <?php foreach ($nfts as $nft): ?>
            <?php if (in_array($nft['nft_id'], $ownedNFTs)) continue; // Skip owned NFTs ?>
            <div class="nft-card">
                <div class="image-container">
                    <img src="<?= htmlspecialchars($nft['image_path']) ?>" alt="<?= htmlspecialchars($nft['title']) ?>" />
                </div>
                <h3><?= htmlspecialchars($nft['title']) ?></h3>
                <p class="price">Price: ₱<?= number_format($nft['price'], 2) ?></p>
                <?php if (isLoggedIn()): ?>
                    <form action="buy.php" method="POST">
                        <input type="hidden" name="nft_id" value="<?= $nft['nft_id'] ?>" />
                        <button type="submit" class="buy-button">Buy</button>
                    </form>
                <?php else: ?>
                    <a href="portal.php" class="buy-button">Login to Buy</a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</main>
</body>
</html>
