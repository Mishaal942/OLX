<?php
include "db.php";
$result = mysqli_query($conn, "SELECT * FROM ads ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>OLX Clone 🛒</title>
  <style>
    /* 🎨 Modern OLX Clone CSS - Professional Design */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      padding-bottom: 40px;
    }

    /* 🎯 Header Styling */
    .header {
      background: linear-gradient(135deg, #23074d 0%, #cc5333 100%);
      color: white;
      padding: 25px 20px;
      text-align: center;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
      position: sticky;
      top: 0;
      z-index: 100;
      backdrop-filter: blur(10px);
    }

    .header h1 {
      font-size: 2.5rem;
      font-weight: 700;
      text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.4);
      letter-spacing: 1px;
      animation: slideDown 0.6s ease-out;
    }

    @keyframes slideDown {
      from {
        opacity: 0;
        transform: translateY(-30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* 🔘 Post New Ad Button */
    .post-btn {
      display: inline-block;
      margin: 30px auto;
      padding: 15px 40px;
      background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
      color: white;
      text-decoration: none;
      border-radius: 50px;
      font-size: 1.2rem;
      font-weight: 600;
      box-shadow: 0 8px 25px rgba(245, 87, 108, 0.4);
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
      left: 50%;
      transform: translateX(-50%);
    }

    .post-btn:hover {
      transform: translateX(-50%) translateY(-3px);
      box-shadow: 0 12px 35px rgba(245, 87, 108, 0.6);
      background: linear-gradient(135deg, #f5576c 0%, #f093fb 100%);
    }

    .post-btn::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      width: 0;
      height: 0;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.3);
      transform: translate(-50%, -50%);
      transition: width 0.6s, height 0.6s;
    }

    .post-btn:hover::before {
      width: 300px;
      height: 300px;
    }

    /* 📦 Container for Cards */
    .container {
      max-width: 1400px;
      margin: 0 auto;
      padding: 20px;
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
      gap: 30px;
      animation: fadeIn 0.8s ease-in;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
      }
      to {
        opacity: 1;
      }
    }

    /* 🎴 Ad Cards */
    .card {
      background: white;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      position: relative;
      animation: popIn 0.5s ease-out;
    }

    @keyframes popIn {
      from {
        opacity: 0;
        transform: scale(0.8);
      }
      to {
        opacity: 1;
        transform: scale(1);
      }
    }

    .card:hover {
      transform: translateY(-10px) scale(1.02);
      box-shadow: 0 15px 45px rgba(0, 0, 0, 0.3);
    }

    .card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 5px;
      background: linear-gradient(90deg, #f093fb 0%, #f5576c 50%, #4facfe 100%);
    }

    .card img {
      width: 100%;
      height: 250px;
      object-fit: cover;
      transition: transform 0.4s ease;
      display: block;
    }

    .card:hover img {
      transform: scale(1.1);
    }

    .card .info {
      padding: 20px;
      background: linear-gradient(180deg, #ffffff 0%, #f8f9fa 100%);
    }

    .card .info h3 {
      font-size: 1.5rem;
      color: #23074d;
      margin-bottom: 12px;
      font-weight: 700;
      line-height: 1.4;
    }

    .card .info p {
      font-size: 1rem;
      color: #555;
      margin: 8px 0;
      line-height: 1.6;
    }

    .card .info p strong {
      color: #f5576c;
      font-weight: 600;
    }

    /* 🎉 Empty State Message */
    .empty-message {
      color: white;
      font-size: 1.5rem;
      font-weight: 500;
      text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.3);
      padding: 60px 40px;
      background: rgba(255, 255, 255, 0.15);
      border-radius: 20px;
      backdrop-filter: blur(10px);
      margin: 40px auto;
      max-width: 600px;
      text-align: center;
      border: 2px solid rgba(255, 255, 255, 0.3);
    }

    /* 📱 Responsive Design */
    @media (max-width: 768px) {
      .header h1 {
        font-size: 1.8rem;
      }

      .container {
        grid-template-columns: 1fr;
        padding: 15px;
        gap: 20px;
      }

      .post-btn {
        font-size: 1rem;
        padding: 12px 30px;
      }

      .card img {
        height: 200px;
      }

      .empty-message {
        font-size: 1.2rem;
        padding: 40px 20px;
      }
    }
  </style>
</head>
<body>
  <header class="header">
    <h1>OLX Clone 🛒</h1>
  </header>
  
  <a href="post_ad.php" class="post-btn">+ Post New Ad</a>
  
  <main class="container">
    <?php
    if (mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        $imgPath = "uploads/" . htmlspecialchars($row['image']);
        
        // Check if image exists
        if (!empty($row['image']) && file_exists($imgPath)) {
          $imgSrc = $imgPath;
        } else {
          $imgSrc = "https://via.placeholder.com/300x200/667eea/ffffff?text=No+Image";
        }
        
        echo "
        <div class='card'>
          <img src='$imgSrc' alt='Ad Image'>
          <div class='info'>
            <h3>" . htmlspecialchars($row['title']) . "</h3>
            <p><strong>Price:</strong> Rs. " . number_format($row['price']) . "</p>
            <p><strong>Description:</strong> " . htmlspecialchars($row['description']) . "</p>
          </div>
        </div>";
      }
    } else {
      echo "<div class='empty-message'>📭 No ads posted yet. Be the first to post!</div>";
    }
    ?>
  </main>
</body>
</html>
