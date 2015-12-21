<?PHP
require_once("./include/common.inc.php");
send_file(decrypt(userinput($_GET['apath'])));
?> 
<script text="text/javascript">
window.close();
</script>