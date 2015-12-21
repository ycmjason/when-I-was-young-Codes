<?php
require_once("./include/common.inc.php");
$v=userinput("abc",1);
echo $v;
$v=decrypt($v);
echo "<br />".$v;
?>