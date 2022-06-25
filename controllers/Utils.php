<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/includes.php";

class utils{
	static function productComparator($p1, $p2){
		return $p1->id - $p2->id;
	}
	/* http://stackoverflow.com/a/3573508 */
	static function unsetValue(array $array, $value){
	    if(($key = array_search($value, $array)) !== FALSE) {
	        unset($array[$key]);
	    }
	    return $array;
	}
	static function sortProductsByNaam(&$unsorted, $order){
		if($order == "asc"){
			usort($unsorted, function($a, $b){
				$a = strtolower($a->naam);
				$b = strtolower($b->naam);
				return strcmp($a, $b);
			});
		}
		else if($order == "desc"){
			usort($unsorted, function($a, $b){
				$a = strtolower($a->naam);
				$b = strtolower($b->naam);
				return strcmp($b, $a);
			});
		}
		return false;
	}
	static function sortProductsByDatum(&$unsorted, $order){
		if($order == "asc"){
			usort($unsorted, function($a, $b){
				return strtotime($a->datum_toegevoegd) - strtotime($b->datum_toegevoegd);
			});
		}
		else if($order == "desc"){
			usort($unsorted, function($a, $b){
				return strtotime($b->datum_toegevoegd) - strtotime($a->datum_toegevoegd);
			});
		}
		return false;
	}
	static function sortProductsByCategorie(&$unsorted, $order){
		if($order == "asc"){
			usort($unsorted, function($a, $b){
				$a = strtolower($a->categorie);
				$b = strtolower($b->categorie);
				return strcmp($a, $b);
			});
		}
		else if($order == "desc"){
			usort($unsorted, function($a, $b){
				$a = strtolower($a->categorie);
				$b = strtolower($b->categorie);
				return strcmp($b, $a);
			});
		}
		return false;
	}
	static function sortProductsByPrijs(&$unsorted, $order){
		if($order == "asc"){
			usort($unsorted, function($a, $b){
				return $a->prijs > $b->prijs;
			});
		}
		else if($order == "desc"){
			usort($unsorted, function($a, $b){
				return $b->prijs > $a->prijs;
			});
		}
		return false;
	}
	static function sortProductsByRating(&$unsorted, $order){
		if($order == "asc"){
			usort($unsorted, function($a, $b){
				return $a->rating > $b->rating;
			});
		}
		else if($order == "desc"){
			usort($unsorted, function($a, $b){
				return $b->rating > $a->rating;
			});
		}
		return false;
	}
	static function authorize($allowedAuth, $redirect, $specificUser = false){
		$authority = -1;
		if(isset($_SESSION["user"])){
			$authority = $_SESSION["user"]->authority;
		}

		$mayPass = false;
		if($authority == 1){
			$mayPass = true;
		}
		else if($specificUser){
			if($specificUser == $_SESSION["user"]->id){
				$mayPass = true;
			}
		}
		else{
			if(in_array($authority, $allowedAuth)){
				$mayPass = true;
			}
		}
		if(!$mayPass) self::notify($redirect, "Deze pagina is niet beschikbaar", "red");
	}
	static function notify($url, $errors = [], $color = "green"){
		$errorarray = ["red" => [], "green" => []];

		if(is_array($errors)){
			if(!empty($errors)){
				$errorarray = $errors;
			}
		}
		else{
			array_push($errorarray[$color], $errors);
		}

		setcookie("errors", serialize($errorarray), time() + 60*60*24*30, '/');

	    header("Location: " . URL . $url);
	}
	//http://stackoverflow.com/a/15262442
	static function redirect_post($url, array $postData)
	{
	    ?>
	    <html xmlns="http://www.w3.org/1999/xhtml">
	    <head>
	        <script type="text/javascript">
	            function closethisasap() {
	                document.forms["redirectpost"].submit();
	            }
	        </script>
	    </head>
	    <body onload="closethisasap();">
	    <form name="redirectpost" method="post" action="<?php echo $url; ?>">
		    <?php
	            foreach ($postData as $key => $value) {
	            	if(is_array($value)){
	            		foreach ($value as $arrayItem) {
			                echo '<input type="hidden" name="' . $key . '[]" value="' . $arrayItem . '"> ';
			            }
	            	}
	            	else{
	            		echo '<input type="hidden" name="' . $key . '" value="' . $value . '"> ';
	            	}
	            }
		    ?>
	    </form>
	    </body>
	    </html>
	    <?php
	    exit;
	}
	static function arrayToUrl($array){
		$categorien = CategorieModel::getAllCategorien();
		$filtersString = "";
	    foreach ($categorien as $categorie) {
	    	if(in_array($categorie->naam, $array)){
	            $filtersString .= $categorie->naam . ",";
	       	}
	    }
	    return rtrim($filtersString,",");
	}
}

?>