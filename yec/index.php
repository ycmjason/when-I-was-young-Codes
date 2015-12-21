<?php
require_once("./include/common.inc.php");
require_once("./include/class_post.inc.php");
$tp['css'][0]="post";
$tp['js_head'][0]="post";
$pg=($_GET['page']==NULL)?0:intval($_GET['page']-1);
if($pg>=0){
	$sql="SELECT * FROM `yec_post`";
	if($_GET['filter']=="news"){
		$sql.=" WHERE `pevent`='0'";
		$tp['nav']=2;
		$tp['posts']=new post(0,0,$pg,$tp['site']['postperpage']);
	}elseif($_GET['filter']=="events"){
		$sql.=" WHERE `pevent`='1'";
		$tp['nav']=3;
		$tp['posts']=new post(0,1,$pg,$tp['site']['postperpage']);
	}else{
		$tp['nav']=1;		$tp['posts']=new post(0,NULL,$pg,$tp['site']['postperpage']);
	}
	$sql.=";";
	$rs=$mysqli->query($sql);
	$num=$rs->num_rows;
	for($i=1;$i<=ceil($num/$tp['site']['postperpage']);$i++){
		$tp['page'].="<a href=\"./index.php?page=".$i."\">".$i."</a> ";
	}
	$template=new template("post",$tp);
	$template->genHTML();
}else{
	header("location:./index.php");
}?>