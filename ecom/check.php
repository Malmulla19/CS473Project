<?php  
//check.php  
include('init.php')
extract($_POST);
$stmt = $db->prepare("SELECT Username FROM users WHERE Username=?");
$stmt->execute(array($user_name));
$rs = $stmt->rowCount();
echo $rs;
?>
