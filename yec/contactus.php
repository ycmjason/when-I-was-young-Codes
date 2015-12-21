<?php
require_once("./include/common.inc.php");
$tp['css'][0]="contactus";
$tp['js_body'][0]="forms";
$tp['js_body'][1]="contactus";
$tp['nav']=4;
if($_GET['done']==1){
	$tp['systemmsg'][]=$msg['contactussuccess'];
}
$template=new template("contactus",$tp);
$template->genHTML();
?>