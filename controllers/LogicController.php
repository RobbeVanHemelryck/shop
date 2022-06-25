<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/includes.php";

class LogicController{
	static function getUitgelichteProducten(){
		return ProductModel::getAllUitgelichteProducten();
	}

	static function getNieuwsteProducten(){
		$alleProducten = ProductModel::getAllProducten();
		Utils::sortProductsByDatum($alleProducten, "desc");
		return $alleProducten;
	}

	static function removeFromWinkelwagen($product){
		//Heel het winkelmandje doorlopen, en de goede verwijderen
		for($i = 0; $i < count($_SESSION["winkelwagen"]); $i++){
			$winkelObj = $_SESSION["winkelwagen"][$i];
			if($winkelObj[0]->id == $product->id){
				unset($_SESSION["winkelwagen"][$i]);

				//Dit dient om de indexen te updaten, om gaten te voorkomen
				$_SESSION["winkelwagen"] = array_values($_SESSION["winkelwagen"]);
			}
		}
	}

	static function addToWinkelwagen($product, $aantal = -1){
		/*Indien er nog geen winkelmandje is, er een aanmaken
		winkelmandje is opgebouwd als volgt:
			winkelmandje[
				obj: [0 (is een product), 1 (is het aantal)],
				obj: [0 (is een product), 1 (is het aantal)],
				obj: [0 (is een product), 1 (is het aantal)],
				...
			]
		*/

		if(isset($_SESSION["winkelwagen"]) == false) $_SESSION["winkelwagen"] = [];

		//De (mogelijke) index zoeken van het product in het winkelmandje
		$existingIndex = -1;
		for($i = 0; $i < count($_SESSION["winkelwagen"]); $i++){
			$winkelObj = $_SESSION["winkelwagen"][$i];
			if($winkelObj[0] == $product){
				$existingIndex = $i;
				break;
			}
		}

		//Als er een index gevonden is (het product staat er al in)
		if($existingIndex != -1){
			//Is er een specifiek aantal meegegeven? ja ? -->
			if($aantal != -1){
				if($aantal == 0){
					//Deze regel code werkt niet op de schoolserver, geen idee waarom
					self::removeFromWinkelwagen($product);
				}
				else $_SESSION["winkelwagen"][$existingIndex][1] = $aantal;
			}
			//Zo nee, gewoon het aantal vermeerderen
			else $_SESSION["winkelwagen"][$existingIndex][1]++;
		}
		//Nieuw product-aantal object toevoegen
		else{
			$newWinkelObj = [];
			$newWinkelObj[0] = $product;

			if($aantal != -1) $newWinkelObj[1] = $aantal;
			else $newWinkelObj[1] = 1;
			
			array_push($_SESSION["winkelwagen"], $newWinkelObj);
		}
	}

	static function sort($producten, $method){
		$sortBy = explode("-", $method)[0];
		$order = explode("-", $method)[1];

		if($sortBy == "prijs"){
			Utils::sortProductsByPrijs($producten, $order);
		}elseif($sortBy == "datum"){
			Utils::sortProductsByDatum($producten, $order);
		}elseif($sortBy == "naam"){
			Utils::sortProductsByNaam($producten, $order);
		}elseif($sortBy == "categorie"){
			Utils::sortProductsByCategorie($producten, $order);
		}elseif($sortBy == "beoordeling"){
			Utils::sortProductsByRating($producten, $order);
		}
		
		$html = "";
		foreach($producten as $product){
			$html .= ContentController::makeProductHtml($product);
		}

		echo $html;
	}

	public static function addReview($review){
		echo "haha";
		//nieuw gem = ((huidig gemiddelde * aantal) + ingevoerd gemiddelde)/aantal + 1
	    $product = ProductModel::getProduct($review->product_id);
	    $avgRating = $product->rating;
	    $aantal = $product->aantal_ratings;
	    $newAvg = (($avgRating * $aantal) + $review->rating)/($aantal + 1);

	    $product->rating = $newAvg;
	    $product->aantal_ratings++;

	    ProductModel::updateProduct($product);
	    ReviewModel::addReview($review);
	}
	
	public static function removeReview($review){
		//nieuw gem = ((huidig gemiddelde * aantal) - ingevoerd gemiddelde)/aantal - 1
	    $product = ProductModel::getProduct($review->product_id);
	    $avgRating = $product->rating;
	    $aantal = $product->aantal_ratings;

	    if($aantal - 1 > 0){
	    	$newAvg = (($avgRating * $aantal) - $review->rating)/($aantal - 1);
	    }
	    else{
	    	$newAvg = 0;
	    }
	    

	    $product->rating = $newAvg;
	    $product->aantal_ratings--;

	    ProductModel::updateProduct($product);
	    ReviewModel::removeReview($review->id);
	}

	public static function updateReview($newReview){
		$oldReview = ReviewModel::getReview($newReview->id);
	    $product = ProductModel::getProduct($newReview->product_id);

	    //Oude review verwijderen
	    $avgRating = $product->rating;
	    $aantal = $product->aantal_ratings;

	    if($aantal - 1 > 0){
	    	$newAvg = (($avgRating * $aantal) - $oldReview->rating)/($aantal - 1);
	    }
	    else{
	    	$newAvg = 0;
	    }

	    $aantal--;

	    //Nieuwe review toevoegen
	    $newAvg = (($newAvg * $aantal) + $newReview->rating)/($aantal + 1);

	    $product->rating = $newAvg;


	    ProductModel::updateProduct($product);
	    ReviewModel::updateReview($newReview);
	}

	public static function sendEmail($sender, $senderName, $ontvangers, $onderwerp, $inhoud){
		global $_ERRORS;
		require_once "/shop/vendor/autoload.php";

		/* https://www.sitepoint.com/sending-emails-php-phpmailer/ */

		$mail = new PHPMailer;
 
		$mail->isSMTP();                           
		$mail->Host = "smtp.gmail.com";
		$mail->SMTPAuth = true;                          
   
		$mail->Username = "robbewebshop@gmail.com";                 
		$mail->Password = "webdevadvanced";

		$mail->SMTPSecure = "tls";

		$mail->Port = 587;                                   

		$mail->From = $sender;
		$mail->FromName = $senderName;

		foreach($ontvangers as $ontvanger){
			$mail->addAddress($ontvanger[0], $ontvanger[1]);
		}

		$mail->isHTML(true);

		$mail->Subject = $onderwerp;
		$mail->Body = $inhoud;
		$mail->AltBody = "De tekst kon niet geladen worden.";

		if(!$mail->send()) 
		{
			array_push($_ERRORS['red'], 'Er is iets misgelopen bij het vesturen van je e-mail');
		    echo "Mail error: " . $mail->ErrorInfo;
		}
	}

	public static function editAccount($password, $confirmPassword, $firstname, $lastname, $email, $file){
		global $_ERRORS;
		$img_path = null;

		if(!empty($file["name"])){
	    	$uploadedFile = "../resources/images/avatars/" . $file["name"];
	    	move_uploaded_file($file["tmp_name"], $uploadedFile);
	    	$img_path = $file["name"];
	    }
	    if(!$img_path) $img_path = $_SESSION["user"]->img_path;

		/*Als de user zijn paswoord veranderd heeft*/
		if($password != $_SESSION["user"]->password){
			if($password == $confirmPassword){
				$password = password_hash($password, PASSWORD_DEFAULT);
			}
			else{
				array_push($_ERRORS['red'], 'De wachtwoorden komen niet overeen');
			}
			
		}

		/*Als er al een andere user bestaat met het nieuwe e-mailadres*/
		$tempUser = UserModel::getUserByEmail($email);
		if($tempUser && ($tempUser->id != $_SESSION["user"]->id)){
			array_push($_ERRORS['red'], 'Dit e-mailadres is al bezet');
		}

		if(!empty($_ERRORS["red"])){
			Utils::notify("views/account.php?edit=true", $_ERRORS);
		}
		else{
			$updatedUser = $_SESSION["user"];
			$updatedUser->password = $password;
			$updatedUser->firstname = $firstname;
			$updatedUser->lastname = $lastname;
			$updatedUser->email = $email;
			$updatedUser->img_path = $img_path;

			UserModel::updateUser($updatedUser);

			$_SESSION["user"] = $updatedUser;

			Utils::notify("views/account.php", 'Je account is succesvol bijgewerkt');
		}

		
	}

	public static function search($keyword){
		$found = [
			"naam" => [],
			"beschrijving" => [],
			"categorie" => []
		];
		$productenNaam = ProductModel::search("naam", $keyword);
		$productenBeschrijvingBeta = ProductModel::search("beschrijving", $keyword);
		$productenBeschrijving = array_udiff($productenBeschrijvingBeta, $productenNaam, 'Utils::productComparator');
		$productenCategorie = ProductModel::search("categorie", $keyword);

		foreach($productenNaam as $product){
			array_push($found["naam"], $product->id);
		}
		foreach($productenBeschrijving as $product){
			array_push($found["beschrijving"], $product->id);
		}
		foreach($productenCategorie as $product){
			array_push($found["categorie"], $product->id);
		}

		return $found;
	}
}

?>