-- Create the database (run once)
CREATE DATABASE IF NOT EXISTS pinoy_meme_nft;
USE pinoy_meme_nft;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    wallet_address VARCHAR(100),
    registration_date DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- NFTs table
CREATE TABLE IF NOT EXISTS nfts (
    nft_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    image_path VARCHAR(255) NOT NULL,
    creator_id INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    mint_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (creator_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- User NFTs ownership table (which user owns which NFT)
CREATE TABLE IF NOT EXISTS user_nfts (
    user_id INT NOT NULL,
    nft_id INT NOT NULL,
    purchase_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, nft_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (nft_id) REFERENCES nfts(nft_id) ON DELETE CASCADE
);

-- Transactions table (records each purchase)
CREATE TABLE IF NOT EXISTS transactions (
    transaction_id INT AUTO_INCREMENT PRIMARY KEY,
    nft_id INT NOT NULL,
    buyer_id INT NOT NULL,
    purchase_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    amount DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (nft_id) REFERENCES nfts(nft_id) ON DELETE CASCADE,
    FOREIGN KEY (buyer_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Insert default admin user (example password 'admin123' hashed)
INSERT INTO users (username, email, password_hash, wallet_address)
VALUES ('admin', 'admin@example.com', '$2y$10$wW0YqGmTk2xA2ZlhDE4PCuKcOWqGnHvAo04QUKVJ6xKODvSSLMjFq', '0x123456789');

-- Insert example NFTs linked to admin user (user_id = 1)
INSERT INTO nfts (title, description, image_path, creator_id, price) VALUES
('Pinoy Meme #1', 'Classic meme image #1', 'https://assets.onecompiler.app/43apkkj7t/43hpm2at3/212acd3d51dc7661ed91c95e3b70d65c.jpg', 1, 100.00),
('Pinoy Meme #2', 'Classic meme image #2', 'https://assets.onecompiler.app/43apkkj7t/43g6cz2ke/a438b16c-d4bc-4c7b-b270-6b683434a21e-1668124278647.jpeg', 1, 100.00),
('Pinoy Meme #3', 'Classic meme image #3', 'https://assets.onecompiler.app/43apkkj7t/43g6cz2ke/images%20(2).jfif', 1, 100.00),
('Pinoy Meme #4', 'Classic meme image #4', 'https://assets.onecompiler.app/43apkkj7t/43hpm2at3/6407fed479ae13394d59822b12bc2ec2.jpg', 1, 100.00),
('Pinoy Meme #5', 'Classic meme image #5', 'https://assets.onecompiler.app/43apkkj7t/43hpm2at3/b9241d02-1d20-4288-bfd9-264ba6eea5f4-1696624462812.jpg', 1, 100.00),
('Pinoy Meme #6', 'Classic meme image #6', 'https://assets.onecompiler.app/43apkkj7t/43hpm2at3/barilanpng.png', 1, 100.00),
('Pinoy Meme #7', 'Classic meme image #7', 'https://assets.onecompiler.app/43apkkj7t/43hpm2at3/31ee8cf175d26d71d01e471bdad33af9.jpg', 1, 100.00),
('Pinoy Meme #8', 'Classic meme image #8', 'https://assets.onecompiler.app/43apkkj7t/43hpm2at3/images%20(3).jfif', 1, 100.00);
