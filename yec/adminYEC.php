<?php
require_once("./include/common.inc.php");
	header("location:admin_panel.php");
}
if($_GET['logout']==1){
	$tp['systemmsg'][]="You have logged out.";
}
$tp['css'][0]="forms";
$tp['js'][0]="";
$tp['js_body'][0]="forms";
$template=new template("admin",$tp);