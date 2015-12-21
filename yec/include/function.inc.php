<?php
function os($number){
	$ends = array('th','st','nd','rd','th','th','th','th','th','th');
	if (($number %100) >= 11 && ($number%100) <= 13)
	   $a= $number. 'th';
	else
	   $a=$number. $ends[$number % 10];
	return $a;
}
function timeAgo($timestamp, $granularity=1, $format="F j, Y"){
        $difference = time() - strtotime($timestamp);
        if($difference < 5) return 'just now';
        elseif($difference < 518400){
                $periods = array('day' => 86400,'hour' => 3600,'minute' => 60,'second' => 1);
                $output = '';
                foreach($periods as $key => $value){
                        if($difference >= $value){
                                $time = round($difference / $value);
                                $difference %= $value;
				if($key=="day" && $time>=1){
					$tt=explode(" ",$timestamp);
					$tt=explode(":",$tt[1]);
					if($tt[0]=="00"){
						$a="12:".$tt[1]." am";
					}elseif($tt[0]>12){
						$a=($tt[0]-12).":".$tt[1]." pm";
					}else{
						$a=$tt[0].":".$tt[1]." am";
					}
					if($time==1){
						return "Yesterday at ".$a;
					}else{
						return date("l",strtotime($timestamp))." at ".$a;
					}
				}else{
                                	$output .= ($output ? ' ' : '').$time.' ';
	                                $output .= (($time > 1) ? $key.'s' : $key);
				}
                                $granularity--;
                        }
                        if($granularity == 0) break;
                }
                return ($output ? $output : '0 seconds').' ago';
	}
        else{
		$timestamp=explode(" ",$timestamp);
		return date($format,strtotime($timestamp[0]));
	}
}
function encrypt($v){
	global $encryption_key;
	return ($v==NULL)?NULL:base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($encryption_key), $v, MCRYPT_MODE_CBC, md5(md5($encryption_key))));
}
function decrypt($v){
	global $encryption_key;
	return ($v==NULL)?NULL:rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($encryption_key), base64_decode($v), MCRYPT_MODE_CBC, md5(md5($encryption_key))), "\0");
}
function userinput($v,$e=0){
	global $mysqli;
	$v=$mysqli->real_escape_string(trim($v));
	return  ($e==1)?encrypt($v):$v;
}
function islogin(){
	if(!isset($_SESSION['yid'])||!($_SESSION['yid']>0)){
		return 0;
	}else{
		return 1;
	}
}
function isadminlogin(){
	global $mysqli;
	if(!isset($_SESSION['admin'])){
		return 0;
	}else{
		$rs=$mysqli->query("SELECT * FROM `yec_admin` WHERE 1;");
		$row = $rs->fetch_row();
		if($_SESSION['admin']==$row[0]){
			return 1;
		}else{
			return 0;
		}
	}
}
function sitesetup(){
	global $mysqli;
	$sql="SELECT * FROM  `yec_site`";
	$rs=$mysqli->query($sql);
	while($row=$rs->fetch_assoc()){
		$r[$row['sattr']]=stripslashes($row['svalue']);
	}
	return $r;
}
function checkhkid($str){
	if(preg_match("/(^[A-Z][0-9]{6,6}\([0-9]\))/",$str)){
		return TRUE;
	}else{
		return FALSE;
	}
}
function microtime_float(){
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}
function send_mail($to,$bcc="",$replyto="",$subject="",$message,$attm=array("")){//use comma to seperate $to emails\
	global $emailsender;
	require_once("./phpmailer/class.phpmailer.php");
	$from=$emailsender[0];
	$fromname=$emailsender[1];

	$mail = new PHPMailer();
	$mail->SetLanguage("en", './phpmailer/language/');
	$mail->IsSMTP(); // send via SMTP
	$mail->Host = "smtp.pacific.net.hk"; // your ISP SMTP servers
	//$mail->Host = "localhost"; // your ISP SMTP servers
	$mail->SMTPAuth = False; // turn on SMTP authentication
	$mail->From = $from;
	$mail->FromName=$fromname;
	$to=($to!="")?explode(";", $to):NULL;
	$bcc=($bcc!="")?explode(";", $bcc):NULL;
	$replyto=($replyto!="")?explode(";", $replyto):NULL;
	for($i=0;$i<count($to);$i++){
		if($to[$i]!=""){
			$mail->AddAddress($to[$i]);
		}
	}
	for($i=0;$i<count($bcc);$i++){
		if($bcc[$i]!=""){
			$mail->AddBCC($bcc[$i]);
		}
	}
	for($i=0;$i<count($replyto);$i++){
		if($replyto[$i]!=""){
			$mail->AddReplyTo($replyto[$i]);
		}
	}
	//$mail->WordWrap = 50; // set word wrap
	$mail->IsHTML(true); // send as HTML
	$mail->Subject = $subject;
	$mail->Body = stripslashes($message);
	for($i=0;$i<count($attm);$i++){
		if($attm[$i]!=""){
			$mail->AddAttachment($attm[$i]);
		}
	}
	if(!$mail->Send()){
	print_r($mail);
		echo $mail->ErrorInfo;
		die();
	}
}
FUNCTION send_file($name) {
  OB_END_CLEAN();
  $path = $name;
  IF (!IS_FILE($path) or CONNECTION_STATUS()!=0) RETURN(FALSE);
  HEADER("Cache-Control: no-store, no-cache, must-revalidate");
  HEADER("Cache-Control: post-check=0, pre-check=0", FALSE);
  HEADER("Pragma: no-cache");
  HEADER("Expires: ".GMDATE("D, d M Y H:i:s", MKTIME(DATE("H")+2, DATE("i"), DATE("s"), DATE("m"), DATE("d"), DATE("Y")))." GMT");
  HEADER("Last-Modified: ".GMDATE("D, d M Y H:i:s")." GMT");
  HEADER("Content-Type: application/octet-stream");
  HEADER("Content-Length: ".(string)(FILESIZE($path)));
  HEADER("Content-Disposition: inline; filename=$name");
  HEADER("Content-Transfer-Encoding: binary\n");
  IF ($file = FOPEN($path, 'rb')) {
   WHILE(!FEOF($file) and (CONNECTION_STATUS()==0)) {
     PRINT(FREAD($file, 1024*8));
     FLUSH();
   }
   FCLOSE($file);
  }
  RETURN((CONNECTION_STATUS()==0) and !CONNECTION_ABORTED());
}
?>
