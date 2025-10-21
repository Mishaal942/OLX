<?php
include('db.php');
$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM ads WHERE id='$id'");
$ad = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $ad['title']; ?> - OLX Clone</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <h2><?php echo $ad['title']; ?></h2>
        <img src="<?php echo $ad['image']; ?>" width="400"><br>
        <p><strong>Price:</strong> Rs <?php echo $ad['price']; ?></p>
        <p><strong>Category:</strong> <?php echo $ad['category']; ?></p>
        <p><strong>Description:</strong> <?php echo $ad['description']; ?></p>
    </div>
</body>
</html>
