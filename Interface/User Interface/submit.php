
<!--
Author: W3layouts
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE html>
<html>
<head>
<title>xIdent</title>
<!-- for-mobile-apps -->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<!-- //for-mobile-apps -->

<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />

</head>
<body>
	<div class="content">
	
	<div id="logo">
		<a href="index.html"><img src="images/Logo.png"/></a>
	</div>
	
			<div class="main">
				
			<div id="fade" class="fade">
				<div class="subscribe">
					<h2></h2>
<?php
 $verbindung = mysql_connect("localhost", "root" , "")
 or die ("Fehler! Das System konnte sich nicht mit der Datenbank verbinden");
mysql_select_db("gauth")
or die("Konnte die Angeforderte Datenbank nicht finden!");
$geheimniss = $_POST["gauth"];
if($geheimniss == ""){
	echo "Bitte gebe den Code erneut ein!";
}else{
	$gkey ="INSERT INTO gauth(gauth)VALUES('$geheimniss');
}
$done= mysql_query($gkey)
mysql_close($verbindung)";

?>


  					</div>
				</div>
			</div>
				
		
				

			</div>
			
			<div id="footer">
				<p class="copy_rights">&copy; 2016 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="download.html">Downloads</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="about.html">About</a></p>
			</div>
	</div>

				
</body>
</html>