<?php
	include_once $_SERVER['DOCUMENT_ROOT'] . "/includes.php";
?>

<!doctype HTML>
<html lang="nl">
<head>
	<title>404 - Pagina niet gevonden</title>
	<?php include("views/partials/_stylesheets.php"); ?>
</head>
<body>
	<?php include("views/partials/_nav.php"); ?>

	
	<div id="content-cont" class="scroll-clean">
		<div id="main-wrapper">
			
			<?php include("views/partials/_notifications.php"); ?>

			<img id='img-404-403' src='resources/images/404.jpg'>
			<a id='button-404-403' href='index.php' title='<?php echo $_SESSION['configuratie']->winkel_naam ?> homepage'><?php echo $_SESSION['configuratie']->winkel_naam ?> homepage</a>

		</div>
	</div>
	
	<?php include("views/partials/_scripts.php"); ?>
</body>
</html>