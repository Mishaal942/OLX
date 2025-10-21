<?php include('db.php'); session_start(); ?>
<!DOCTYPE html>
<html>
<head>
<title>Login - OLX Clone</title>
<style>
body { font-family: Arial; background:#f1f1f1; }
form { background:white; width:350px; margin:80px auto; padding:30px; border-radius:10px; box-shadow:0 0 10px rgba(0,0,0,0.1); }
input,button { width:100%; padding:10px; margin:8px 0; border:1px solid #ccc; border-radius:5px; }
button { background:#007bff; color:white; border:none; cursor:pointer; }
a { color:#007bff; text-decoration:none; font-size:14px; }
</style>
<script>
function redirectSignup(){ window.location.href="signup.php"; }
</script>
</head>
<body>
<form method="POST">
<h2>Login 🔑</h2>
<input type="email" name="email" placeholder="Email" required>
<input type="password" name="password" placeholder="Password" required>
<button type="submit" name="login">Login</button>
<p>Don't have an account? <a href="#" onclick="redirectSignup()">Signup</a></p>
</form>

<?php
if(isset($_POST['login'])){
    $email=$_POST['email']; $password=$_POST['password'];
    $query=mysqli_query($conn,"SELECT * FROM users WHERE email='$email'");
    if($row=mysqli_fetch_assoc($query)){
        if(password_verify($password,$row['password'])){
            $_SESSION['user_id']=$row['id'];
            echo "<script>alert('Login successful!');window.location='profile.php';</script>";
        }else{
            echo "<script>alert('Invalid password!');</script>";
        }
    }else{
        echo "<script>alert('Email not found!');</script>";
    }
}
?>
</body>
</html>
