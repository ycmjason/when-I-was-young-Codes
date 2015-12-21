<?php
//error_reporting(0);
session_start();
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set("Asia/Hong_Kong");
require_once("./config.inc.php");
require_once("./include/var.inc.php");
$mysqli=new mysqli($dbhost,$dbuser,$dbpw,$dbname);
if (mysqli_connect_errno()||!$mysqli->set_charset("utf8")) {
	printf("Can't Connect to database.");
	exit();
}
require_once("./include/function.inc.php");
require_once("./include/class_template.inc.php");
//force update
if(islogin()){
	require_once("./include/class_ye.inc.php");
	$ye=new ye($_SESSION['yid']);
	$tp['ye']=$ye;
	if(basename($_SERVER['PHP_SELF'])!="yeinformation.php"&&basename($_SERVER['PHP_SELF'])!="work.php"){
		if($ye->info['ypassword']==NULL){
			header("location:./yeinformation.php");
		}
	}
}
//login msg
if($_GET['loginsuccess']==1&&islogin()){
	$tp['systemmsg'][]="Welcome back ".$ye->info['yname_en'].".";
}
$tp['site']=sitesetup();
?>