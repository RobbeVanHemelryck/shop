<?php
	$errors = (isset($_COOKIE["errors"]))? unserialize($_COOKIE["errors"]) : null;

	if($errors){
		echo '<div id="notifications-cont">';
		foreach($errors["red"] as $error){
			echo '<div class="notification-cont notification-red">';
			echo '<div class="notification-message">' . $error . '</div>';
			echo '<div class="notification-close close"></div>';
			echo '</div>';
		}
		foreach($errors["green"] as $error){
			echo '<div class="notification-cont notification-green">';
			echo '<div class="notification-message">' . $error . '</div>';
			echo '<div class="notification-close close"></div>';
			echo '</div>';
		}
		echo '</div>';
	}
	setcookie("errors", null, 1, '/');
?>