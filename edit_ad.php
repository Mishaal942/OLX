<?php include('db.php'); session_start(); if(!isset($_SESSION['user_id'])){ echo "<script>window.location='login.php';</script>"; exit; } ?>
<!DOCTYPE html>
<html>
<head>
<title>Edit Ad - OLX Clone</title>
<style>
body{font-family:Arial;background:#f1f1f1;}
form{background:white;width:400px;margin:50px auto;padding:30px;border-radius:10px;box-shadow:0 0 10px rgba(0,0,0,0.1);}
input,textarea,select,button{width:100%;padding:10px;margin:8px 0;border:1px solid #ccc;border-radius:5px;}
button{background:#007bff;color:white;border:none;cursor:pointer;}
</style>
<script>
function redirectProfile(){ window.location.href='profile.php'; }
</script>
</head>
<body>
<?php
$id = $_GET['id'];
$ad = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM ads WHERE id='$id'"));
?>
<form method="POST" enctype="multipart/form-data">
<h2>Edit Ad ✏️</h2>
<input type="text" name="title" value="<?php echo $ad['title']; ?>" required>
<textarea name="description" required><?php echo $ad['description']; ?></textarea>
<input type="number" name="price" value="<?php echo $ad['price']; ?>" required>
<select name="category">
<option selected><?php echo $ad['category']; ?></option>
<option>Electronics</option><option>Vehicles</option><option>Furniture</option><option>Mobiles</option><option>Others</option>
</select>
<input type="text" name="location" value="<?php echo $ad['location']; ?>" required>
<input type="file" name="image" accept="image/*">
<button type="submit" name="update">Update</button>
<p><a href="#" onclick="redirectProfile()">Back to Profile</a></p>
</form>

<?php
if(isset($_POST['update'])){
    $title=$_POST['title']; $desc=$_POST['description']; $price=$_POST['price']; $cat=$_POST['category']; $loc=$_POST['location'];
    if(!empty($_FILES['image']['name'])){
        $img=basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'],"uploads/".$img);
        $img_sql=", image='$img'";
    } else { $img_sql=""; }
    $sql="UPDATE ads SET title='$title', description='$desc', price='$price', category='$cat', location='$loc' $img_sql WHERE id='$id'";
    if(mysqli_query($conn,$sql)){
        echo "<script>alert('Ad updated successfully!');window.location='profile.php';</script>";
    } else {
        echo "<script>alert('Update failed!');</script>";
    }
}
?>
</body>
</html>
