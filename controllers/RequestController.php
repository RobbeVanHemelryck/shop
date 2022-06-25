<?php

	include_once $_SERVER['DOCUMENT_ROOT'] . "/shop/includes.php";

	define('URL', 'https://projects.taltiko.com/shop/');
	if(isset($_SERVER['HTTP_REFERER'])){
		define('PREV_URL', str_replace(URL, "", $_SERVER['HTTP_REFERER']));
	}
	else{
		define('PREV_URL', 'index.php');
	}

	$_ERRORS = ["red" => [], "green" => []];

	date_default_timezone_set("Europe/Amsterdam");

	session_start();
	$_SESSION["configuratie"] = ConfiguratieModel::getConfiguratie();


	if(isset($_POST["winkelwagenAdd"])){
		$productId = $_POST["winkelwagenAdd"];
		$product = ProductModel::getProduct($productId);

		if(!$product){
			Utils::notify(PREV_URL, 'Er is een probleem opgetreden', 'red');
		}
		else if($product->active == 0){
			Utils::notify(PREV_URL, 'Dit product wordt niet meer verkocht', 'red');
		}
		else{
			LogicController::addToWinkelwagen($product);
			echo ContentController::makeWinkelmandjePreviewHtml();

			if(!isset($_POST["ajax"])){
				header("Location: " . URL .  PREV_URL);
			}
		}
		
	}

	if(isset($_POST["winkelmandje-update-id"])){
		$aantal = $_POST["overview-update-aantal"];
		$productId = $_POST["winkelmandje-update-id"];

		$product = ProductModel::getProduct($productId);
		LogicController::addToWinkelwagen($product, $aantal);
		
		Utils::notify("views/winkelmandje.php", 'Aantal is succesvol gewijzigd');
	}

	if(isset($_POST["winkelmandje-delete-id"])){
		$product = ProductModel::getProduct($_POST["winkelmandje-delete-id"]);
		LogicController::removeFromWinkelwagen($product);

		Utils::notify("views/winkelmandje.php", 'Product is succesvol verwijderd');
	}

	if(isset($_GET["logout"])){
		unset($_SESSION["user"]);
		if(isset($_COOKIE["saved-login"])){
			setcookie("saved-login", null, 1, '/');
		}
		Utils::notify(PREV_URL,'Je bent succesvol uitgelogd');
	}

	if(isset($_POST["register"])){
		$firstname = isset($_POST["register-firstname"])? $_POST["register-firstname"] : '';
		$lastname = isset($_POST["register-lastname"])? $_POST["register-lastname"] : '';
		$email = isset($_POST["register-email"])? $_POST["register-email"] : '';
		$password = isset($_POST["register-password"])? $_POST["register-password"] : '';
		$confirmPassword = isset($_POST["register-confirmpassword"])? $_POST["register-confirmpassword"] : '';
		$img_path = "default-user.png";

		//Error handling
		if(empty($firstname)) array_push($_ERRORS['red'], "Gelieve je voornaam in te vullen");
		if(empty($lastname)) array_push($_ERRORS['red'], "Gelieve je achternaam in te vullen");
		if(empty($email)) array_push($_ERRORS['red'], "Gelieve je e-mailadres in te vullen");
		if(empty($password) || empty($confirmPassword)) array_push($_ERRORS['red'], "Gelieve beide paswoorden in te vullen");
		if($password != $confirmPassword) array_push($_ERRORS['red'], "De wachtwoorden komen niet overeen");
		if(UserModel::getUserByEmail($email)) array_push($_ERRORS['red'], "Dit e-mailadres is al bezet");

		if(!empty($_ERRORS['red'])){
			Utils::notify("views/register.php", $_ERRORS);
		}
		else{
			$password = password_hash($password, PASSWORD_DEFAULT);
			$user = new UserEntity(null, $password, $lastname, $firstname, 0, $email, null, $img_path, 1);

			$id = UserModel::addUser($user);
			$user->id = $id;

			$_SESSION["user"] = $user;

			Utils::notify("index.php", "Je account is succesvol aangemaakt! Welkom'");
		}
	}

	if(isset($_POST["login"])){

		//Als de vorige URL nog niet is ingesteld, deze instellen (indien er bvb een foutmelding wordt gegenereerd, zou de echte vorige URL vervangen worden door 'login' zelf)
		$_SESSION["login-prev"] = (isset($_SESSION["login-prev"]))? $_SESSION["login-prev"] : $_POST["login"];

		$user = UserModel::getUserByEmail($_POST["email"]);

		if(!$user){
			Utils::notify("views/login.php", "Er bestaat geen account met dit e-mailadres", "red");
		}
		else{
			if(password_verify($_POST["password"], $user->password)){
				$_SESSION["user"] = $user;
				
				$url = str_replace(URL, "", $_SESSION["login-prev"]); 
				unset($_SESSION["login-prev"]);

				//De user in een cookie steken opdat hij ingelogd kan blijven
				$keepLogin = isset($_POST["keep-login"])? true : false;
				if($keepLogin){
					$logindata = ["email" => $user->email, "password" => $user->password];
					setcookie("saved-login", serialize($logindata), mktime(0, 0, 0, 12, 31, 2030), '/');
				}

				Utils::notify($url, 'Je bent succesvol ingelogd. Welkom!');
			}
			else{
				Utils::notify("views/login.php", 'Het ingevulde wachtwoord is niet correct', 'red');
			}
		}
	}

	if(isset($_POST["password-reset-request"])){

		$email = isset($_POST["email"])? $_POST["email"] : "";
		if(empty($email)) Utils::notify('views/resetpassword.php', 'Gelieve een e-mailadres in te geven', 'red');
		else{
			$user = UserModel::getUserByEmail($email);
			if(!$user) Utils::notify('views/resetpassword.php', 'Er bestaat geen account met dit e-mailadres', 'red');
			else{
				$passwordReset = new PasswordResetEntity(null, $email, date("Y-m-d H:i:s", time()));
				$id = PasswordResetModel::addPasswordReset($passwordReset);

				$sender = "robbewebshop@gmail.com";
				$senderName = $_SESSION["configuratie"]->winkel_naam;
				$ontvangers = [[$email, $user->firstname . " " . $user->lastname]];
				$onderwerp = $_SESSION["configuratie"]->winkel_naam . " - Paswoord reset van " . $email;

				$url = URL . '/views/resetpassword.php?id=' . $id . '&user-id=' . $user->id . '&email=' . $email . '&user-password=' . $user->password;
				$inhoud = '<a href="' . $url . '">Klik hier om je paswoord te resetten</a>';


				LogicController::sendEmail($sender, $senderName, $ontvangers, $onderwerp, $inhoud);
				Utils::notify("views/login.php", "Er is een e-mail verzonden naar " . $email);
			}
		}
	}

	if(isset($_POST["password-reset-submit"])){
		$password = isset($_POST["reset-password"])? $_POST["reset-password"] : "";
		$confirmPassword = isset($_POST["reset-confirmpassword"])? $_POST["reset-confirmpassword"] : "";

		if(empty($password) || empty($confirmPassword)) Utils::notify(PREV_URL, 'Gelieve je paswoord 2x in te vullen', 'red');
		else if($password != $confirmPassword) Utils::notify(PREV_URL, 'De paswoorden komen niet overeen', 'red');
		else{
			$user = UserModel::getUser($_POST["reset-user"]);
			if(!$user){
				Utils::notify(PREV_URL, 'Er is iets misgegaan bij het resetten van je paswoord', 'red');
			}
			else{
				$user->password = password_hash($password, PASSWORD_DEFAULT);
				UserModel::updateUser($user);
				Utils::notify("views/login.php", 'Je paswoord is succesvol gewijzigd');
			}
		}
	}

	if(isset($_GET["fb-login-redirect"])){
		FacebookController::login();
	}

	if(isset($_POST["sortMethod"])){
		$producten = [];

		if(!empty($_POST["filters"])){
			foreach($_POST["filters"] as $filter) {
				$cat = CategorieModel::getCategorieByNaam($filter);
		        $producten = array_merge($producten, ProductModel::getAllProductenByCategorie($cat->id));
		    }
		}
		else{
			$producten = ProductModel::getAllProducten();
		}

		LogicController::sort($producten, $_POST["sortMethod"]);

	}

	if(isset($_POST["onlyFilter"])){
		$producten = [];
		$pagina = $_POST['pagina'];
		$perPagina = $_POST['perPagina'];
		$filters = empty($_POST["filters"])? [] : $_POST["filters"];

		$filtersString = '';

		if(!empty($filters)){
			$filtersString = Utils::arrayToUrl($filters);
			foreach($filters as $filter) {
		        $cat = CategorieModel::getCategorieByNaam($filter);
		        $producten = array_merge($producten, ProductModel::getAllProductenByCategorie($cat->id));
		    }
		}
		else{
			$producten = ProductModel::getAllProducten();
		}

		$aantalPaginas = ceil(count($producten)/$perPagina);

		$producten = array_slice($producten, ($pagina-1) * $perPagina, $perPagina);

		$html = array('producten' => '', 'paginas' => '');
		foreach($producten as $product){
			$html['producten'] .= ContentController::makeProductHtml($product);
		}


		$html['paginas'] = ($aantalPaginas > 1)? ContentController::makePaginasHtml($aantalPaginas, $pagina, $filters) : '';
		echo json_encode($html);

	}

	if (isset($_POST['review-edit-id'])){
		$review = ReviewModel::getReview($_POST["review-edit-id"]);

		$review->rating = $_POST["review-edit-rating"];
		$review->title = $_POST["review-edit-title"];
		$review->comment = $_POST["review-edit-comment"];
		$review->datum = date("Y-m-d H:i:s", time());

	    LogicController::updateReview($review);

	    Utils::notify("views/detail.php?id=" . $review->product_id, 'Review is succesvol gewijzigd');
	}

	if (isset($_POST['review-delete-id'])){
		$review = ReviewModel::getReview($_POST["review-delete-id"]);

	    LogicController::removeReview($review);

	    Utils::notify("views/detail.php?id=" . $review->product_id, 'Review is succesvol verwijderd');
	}

	if (isset($_POST['toAddReview'])){
		$userId = $_SESSION['user']->id;
		$productId = $_POST['product_ID'];
		$title = $_POST['rating-add-title'];
		$comment = $_POST['rating-add-comment'];
		$score = $_POST['rating-add-score'];

		if(empty($title)){
			array_push($_ERRORS['red'], 'Gelieve je review een titel te geven');
		}
		if($score == '' || $score < 0 || $score > 10){
			array_push($_ERRORS['red'], 'Gelieve je review een score tussen 0 en 10 te geven');
		}
		if(empty($comment)){
			array_push($_ERRORS['red'], 'Gelieve je review inhoud te geven');
		}

		if(!empty($_ERRORS['red'])){
			Utils::notify("views/detail.php?id=" . $productId, $_ERRORS);
		}
		else{
			$review = new ReviewEntity(null, $userId, $productId, $comment, $score, date("Y-m-d H:i:s", time()), $title);

		    LogicController::addReview($review);

		    //Ik had een bug (ENKEL op de schoolserver) waar de variabelen in de POST bleven, waardoor de functie 2x werd aangeroepen --> POST
		    $_POST = [];

		    Utils::notify("views/detail.php?id=" . $productId, 'Review is succesvol toegevoegd');
		}
	    
	}

	if(isset($_POST["producten-filters"])){
		$page = $_POST["producten-filters-page"];

	    $filtersString = Utils::arrayToUrl($_POST['producten-filters']);

	    Utils::notify("views/browse.php?&filters=" . $filtersString . "&pagina=" . $page);
	}

	if(isset($_POST["afrekenen"])){
		$lever_straat = isset($_POST["lever-straat"])? $_POST["lever-straat"] : '';
		$lever_huisnummer = isset($_POST["lever-huisnummer"])? $_POST["lever-huisnummer"] : '';
		$lever_gemeente = isset($_POST["lever-gemeente"])? $_POST["lever-gemeente"] : '';
		$lever_postcode = isset($_POST["lever-postcode"])? $_POST["lever-postcode"] : '';

		$same = isset($_POST["factuur-checkbox"])? true : false;

		if(!$same){
			$factuur_straat = isset($_POST["factuur-straat"])? $_POST["factuur-straat"] : '';
			$factuur_huisnummer = isset($_POST["factuur-huisnummer"])? $_POST["factuur-huisnummer"] : '';
			$factuur_gemeente = isset($_POST["factuur-gemeente"])? $_POST["factuur-gemeente"] : '';
			$factuur_postcode = isset($_POST["factuur-postcode"])? $_POST["factuur-postcode"] : '';
		}
		else{
			$factuur_straat = $lever_straat;
			$factuur_huisnummer = $lever_huisnummer;
			$factuur_gemeente = $lever_gemeente;
			$factuur_postcode = $lever_postcode;
		}

		$betaalmethode_id = isset($_POST["betaalmethode"])? $_POST["betaalmethode"] : '';
		$levermethode_id = isset($_POST["levermethode"])? $_POST["levermethode"] : '';

		$akkoord = isset($_POST["afrekenen-akkoord"])? true : false;

		//Server-side error handling
		if(empty($lever_straat) || empty($lever_huisnummer) || empty($lever_gemeente) || empty($lever_postcode)){
			array_push($_ERRORS['red'], ["red", "Gelieve het leveradres volledig in te vullen"]);
		}
		if(!$same && (empty($factuur_straat) || empty($factuur_huisnummer) || empty($factuur_gemeente) || empty($factuur_postcode))){
			array_push($_ERRORS['red'], "Gelieve het factuuradres volledig in te vullen");
		}
		if(empty($betaalmethode_id)){
			array_push($_ERRORS['red'], "Gelieve een betaalmethode te selecteren");
		}
		else if(!BetaalmethodeModel::getBetaalmethode($betaalmethode_id)){
			array_push($_ERRORS['red'], "Gelieve een geldige betaalmethode te selecteren");
		}
		if(empty($levermethode_id)){
			array_push($_ERRORS['red'], "Gelieve een levermethode te selecteren");
		}
		else if(!LevermethodeModel::getLevermethode($levermethode_id)){
			array_push($_ERRORS['red'], "Gelieve een geldige levermethode te selecteren");
		}
		if(!$akkoord) array_push($_ERRORS['red'], "Gelieve akkoord te gaan met de algemene voorwaarden");
 
		if(!empty($_ERRORS['red'])){
			Utils::notify("views/winkelmandje.php?afrekenen", $_ERRORS);
		}


		else{
			$user_id = $_SESSION["user"]->id;
			$datum = date("Y-m-d H:i:s", time());

			//Totaal berekenen
			$totaal = 0;
		    foreach($_SESSION["winkelwagen"] as $productObj){
		    	$totaal += $productObj[0]->prijs * $productObj[1];
		    }
		    $betaalmethode = BetaalmethodeModel::getBetaalmethode($betaalmethode_id);
		    $levermethode = LevermethodeModel::getLevermethode($levermethode_id);
		    $betaalprocent = $totaal * $betaalmethode->kosten_procent;
		    $leverprocent = $totaal * $levermethode->kosten_procent;
		    $totaal += $betaalprocent + $leverprocent + $betaalmethode->kosten_geld + $levermethode->kosten_geld;

			//Bestelling toevoegen
			$bestelling = new BestellingEntity(null, $lever_straat, $lever_huisnummer, $lever_gemeente, $lever_postcode, $factuur_straat, $factuur_huisnummer, $factuur_gemeente, $factuur_postcode, $levermethode_id, $betaalmethode_id, $user_id, $datum, $totaal);

		    $id = BestellingModel::addBestelling($bestelling);

		    //Producten van de bestelling toevoegen
		    foreach($_SESSION["winkelwagen"] as $productObj){
		    	$bestellingProduct = new BestellingProductEntity($id, $productObj[0]->id, $productObj[1]);
		    	BestellingProductModel::addBestellingProduct($bestellingProduct);
		    }

		    unset($_SESSION["winkelwagen"]);
		    Utils::notify("views/bestellingsoverzicht.php?id=" . $id, 'Je bestelling is succesvol geplaatst');
		}
	}

	if(isset($_POST["account-edit"])){

		//Bepalen welke data veranderd is en welke niet
		$password = isset($_POST["password"])? $_POST["password"] : null;
		$confirmPassword = isset($_POST["confirmPassword"])? $_POST["confirmPassword"] : null;
		$firstname = isset($_POST["firstname"])? $_POST["firstname"] : $_SESSION["user"]->firstname;
		$lastname = isset($_POST["lastname"])? $_POST["lastname"] : $_SESSION["user"]->lastname;
		$file = isset($_FILES["avatar"])? $_FILES["avatar"] : null;
		$email = $_POST["email"];
		
		LogicController::editAccount($password, $confirmPassword, $firstname, $lastname, $email, $file);
	}
	
	if(isset($_POST["email-send"])){
		$ontvangers = UserModel::getAllUsers();
		$ontvangerMails = [];
		foreach($ontvangers as $ontvanger){
			if($ontvanger->authority == 1){
				array_push($ontvangerMails, [$ontvanger->email, $_SESSION["configuratie"]->winkel_naam . " - " . $ontvanger->firstname . " " . $ontvanger->lastname]);
			}
		}
		$sender = $_SESSION["user"]->email;
		$senderName = $_SESSION["user"]->firstname . ' ' . $_SESSION["user"]->lastname;
		$onderwerp = $_POST["onderwerp"];
		$inhoud = $_POST["inhoud"];

		LogicController::sendEmail($sender, $senderName, $ontvangerMails, $onderwerp, $inhoud);

		if(!empty($_ERRORS["red"])){
			Utils::notify("views/contact.php", $_ERRORS);
		}
		else{
			Utils::notify("views/contact.php", 'Je e-mail is succesvol verzonden');
		}
	}

	if(isset($_POST["zoek-keyword"])){
		utils::notify('zoeken.php?q=' . $_POST["zoek-keyword"]);
	}

	/*ADMIN*/
	if(isset($_POST["admin-edit-product"])){
		$naam = $_POST["edit-product-naam"];
	    $prijs = $_POST["edit-product-prijs"];
	    $categorie = $_POST["edit-product-categorie"];
	    $beschrijving = $_POST["edit-product-beschrijving"];
	    $uitgelicht = ($_POST["edit-product-uitgelicht"] == "true")? 1: 0;
		$datum_toegevoegd = date("Y-m-d");
		$img_path = null;

	    $product = ProductModel::getProduct($_POST["admin-edit-product"]);
	    $product->uitgelicht = $uitgelicht;
	    $product->naam = $naam;
	    $product->prijs = $prijs;
	    $product->categorie = $categorie;
	    $product->beschrijving = $beschrijving;


	    if(!empty($_FILES["edit-product-foto"]["name"])){
	    	$uploadedFile = "../resources/images/producten/" . $_FILES["edit-product-foto"]["name"];
	    	move_uploaded_file($_FILES["edit-product-foto"]["tmp_name"], $uploadedFile);
	    	$product->img_path = 'producten/' . $_FILES["edit-product-foto"]["name"];
	    }

	    if(!CategorieModel::getCategorie($categorie)) Utils::notify("views/admin/overview.php?product=" . $_POST["admin-edit-product"], "Gelieve een geldige categorie te selecteren", "red");

	    else{
		    ProductModel::updateProduct($product);
		    Utils::notify(PREV_URL, 'Product is succesvol aangepast');
		}
	}
	if(isset($_POST["admin-delete-product"])){
		$id = $_POST["admin-delete-product"];
		$product = ProductModel::getProduct($id);
		$product->active = 0;

		if(ProductModel::updateProduct($product)){
			Utils::notify("views/admin/overview.php?product=" . $id, 'Product is succesvol geïnactiveerd');
		}
		else{
			Utils::notify("views/admin/overview.php", "Er is een fout opgetreden bij het verwijderen van dit product", "red");
		}
	}
	if(isset($_POST["admin-add-product"])){
	    $naam = $_POST["add-product-naam"];
	    $prijs = $_POST["add-product-prijs"];
	    $categorie = $_POST["add-product-categorie"];
	    $beschrijving = $_POST["add-product-beschrijving"];
	    $uitgelicht = ($_POST["edit-product-uitgelicht"] == "true")? 1: 0;
		$datum_toegevoegd = date("Y-m-d H:i:s", time());
		$img_path = null;

	    if(!empty($_FILES["add-product-foto"]["name"])){
	    	$uploadedFile = "../resources/images/producten/" . $_FILES["add-product-foto"]["name"];
	    	move_uploaded_file($_FILES["add-product-foto"]["tmp_name"], $uploadedFile);
	    	$img_path = 'producten/' . $_FILES["add-product-foto"]["name"];
	    }

	    if(!CategorieModel::getCategorie($categorie)) array_push($_ERRORS['red'], ["red", "Gelieve een geldige categorie te selecteren"]);
	    if(!$img_path) array_push($_ERRORS['red'],"Gelieve een foto te uploaden");
	    if(empty($naam)) array_push($_ERRORS['red'],"Gelieve een naam in te geven");
	    if(empty($prijs)) array_push($_ERRORS['red'], "Gelieve een prijs in te geven");
	    if(empty($beschrijving)) array_push($_ERRORS['red'], "Gelieve een beschrijving in te geven");

	    if(!empty($_ERRORS['red'])){
	    	 Utils::notify("views/admin/overview.php?product-add", $_ERRORS);
	    }
	    else{
	    	$product = new ProductEntity(null, $categorie, $naam, $prijs, $beschrijving, $datum_toegevoegd, $img_path, $uitgelicht, 0, 0, 1);

	    	//Ik had een bug (ENKEL op de schoolserver) waar de variabelen in de POST bleven, waardoor de functie 2x werd aangeroepen --> POST
		    $_POST = [];
		    
		    $id = ProductModel::addProduct($product);

		    Utils::notify("views/admin/overview.php?product=" . $id, 'Product is succesvol toegevoegd');
	    }
	    
	}

	if(isset($_GET["admin-activate-product"])){
		$id = $_GET["admin-activate-product"];

		$product = ProductModel::getProduct($id);
		$product->active = 1;

		if(ProductModel::updateProduct($product)){
			Utils::notify("views/admin/overview.php?product=" . $id, "Product '" . $product->naam . "' is succesvol geactiveerd");
		}
		else{
			Utils::notify("views/admin/overview.php?product=" . $id, "Er is iets misgegaan bij het activeren van dit product", 'red');
		}
	}

	if(isset($_POST["admin-add-categorie"])){
		$naam = $_POST["admin-add-categorie"];
		if(empty($naam)) Utils::notify("views/admin/overview.php", "Gelieve een naam in te geven", "red");
		else{
			$cat = new CategorieEntity(null, $naam, 1);
			CategorieModel::addCategorie($cat);
			Utils::notify("views/admin/overview.php", "Categorie '" . $naam . "' is succesvol toegevoegd");
		}
	}

	if(isset($_POST["admin-edit-categorie"])){
		$id = $_POST["admin-edit-categorie"];
		$naam = $_POST["admin-edit-categorie-naam"];

		$cat = CategorieModel::getCategorie($id);

		if(!$cat) array_push($_ERRORS['red'], "Er is een fout opgetreden");
		if(empty($naam)) array_push($_ERRORS['red'], "Gelieve een naam in te geven");

		if(!empty($_ERRORS['red'])){
			Utils::notify("views/admin/overview.php", $_ERRORS);
	    }
		else{
			$cat->naam = $naam;
			CategorieModel::updateCategorie($cat);
			Utils::notify("views/admin/overview.php", "Categorie '" . $naam . "' is succesvol gewijzigd");
		}
	}

	if(isset($_GET["admin-delete-categorie"])){
		$id = $_GET["admin-delete-categorie"];

		$cat = CategorieModel::getCategorie($id);
		$cat->active = 0;

		if(CategorieModel::updateCategorie($cat)){
			Utils::notify("views/admin/overview.php", "Categorie '" . $cat->naam . "' is succesvol verwijderd");
		}
		else{
			Utils::notify("views/admin/overview.php", "Er is iets misgegaan bij het verwijderen van '" . $cat->naam . "'", 'red');
		}
	}

	if(isset($_POST["admin-edit-config"])){
		$winkelNaam = $_POST["algemeen-winkel_naam"];
		$aantalUitgelicht = $_POST["algemeen-aantal_uitgelicht"];
		$aantalNieuwste = $_POST["algemeen-aantal_nieuwste"];

		$conf = new ConfiguratieEntity($winkelNaam, $aantalUitgelicht, $aantalNieuwste);
		ConfiguratieModel::updateConfiguratie($conf);
		$_SESSION["configuratie"] = $conf;
		Utils::notify("views/admin/overview.php", 'Je website is sucesvol aangepast');
	}

	if(isset($_POST["admin-add-user"])){
		$firstname = isset($_POST["add-user-firstname"])? $_POST["add-user-firstname"] : '';
		$lastname = isset($_POST["add-user-lastname"])? $_POST["add-user-lastname"] : '';
		$email = isset($_POST["add-user-email"])? $_POST["add-user-email"] : '';
		$authority = isset($_POST["add-user-authority"])? $_POST["add-user-authority"] : '';
		$password = isset($_POST["add-user-password"])? $_POST["add-user-password"] : '';
		$confirmPassword = isset($_POST["add-user-confirmpassword"])? $_POST["add-user-confirmpassword"] : '';
		$file = isset($_FILES["add-user-foto"])? $_FILES["add-user-foto"] : '';

		$img_path = null;
		if(!empty($file["name"])){
	    	$uploadedFile = "../resources/images/avatars/" . $file["name"];
	    	move_uploaded_file($file["tmp_name"], $uploadedFile);
	    	$img_path = $file["name"];
	    }
	    else{
	    	$img_path = 'default-user.png';
	    }

		//Error handling
		if(empty($firstname)) array_push($_ERRORS['red'], "Gelieve een voornaam in te vullen");
		if(empty($lastname)) array_push($_ERRORS['red'], "Gelieve een achternaam in te vullen");
		if(empty($email)) array_push($_ERRORS['red'], "Gelieve een e-mailadres in te vullen");
		if(empty($password) || empty($confirmPassword)) array_push($_ERRORS['red'], "Gelieve beide paswoorden in te vullen");
		if(!in_array($authority, [0,1])) array_push($_ERRORS['red'], "Gelieve een geldige bevoegdheid in te vullen");
		if($password != $confirmPassword) array_push($_ERRORS['red'], "De wachtwoorden komen niet overeen");
		if(UserModel::getUserByEmail($email)) array_push($_ERRORS['red'], "Dit e-mailadres is al bezet");

		if(!empty($_ERRORS['red'])){
			Utils::notify(PREV_URL, $_ERRORS);
		}
		else{
			$password = password_hash($password, PASSWORD_DEFAULT);
			$user = new UserEntity(null, $password, $lastname, $firstname, $authority, $email, null, $img_path, 1);

			//Ik had een bug (ENKEL op de schoolserver) waar de variabelen in de POST bleven, waardoor de functie 2x werd aangeroepen --> POST
		    $_POST = [];

			$id = UserModel::addUser($user);

			Utils::notify("views/admin/overview.php?user-id=" . $id, "Gebruiker '" . $firstname . ' ' . $lastname . "' succesvol aangemaakt'");
		}
	}

	if(isset($_POST["admin-delete-user"])){
		$id = $_POST["admin-delete-user"];

		$user = UserModel::getUser($id);
		$user->active = 0;

		if(UserModel::updateUser($user)){
			Utils::notify("views/admin/overview.php?user-id=" . $id, "Gebruiker '" . $user->firstname . " " . $user->lastname . "' is succesvol geïnactiveerd");
		}
		else{
			Utils::notify("views/admin/overview.php?user-select", "Er is iets misgegaan bij het verwijderen van deze gebruiker", 'red');
		}
	}
	if(isset($_GET["admin-activate-user"])){
		$id = $_GET["admin-activate-user"];

		$user = UserModel::getUser($id);
		$user->active = 1;

		if(UserModel::updateUser($user)){
			Utils::notify("views/admin/overview.php?user-id=" . $id, "Gebruiker '" . $user->firstname . " " . $user->lastname . "' is succesvol geactiveerd");
		}
		else{
			Utils::notify("views/admin/overview.php?user-id=" . $id, "Er is iets misgegaan bij het activeren van deze gebruiker", 'red');
		}
	}
	if(isset($_POST["admin-edit-user"])){
		$id = $_POST["admin-edit-user"];
		$firstname = isset($_POST["edit-user-firstname"])? $_POST["edit-user-firstname"] : '';
		$lastname = isset($_POST["edit-user-lastname"])? $_POST["edit-user-lastname"] : '';
		$email = isset($_POST["edit-user-email"])? $_POST["edit-user-email"] : '';
		$authority = isset($_POST["edit-user-authority"])? $_POST["edit-user-authority"] : '';
		$password = isset($_POST["edit-user-password"])? $_POST["edit-user-password"] : '';
		$confirmPassword = isset($_POST["edit-user-confirmpassword"])? $_POST["edit-user-confirmpassword"] : '';
		$file = isset($_FILES["edit-user-foto"])? $_FILES["edit-user-foto"] : '';



		$oldUser = UserModel::getUserByEmail($email);

		$img_path = null;
		if(!empty($file["name"])){
	    	$uploadedFile = "../resources/images/avatars/" . $file["name"];
	    	move_uploaded_file($file["tmp_name"], $uploadedFile);
	    	$img_path = $file["name"];
	    }
	    else{
	    	$img_path = $oldUser->img_path;
	    }

		//Error handling
		
		if($oldUser && $oldUser->id != $id) array_push($_ERRORS['red'], "Dit e-mailadres is al bezet");
		if(empty($firstname)) array_push($_ERRORS['red'], "Gelieve een voornaam in te vullen");
		if(empty($lastname)) array_push($_ERRORS['red'], "Gelieve een achternaam in te vullen");
		if(empty($email)) array_push($_ERRORS['red'], "Gelieve een e-mailadres in te vullen");
		if(empty($password) || empty($confirmPassword)) array_push($_ERRORS['red'], "Gelieve beide paswoorden in te vullen");
		if(!in_array($authority, [0,1])) array_push($_ERRORS['red'], "Gelieve een geldige bevoegdheid in te vullen");
		if($password != $confirmPassword) array_push($_ERRORS['red'], "De wachtwoorden komen niet overeen");

		if(!empty($_ERRORS['red'])){
			Utils::notify(PREV_URL, $_ERRORS);
		}
		else{
			$user = UserModel::getUser($id);
			if($password != $user->password) $user->password = $password;
			
			$user = new UserEntity($id, $user->password, $lastname, $firstname, $authority, $email, null, $img_path, $oldUser->active);

			UserModel::updateUser($user);

			Utils::notify("views/admin/overview.php?user-id=" . $id, "Gebruiker '" . $firstname . ' ' . $lastname . "' succesvol aangepast");
		}
	}

?>