<?php
// Error reporting enable karein debugging ke liye
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Session start karein
session_start();

include "db.php";
$message = "";
$message_type = "";

// Function to create default user if not exists
function createDefaultUser($conn) {
    try {
        // Check if user exists
        $check = mysqli_query($conn, "SELECT id FROM users WHERE id = 1");
        
        if (!$check || mysqli_num_rows($check) == 0) {
            // Try to create default user
            $username = 'default_user';
            $email = 'default@olx.com';
            $password = password_hash('default123', PASSWORD_DEFAULT);
            
            $sql = "INSERT INTO users (id, username, email, password) VALUES (1, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "sss", $username, $email, $password);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }
        }
    } catch (Exception $e) {
        // If error occurs, try alternative method
        mysqli_query($conn, "INSERT IGNORE INTO users (id, username, email, password) VALUES (1, 'default_user', 'default@olx.com', 'default123')");
    }
}

// Create default user
createDefaultUser($conn);

// Get valid user_id
$user_id = 1;
$user_check = mysqli_query($conn, "SELECT id FROM users LIMIT 1");
if ($user_check && mysqli_num_rows($user_check) > 0) {
    $user_row = mysqli_fetch_assoc($user_check);
    $user_id = $user_row['id'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all POST data exists
    $title = isset($_POST['title']) ? mysqli_real_escape_string($conn, trim($_POST['title'])) : '';
    $price = isset($_POST['price']) ? mysqli_real_escape_string($conn, trim($_POST['price'])) : '';
    $description = isset($_POST['description']) ? mysqli_real_escape_string($conn, trim($_POST['description'])) : '';
    $category = isset($_POST['category']) ? mysqli_real_escape_string($conn, trim($_POST['category'])) : '';
    
    // Validation
    if (empty($title) || empty($price) || empty($description) || empty($category)) {
        $message = '⚠️ Please fill all required fields!';
        $message_type = "error";
    } else {
        // Folder create if not exists
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        
        // Check if file was uploaded
        if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
            // File name fix (unique name)
            $original_name = basename($_FILES["image"]["name"]);
            $image_name = time() . "_" . preg_replace("/[^a-zA-Z0-9.]/", "_", $original_name);
            $target_file = $target_dir . $image_name;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $allowed = array("jpg", "jpeg", "png", "gif", "webp");
            
            // Check file size (max 5MB)
            if ($_FILES["image"]["size"] > 5000000) {
                $message = '⚠️ File is too large! Maximum size is 5MB.';
                $message_type = "error";
            } elseif (in_array($imageFileType, $allowed)) {
                // Move uploaded file
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    // Save data in DB
                    try {
                        $sql = "INSERT INTO ads (user_id, title, price, description, category, image) VALUES (?, ?, ?, ?, ?, ?)";
                        $stmt = mysqli_prepare($conn, $sql);
                        
                        if ($stmt) {
                            mysqli_stmt_bind_param($stmt, "isisss", $user_id, $title, $price, $description, $category, $image_name);
                            
                            if (mysqli_stmt_execute($stmt)) {
                                mysqli_stmt_close($stmt);
                                $message = '✅ Ad Posted Successfully! Redirecting...';
                                $message_type = "success";
                                echo "<script>
                                    setTimeout(function() {
                                        window.location.href = 'index.php';
                                    }, 1500);
                                </script>";
                            } else {
                                $error_msg = mysqli_stmt_error($stmt);
                                $message = '❌ Database error: ' . $error_msg;
                                $message_type = "error";
                                
                                // Delete uploaded image on error
                                if (file_exists($target_file)) {
                                    unlink($target_file);
                                }
                            }
                            mysqli_stmt_close($stmt);
                        } else {
                            $message = '❌ Database prepare error: ' . mysqli_error($conn);
                            $message_type = "error";
                        }
                    } catch (Exception $e) {
                        $message = '❌ Error: ' . $e->getMessage();
                        $message_type = "error";
                    }
                } else {
                    $message = '⚠️ Image upload failed! Check folder permissions.';
                    $message_type = "error";
                }
            } else {
                $message = '❌ Invalid file type! Only JPG, JPEG, PNG, GIF, WEBP allowed.';
                $message_type = "error";
            }
        } else {
            $upload_error = isset($_FILES["image"]["error"]) ? $_FILES["image"]["error"] : 'unknown';
            $message = '⚠️ Please select an image file! Error code: ' . $upload_error;
            $message_type = "error";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Post New Ad 📸</title>
  <style>
    /* 🎨 Modern Post Ad Form CSS */
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
      margin-bottom: 30px;
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

    /* 🔙 Back Button */
    .back-btn {
      display: inline-block;
      margin: 20px 0 0 20px;
      padding: 12px 30px;
      background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
      color: white;
      text-decoration: none;
      border-radius: 50px;
      font-size: 1rem;
      font-weight: 600;
      box-shadow: 0 6px 20px rgba(79, 172, 254, 0.4);
      transition: all 0.3s ease;
    }

    .back-btn:hover {
      transform: translateY(-3px);
      box-shadow: 0 10px 30px rgba(79, 172, 254, 0.6);
    }

    /* 📝 Form Container */
    .form-container {
      max-width: 650px;
      margin: 40px auto;
      background: white;
      padding: 45px;
      border-radius: 25px;
      box-shadow: 0 15px 50px rgba(0, 0, 0, 0.3);
      animation: slideUp 0.6s ease-out;
      position: relative;
      overflow: hidden;
    }

    .form-container::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 6px;
      background: linear-gradient(90deg, #f093fb 0%, #f5576c 50%, #4facfe 100%);
    }

    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translateY(50px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .form-container h2 {
      color: #23074d;
      margin-bottom: 30px;
      font-size: 1.8rem;
      text-align: center;
    }

    .form-container form {
      display: flex;
      flex-direction: column;
      gap: 20px;
    }

    .form-group {
      position: relative;
    }

    .form-container label {
      display: block;
      margin-bottom: 8px;
      color: #23074d;
      font-weight: 600;
      font-size: 0.95rem;
    }

    .form-container input[type="text"],
    .form-container input[type="number"],
    .form-container select,
    .form-container textarea {
      width: 100%;
      padding: 15px 20px;
      font-size: 1rem;
      border: 2px solid #e0e0e0;
      border-radius: 12px;
      outline: none;
      transition: all 0.3s ease;
      font-family: inherit;
      background: #f8f9fa;
    }

    .form-container input[type="text"]:focus,
    .form-container input[type="number"]:focus,
    .form-container select:focus,
    .form-container textarea:focus {
      border-color: #f5576c;
      box-shadow: 0 0 0 4px rgba(245, 87, 108, 0.1);
      transform: translateY(-2px);
      background: white;
    }

    .form-container select {
      cursor: pointer;
      appearance: none;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%23f5576c' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 15px center;
      background-size: 20px;
      padding-right: 45px;
    }

    .form-container select:hover {
      border-color: #764ba2;
    }

    .form-container textarea {
      resize: vertical;
      min-height: 120px;
    }

    .form-container input[type="file"] {
      width: 100%;
      padding: 15px;
      font-size: 1rem;
      border: 2px dashed #f5576c;
      border-radius: 12px;
      cursor: pointer;
      transition: all 0.3s ease;
      background: #fff5f7;
    }

    .form-container input[type="file"]:hover {
      border-color: #23074d;
      background: #f0e8ff;
      transform: scale(1.02);
    }

    .file-info {
      font-size: 0.85rem;
      color: #666;
      margin-top: 5px;
    }

    .form-container button {
      padding: 18px;
      font-size: 1.2rem;
      font-weight: 600;
      color: white;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border: none;
      border-radius: 12px;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
      margin-top: 10px;
    }

    .form-container button:hover {
      transform: translateY(-3px);
      box-shadow: 0 12px 30px rgba(102, 126, 234, 0.6);
      background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    }

    .form-container button:active {
      transform: translateY(0);
    }

    /* 💬 Message Alert */
    .msg {
      margin-top: 20px;
      padding: 16px 20px;
      border-radius: 12px;
      text-align: center;
      font-weight: 600;
      animation: shake 0.5s ease;
      border-left: 5px solid;
    }

    .msg.error {
      background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
      color: #8b4513;
      border-left-color: #ff6b6b;
    }

    .msg.success {
      background: linear-gradient(135deg, #d4fc79 0%, #96e6a1 100%);
      color: #2d5016;
      border-left-color: #4caf50;
    }

    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      25% { transform: translateX(-10px); }
      75% { transform: translateX(10px); }
    }

    /* 📱 Responsive Design */
    @media (max-width: 768px) {
      .header h1 {
        font-size: 1.8rem;
      }

      .form-container {
        padding: 30px 20px;
        margin: 20px;
      }

      .form-container h2 {
        font-size: 1.5rem;
      }

      .back-btn {
        margin: 15px;
        padding: 10px 25px;
        font-size: 0.9rem;
      }
    }

    /* ✨ Loading Animation on Submit */
    button.loading {
      position: relative;
      color: transparent;
    }

    button.loading::after {
      content: '';
      position: absolute;
      width: 20px;
      height: 20px;
      top: 50%;
      left: 50%;
      margin-left: -10px;
      margin-top: -10px;
      border: 3px solid #ffffff;
      border-radius: 50%;
      border-top-color: transparent;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }
  </style>
</head>
<body>
  <header class="header">
    <h1>Post New Ad 📸</h1>
  </header>

  <a href="index.php" class="back-btn">⬅ Back to Home</a>

  <div class="form-container">
    <h2>📝 Create Your Advertisement</h2>
    
    <form method="POST" enctype="multipart/form-data">
      <div class="form-group">
        <label for="title">Ad Title *</label>
        <input type="text" id="title" name="title" placeholder="e.g. iPhone 13 Pro Max" required>
      </div>

      <div class="form-group">
        <label for="category">Category *</label>
        <select id="category" name="category" required>
          <option value="">Select a category</option>
          <option value="Mobiles">📱 Mobiles</option>
          <option value="Vehicles">🚗 Vehicles</option>
          <option value="Property">🏠 Property for Sale</option>
          <option value="Property Rent">🏘️ Property for Rent</option>
          <option value="Electronics">💻 Electronics & Home Appliances</option>
          <option value="Bikes">🏍️ Bikes</option>
          <option value="Business">💼 Business, Industrial & Agriculture</option>
          <option value="Services">🔧 Services</option>
          <option value="Jobs">💼 Jobs</option>
          <option value="Animals">🐾 Animals</option>
          <option value="Furniture">🪑 Furniture & Home Decor</option>
          <option value="Fashion">👗 Fashion & Beauty</option>
          <option value="Books">📚 Books, Sports & Hobbies</option>
          <option value="Kids">🧸 Kids</option>
        </select>
      </div>

      <div class="form-group">
        <label for="price">Price (Rs) *</label>
        <input type="number" id="price" name="price" placeholder="e.g. 150000" min="0" required>
      </div>

      <div class="form-group">
        <label for="description">Description *</label>
        <textarea id="description" name="description" placeholder="Describe your product in detail..." required></textarea>
      </div>

      <div class="form-group">
        <label for="image">Upload Image *</label>
        <input type="file" id="image" name="image" accept="image/*" required>
        <div class="file-info">📌 Max size: 5MB | Formats: JPG, PNG, GIF, WEBP</div>
      </div>

      <button type="submit">🚀 Post Ad Now</button>
    </form>

    <?php if ($message): ?>
      <div class='msg <?php echo $message_type; ?>'><?php echo $message; ?></div>
    <?php endif; ?>
  </div>

  <script>
    // Form submission loading effect
    document.querySelector('form').addEventListener('submit', function(e) {
      const btn = this.querySelector('button[type="submit"]');
      btn.classList.add('loading');
      btn.disabled = true;
    });

    // File input preview
    document.getElementById('image').addEventListener('change', function(e) {
      const fileName = e.target.files[0]?.name;
      if (fileName) {
        const fileInfo = document.querySelector('.file-info');
        fileInfo.textContent = '✅ Selected: ' + fileName;
        fileInfo.style.color = '#4caf50';
      }
    });
  </script>
</body>
</html>
