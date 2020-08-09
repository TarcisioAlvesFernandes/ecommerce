<?php

require_once("vendor/autoload.php");
use \Hcode\Page;

$app->get('/', function() {
    
	$page = new Page();
	
	$page->setTpl("index");


});



?>