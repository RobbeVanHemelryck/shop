<?php
	include_once $_SERVER['DOCUMENT_ROOT'] . "/shop/includes.php";
	error_reporting(E_ALL);
	ini_set("display_errors","On");

?>

<!doctype HTML>
<html lang="nl">
<head>
	<title>Home</title>
	<?php include("views/partials/_stylesheets.php"); ?>
</head>
<body>

	<?php include("views/partials/_nav.php"); ?>
	
	<div id="content-cont" class="scroll-clean">
		<div id="main-wrapper">

			<?php include("views/partials/_notifications.php"); ?>

			<div class="wrapper-titel">Uitgelichte producten</div>
			<div class="producten-cont">
				<?php
					$producten = LogicController::getUitgelichteProducten();

					shuffle($producten);
					$randProducten = array_slice($producten, 0, $_SESSION["configuratie"]->aantal_uitgelicht);

					$i = 0;
					while($i < count($randProducten) && $i < $_SESSION["configuratie"]->aantal_uitgelicht){
						echo ContentController::makeProductHtml($randProducten[$i]);
						$i++;
					}
				?>
			</div>

			<div class="wrapper-titel">Nieuwste producten</div>
				<div class="producten-cont">
					<?php
						$producten = LogicController::getNieuwsteProducten();

						$i = 0;
						while($i < count($producten) && $i < $_SESSION["configuratie"]->aantal_nieuwste){
							echo ContentController::makeProductHtml($producten[$i]);
							$i++;
						}
					?>
				</div>
			</div>
		</div>
	</div>
	
	<?php include("views/partials/_scripts.php"); ?>
</body>
</html>