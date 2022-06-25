<?php
	include_once $_SERVER['DOCUMENT_ROOT'] . "/includes.php";
	Utils::authorize([0,1], "index.php");
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
			
			<?php if(!isset($_GET["edit"])){ ?>
				<div id="account-cont">
					<div id="account-titel">Jouw profiel</div>
					<div id="account-avatar-cont">
						<div class="img-height-helper">
							<?php
								$user = $_SESSION["user"];
								echo "<img id='account-avatar' src='" . URL . "resources/images/avatars/" . $user->img_path . "'>";
							?>
						</div>
					</div>
					<div id="account-info-cont">
						<div class="account-info-subcont">
							<div class="account-info-label cursor-default">Voornaam</div>
							<div class="account-info-data cursor-default"><?php echo $user->firstname ?></div>
						</div>
						<div class="account-info-subcont cursor-default">
							<div class="account-info-label cursor-default">Achternaam</div>
							<div class="account-info-data cursor-default"><?php echo $user->lastname ?></div>
						</div>
						<div class="account-info-subcont cursor-default">
							<div class="account-info-label cursor-default">E-mailadres</div>
							<div class="account-info-data cursor-default">
								<?php 
									if($user->email) echo $user->email;
									else echo "Nog niet ingesteld";
								?>
							</div>
						</div>
						<div class='account-info-label cursor-default'>Paswoord</div>
						<input disabled type='password' class='cursor-default account-info-data cursor-default' value='********'>
						
					</div>

					<?php
						if($user->facebook_id){
							echo "<a href='http://www.facebook.com/" . $user->facebook_id . "' title='Je account is gelinkt met Facebook' id='account-facebooklogo'></a>";
						}
						else{
							$fbUrl = FacebookController::getLoginUrl();
							echo "<a href='" . $fbUrl . "' title='Link je account met Facebook' id='account-facebooklogo'></a>";
						}
					?>

					<div style="clear:both"></div>
					<a id="account-edit" class="button-green" title="Pas je gegevens aan" href="<?php echo URL ?>views/account.php?edit">Aanpassen</a>
				</div>
			<?php 
				}
				else{
			?>

				<div id="account-cont">
					<form action="../controllers/RequestController.php" method="POST" enctype="multipart/form-data">
						<div id="account-titel">Jouw profiel</div>
						<div id="account-avatar-cont">
							<div class="img-height-helper">
								<?php
									$user = $_SESSION["user"];
									echo "<img id='account-avatar' src='" . URL . "resources/images/avatars/" . $user->img_path . "'>";
								?>
							</div>
							<input type="file" name="avatar">
							
						</div>
						<div id="account-info-cont">
							<div class="account-info-subcont">
								<div class="account-info-label cursor-default">Voornaam</div>
								<input type="text" name="firstname" class="account-info-data input-editable" value="<?php echo $user->firstname ?>">;
								
							</div>
							<div class="account-info-subcont">
								<div class="account-info-label cursor-default">Achternaam</div>
								<input type="text" name="lastname" class="account-info-data input-editable" value="<?php echo $user->lastname ?>">
							</div>
							<div class="account-info-subcont">
								<div class="account-info-label cursor-default">E-mailadres</div>
								<input type='email' name='email' class='account-info-data input-editable' value='<?php echo $user->email ?>'>
							</div>

							<div class='account-info-label'>Paswoord</div>
							<input type='password' name='password' class='account-info-data input-editable' value='<?php echo $user->password ?>'>
							<div class='account-info-label'>Bevestig paswoord</div>
							<input type='password' name='confirmPassword' class='account-info-data input-editable' value='<?php echo $user->password ?>'>
							
						</div>

						<?php
							if($user->facebook_id){
								echo "<a href='http://www.facebook.com/" . $user->facebook_id . "' title='Dit account is gelinkt met Facebook' id='account-facebooklogo'></a>";
							}
							else{
								$fbUrl = FacebookController::getLoginUrl();
								echo "<a href='" . $fbUrl . "' title='Link je account met Facebook' id='account-facebooklogo'></a>";
							}
						?>

						<div style="clear:both"></div>
						<input type="hidden" name="account-edit" value="true">
						<input type="submit" id="account-edit" class="button-green" value="Klaar">
					</form>
				</div>

			<?php 

				} 

				if(isset($_GET["fb-link-popup"])){
					
				}

			?>
		</div>
	</div>

	<?php include("partials/_scripts.php"); ?>
</body>
</html>