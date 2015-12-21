<?php
ini_set('max_execution_time', -1);
set_time_limit(0);
$starttime=time();
/*$subjects[]="mathq";
$subjects[]="matha";
$subjects[]="phyq";
$subjects[]="phya";*/
$subjects[]=$_GET['s'];
$filetransfer=0;
foreach($subjects as $subject){
	unset($url);unset($filename);
	$lines = file('gceexampaper_'.$subject.'.txt');
	$v="";
	foreach ($lines as $line_num => $line) {
		$v.=$line;
	}
	//echo $v;
	$x=explode("href=\"",$v);
	foreach($x as $i =>$s){
		if ($i==0) continue;
		$a=explode("\"",$s);
		//echo $a[0]."\n";
		$url[]=preg_replace("/ /", "%20", $a[0]);
	}
	foreach($url as $s){
		$x=explode("/",$s);
		$filename[]=$x[count($x)-1];
	}
	/*foreach($url as $i=>$s){
		echo $s;
	}
	exit();*/
	foreach($url as $i=>$s){
		$url = $s;
		//analyse for year of pastpaper
		for($year=1990;$year<=2014;$year++){
			if(preg_match("/.*".$year.".*/",$filename[$i])){
				break;
			}
		}
		if(preg_match("/.*jan.*/i",$filename[$i])||preg_match("/.*".$year."01.*/",$filename[$i])||
			preg_match("/.*feb.*/i",$filename[$i])||preg_match("/.*".$year."02.*/",$filename[$i])||
			preg_match("/.*mar.*/i",$filename[$i])||preg_match("/.*".$year."03.*/",$filename[$i])){
			$month="Jan";
		}elseif(preg_match("/.*may.*/i",$filename[$i])||preg_match("/.*".$year."05.*/",$filename[$i])||
			preg_match("/.*jun.*/i",$filename[$i])||preg_match("/.*".$year."06.*/",$filename[$i])||
			preg_match("/.*jul.*/i",$filename[$i])||preg_match("/.*".$year."07.*/",$filename[$i])){
			$month="Jun";
		}else{
			$month="nuts";
			$year="";
		}
		$file = "./gceexampaper/".$subject."/".$year.$month."/".$filename[$i];
		$dirname = dirname($file);
		if (!file_exists($dirname) && !is_dir($dirname)){
			mkdir($dirname, 0777, true);
		}
		if(file_exists($file)&&filesize($file)>0) continue;
		
		
		if(!($src = fopen($url, 'r'))){
			$error[]=$url;
			continue;
		}
		$dest = fopen($file, 'w');
		stream_copy_to_stream($src,$dest);
		$filetransfer++;
		echo $filename[$i] . " is copied.<br />";
	}
}
$endtime=time();
echo "DONE! Time used: ".($endtime-$starttime)/60 ." mins.<br />Please download the files listed below manually:<p>";
foreach($error as $i=>$v){
	echo ($i+1).") <a href=\"".$v."\" target=\"_blank\">".$v."</a><br />";
}
echo "</p>";
?>