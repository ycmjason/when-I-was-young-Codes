<?php
require_once("./include/common.inc.php");
if(!islogin()){
	header("location:./login.php?p=1&return=yeinformation.php");
}
if($_GET['updatesuccess']=="1"){
	$tp['systemmsg'][]=($_GET['newpassword']=="1")?$msg['updatesuccess_pw']:$msg['updatesuccess'];
}
if($_GET['newpassword']=="0"){
	$tp['systemerror'][]=$msg['newpasswordfailed'];
}
if($_GET['viewpastevent']==1){
    $template=new template("yeinformation_viewpastevent",$tp);
    $template->genHTML();
}
if($ye->info['ypassword']==NULL){
	$tp['systemmsg'][]=$msg['forceupdate'];
}
$sql="SELECT * FROM  `yec_site` WHERE `sattr`='disclaimer'";
$rs=$mysqli->query($sql);
$row=$rs->fetch_assoc();
$tp['disclaimer']=$row['svalue'];
$tp['css'][0]="yeinformation";
$tp['js_body'][0]="forms";
$tp['nav']=0;
$template=new template("yeinformation",$tp);
$template->genHTML();
?>
