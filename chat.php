<?php include('db.php'); session_start(); if(!isset($_SESSION['user_id'])){ echo "<script>window.location='login.php';</script>"; exit; } ?>
<!DOCTYPE html>
<html>
<head>
<title>Chat - OLX Clone</title>
<style>
body{font-family:Arial;background:#f1f1f1;margin:0;}
.container{width:60%;margin:40px auto;background:white;padding:20px;border-radius:10px;box-shadow:0 0 10px rgba(0,0,0,0.1);}
.messages{height:300px;overflow-y:auto;border:1px solid #ddd;padding:10px;border-radius:5px;margin-bottom:15px;}
.msg{margin:8px 0;padding:8px 10px;border-radius:5px;max-width:70%;}
.sender{background:#007bff;color:white;float:right;clear:both;}
.receiver{background:#eee;float:left;clear:both;}
form{display:flex;}
input{flex:1;padding:10px;border:1px solid #ccc;border-radius:5px;}
button{padding:10px;background:#007bff;color:white;border:none;border-radius:5px;margin-left:5px;cursor:pointer;}
</style>
<script>
function refreshChat(){
    window.location.reload();
}
</script>
</head>
<body>
<div class="container">
<h2>Chat 💬</h2>
<div class="messages">
<?php
$my_id = $_SESSION['user_id'];
$other_id = $_GET['uid'];
$q = mysqli_query($conn, "SELECT * FROM messages WHERE (sender_id='$my_id' AND receiver_id='$other_id') OR (sender_id='$other_id' AND receiver_id='$my_id') ORDER BY id ASC");
while($m = mysqli_fetch_assoc($q)){
    $cls = ($m['sender_id'] == $my_id) ? "sender" : "receiver";
    echo "<div class='msg $cls'>{$m['message']}</div>";
}
?>
</div>
<form method="POST">
<input type="text" name="message" placeholder="Type a message..." required>
<button type="submit" name="send">Send</button>
</form>
</div>

<?php
if(isset($_POST['send'])){
    $msg = $_POST['message'];
    mysqli_query($conn, "INSERT INTO messages(sender_id, receiver_id, message) VALUES('$my_id','$other_id','$msg')");
    echo "<script>refreshChat();</script>";
}
?>
</body>
</html>
