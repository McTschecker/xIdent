
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

        echo '<li>First create a secret and associate it with a user';
        $secret = $tfa->createSecret();
        echo '<li>Next create a QR code and let the user scan it:<br><img src="' . $tfa->getQRCodeImageAsDataUri('My label', $secret) . '"><br>...or display the secret to the user for manual entry: ' . chunk_split($secret, 4, ' ');
        $code = $tfa->getCode($secret);
        echo '<li>Next, have the user verify the code; at this time the code displayed by a 2FA-app would be: <span style="color:#00c">' . $code . '</span> (but that changes periodically)';
        echo '<li>When the code checks out, 2FA can be / is enabled; store (encrypted?) secret with user and have the user verify a code each time a new session is started.';
        echo '<li>When aforementioned code (' . $code . ') was entered, the result would be: ' . (($tfa->verifyCode($secret, $code) === true) ? '<span style="color:#0c0">OK</span>' : '<span style="color:#c00">FAIL</span>');
        ?>
					<div class="contact-form">
						<form action="test.html" method="post">
							<input type="text" value="Auth Key" name="key" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Auth Key';}" required="">	
						    <input type="submit" value="Login">
                             
						</form>
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
