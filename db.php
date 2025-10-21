<?php
$host = "localhost";
$user = "uppbmi0whibtc";
$pass = "bjgew6ykgu1v";
$dbname = "dbkrgc28jc3ukm";

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
