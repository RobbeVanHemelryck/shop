<?php
	include_once $_SERVER['DOCUMENT_ROOT'] . "/shop/includes.php";
?>

<!doctype HTML>
<html lang="nl">
<head>
	<title>Home</title>
	<?php include("partials/_stylesheets.php"); ?>
</head>
<body>
	<?php include("partials/_nav.php"); ?>
	

	<div id="content-cont">
		<div id="main-wrapper-shadow"></div>
		<div id="main-wrapper">

			<?php include("partials/_notifications.php"); ?>
		
			<h2>Geef de gegevens voor je account in</h2>
			<div id="login-cont">
				<form action="../controllers/RequestController.php" method="POST">
					<div class="login-input-cont">
						<div class="login-input-label">Voornaam</div>
						<input required class="login-input-input" type="text" name="register-firstname">
					</div>
					<div class="login-input-cont">
						<div class="login-input-label">Achternaam</div>
						<input required class="login-input-input" type="text" name="register-lastname">
					</div>
					<div class="login-input-cont">
						<div class="login-input-label">E-mailadres</div>
						<input required class="login-input-input" type="email" name="register-email">
					</div>
					<div class="login-input-cont">
						<div class="login-input-label">Paswoord</div>
						<input required class="login-input-input" type="password" name="register-password">
					</div>
					<div class="login-input-cont">
						<div class="login-input-label">Bevestig paswoord</div>
						<input required class="login-input-input" type="password" name="register-confirmpassword">
					</div>
					<input type="hidden" name="register">
					<input id="login-submit" type="submit" value="Verzend">
				</form>
			</div>
			

		</div>
	</div>



	<?php include("partials/_scripts.php"); ?>
</body>
</html>