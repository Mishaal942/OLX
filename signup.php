<?php include('db.php'); ?>
<!DOCTYPE html>
<html>
<head>
<title>Signup - OLX Clone</title>
<style>
body { font-family: Arial; background:#f1f1f1; }
form { background:white; width:350px; margin:80px auto; padding:30px; border-radius:10px; box-shadow:0 0 10px rgba(0,0,0,0.1); }
input,button { width:100%; padding:10px; margin:8px 0; border:1px solid #ccc; border-radius:5px; }
button { background:#007bff; color:white; border:none; cursor:pointer; }
a { color:#007bff; text-decoration:none; font-size:14px; }
</style>
<script>
function redirectLogin(){ window.location.href="login.php"; }
</script>
</head>
<body>
<form method="POST">
<h2>Create Account 🧍‍♀️</h2>
<input type="text" name="name" placeholder="Full Name" required>
<input type="email" name="email" placeholder="Email" required>
<input type="password" name="password" placeholder="Password" required>
<input type="text" name="phone" placeholder="Phone (optional)">
<button type="submit" name="signup">Signup</button>
<p>Already have an account? <a href="#" onclick="redirectLogin()">Login</a></p>
</form>

<?php
if(isset($_POST['signup'])){
    $name=$_POST['name']; $email=$_POST['email']; $pass=password_hash($_POST['password'], PASSWORD_BCRYPT);
    $phone=$_POST['phone'];
    $sql="INSERT INTO users(name,email,password,phone) VALUES('$name','$email','$pass','$phone')";
    if(mysqli_query($conn,$sql)){
        echo "<script>alert('Signup Successful!');window.location='login.php';</script>";
    }else{
        echo "<script>alert('Error: Email already exists');</script>";
    }
}
?>
</body>
</html>
