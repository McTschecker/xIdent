
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
        require_once 'loader.php';
        Loader::register('../lib','RobThree\\Auth');
        
        use \RobThree\Auth\TwoFactorAuth;

        $tfa = new TwoFactorAuth('xIdent');

        echo '<li>Hier wird ein <b<<span style="color:#c00">Geheimnis</span></b> ';
        $secret = $tfa->createSecret();
        echo '<li>Hier können sie wenn sie wollen eine QR code scannen um das secret direkt zu erhalten:<br><img src="' . $tfa->getQRCodeImageAsDataUri('xIdent', $secret) . '"><br>... oder sie schreiben es hier ab ' . chunk_split($secret, 4, ' ');
        $code = $tfa->getCode($secret);
        echo '<li>Nun können sie vergleichen ob der Generierte code ihrem übereinstimmt! <o>Ansonsten Fangen sie bitte von vorne an!<p><i><span style="color:#00c">' . $code . '</span> (aber das ändert sich alle 30 Sekunden)';
        echo '<li>Wenn der Code übereinstimmt klicke auf code stimmt überein';
        echo '<li>When aforementioned code (' . $code . ') was entered, the result would be: ' . (($tfa->verifyCode($secret, $code) === true) ? '<span style="color:#0c0">OK</span>' : '<span style="color:#c00">Fehler</span>');
        ?>
                    <p></p>
					<button type="button" onclick="savecode()" id="Code stimmt überein">Code stimmt überein</button>
					</div>
				</div>
			</div>
				
		
				

			</div>
			
			<div id="footer">
				<p class="copy_rights">&copy; 2016 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="download.html">Downloads</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="about.html">About</a></p>
			</div>
	</div>
    <?php
function savecode ()
{
   $pdo = new PDO('mysql:host=127.0.1;dbname=gauth', 'setup1', 'P4Tra8S34ZH9pmdb');
 
$statement = $pdo->prepare("INSERT INTO gauth (gauth) VALUES (gauth)");
$statement->execute($secret);    


}
?>
				
</body>
</html>
