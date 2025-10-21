<?php
include('db.php'); session_start();
if(!isset($_SESSION['user_id'])){ echo "<script>window.location='login.php';</script>"; exit; }
$id = $_GET['id'];
mysqli_query($conn,"DELETE FROM ads WHERE id='$id'");
echo "<script>alert('Ad deleted successfully!');window.location='profile.php';</script>";
?>
