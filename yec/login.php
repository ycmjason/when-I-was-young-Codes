<?php
require_once("./include/common.inc.php");
if(islogin()){
	$tp['systemerror'][]=$msg['alreadylogin'];
	$template=new template("blank",$tp);
	$template->genHTML();
	exit();
}
$tp['css'][0]="login";
$tp['js_body'][0]="forms";
$tp['js_body'][1]="login";
$tp['nav']=0;

if($_GET['p']==1){
	$tp['systemerror'][]=$msg['permissiondenied'];
}
if($_GET['logout']==1){
	$tp['systemmsg'][]=$msg['logoutsuccess'];
}
$tp['return']=(isset($_GET['return']))?userinput($_GET['return']):"index.php";
$template=new template("login",$tp);
$template->genHTML();

?>