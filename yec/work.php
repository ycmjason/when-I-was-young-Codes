<?php
require_once("./include/common.inc.php");
$back="Required page is not found. Please go back.";
if($_GET['contactus']==1){//nutral work
	//send email program
	$feedback=userinput(nl2br($_POST['feedback']));
	
	if($feedback==""||$feedback=="Feedback"){
		$back="You have not write your feedback. Please go back and try again.";
	}else{
		if(!islogin()){
			$name=userinput($_POST['name']);
			$replyto=userinput($_POST['email']);
		}else{
			$name=$ye->info['yname_en'];
			$replyto=$ye->info['yemail'];
		}
		$to=$tp['site']['feedbackemail'];
		$subject="Feedback sent on ".date('d/n/Y, \a\t g:i a')." by ".$name;
		$msg="<p><i>Below is the message:</i></p><blockquote>".$feedback."</blockquote><p><i>Sent on ".date('d/n/Y, \a\t g:i a')."<br />Sent by ".$name."&lt;".$replyto."&gt;</i></p>";
		send_mail($to,"",$replyto,$subject,$msg);
		$url="contactus.php?done=1";
	}
}elseif(!islogin()){//work before login
	if($_GET['login']==1){
			$back="Wrong login details.	";
			$year	 =userinput($_POST['year'],1);
			$name =userinput($_POST['name'],1);
			$password=userinput($_POST['password']);
			$sql="SELECT * FROM `yec_ye` WHERE `yyear`='".$year."' && `yname_ch`='".$name."';";
			$rs=$mysqli->query($sql);
			$num=$rs->num_rows;
			if($num>0){
				while($row=$rs->fetch_assoc()){
					if($row['ypassword']==NULL && (encrypt($password)==$row['yphone_h']||encrypt($password)==$row['yphone_m']) || (md5($password)==$row['ypassword'])){
						$sql="INSERT INTO `yec_yelogin` (`yid`) VALUES (?);";
						$rs=$mysqli->prepare($sql);
						$rs->bind_param("s",$row['yid']);
						$rs->execute();
						$_SESSION['yid']=$row['yid'];
						$url=(isset($_GET['return']))?$_GET['return']:"index.php";
						$url.="?loginsuccess=1";
						break;
					}else{
						unset($_SESSION['yid']);
					}
				}
			}
	}
}elseif(islogin()){//work after login
	if($_GET['logout']==1){
		unset($_SESSION['yid']);
		$url="login.php?logout=1";
	}elseif($_GET['yeinfoupdate']==1){
		if($_POST['dob_y']!=NULL&&$_POST['dob_m']!=NULL&&$_POST['dob_d']!=NULL){
			$dob=userinput(date('Y-m-d', strtotime($_POST['dob_y']."-".$_POST['dob_m']."-".$_POST['dob_d'])),1);
		}
		$name_en=userinput($_POST['name_en'],1);
		$name_ch=userinput($_POST['name_ch'],1);
		$adr=userinput($_POST['address'],1);
		$phone_h=userinput($_POST['phone_h'],1);
		$phone_m=userinput($_POST['phone_m'],1);
		$email=userinput($_POST['email'],1);
		$newpw=userinput($_POST['newpw']);
		$newpwc=userinput($_POST['newpwc']);
		$pw=userinput($_POST['pw']);
        $disclaimer=intval($_POST['disclaimer']);
		$addon="";
		if($name_en!="" && $name_en!="" && $phone_h!="" && $phone_m!="" && $email!=""){
			if($tp['ye']->info['ypassword']=="" && ($pw==$tp['ye']->info['yphone_h']||$pw==$tp['ye']->info['yphone_m']) || (md5($pw)==$tp['ye']->info['ypassword'])){
				$sql="UPDATE `yec_ye` SET `yname_en`=?,`yname_ch`=?,";
				if($_POST['dob_y']==NULL||$_POST['dob_m']==NULL||$_POST['dob_d']==NULL){
					$sql.="`ydob`=NULL";
				}else{
					$sql.="`ydob`=?";
				}
				$sql.=",`yaddress` =  ?,`yphone_h` = ?,`yphone_m` = ?,`yemail` = ?";
				if($newpw!=""||$newpwc!=""){
					if($newpw==$newpwc){
						$sql.=",`ypassword`=md5('".$newpw."')";
						$addon="&newpassword=1";
					}else{
						$addon="&newpassword=0";
					}
				}
				$sql.=",`ydisclaimer`=? WHERE `yid`=".$_SESSION['yid'].";";
				$rs=$mysqli->prepare($sql);
				if($_POST['dob_y']==NULL||$_POST['dob_m']==NULL||$_POST['dob_d']==NULL){
					$rs->bind_param("ssssssi",$name_en,$name_ch,$adr,$phone_h,$phone_m,$email,$disclaimer);
				}else{
					$rs->bind_param("sssssssi",$name_en,$name_ch,$dob,$adr,$phone_h,$phone_m,$email,$disclaimer);
				}
				$rs->execute();
				if($mysqli->affected_rows>0){
					
					$style['name_en']=(decrypt($name_en)!=$tp['ye']->info['yname_en'])?"background-color:#FF7070;":"";
					$style['name_ch']=(decrypt($name_ch)!=$tp['ye']->info['yname_ch'])?"background-color:#FF7070;":"";
					$style['dob']=(decrypt($dob)!=$tp['ye']->info['ydob'])?"background-color:#FF7070;":"";
					$style['adr']=(decrypt($adr)!=$tp['ye']->info['yaddress'])?"background-color:#FF7070;":"";
					$style['phone_h']=(decrypt($phone_h)!=$tp['ye']->info['yphone_h'])?"background-color:#FF7070;":"";
					$style['phone_m']=(decrypt($phone_m)!=$tp['ye']->info['yphone_m'])?"background-color:#FF7070;":"";
					$style['email']=(decrypt($email)!=$tp['ye']->info['yemail'])?"background-color:#FF7070;":"";
					$style['disclaimer']=($disclaimer!=$tp['ye']->info['ydisclaimer'])?"background-color:#FF7070;":"";
					$flag=0;
					foreach($style as $v){
						if($v!=""){
							$flag=1;
						}
					}
					if($flag==1){
						$subject=decrypt($name_en)." has changed his/her information";
						$msg="Here's the information.<br/><table cellpadding=\"5\">
						<tr>
							<td>id</td>
							<td>english name</td>
							<td>chinese name</td>
							<td>date of birth</td>
							<td>address</td>
							<td>home number</td>
							<td>mobile number</td>
							<td>email</td>
							<td>disclaimer</td>
						</tr>
						<tr>
							<td>".$_SESSION['yid']."</td>
							<td style=\"".$style['name_en']."\">".decrypt($name_en)."</td>
							<td style=\"".$style['name_ch']."\">".decrypt($name_ch)."</td>
							<td style=\"".$style['dob']."\">".decrypt($dob)."</td>
							<td style=\"".$style['adr']."\">".decrypt($adr)."</td>
							<td style=\"".$style['phone_h']."\">".decrypt($phone_h)."</td>
							<td style=\"".$style['phone_m']."\">".decrypt($phone_m)."</td>
							<td style=\"".$style['email']."\">".decrypt($email)."</td>
							<td style=\"".$style['disclaimer']."\">".$disclaimer."</td>
						</tr>
						</table>";
						send_mail($tp['site']['emailupdate'],"","",$subject,$msg,NULL);
					}
				}
				$rs->close();
				$url="yeinformation.php?updatesuccess=1".$addon;
			}else{
				$back="Your password is invalid. Please go back and try again.";
			}
		}else{
			$back="Not all required information is filled yet. Please go back and fill in them.";
		}
	}elseif($_GET['joinevent']==1&&intval($_GET['pid'])>0){
		$pid=intval(userinput($_GET['pid']));
		if($_GET['q']==1){
			$sql="DELETE FROM `yec_eventapply` WHERE `pid`=? && `yid`=?;";
			$rs=$mysqli->prepare($sql);
			$rs->bind_param("ii",$pid,$_SESSION['yid']);
			$rs->execute();
			$rs->close();
			$url="post.php?pid=".$pid."&eventquitsuccess=1";
		}else{
			$remarks=userinput($_GET['r']);	
			$sql="INSERT INTO `yec_eventapply` (`pid` ,`yid`,`eremarks`)VALUES (?,?,?);";
			$rs=$mysqli->prepare($sql);
			$rs->bind_param("iis",$pid,$ye->yid,$remarks);
			$rs->execute();
			$rs->close();
			$url="post.php?pid=".$pid."&eventapplysuccess=1";
		}
	}
}
if(!isset($url)){?>
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
}else{	header("location:./".$url);
}
?>
