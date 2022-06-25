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

			<?php 

				include("partials/_notifications.php"); 

				$permitted = false;

				if(!empty($_GET)){
					//Checken of de user wel de echte persoon is

					//Checken of de PasswordReset klopt
					$email = isset($_GET["email"])? $_GET["email"] : '';
					$passwordReset = PasswordResetModel::getPasswordResetsByEmail($email)[0];

					if($passwordReset){
						$now = new DateTime("now");
						$resetDate = date_create_from_format("Y-m-d H:i:s", $passwordReset->datum);
						$interval = date_diff($resetDate, $now)->h;


						if($passwordReset->id == $_GET["id"] && $interval == 0){

							//Checken of de usergegevens kloppen
							$userId = isset($_GET["user-id"])? $_GET["user-id"] : '';
							$user = UserModel::getUser($userId);
							if($user){
								if($_GET["user-password"] == $user->password){
									$permitted = true;
								}
							}
						}
					}
					if(!$permitted){
						Utils::notify("index.php", 'Deze pagina is niet beschikbaar', 'red');
					}
				}

			?>

			<?php 

				if(!$permitted){
					?>
					<div class="wrapper-titel-2">Er zal een reset e-mail gestuurd worden naar dit e-mailadres</div>
					<div id="login-cont">
						<form action="../controllers/RequestController.php" method="POST">
							<div class="login-input-cont">
								<div class="login-input-label">E-mailadres</div>
								<input required class="login-input-input" type="email" name="email">
							</div>
							<input type="hidden" name="password-reset-request" value="index">
							<input id="login-submit" type="submit" value="Reset paswoord">
						</form>
					</div>
					<?php 
				}
				else{
					?>
					<div class="wrapper-titel-2">Vul je nieuw paswoord in</div>
					<div id="login-cont">
						<form action="../controllers/RequestController.php" method="POST">
							<div class="login-input-cont">
								<div class="login-input-label">Paswoord</div>
								<input required class="login-input-input" type="password" name="reset-password">
							</div>
							<div class="login-input-cont">
								<div class="login-input-label">Bevestig paswoord</div>
								<input required class="login-input-input" type="password" name="reset-confirmpassword">
							</div>

							<input type="hidden" name="reset-user" value="<?php echo $_GET['user-id'] ?>">
							<input type="hidden" name="password-reset-submit" value="true">
							<input id="login-submit" type="submit" value="Reset paswoord">
						</form>
					</div>
					<?php
				}
			?>
		</div>
	</div>



	<?php include("partials/_scripts.php"); ?>
</body>
</html>