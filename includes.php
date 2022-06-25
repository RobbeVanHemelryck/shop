<?php
	error_reporting(E_ALL);
	ini_set("display_errors","On");


	/*http://stackoverflow.com/a/599694*/
	foreach (glob($_SERVER['DOCUMENT_ROOT'] . "/shop/models/*.php") as $filename){
	    include_once $filename;
	}
	foreach (glob($_SERVER['DOCUMENT_ROOT'] . "/shop/entities/*.php") as $filename){
	    include_once $filename;
	}
	foreach (glob($_SERVER['DOCUMENT_ROOT'] . "/shop/controllers/*.php") as $filename){
	    include_once $filename;
	}
?>