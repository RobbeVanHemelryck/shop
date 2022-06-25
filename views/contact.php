<?php
	include_once $_SERVER['DOCUMENT_ROOT'] . "/includes.php";
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
			
			<div class="wrapper-titel">Contacteer ons via e-mail</div>

			<div id="account-cont">
				<form action="../controllers/RequestController.php" method="POST">

					<div class="account-info-label cursor-default">Onderwerp</div>
					<input type="text" name="onderwerp" class="account-info-data" value="">

					<div class="account-info-label cursor-default">Inhoud</div>
					<input type="text" name="inhoud" class="account-info-data" value="">

					<input type="hidden" name="email-send" value="true">

					<input type="submit" class="button-blue" value="Stuur">

				</form>
				<div style="clear:both"></div>
			</div>
		</div>
	</div>

	<?php include("partials/_scripts.php"); ?>
</body>
</html>