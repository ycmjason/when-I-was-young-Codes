<?php
require_once("./include/common.inc.php");
$back="Required page is not found. Please go back.";
if(!isadminlogin()){//work before login
	if($_GET['login']==1){
			$back="Wrong login details.	";
			$password=userinput($_POST['password']);
			$sql="SELECT * FROM `yec_admin` WHERE `ap`=?;";
			$rs=$mysqli->prepare($sql);
			$rs->bind_param("s",md5($password));
			$rs->execute();
			$rs->store_result();
			$num=$rs->num_rows;
			$rs->close();
			if($num>0){
				$_SESSION['admin']=md5($password);
				$url="admin_panel.php";
			}
	}
}elseif(isadminlogin()){//work after login
	if($_GET['logout']==1){
		unset($_SESSION['admin']);
		$url="adminYEC.php?logout=1";
	}elseif($_GET['passwordupdate']==1){
		$newpw=userinput($_POST['newpw']);
		$newpwc=userinput($_POST['newpwc']);
		$pw=userinput($_POST['pw']);
		$addon="";
	}elseif($_GET['setup']==1){
		if($_SESSION['admin']==md5($_POST['password'])){
			$sql="SELECT * FROM `yec_site`";
			$rs=$mysqli->query($sql);
			while($row=$rs->fetch_assoc()){
				$sql="UPDATE `yec_site` SET `svalue`=? WHERE `sid` =?;";
				$rs1=$mysqli->prepare($sql);
				$rs1->bind_param("si",userinput($_POST[$row['sid']]),$row['sid']);
				$rs1->execute();
				$rs1->close();
			}
			$newpw=userinput($_POST['newpw']);
			$newpwc=userinput($_POST['newpwc']);
			if($newpw!=""){
				if($newpw==$newpwc){
					$sql="UPDATE `yec_admin` SET `ap`=? WHERE `ap`=?";
					$rs=$mysqli->prepare($sql);
					$rs->bind_param("si",md5($newpw),$_SESSION['admin']);
					$rs->execute();
					$rs->close();
					$_SESSION['admin']=md5($newpw);
					$addon="&pwupdatesuccess=1";
				}else{
					$addon="&pwupdatesuccess=0";
				}
			}
			$url="admin_panel.php?setup=1&setupsuccess=1".$addon;
		}else{
			$back="Your password is wrong. Please try again.";
		}
	}elseif($_GET['newpost']==1){
		$title=userinput($_POST['title']);
		$content=userinput($_POST['content']);
		$event=(intval($_POST['event'])==1)?1:0;
		$email=(intval($_POST['email'])==1)?1:0;
		if($title=="" || $content==""){
			$back="Blank field is detected. Please try again.";
		}else{
			$sql="INSERT INTO `yec_post` (`pid`, `ptitle`, `pcontent`, `ptimestamp`, `pevent`, `pemail`) VALUES (NULL, ?, ?, CURRENT_TIMESTAMP, ?, ?);";
			$rs=$mysqli->prepare($sql);
			$rs->bind_param("ssii",$title,$content,$event,$email);
			/*foreach ($_FILES["file"]["error"] as $key => $error) {
				if ($error == UPLOAD_ERR_OK) {
					$tmp_name = $_FILES["file"]["tmp_name"][$key];
					$name = $_FILES["file"]["name"][$key];
					$path="./attachments/".substr(md5(microtime().$name),5)."_".$name;
					$attm[]=$path;
					move_uploaded_file($tmp_name, $path);
					$sql="INSERT INTO `yec_postAttachment` (`apath`,`aname`, `asize`, `pid`) VALUES (? , ?, ?, ?);";
					$rs=$mysqli->prepare($sql);
					$rs->bind_param("ssii",$path,$name,$_FILES["file"]["size"][$key]/1024,$mysqli->insert_id);
					$rs->execute();
					$rs->close();
				}
			}*/
			if($rs->execute()){
				if($email){
					$replace=array("\\n","\\r");
					$replyto=$emailreplyto;
					$sql="SELECT * FROM  `yec_ye`";
					$rs=$mysqli->query($sql);
					$subject="[UNICEF YEC]";
					$subject.=($event==1)?"[EVENT] ":"[NEWS] ";
					$subject.=stripslashes(str_replace($replace,"",$title));
					$msg=stripslashes(str_replace($replace,"",$content));
					$attm=NULL;
					while($row=$rs->fetch_assoc()){
						$bcc=decrypt($row['yemail']);
						$bcc=explode("/",$bcc);
						foreach($bcc as $v){
							$b=trim($v);
							if($b!=""){
								send_mail($b,"",$replyto,$subject,$msg,$attm);
							}
						}
						break;
					}
				}
			}
			$url="admin_post.php?addsuccess=1";
		}
	}elseif($_GET['editpost']==1){
		if($_GET['pid']>0){
			$title=userinput($_POST['title']);
			$content=userinput($_POST['content']);
			$event=intval($_POST['event']);
			if($event!=1){
				$event=0;
			}
			if($title=="" || $content==""){
				$back="Blank field is detected. Please try again.";
			}else{
				$sql="UPDATE `yec_post` SET `ptitle`= ? ,`pcontent`= ? ,`pevent`= ?  WHERE `pid` = ? ;";
				$rs=$mysqli->prepare($sql);
				$rs->bind_param("ssii",$title,$content,$event,intval($_GET['pid']));
				$rs->execute();
				$rs->close();
				$url="admin_post.php?editsuccess=1";
			}
		}else{
			$url="admin_post.php?editsuccess=0";
		}
	}elseif($_GET['post_delete']==1){
		if($_GET['pid']>0){
			$sql="DELETE FROM `yec_post` WHERE `pid`=?";
			$rs=$mysqli->prepare($sql);
			$rs->bind_param("i",intval($_GET['pid']));
			$rs->execute();
			$rs->close();
			$url="admin_post.php?deletesuccess=1";
		}else{
			$url="admin_post.php?deletesuccess=0";
		}
	}elseif(intval($_GET['event_attended'])>0){
		$pid=intval($_GET['event_attended']);
		$sql="SELECT `yid` FROM `yec_eventapply` WHERE `pid`=?";
		$rs=$mysqli->prepare($sql);
		$rs->bind_param("i",$pid);
		$rs->execute();
		$rs->bind_result($row['yid']);
		while ($rs->fetch()) {
			if($_POST['attended'][$row['yid']]==1){
				$attending[$row['yid']]=1;	
			}else{
				$attending[$row['yid']]=0;	
			}
		}
		$flag=0;
		$sql="UPDATE  `yec_db`.`yec_eventapply` SET  `eattended` =  '1' WHERE `pid` =".$pid." AND (";
		foreach($attending as $yid=>$attended){
			if($attended==1){
				$flag=1;
				$sql.="`yid`=".$yid." OR ";
			}
		}
		if($flag){
			$sql=substr($sql,0,-4).");";
			$rs=$mysqli->query($sql);
		}
		$flag=0;
		$sql="UPDATE  `yec_db`.`yec_eventapply` SET  `eattended` =  '0' WHERE `pid` =".$pid." AND (";
		foreach($attending as $yid=>$attended){
			if($attended!=1){
				$flag=1;
				$sql.="`yid`=".$yid." OR ";
			}
		}
		if($flag){
			$sql=substr($sql,0,-4).");";
			$rs=$mysqli->query($sql);
		}
		$url="admin_post.php?p=".$pid."&&viewjoint=1&&updatesuccess=1";
	}elseif(intval($_GET['yeinfoupdate'])>0){
		if(intval($_GET['resetpw'])==1){
			$sql="UPDATE `yec_ye` SET  `ypassword` = NULL WHERE  `yec_ye`.`yid` =".intval($_GET['yeinfoupdate']).";";
			$mysqli->query($sql);
			$url="admin_yetracker.php?viewmembers=1&resetpw=1&edit=".intval($_GET['yeinfoupdate']);
		}else{
			require_once("./include/class_ye.inc.php");
			$ye=new ye(intval($_GET['yeinfoupdate']));
			if($_POST['dob_y']!=NULL&&$_POST['dob_m']!=NULL&&$_POST['dob_d']!=NULL){
				$dob=userinput(date('Y-m-d', strtotime($_POST['dob_y']."-".$_POST['dob_m']."-".$_POST['dob_d'])),1);
			}
			$counter=0;
			foreach($_POST as $v){
				if($v==""||$v==NULL)
					$counter++;
			}
			if($counter>=4){
				$back="Too many empty fields. System sees it as an error in order to protect personal data. Please find Jason Yu if it is not an error.";
			}else{
				$year=userinput(intval($_POST['year']),1);
				$name_en=userinput($_POST['name_en'],1);
				$name_ch=userinput($_POST['name_ch'],1);
				$adr=userinput($_POST['address'],1);
				$phone_h=userinput($_POST['phone_h'],1);
				$phone_m=userinput($_POST['phone_m'],1);
				$email=userinput($_POST['email'],1);
				$newpw=userinput($_POST['newpw']);
				$newpwc=userinput($_POST['newpwc']);
				$pw=userinput($_POST['pw']);
				$sql="UPDATE `yec_ye` SET `yyear`=?,`yname_en`=?,`yname_ch`=?,";
				if($_POST['dob_y']==NULL||$_POST['dob_m']==NULL||$_POST['dob_d']==NULL){
					$sql.="`ydob`=NULL";
				}else{
					$sql.="`ydob`=?";
				}
				$sql.=",`yaddress` = ?,`yphone_h` = ?,`yphone_m` = ?,`yemail`=? WHERE `yid`=?;";
				$rs=$mysqli->prepare($sql);
				if($_POST['dob_y']==NULL||$_POST['dob_m']==NULL||$_POST['dob_d']==NULL){
					$rs->bind_param("sssssssi",$year,$name_en,$name_ch,$adr,$phone_h,$phone_m,$email,$ye->yid);
				}else{
					$rs->bind_param("ssssssssi",$year,$name_en,$name_ch,$dob,$adr,$phone_h,$phone_m,$email,$ye->yid);
				}
				$rs->execute();
				$rs->close();
				$url="admin_yetracker.php?viewmembers=1&infoupdate=1&edit=".$ye->yid;
			}
		}
	}
}
if(!isset($url)){
?>
<html>
<head>
<script type="text/javascript">
alert("<?php echo $back;?>");
window.history.back();
</script>
</head>
<body>
</body>
</html>
<?php
}else{
	header("location:./".$url);
}
?>
