<?php
require_once("./include/common.inc.php");
$sql="SELECT * FROM  `yec_ye`";
$rs=$mysqli->query($sql);
$bcc="";
$replyto=$emailreplyto;
//$to=$emailreplyto;
$subject="An Update on YEC Reform Proposal Voting and Amendments to the Present Constitution";
$i=0;
$msg='
<div style="word-wrap:break-word"><div></div><div><br></div><div>
<p class="MsoNormal" style="text-autospace:none"><span lang="EN-US" style="font-size:16.0pt;font-family:&quot;Times New Roman&quot;">Dear
Young Leaders/Young Envoys,</span><span lang="EN-US" style="font-size:16.0pt;font-family:Garamond"><u></u><u></u></span></p><p class="MsoNormal" style="text-autospace:none"><span lang="EN-US" style="font-size:16.0pt;font-family:&quot;Times New Roman&quot;">&nbsp;</span><span lang="EN-US"><u></u><u></u></span></p><p class="MsoNormal" align="center" style="text-align:center;text-autospace:none"><b><span lang="EN-US" style="font-size:16.0pt;font-family:&quot;Times New Roman&quot;">An Update on YEC Reform
Proposal Voting and Amendments to the Present Constitution</span></b><span lang="EN-US"><u></u><u></u></span></p><p class="MsoNormal" style="text-autospace:none"><span lang="EN-US" style="font-size:16.0pt;font-family:&quot;Times New Roman&quot;">&nbsp;</span><span lang="EN-US"><u></u><u></u></span></p><p class="MsoNormal" style="text-autospace:none"><span lang="EN-US" style="font-size:16.0pt;font-family:&quot;Times New Roman&quot;">Over
the past week, voting on the final proposal of Young Envoys Club reform has
been going on. Unfortunately, the voting results cannot be properly recorded
due to a technical problem with the voting link used. After fixing the system,
the voting procedure is about to resume. Please kindly show your support by
visiting the following link and casting your vote on the reform proposal by
20/8 ( Tue ). Your decision will surely make a change to YEC’s development in
the future. (Young Envoys who have voted at the previous link please kindly
vote again.)</span><span lang="EN-US" style="font-size:16.0pt;font-family:Garamond"><u></u><u></u></span></p><p class="MsoNormal" style="text-autospace:none"><span lang="EN-US" style="font-size:16.0pt;font-family:&quot;Times New Roman&quot;">&nbsp;</span><span lang="EN-US"><u></u><u></u></span></p><p class="MsoNormal" style="text-autospace:none"><span lang="EN-US" style="font-size:16.0pt;font-family:Garamond"><a href="https://docs.google.com/forms/d/1J95P0E81oOvjUVTkfek-3bQhKpTWJILZX0ja5X9XqsM/viewform" target="_blank"><span style="font-family:&quot;Times New Roman&quot;;color:#314983;text-decoration:none">https://docs.google.com/forms/<wbr>d/1J95P0E81oOvjUVTkfek-<wbr>3bQhKpTWJILZX0ja5X9XqsM/<wbr>viewform</span></a></span><span lang="EN-US" style="font-size:16.0pt;font-family:&quot;Times New Roman&quot;"> &nbsp;</span><span lang="EN-US"><u></u><u></u></span></p><p class="MsoNormal" style="text-autospace:none"><span lang="EN-US" style="font-size:16.0pt;font-family:&quot;Times New Roman&quot;">&nbsp;<u></u><u></u></span></p><p class="MsoNormal" style="text-autospace:none"><span lang="EN-US" style="font-size:16.0pt;font-family:&quot;Times New Roman&quot;">Furthermore,
in order to reflect changes of our Club, substantial amendments have been made
to the present Constitution. Pursuant to Article 8.1(d) of the present
Constitution, members have 14 days (on or before 25/8, our Annual General
Meeting) to submit objections to the amendments in written form. A vote will be
carried out on the Annual General Meeting to pass the constitution pursuant to
Article 8.1(e) of the Constitution.<u></u><u></u></span></p><p class="MsoNormal" style="text-autospace:none"><span lang="EN-US" style="font-size:16.0pt;font-family:Garamond"><u></u>&nbsp;<u></u></span></p><p class="MsoNormal" style="text-autospace:none"><span lang="EN-US" style="font-size:16.0pt;font-family:&quot;Times New Roman&quot;">Sorry
for any inconvenience caused and we look forward to hearing from you !</span><span lang="EN-US"><u></u><u></u></span></p><p class="MsoNormal" style="text-autospace:none"><span lang="EN-US" style="font-size:16.0pt;font-family:&quot;Times New Roman&quot;">&nbsp;</span><span lang="EN-US"><u></u><u></u></span></p><p class="MsoNormal" style="text-autospace:none"><span lang="EN-US" style="font-size:16.0pt;font-family:&quot;Times New Roman&quot;">Best
regards,</span><span lang="EN-US" style="font-size:16.0pt;font-family:Garamond"><u></u><u></u></span></p><p class="MsoNormal" style="text-autospace:none"><span lang="EN-US" style="font-size:16.0pt;font-family:&quot;Times New Roman&quot;">Alvin
Au</span><span lang="EN-US" style="font-size:16.0pt;font-family:Garamond"><u></u><u></u></span></p><p class="MsoNormal" style="text-autospace:none"><span lang="EN-US" style="font-size:16.0pt;font-family:&quot;Times New Roman&quot;">Chairperson,</span><span lang="EN-US"><u></u><u></u></span></p><p class="MsoNormal" style="text-autospace:none"><span lang="EN-US" style="font-size:16.0pt;font-family:Garamond"><u></u>&nbsp;<u></u></span></p><p class="MsoNormal"><span lang="EN-US" style="font-size:16.0pt;font-family:&quot;Times New Roman&quot;">UNICEF Young Envoys Club Session 2013&nbsp;</span><span lang="EN-US"><u></u><u></u></span></p><div class="yj6qo"></div><div class="adL">

</div></div></div>
';
$attm[]="./attachments/UNICEF_Young_Envoys_Club_Constitution_2012.pdf";
$attm[]="./attachments/Proposed_Amendments_to_the_Constitution.pdf";
if($_GET['ok']!=1){
	send_mail("ycm.jason@gmail.com","",$replyto,$subject,$msg,$attm);
}else{
	while($row=$rs->fetch_assoc()){
		$i++;
		echo $i."<br>";
		$bcc=decrypt($row['yemail']);
		$bcc=explode("/",$bcc);
		foreach($bcc as $v){
			$b=trim($v);
			if($b!=""){
				send_mail($b,"",$replyto,$subject,$msg,$attm);
			}
		}
	}
}
?>
