<?php
session_start();
$noNavBar =  "";
$title = "Login";?>
<form class=login method=post action="">
 <h3 class="text-center">Admin Login</h3>
 <hr>
    <input class="form-control input-lg" type=text name="user" placeholder="Username" autocomplete=off required></br>
    <input class="form-control input-lg" type=password name=pass placeholder="Password" autocomplete=new_password required> <br>
    <input class="btn btn-lg btn-primary btn-block" type=Submit name=login Value=Login>
 </form>
<?php
if(isset($_SESSION['uname'])){
    header('Location: dashboard.php'); //Redirection to Dashboard.
} 
 include("init.php");
 extract($_POST);

 if(isset($login)){
    $username = $user;
    $password = $pass;
    $hsedpass = md5($password);
   $stmt = $db->prepare("SELECT UserID, Username, GroupID, Password
   FROM users
   WHERE Username= ?
   AND Password = ?
   AND GroupID IN (1,2) 
   LIMIT 1");
   $stmt->execute(array($username, $hsedpass));
   $row= $stmt->fetch();
   $rs = $stmt->rowCount();

   //If $rs>0, username exits.

   if($rs>0){
       $_SESSION['uname']=$username;  //Register Session name.
       $_SESSION['uid']=$row['UserID'];
       $_SESSION['GID']=$row['GroupID'];
        header('Location: dashboard.php'); //Redirection to Dashboard.
        exit(); //Stop executing the program.
      }
      else {
          ?>
          <div class="container">
          <div class="row">
            <div class= "alert alert-danger">Wrong Username or Password</div>
            </div>
            </div>
          <?php
      }
 }
 ?>

<?php include($tpl."footer.php"); ?>