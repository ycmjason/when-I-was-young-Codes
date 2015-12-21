<?php
require_once("./include/common.inc.php");if( !isadminlogin() ){
	header("location:./index.php");
}
$tpname="blank";
if($_GET['setup']==1){
	if($_GET['setupsuccess']==1){
		if($_GET['pwupdatesuccess']==1){
			$tp['systemmsg'][]="Your setup and new password have been saved.";
		}else{
			$tp['systemmsg'][]="Your setups have been saved.";
			if($_GET['pwupdatesuccess']=="0"){
				$tp['systemerror'][]="Your new password has not been saved because of mismatch of two password. Please try again.";
			}
		}
	}
	$sql="SELECT * FROM `yec_site`";
	$rs=$mysqli->query($sql);
	$i=0;
	while($row=$rs->fetch_assoc()){
		$tp['sitesetup'][$i]['sid']=$row['sid'];
		$tp['sitesetup'][$i]['sname']=stripslashes($row['sname']);
		$tp['sitesetup'][$i++]['svalue']=stripslashes($row['svalue']);
	}
	$tp['css'][]="admin_sitesetup";
	$tpname="admin_sitesetup";
}
$tp['css'][]="forms";
$tp['js'][]="";
$tp['js_body'][]="forms";
$template=new template($tpname,$tp);
$template->genHTML(1);?>