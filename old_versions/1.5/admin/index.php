<?
session_start();
include("admin_functions.php");

if($_GET['destroy']==true)
	$_SESSION['pw'] = null;

checkLoggedIn();

disHeader();
?>
