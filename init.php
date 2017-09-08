<?php

	include "connect.php";

	$lang   = 'includes/languages/';
	$tpl 	= 'includes/templats/'; //template directoly
	$fun    = 'includes/functions/';
	//Include the important files

	include $fun . 'fun.php';
	include $lang . 'english.php';
	include $tpl . 'header.php';

	if (!isset($noNavbar)) {  include $tpl . 'navbar.php';  }
	