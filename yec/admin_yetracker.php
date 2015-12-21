<?php
require_once("./include/common.inc.php");
require_once("./include/class_ye.inc.php");
if( !isadminlogin() ){
	header("location:./index.php");
}
if($_GET['viewmembers']==1){
	if($_GET['edit']>0){
		if($_GET['infoupdate']==1){
			$tp['systemmsg'][]="The information has been updated.";
		}
		if($_GET['resetpw']==1){
			$tp['systemmsg'][]="His/Her password has been reset.";
		}
		$tp['css'][0]="yeinformation";
		unset($tp['ye']);
		$tp['ye']=new ye(intval($_GET['edit']));
	}else{
		if($_GET['xls']==1){
			$field="yid,yyear,yname_en,yemail,yphone_h,yphone_m";
			switch($_GET['filter']){
				case "login":
					$sql="SELECT distinct ".$field." FROM `yec_yelogin`,`yec_ye` WHERE `yec_yelogin`.`yid`=`yec_ye`.`yid`";
					break;
				case "notlogin":
					$sql="SELECT distinct ".$field." FROM `yec_yelogin`,`yec_ye` WHERE `yec_yelogin`.`yid`!=`yec_ye`.`yid`";
					break;
				case "updated":
					$sql="SELECT ".$field." FROM `yec_ye` WHERE `ypassword` is not NULL";
					break;
				case "notupdated":
					$sql="SELECT ".$field." FROM `yec_ye` WHERE `ypassword` is NULL";
					break;
				case "year":
					$sql="SELECT ".$field." FROM `yec_ye` WHERE `yyear`='".userinput($_GET['year'],1)."'";
					break;
				default:
					$sql="SELECT ".$field." FROM `yec_ye`";
			}
			$sql.=" ORDER BY `yid` ASC ";
			require_once("./include/xls_psxlsgen.php");
			$cur_con = mysql_connect($dbhost, $dbuser, $dbpw);
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
			break;
		}
		switch($_GET['filter']){
			case "login":
				$sql="SELECT distinct `yec_ye`.* FROM `yec_yelogin`,`yec_ye` WHERE `yec_yelogin`.`yid`=`yec_ye`.`yid`";
				break;
			case "notlogin":
				$sql="SELECT distinct `yec_ye`.* FROM `yec_yelogin`,`yec_ye` WHERE `yec_yelogin`.`yid`!=`yec_ye`.`yid`";
				break;
			case "updated":
				$sql="SELECT * FROM `yec_ye` WHERE `ypassword` is not NULL";
				break;
			case "notupdated":
				$sql="SELECT * FROM `yec_ye` WHERE `ypassword` is NULL";
				break;
			case "year":
				$sql="SELECT * FROM `yec_ye` WHERE `yyear`='".userinput($_GET['year'],1)."'";
				break;
			default:
				$sql="SELECT * FROM `yec_ye`";
		}
		/*$area=userinput($_GET['search_area']);
		$keywords=userinput($_GET['search_keyword']);
		if($area!=NULL && $keywords!=NULL){
			$sql="SELECT * FROM `yec_ye` WHERE `".$area."` LIKE '%".$keywords."%'";
		}*/
		$sql.=" ORDER BY `yid` ASC ";
		$initime=microtime_float();
		$rs=$mysqli->query($sql);
		$time=round(microtime_float()-$initime,5);
		$num=$rs->num_rows;
		if($area!=NULL && $keywords!=NULL){
			$tp['systemmsg'][]="About ".$num." results (".$time."seconds)";
		}
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
				if($i!=0&&$i!=10)$row[$i]=decrypt($row[$i]);
				$tp['ye'][$j][]=(stripslashes($row[$i])==NULL)?"---":nl2br(stripslashes($row[$i]));
			}
			$j++;
		}
		$tp['css'][]="admin_yetracker_viewmembers";
	}
}else{
	$sql="SELECT COUNT(*),COUNT(`ypassword`) FROM `yec_ye`";
	$rs=$mysqli->query($sql);
	$row=$rs->fetch_row();
	$tp['yenum']=$row[0];
	$tp['yeinfo']=$row[1];
	$sql="SELECT count(distinct `yid`) FROM `yec_yelogin`";
	$rs=$mysqli->query($sql);
	$row=$rs->fetch_row();
	$tp['yelogin']=$row[0];
	$tp['css'][]="admin_yetracker_stats";
}
$tp['css'][]="forms";
$tp['css'][]="admin_sidemenu";
//$tp['js'][]="";
$tp['js_body'][]="forms";
$template=new template("admin_yetracker",$tp);
$template->genHTML(1);
?>
