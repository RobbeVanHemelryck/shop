<?php
	include_once $_SERVER['DOCUMENT_ROOT'] . "/shop/includes.php";

	//Checken of de user ingelogd wou blijven
	if(!isset($_SESSION["user"]) && isset($_COOKIE["saved-login"])){
		$login = unserialize($_COOKIE["saved-login"]);
		$user = UserModel::getUserByEmail($login["email"]);
		if($login["password"] == $user->password){
			$_SESSION["user"] = $user;
		}
	}
?>
<div id="nav-cont">
	<div id="nav-cont-top">
		<?php echo "<div id='titel'><a title='Home' href='" . URL . "index.php'>" . $_SESSION["configuratie"]->winkel_naam . "</a></div>" ?>
		<div id="nav-winkelmandje" class="nav-winkelmandje-account-btn" title="Winkelmandje"></div>
		<div id="nav-winkelmandje-dropdown" class="nav-winkelmandje-account-dropdown">
			<?php
				echo ContentController::makeWinkelmandjePreviewHtml();
			?>
		</div>

		<div id="nav-account" class="nav-winkelmandje-account-btn" title="Jouw account"></div>
		<div id="nav-account-dropdown" class="scroll-none nav-winkelmandje-account-dropdown">
			<?php
				$ingelogd = false;
				if(isset($_SESSION["user"])){
					$ingelogd = true;
				}


				$html = "";
				if($ingelogd){
					$html .= "<div href='' class='nav-account-dropdown-item' id='nav-account-dropdown-item-head'>"
					      . "Welkom " . $_SESSION["user"]->firstname
					      . "</div>"
					      .  "<a class='nav-account-dropdown-item' href='" . URL . "controllers/RequestController.php?logout=true'>Afmelden</a>"
					      . "<a class='nav-account-dropdown-item' href='" . URL . "views/bestellingen.php'>Jouw bestellingen</a>"
				      	  . "<a class='nav-account-dropdown-item' href='" . URL . "views/account.php'>Jouw profiel</a>";
				}
				else{
					$fbUrl = FacebookController::getLoginUrl();
					$html .= "<a class='nav-account-dropdown-item' href='" . URL . "views/login.php'>Aanmelden</a>"
						  . "<a class='nav-account-dropdown-item' href='" . $fbUrl . "'>Aanmelden met Facebook</a>"
						  . "<a class='nav-account-dropdown-item' href='" . URL . "views/register.php'>Maak een account</a>";
				}
				

				if(isset($_SESSION["user"]) && $_SESSION["user"]->authority == 1){
					$html .= "<a class='nav-account-dropdown-item' href='" . URL . "views/admin/overview.php'>Admin</a>";
				}

				echo $html;
			?>
		</div>
	</div>
	<div id="nav-cont-bottom">
		<div id="nav-cont-bottom-subcont">
			
		<a class='nav-cont-bottom-item' href='<?php echo URL ?>views/browse.php'>Alle producten</a>
		<a class='nav-cont-bottom-item' href='<?php echo URL ?>views/contact.php'>Contact</a>

		</div>

		<div id="zoeken-cont">
			<form action="<?php echo URL ?>controllers/RequestController.php" method="POST">
				<input id="zoeken-zoekbalk" type="text" name="zoek-keyword" placeholder="Product, categorie, ...">
				<input id="zoeken-submit" type="submit" title="zoeken.php" value="">
			</form>
		</div>

	</div>
</div>