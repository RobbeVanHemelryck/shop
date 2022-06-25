<?php
	include_once $_SERVER['DOCUMENT_ROOT'] . "/shop/includes.php";
	$originalProduct = ProductModel::getProduct($_GET["id"]);
	if(!$originalProduct) Utils::notify('404.php');
	$deleted = ($originalProduct->active == 0)? true : false;
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
			
			<?php
				if($deleted) echo '<div class="wrapper-titel-2">Dit product wordt niet meer verkocht</div><br>';

				$product = $originalProduct;
				$cat = CategorieModel::getCategorie($product->categorie);
				$html = "<div id='product-detail-naam' class='scroll-none'>" . $product->naam . "</div>"
					   . "<div id='product-detail-cont'>"
					   . "<img src='" . URL . "resources/images/" . $product->img_path . "' id='product-detail-img-main'>"
					   . "<div id='product-detail-info-cont'>"

					   . "<div class='product-detail-info-subcont'>" 
					   	. "<div class='product-detail-info-label'>Klantenscore</div>"
					   	. "<div class='product-detail-info-content'>";

					   	/*Ratings bepalen*/
						$rating = $product->rating;
						$html .= "<div class='product-detail-stars-cont' title='" . round($rating, 2) . "/10'>";
						
						if($product->aantal_ratings > 0){
							for($j = 0; $j < 5; $j++){
								if($rating > 1.5) $html .= "<img src='" . URL . "resources/images/star-full.png' class='product-detail-star'>";
								else if($rating > 0.5 && $rating <= 1.5) $html .= "<img src='" . URL . "resources/images/star-half.png' class='product-detail-star'>";
								else if($rating <= 0.5) $html .= "<img src='" . URL . "resources/images/star-empty.png' class='product-detail-star'>";
								$rating -= 2;
							}
							$html .= "(" . $product->aantal_ratings . ")";
						}
						else{
							$html .= "<div class='product-detail-info-content'>Nog geen reviews</div>";
						}

					    $html .= "</div></div>"
					   . "</div>"

					   . "<div class='product-detail-info-subcont'>" 
					   	. "<div class='product-detail-info-label'>Prijs</div>"
					   	. "<div class='product-detail-info-content'>€ " . $product->prijs . "</div>"
					   . "</div>"

					   . "<div class='product-detail-info-subcont'>" 
					   	. "<div class='product-detail-info-label'>Categorie</div>"
					   	. "<div class='product-detail-info-content'>" . $cat->naam . "</div>"
					   . "</div>"

					   . "<div class='product-detail-info-subcont'>"
					   	. "<div class='product-detail-info-label' id='product-detail-beschrijving-label'>Beschrijving</div>"
					   	. "<div id='product-detail-beschrijving-content' class='scroll-clean'>" . $product->beschrijving . "</div>"
					   . "</div>"
					   . "<div id='product-detail-toegevoegd'>Toegevoegd op " . $product->datum_toegevoegd . "</div>"

					   ."</div>";

					   if(!$deleted){
					   		$html .= "<form action='../controllers/RequestController.php' method='POST'>"
							 . "<input type='hidden' name='winkelwagenAdd' value='" . $product->id . "'>"
							 . "<input type='submit' value='' name='" . $product->id . "' id='product-detail-winkelmandje' class='winkelwagen-add' title='Voeg toe aan winkelmandje'>"
						   . "</form>";
					   }
					   
					   $html .= "<div class='metadata product-metadata-id'>" . $product->id . "</div>"
					   . "<div class='metadata product-metadata-prijs'>" . $product->prijs . "</div>"
					   . "<div class='metadata product-metadata-naam'>" . $product->naam . "</div>"
					   . "<div style='display: block; clear: both;'></div></div>";

				echo $html;
			?>

			<hr-large></hr-large>

			<div class="wrapper-div" id="rating-cont">
				<div class="wrapper-div-subcont">
					<?php
						$alleReviews = ReviewModel::getAllReviews($originalProduct->id);
						if(!$deleted){
							echo '<form action="../controllers/RequestController.php" method="POST">';
							
								if(!empty($alleReviews)){
									echo "<div class='wrapper-div-titel' id='rating-add-titel'>Plaats een review</div>";
								}
								else{
									echo "<div class='wrapper-div-titel' id='rating-add-titel'>Plaats als eerste een review</div>";
								}
							?>
							
							<div class="wrapper-div-input-cont">
								<p class="wrapper-div-input-label">Score</p>
								<input required class="wrapper-div-input-input wrapper-div-input-number" type="number" min="0" max="10" name="rating-add-score">
								<div class="clearfix"></div>
							</div>
							<div class="wrapper-div-input-cont">
								<p class="wrapper-div-input-label">Titel</p>
								<input required type="text" class="wrapper-div-input-input wrapper-div-input-text" name="rating-add-title">
								<div class="clearfix"></div>
							</div>
							<div class="wrapper-div-input-cont">
								<p class="wrapper-div-input-label">Review</p>
								<textarea required class="wrapper-div-input-input wrapper-div-input-textarea" name="rating-add-comment"></textarea>
								<div class="clearfix"></div>
							</div>
							<div style="clear:both;"></div>
							<?php 
								if(isset($_SESSION['user'])){
									echo "<input class='button-blue' type='submit'>";
								}
								else{
									echo "<a href='" . URL . "views/login.php' class='button-blue button-blue-disabled'>Meld je eerst aan</a>";
								}
								echo "<input type='hidden' name='product_ID' value='".$originalProduct->id."'>";
							
							echo '<input type="hidden" name="toAddReview" value="true" >';
							echo '</form>';
						}

					?>
				
					<div id="review-hr"></div>
			
					<div id="reviews-cont">

						<?php
							foreach ($alleReviews as $review){
								$user = UserModel::getUser($review->user_id);
								$html = '<div class="review-cont">'
										. '<div class="review-header-cont">'
										. '<div class="review-header-user">';


								if(isset($_SESSION["user"])){
									if($user->id == $_SESSION["user"]->id){
										$html .= '<a title="Bekijk jouw profiel" href="' . URL . 'views/account.php">' . $user->firstname . " " . $user->lastname . '</a></div>';
									}
									else{
										$html .= '<a title="Bekijk ' . $user->firstname . '" href="' . URL . 'views/user.php?id=' . $user->id . '">' . $user->firstname . " " . $user->lastname . '</a></div>';
									}
								}
								else{
									$html .= '<a title="Bekijk ' . $user->firstname . '" href="' . URL . 'views/user.php?id=' . $user->id . '">' . $user->firstname . " " . $user->lastname . '</a></div>';
								}


								if(isset($_SESSION["user"]) && !$deleted){
									if($user->id == $_SESSION["user"]->id){
										$html .= '<a href="' . URL . 'views/detail.php?id=' . $_GET["id"] . '&edit-review=' . $review->id . '" class="review-header-img review-header-edit" title="Bewerk je review"></a>';
										$html .= '<form action="../controllers/RequestController.php" method="POST">'
													. '<input type="hidden" name="review-delete-id" value="' . $review->id . '">'
													. '<input type="submit" class="review-header-img review-header-delete" title="Verwijder je review" value="">'
												. '</form>';
									}
								}
								

								$html .=  '<div class="review-header-rating">' . $review->rating . '/10</div></div>';

								if(isset($_SESSION["user"])){
									if($user->id == $_SESSION["user"]->id){
										$html .= '<div class="review-avatar-cont"><a title="Bekijk jouw profiel" href="' . URL . 'views/account.php"><img class="review-avatar" src="../resources/images/avatars/' . $user->img_path . '"></a></div>';
									}
									else{
										$html .= '<div class="review-avatar-cont"><a title="Bekijk ' . $user->firstname . '" href="' . URL . 'views/user.php?id=' . $user->id . '"><img class="review-avatar" src="../resources/images/avatars/' . $user->img_path . '"></a></div>';
									}
								}
								else{
									$html .= '<div class="review-avatar-cont"><a title="Bekijk ' . $user->firstname . '" href="' . URL . 'views/user.php?id=' . $user->id . '"><img class="review-avatar" src="../resources/images/avatars/' . $user->img_path . '"></a></div>';
								}
								
										

								$html .= '<div class="review-title">' . $review->title . '</div>'
										. '<div class="review-comment scroll-clean">' . $review->comment . '</div>'
										. '<div style="clear:both"></div>'
										. '<div class="review-date">' . date("d/m/Y", strtotime($review->datum)) . " om " . date("H\ui", strtotime($review->datum)) . '</div>'
									. '</div>';
								echo $html;
								
							}
						?>
					
					</div>
				</div>
			</div>
			
			<hr-large></hr-large>

			<div class="wrapper-titel">Vergelijkbare producten</div>
			<div id="vergelijkbare-cont" class="producten-cont">
				<?php
		        	$allRelatableProducts = ProductModel::getAllProductenByCategorie($product->categorie);

					$allRelatableProducts = Utils::unsetValue($allRelatableProducts, $product);
					
					shuffle($allRelatableProducts);
					$randRelatableProducts = array_slice($allRelatableProducts, 0, 4);

					foreach($randRelatableProducts as $product){
						echo ContentController::makeProductHtml($product);
					}
				?>
			</div>

		</div>
	</div>


	<?php 
		//Als de user net op 'edit review' geklikt heeft
		$review_id = (isset($_GET["edit-review"]))? $_GET["edit-review"] : -1;
		if($review_id != -1){
			$review = ReviewModel::getReview($_GET["edit-review"]);
		
			//Checken of het wel degelijk de juiste user is
			if($review->user_id == $_SESSION["user"]->id){
				?>
				<div class="popup-maincont">
					<div class="popup-cont">
						<div class="popup-titel">
							Bewerk je review
							<?php
								$link = URL . 'views/detail.php?id=' . $_GET["id"];
								echo '<a href="' . $link . '" class="popup-close close" title="Sluiten"></a>';
							?>
							
						</div>
						<form class="popup-content scroll-clean" action="../controllers/RequestController.php" method="POST">
							<div class="wrapper-div-input-cont">
								<p class="wrapper-div-input-label">Score</p>
								<input class="wrapper-div-input-input" type="number" min="0" max="10" name="review-edit-rating" value="<?php echo $review->rating ?>">

								<div class="clearfix"></div>
							</div>
							<div class="wrapper-div-input-cont">
								<p class="wrapper-div-input-label">Titel</p>
								<input type="text" class="wrapper-div-input-input wrapper-div-input-text" name="review-edit-title" value="<?php echo $review->title ?>">

								<div class="clearfix"></div>
							</div>
							<div class="wrapper-div-input-cont">
								<p class="wrapper-div-input-label">Review</p>
								<textarea class="wrapper-div-input-input scroll-clean wrapper-div-input-textarea" name="review-edit-comment"><?php echo $review->comment ?></textarea>

								<div class="clearfix"></div>
							</div>
							<div style="clear:both;"></div>
							<input class='button-blue' type='submit'>
							<input type="hidden" name="review-edit-id" value="<?php echo $review->id ?>" >
						</form>
					</div>
				</div>
				<?php
			}
		}
	?>


	<?php include("partials/_scripts.php"); ?>
</body>
</html>