<?php
require_once("./include/common.inc.php");
require_once("./include/class_post.inc.php");
if(islogin()){
	if($_GET['eventapplysuccess']==1){
		$tp['systemmsg'][]=$msg['eventapplysuccess'];
	}elseif($_GET['eventquitsuccess']==1){
		$tp['systemmsg'][]=$msg['eventquitsuccess'];

	}
}
if(intval($_GET['pid'])>0){
	$tp['css'][0]="post";
	$tp['js_head'][0]="post";
	$pid=intval($_GET['pid']);
	$tp['posts']=new post($pid);
	$tp['nav']=$tp['posts']->pevent[0]+2;
	$template=new template("post",$tp);
	$template->genHTML();
}else{
	header("location:index.php");
}
?>