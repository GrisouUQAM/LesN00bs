<?php
# DEBUG
error_reporting(-1);
ini_set('display_errors', 'On');

# Session related code.
if(!isset($_SESSION)){
	session_start(); 
}

if(isset($_GET["wiki"]) || isset($_GET["user"])){
	$_SESSION['grisouUser'] = $_GET["user"];
	$_SESSION['grisouUrl'] = $_GET["wiki"];
}

# Includes
include_once( dirname(__FILE__) . '/diff.php');
include_once( dirname(__FILE__) . '/frame.php');
include_once( dirname(__FILE__) . '/base.php');


# Variable initialization
$contributor = $_SESSION['grisouUser'];
$wikiUrl = $_SESSION['grisouUrl'];

$base = new Base($wikiUrl, $contributor);
$diff = new Diff();
$frame = new Frame($wikiUrl);

if(!isset($_GET['page'])){
	$data = $base->getContribs();
	echo $frame->formatContribs($data);
} else{
	switch ($_GET['page']){
		case "load":
			$data = $base->getPage($_GET['info']);
			echo $frame->formatPage($data);
			break;
		case "base":
		default:
			$data = $base->getContribs();
			echo $frame->formatContribs($data);
			break;
	}
}

