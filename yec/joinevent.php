<?php
if($_GET['q']==1){
	header("location:work.php?joinevent=1&pid=".$_GET['pid']."&q=1");
}else{
	header("location:work.php?joinevent=1&pid=".$_GET['pid']."&r=".$_GET['r']);
}?>