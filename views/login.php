<?php
	include_once $_SERVER['DOCUMENT_ROOT'] . "/includes.php";
	Utils::authorize([-1], "index.php");
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

			<div class="wrapper-titel-2">Meld je hier aan</div>
			<div id="login-cont">
				<form action="../controllers/RequestController.php" method="POST">
					<div class="login-input-cont">
						<div class="login-input-label">E-mailadres</div>
						<input required class="login-input-input" type="email" name="email">
					</div>
					<div class="login-input-cont">
						<div class="login-input-label">Paswoord</div>
						<input required class="login-input-input" type="password" name="password">
					</div>

					<?php  
						if(isset($_SERVER["HTTP_REFERER"])){
							echo '<input type="hidden" name="login" value="' . $_SERVER["HTTP_REFERER"] . '">';
						}
						else{
							echo '<input type="hidden" name="login" value="index.php">';
						}
					?>

					<div id="afrekenen-terms-cont">
						<div id="afrekenen-terms-subcont">
							<p class="wrapper-div-box-input-label">Ingelogd blijven</a></b></p>
							<input type="checkbox" class="wrapper-div-box-input-checkbox" name="keep-login">
							<div class="clearfix"></div>
						</div>
					</div>

					<div id="afrekenen-terms-cont">
						<div id="afrekenen-terms-subcont">
							<a href="resetpassword.php" class="link" title="Klik hier om je paswoord te resetten">Wachtwoord vergeten?</a>
							<div class="clearfix"></div>
						</div>
					</div>

					<input id="login-submit" type="submit" value="Aanmelden">
				</form>
			</div>

		</div>
	</div>



	<?php include("partials/_scripts.php"); ?>
</body>
</html>