<?php
session_start();

$page;
if(isset($_SESSION["s_active_page"])){
	$page = $_SESSION["s_active_page"];
}else{
	$page = "login.php";
}



include($page);