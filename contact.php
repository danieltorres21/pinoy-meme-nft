contact.html
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pinoy Meme NFT - Contact Us</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <header>
    <h1>Pinoy Meme NFT</h1>
    <nav>
      <a href="index.html">Home</a>
      <a href="about.html">About</a>
      <a href="nfts.html">NFTs</a>
      <a href="contact.html">Contact</a>
      <a href="portal.html">Portal</a>
    </nav>
  </header>
  
  <main>
    <section class="contact-form">
      <h2>Contact Us</h2>
      <form onsubmit="alert('Message sent!'); return false;">
        <label for="name">Name:</label>
        <input type="text" id="name" required>

        <label for="email">Email:</label>
        <input type="email" id="email" required>

        <label for="message">Message:</label>
        <textarea id="message" required></textarea>

        <button type="submit">Send</button>
      </form>
    </section>
  </main>

  <footer>
    <p>&copy; 2025 Pinoy Meme NFT. All rights reserved.</p>
  </footer>
</body>
</html>

