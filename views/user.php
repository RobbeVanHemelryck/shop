<?php
	include_once $_SERVER['DOCUMENT_ROOT'] . "/includes.php"; 	
	$user = UserModel::getUser($_GET["id"]); 
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
		
			<div id="account-cont">
				<div id="account-titel"><?php echo $user->firstname ?>'s profiel</div>
				<div id="account-avatar-cont">
					<div class="img-height-helper">
						<?php
							echo "<img id='account-avatar' src='/resources/images/avatars/" . $user->img_path . "'>";
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
				</div>

				<?php
					if($user->facebook_id){
						echo "<a href='http://www.facebook.com/" . $user->facebook_id . "' title='Dit account is gelinkt met Facebook' id='account-facebooklogo'></a>";
					}
				?>

				<div style="clear:both"></div>
			</div>
		</div>
	</div>

	<?php include("partials/_scripts.php"); ?>
</body>
</html>