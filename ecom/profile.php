<?php
$title="Profile Page";
include("init.php");
$USR = $_SESSION['username'];
$USRID=$_SESSION['uid'];
$getUser= $db->prepare("SELECT * FROM users WHERE Username=? ");
$getUser->execute(array($USR));
$usrData= $getUser->fetch();
extract($_POST);
if(isset($Save)){
    $id = $_POST['uid'];
            $user = $_POST['username'];
            $email = $_POST['email'];
            $fname = $_POST['name'];

             //PROFILE_PICTURE
             $propic = $_FILES['propic'];
             $propicName=$_FILES['propic']['name'];
             $propicSize=$_FILES['propic']['size'];
             $propicTmp=$_FILES['propic']['tmp_name'];
             $propicType=$_FILES['propic']['type'];
             //Allowed File types
             $proPicExten = array("jpeg", "jpg","png", "gif");
 
             //Get Avatar Extension
             $exploded = explode('.',$propicName);
             $propicExtension=strtolower(end($exploded));
 
             

            $pass ='';
            if(empty($_POST['newpassword'])) {
                $pass = $_POST['oldpassword'];
            }
            else {
                $pass = md5($_POST['newpassword']);
            }
            $formerrors=array();
            if(strlen($user)<4){
                $formerrors[]=' Username cannot be less than 4 characters! ';
            }
            if(strlen($user)>20){
                $formerrors[]=' Username cannot be more than 20 characters! ';
            }
            if(empty($user)){
                $formerrors[]= ' Username cannot be empty! ';
            }
            if(empty($fname)){
                $formerrors[]='Name cannot be empty!';
            }
            if(empty($email)){
                $formerrors[]= 'email cannnot be empty! ';
            }
            if(!empty($propicName) && !in_array($propicExtension,$proPicExten)){
                $formerrors[]='File extension not allowed!';
            }
            if($propicSize > 4194304){
                $formerrors[]='File size is too big, avatar can\'t be more than 4MB.';
            }
            foreach($formerrors as $error){
                echo '<div class= "alert alert-danger">'.$error. '</div>';
                echo '<br>';
            }
            if(empty($formerrors)){

                $random=rand(0,2423343564523);
                $avatar=$random.'_'.$propicName;
                move_uploaded_file($propicTmp, "cpanel\uploads\avatars\\".$avatar);

                $check = $db->prepare("SELECT * FROM users WHERE Username=? AND UserID !=?");
                $check->execute(array($user,$id));
                $row= $check->rowCount();
                if($row ==1 ){
                    $msg= '<div class="alert alert-danger">Sorry user already exists!</div>';
                    redirection($msg, "previousPage");                }
                
                else {
                $stmt=$db->prepare("UPDATE users SET Username=?, Email=?, FullName=?, Password=?, propic=? WHERE UserID = ?");
                $stmt->execute(array($user, $email,$fname,$pass,$avatar,$id));
                $_SESSION['username']=$user;
                $msg= '<div class="alert alert-success">'. '<strong align=center>Record Updated!</strong> </div>';
                echo $msg;
                }
}
}

?>
<div id="myDIV">
<?php
           $userID = $_SESSION['uid'];
           $stmt = $db->prepare("SELECT * FROM users where UserID=? LIMIT 1");
          $stmt->execute(array($userID));
          $row= $stmt->fetch();
          $rs = $stmt->rowCount();
          if($rs >0){ 
              ?>


<h1 class="text-center">Edit Profile</h1>
    <div class=container>
        <form class="form-horizontal" method=post action='' enctype="multipart/form-data">
            <input type=hidden name=uid value="<?php echo $userID; ?>">
            <div class="form-group-lg ">
                <lable class= "col-sm-2 control-label">Username</label>
                    <div class="col-sm-10 col-md-4">
                        <input type=text name=username class="form-control" value="<?php echo $row["Username"] ?>" autocomplete=off required="required" /> 
        </div>
        </div>
        <div class="form-group">
                <lable class= "col-sm-2 control-label">Password</label>
                    <div class="col-sm-10 col-md-4">
                    <input type=hidden name=oldpassword value="<?php echo $row["Password"] ?>" />
                    <input type=password name=newpassword class="form-control" autocomplete= new-password/>
        </div>
        </div>
        <div class="form-group">
                <lable class= "col-sm-2 control-label">Email</label>
                    <div class="col-sm-10 col-md-4">
                        <input type=email name=email class="form-control" value="<?php echo $row["Email"] ?>" required=required ">
        </div>
        </div>
        <div class="form-group">
                <lable class= "col-sm-2 control-label">Full Name</label>
                    <div class="col-sm-10 col-md-4">
                        <input type=text name=name class="form-control" value="<?php echo $row["FullName"] ?>" required= required">
        </div>
        </div>
        <br>
        <div class="form-group">
                <lable class= "col-sm-2 control-label">Profile Picture</label>
                    <div class="col-sm-10 col-md-4">
                        <input type=file name=propic class="form-control"  autocomplete=off placeholder="Your Name">
        </div>
        </div>
        <br>
        <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type=submit name=Save class="btn btn-success"/>
        </div>
        </div>
        </form>
    </div>
          </div>
<?php 
          }
$orders=$db->prepare("SELECT * FROM orders WHERE userID=$USRID");
$orders->execute();
$data=$orders->fetchAll ();
if(! empty($data)){ 
    ?>
    <h1 class="text-center">Track Your Orders</h1>
    <div class=container>
        <div class="table-responsive">
            <table class="main-table text-center table">
                <tr>
                    <th>OID</th>
                    <th>Address</th>
                    <th>Delivery Method</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
                <?php foreach($data as $item){ ?>
                <tr>
                <td><?php echo $item[0]; ?></td>
                <td><?php echo $item[2]; ?></td>
                <td><?php echo $item[4]; ?></td>
                <td><?php echo $item[3]; ?></td>
                <td><?php echo $item[6]; if($item[6]=="Delivered"){ echo ' <i class="fas fa-check"></i>'; }?></td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </div>
<?php
}
include($tpl."footer.php");
?>