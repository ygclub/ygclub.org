<?php
$_GET['tid'] = addslashes($_GET['tid']);
header("location:forum.php?mod=viewthread&tid=" . $_GET['tid']);
?>
