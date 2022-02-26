<?php
include('cpanel/connect.php');
//Routes
//template route.
$tpl = 'includes/templates/';
//CSS path.
$css ='themes/css/';
//Js path.
$js ='themes/js/';

//Functions Directory:
$func = "includes/functions/";

//Include some important files: 
include($func."functions.php");
include($tpl."header.php");

?>