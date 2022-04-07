<?php

	// Error Reporting 
	ini_set('display_errors','On');
	error_reporting(E_ALL);


		 //Routs
	include 'admin/connect.php';

	// Fix Error When User Logout In New Window And Stay In Page And Refreshe 
	$sessionUser='';
	if (isset($_SESSION['user'])) {
			$sessionUser=$_SESSION['user'];
	}

	$tpl='includes/templetes/';//Templetes directory
	$css='layout/css/';
	$func='includes/functions/';					//Function Directory
	$js='layout/js/';
	$vendor='layout/vendor/';
	$fonts='layout/fonts/';
	$lang='includes/languages/';//Languge Directory
	$img='login/images/';

//Include The Important 
	Include  $func.'functions.php';
	include  $lang.'english.php'; 
	include  $tpl .'header.php';

	
  //include  "includes/languages/arabic.php"; 

?>
