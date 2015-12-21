<?php
require_once("./include/common.inc.php");
require_once("./include/class_post.inc.php");
if(!isadminlogin()){
	header("location:./index.php");
}
if($_GET['newpost']==1){
	//newpost
	//$tp['js_body'][]="newpost";
}elseif($_GET['delete']==1 && intval($_GET['p'])>0){
	//delete
	header("location:./admin_work.php?post_delete=1&pid=".intval($_GET['p']));
}else{
	if($_GET['p']>0){
		//view event ye
		if($_GET['viewjoint']==1){
			if($_GET['updatesuccess']==1){
				$tp['systemmsg'][]="Updated successfully.";	
			}
			$pid=intval($_GET['p']);
			if($_GET['xls']==1){
				$field="b.yid, b.yyear, b.yname_en, b.yemail, b.yphone_h, b.yphone_m,a.eremarks,a.etimestamp,a.eattended";
				$sql="SELECT ".$field." FROM  `yec_eventapply`a natural join `yec_ye`b WHERE a.`pid`='".$pid."'";
				$sql.=" ORDER BY b.`yid` ASC ";
				require_once("./include/xls_psxlsgen.php");
				$cur_con = mysql_connect( $dbhost,$dbuser,$dbpw );
				mysql_query("SET NAMES 'utf8'",$cur_con);
				$myxls = new Db_SXlsGen;
				$myxls->db_type = "mysql";
				$myxls->db_name  =  "yec_db";
				$myxls->db_con_id = $cur_con;
				$myxls->get_type = 0;   
				$myxls->filename = "yeinfo";
				$myxls->header = 0;
				$myxls->db_close = 1;
				$myxls->GetXlsFromQuery($sql);
				exit();
			}
			$sql="SELECT b.*,a.eremarks,a.etimestamp,a.eattended FROM  `yec_eventapply`a natural join `yec_ye`b WHERE a.`pid`='".$pid."';";
			//maybe wrong
			$rs=$mysqli->query($sql);//edit
			unset($tp['ye']);
			$field=$rs->fetch_fields();
			for($i=0;$i<$mysqli->field_count;$i++){
				if($i==4)continue;
				$tp['field'][]=$field[$i]->name;
			}
			$j=0;
			while($row=$rs->fetch_row()){
				for($i=0;$i<$mysqli->field_count;$i++){
					if($i==4)continue;
					if($i==12){
						switch($row[$i]){
							case 0:
								$tp['ye'][$j][]="No";
								break;
							case 1:
								$tp['ye'][$j][]="Yes";
								break;
						}
						continue;
					}
					if($i!=0&&$i!=$mysqli->field_count-3&&$i!=$mysqli->field_count-2)$row[$i]=decrypt($row[$i]);
					$tp['ye'][$j][]=(stripslashes($row[$i])==NULL)?"---":nl2br(stripslashes($row[$i]));
				}
				$j++;
			}
			$tp['post']=new post(intval($_GET['p']));
			$tp['css'][]="admin_yetracker_viewmembers";
		}else{
			//editor
			$tp['post']=new post(intval($_GET['p']));
			print_r($tp);
		}
	}else{
		if($_GET['filter']=="news"){
			$tp['posts']=new post(0,0);
		}elseif($_GET['filter']=="events"){
			$tp['posts']=new post(0,1);
		}else{
			if($_GET['deletesuccess']==1){
				$tp['systemmsg'][]="The post has been deleted.";
			}elseif($_GET['deletesuccess']=="0"){
				$tp['systemerror'][]="Error occours. Please try again or contact Jason Yu as soon as possible.<br /> error@admin_work.php->postdelete";
			}elseif($_GET['editsuccess']==1){
				$tp['systemmsg'][]="The post has been edited.";
			}elseif($_GET['editsuccess']=="0"){
				$tp['systemerror'][]="Error occours. Please try again or contact Jason Yu as soon as possible.<br /> error@admin_work.php->editpost";
			}elseif($_GET['addsuccess']==1){
				$tp['systemmsg'][]="You successfully added this post.";
			}elseif($_GET['addsuccess']=="0"){
				$tp['systemerror'][]="You failed adding the post. Please find Jason Yu.";
			}
			$tp['posts']=new post(0);
		}
	}

}
$tp['css'][]="forms";
$tp['css'][]="admin_post";
$tp['css'][]="admin_sidemenu";
$tp['js'][]="";
$tp['js_body'][]="forms";
$template=new template("admin_post",$tp);
$template->genHTML(1);
?>
