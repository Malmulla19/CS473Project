<?php
include('init.php');
$q = $db->query("SELECT FullName FROM users WHERE UserID=1");
$row=$q->fetch();
print_r($row);
?>