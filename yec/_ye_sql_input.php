<?php
require_once("./include/common.inc.php");
$csv="Au Yeung Yee Tung,歐陽依彤,,2013,,6435 0806,,yeebb88@hotmail.com
\"Chan Chi Yan, Sophie\",陳子恩,,2013,,9802 4947,,sophieiscute@gmail.com
Chan Ching Mei,陳菁美,,2013,,6354 3310,,mabel_chan@outlook.com
Chan Hiu Ying,陳曉瑩,,2013,,6925 7968,,chanhiuying88@hotmail.com
Chan Ho Wan,陳顥允,,2013,,5168 0627,,wailliamz@yahoo.com.hk
Chan Shuk Ying,陳淑盈,,2013,,9376 2876,,jessamine_csy@yahoo.com.hk
\"Chan Sin Yuen, Sharmaine\",陳倩元,,2013,,9143 6628,,sharmainechan@yahoo.com
Chan Tin Yi,陳天兒,,2013,,6231 2190,,kellychan199839@gmail.com
Chan Tsz Kiu,陳子喬,,2013,,9231 4746,,eugenechantk@gmail.com
\"Chan Wing Yin, Nicole\",陳穎妍,,2013,,6976 6213,,ejmnch@yahoo.com.hk
Chang Chu Ling,張楚淩,,2013,,6907 0931,,priscilla.patsi.chang@gmail.com
Cheng Sui Ying,鄭萃瑩,,2013,,6491 3910,,sherrancheng@hotmail.com
Cheung Chun Sing,張晉陞,,2013,,6685 0326,,jacksoncheung199708165@hotmail.com
Cheung Tak Yiu,張德耀,,2013,,9108 9496,,Cheungtakyiu@gmail.com
\"Chow Ho Chit, Savio\",周昊哲,,2013,,6071 5427,,chowsavio@yahoo.co.uk
Chung Yin,鍾妍,,2013,,5139 6833,,flora7996@gmail.com
Ho King Hei,何景熙,,2013,,5406 6851,,lologordon1998126@gmail.com
Ho Shing Lok,何承樂,,2013,,9072 7400,,georgeho113@gmail.com
Im Man Yi,嚴文宜,,2013,,9077 7845,,immanyi2002@yahoo.com.hk
Ip Ho Kei,葉可淇,,2013,,9701 3591,,yuki0012159193@hotmail.com
Josephine Zschiesche,薜雅蓮,,2013,,9095 9307,,5932ster@gmail.com
Ke Chun Ngai,柯俊霓,,2013,,6189 1565,,fionke716@yahoo.com.hk
Kwok Hei Man,郭曦文,,2013,,6225 2787,,13kwokh1@kgv.hk
Kwong Jasmine Nicole,鄺嘉熙,,2013,,6135 1997,,jasnkwong@gmail.com
Lai Ka Hei,黎嘉禧,,2013,,5139 6828,,laikahei57@yahoo.com.hk
\"Lai Mei Hing, Samantha\",黎美卿,,2013,,9181 9241,,samanthalaimh@gmail.com
\"Lam Hor Ying, Lilian\",林可凝,,2013,,6146 8340,,lilianlamhy@yahoo.com.hk
\"Lam Ting Luk, Yuet Yu Melody\",林悅如,,2013,,6384 8386,,melosmurf@gmail.com
Law Shun Yin,羅信然,,2013,,9731 3732,,justinlaw313@gmail.com
\"Law Yee Kiu, Stefanie\",羅漪蕎,,2013,,6016 6889,,stefanielawyk@gmail.com
Leigh Vanessa,李婥善,,2013,,6306 2378,,vvleigh@gmail.com
Leung On Ying,梁安盈,,2013,,9029 7790,,agnesonying_leung@hotmail.com
Leung Pui Sze,梁珮詩,,2013,,5181 8318,,yuki3b17@hotmail.com
Li Hau Chi,李巧稚,,2013,,6359 9810,,hauchiyoyoli@yahoo.com.hk
Li Oi Shun,李愛純,,2013,,5426 0092,,oishunkl@gmail.com
Lin Wing Sum,連穎琛,,2013,,6399 7626,,sum960825@hotmail.com
Pat Hoi Ling,畢凱玲,,2013,,6577 7202,,hoilingpat@yahoo.com.hk
\"Shek Wing Ling, Vanessa\",石穎靈,,2013,,9584 5280,,vanessashek@gmail.com
Sitou Kit Yiu,司徒潔瑤,,2013,,6581 5185,,katresa1b36@yahoo.com.hk
Tang King Tung,鄧境彤,,2013,,6574 3757,,angelatang1210@gmail.com
To Shu Ho,杜書皓,,2013,,6230 0952,,toshuho@hotmail.com
Tsang I Mau,曾漪懋,,2013,,6964 0254,,tsangitung6b28@yahoo.com.hk
Tse Lok Hei,謝諾稀,,2013,,6493 2532,,calvintse711@yahoo.com.hk
Tse Wang Cheong,謝泓昌,,2013,,9765 9902,,raptkb@hotmail.com
\"Wan Yat Fu, Peter\",尹一夫,,2013,,6382 1542,,rooneyfu@hotmail.com
Wong Lok Yan,黃樂恩,,2013,,6767 2201,,loklokcoco@hotmail.com
\"Wong Nok Yee, Joey\",黃諾頤,,2013,,6030 0517,,joeynywong@yahoo.com.hk
\"Yip Yan Ming, Michelle\",葉欣明,,2013,,9039 6178,,michelleymyip@yahoo.com.hk
Yiu Wing Lam,姚穎霖,,2013,,5169 3900,,fish_yiu@yahoo.com.hk
Yong Ka Yu,楊家裕,,2013,,5113 1718,,yongkayu@gmail.com";
$members=split("\n",$csv);
for($i=0;$i<count($members);$i++){
	$members[$i]=split(",",$members[$i]);
	if(count($members[$i])==9||count($members[$i])==8){
		if(count($members[$i])==8)continue;
		$members[$i][0]=substr($members[$i][0],1).", ".substr($members[$i][1],0,-1);
		
		for($k=1;$k<8;$k++){
			$members[$i][$k]=$members[$i][$k+1];
		}
		unset($members[$i][8]);
	}else{echo count($members[$i])." diu ".$i;exit();}
}
for($i=0;$i<count($members);$i++){
	for($j=0;$j<count($members[$i]);$j++){
		$members[$i][$j]=trim($members[$i][$j]);
		if($j==4||$j==5){
			$members[$i][$j]=str_replace(" ","",$members[$i][$j]);
		}
	}
}
$sum=0;
for($i=0;$i<count($members);$i++){
	$sum+=count($members[$i]);
}
if($sum==count($members)*8){
	for($i=0;$i<count($members);$i++){
		$sql="INSERT INTO  `yec_ye` (`yyear` ,`yname_en` ,`yname_ch` ,`yemail` ,`yphone_h` ,`yphone_m`)VALUES ('".userinput($members[$i][3],1)."','".userinput($members[$i][0],1)."','".userinput($members[$i][1],1)."','".userinput($members[$i][7],1)."','".userinput($members[$i][4],1)."','".userinput($members[$i][5],1)."');";
		echo $sql;
	}
}
?>