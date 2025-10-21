<?php include('db.php'); session_start(); if(!isset($_SESSION['user_id'])){ echo "<script>window.location='login.php';</script>"; exit; } ?>
<!DOCTYPE html>
<html>
<head>
<title>My Profile - OLX Clone</title>
<style>
body{font-family:Arial;background:#f7f7f7;margin:0;padding:0;}
header{background:#007bff;color:white;padding:15px;text-align:center;}
.container{width:90%;margin:30px auto;}
a{color:#007bff;text-decoration:none;}
button{padding:10px 15px;border:none;border-radius:5px;background:#007bff;color:white;cursor:pointer;margin-right:10px;}
</style>
<script>
function postAd(){ window.location.href='post_ad.php'; }
function logout(){ window.location.href='logout.php'; }
</script>
</head>
<body>
<header>Welcome to your profile 👋</header>
<div class="container">
<h2>Your Ads</h2>
<button onclick="postAd()">+ Post New Ad</button>
<button onclick="logout()">Logout</button><br><br>
<?php
$uid=$_SESSION['user_id'];
$result=mysqli_query($conn,"SELECT * FROM ads WHERE user_id='$uid'");
while($ad=mysqli_fetch_assoc($result)){
    echo "<div style='background:white;padding:15px;margin-bottom:10px;border-radius:10px;'>
        <h3>{$ad['title']}</h3>
        <p>Rs. {$ad['price']} | {$ad['location']}</p>
        <a href='edit_ad.php?id={$ad['id']}'>Edit</a> | 
        <a href='delete_ad.php?id={$ad['id']}'>Delete</a>
    </div>";
}
?>
</div>
</body>
</html>
