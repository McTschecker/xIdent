<?php 
$mURL = $_POST["tfURL"];



header("HTTP/1.1 303 See Other");
header("Location: 10.0.15.133/test.php?u=".$URL);
?>