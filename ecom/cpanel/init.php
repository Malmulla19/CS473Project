<?php
include('connect.php');
//Routes
//template route.
$tpl = 'includes/templates/';
//CSS path.
$css ='themes/css/';
//Js path.
$js ='themes/js/';
//Language Directory:
//Functions Directory:
$func = "includes/functions/";

//Include some important files: 
include($func."functions.php");
include($tpl."header.php");
//Include in all pages except that has NoNavBar Variale.
if(!isset($noNavBar)){
    include($tpl."navbar.php");
}
?>