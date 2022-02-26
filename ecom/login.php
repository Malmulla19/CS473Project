<?php
$title = "Login";
include('init.php');
if(isset($_SESSION['username'])){
    header('Location: index.php'); //Redirection to Dashboard.
}
extract($_POST);
if(isset($login)){
 $username1 = $username;
 $password1 = $password;
 if(isset($login)){
    $hsedpass1 = md5($password1);
   $stmt = $db->prepare("SELECT UserID, Username, GroupID, Password FROM users where Username=? AND Password=?");
   $stmt->execute(array($username1, $hsedpass1));
   $rs = $stmt->rowCount();
   $userData=$stmt->fetch();

   //If $rs>0, username exits.

   if($rs>0){
       if($userData['GroupID']!=1 && $userData['GroupID']!=2){
       $_SESSION['username']=$username; 
    }
    else {
        $_SESSION['uname']=$username; 
    }
       $_SESSION['uid']=$userData['UserID'];  //Register Session name.
       $_SESSION['ID']=$userData['UserID'];  //Register Session name.
       $_SESSION['GID'] =$userData['GroupID']; //GroupID
       header('Location: index.php'); //Redirection to Dashboard.
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
}
elseif(isset($signup)){
    if(isset($_POST['username'])){
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $stmt = $db->prepare("SELECT Username FROM users WHERE Username=?");
        $stmt->execute(array($username));
        $check = $stmt->rowCount();
        if($check>0){
            die( '<script>alert("Username Already Exists!")</script>');
            exit();
        }
        $email =$_POST['email'];
        $stmt = $db->prepare("SELECT Email FROM users WHERE Email=?");
        $stmt->execute(array($email));
        $userData=$stmt->fetch();
        $check2 = $stmt->rowCount();
        if($check2>0){
            die('<script>alert("Email Already Exists!")</script>');
            exit();
        }
        $password=md5($_POST['password']);
        $email = $_POST['email'];
        $fullname=$_POST['name'];
        $insert=$db->prepare("INSERT INTO users (Username, Password, Email, FullName, RegStatus)
         VALUES(:iname, :ipass, :imail, :ifname, 0)");
        $insert->execute(array(
            'iname' =>$username,
            'ipass' =>$password,
            'imail' =>$email,
            'ifname' =>$fullname
        ));
        $fetch =$db->query("SELECT *
        FROM users 
        ORDER BY UserID DESC LIMIT 1
        ");
        $NEWUSERDATA = $fetch->fetch();
        $msg= '<div class="alert alert-success">'. '<strong align=center>Record Added!</strong> </div>';
        $_SESSION['username']=$NEWUSERDATA['Username'];
        $_SESSION['uid']=$NEWUSERDATA['UserID'];
        redirection($msg);
        }
}
?>
<!--Start of Login page-->  
<div class="container login-page">
<h1 class="text-center">
<span class="selected" data-class="login">Login</span> |
<span data-class="signup">Signup</span>
</h1>
<form class="login" method=post action="">
<input class="form-control" type="text" name="username" autocomplete="off" placeholder="Username" />
<input class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Password" />
<input class="btn btn-success btn-primary" type="submit" value="Login" name="login" />
</form>
<form class="signup" method=post action="" id="registration_form">

<div>
    <label>
		Username
	</label>	
<input class="form-control" type="text" id="form_fname" name="username" autocomplete="off" placeholder="Username 4 Characters minimum" minlength=4 required />
<span class="error_form" id="name_error_message"></span>
</div>

<div>
<label>
		Password:
</label>	
<input class="form-control" type="password" id="form_password" name="password" autocomplete="new-password" placeholder="Password" required/>
<span class="error_form" id="password_error_message"></span>
</div>

<div>
<label>
		Confirm Password:
</label>	
<input class="form-control" type="password" id="form_retype_password" name="cpassword" autocomplete="new-password" placeholder="Confirm Password"required/>
<span class="error_form" id="retype_password_error_message"></span>
</div>

<div>
<label>
    Full Name:
</label>	
<input class="form-control"  name="name" placeholder="You Full Name" required/>
</div>
<div>
<label>
		Email Address:
</label>	
<input class="form-control" type="email"  id="form_email" name="email" placeholder="Email"required/>
<span class="error_form" id="email_error_message"></span>
</div>

<input class="btn btn-success btn-success" id="register" type="submit" value="Signup" name=signup />
</form>
</div>
<?php
include($tpl.'footer.php');
?>