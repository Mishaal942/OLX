<?php include('db.php'); ?>
<!DOCTYPE html>
<html>
<head>
<title>Search - OLX Clone</title>
<style>
body{font-family:Arial;background:#f9f9f9;}
.container{width:90%;margin:30px auto;}
.card{background:white;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,0.1);margin-bottom:20px;overflow:hidden;}
.card img{width:100%;height:200px;object-fit:cover;}
.card-content{padding:15px;}
.card-content h3{margin:0;color:#007bff;}
input{width:80%;padding:10px;border:1px solid #ccc;border-radius:5px;}
button{padding:10px 20px;background:#007bff;color:white;border:none;border-radius:5px;cursor:pointer;}
</style>
<script>
function goToAd(id){ window.location.href='view_ad.php?id='+id; }
</script>
</head>
<body>
<div class="container">
<form method="GET">
<input type="text" name="q" placeholder="Search here..." value="<?php echo isset($_GET['q'])?$_GET['q']:''; ?>">
<button type="submit">Search</button>
</form>
<br>
<?php
if(isset($_GET['q'])){
    $q = $_GET['q'];
    $result = mysqli_query($conn, "SELECT * FROM ads WHERE title LIKE '%$q%' OR category LIKE '%$q%'");
    if(mysqli_num_rows($result)>0){
        while($row=mysqli_fetch_assoc($result)){
            echo "
            <div class='card' onclick='goToAd({$row['id']})'>
                <img src='uploads/{$row['image']}'>
                <div class='card-content'>
                    <h3>{$row['title']}</h3>
                    <p>Rs. {$row['price']} | {$row['location']}</p>
                </div>
            </div>";
        }
    } else {
        echo "<p>No results found for '$q'</p>";
    }
}
?>
</div>
</body>
</html>
