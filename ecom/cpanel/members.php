<?php
session_start();
if(isset($_SESSION['uname'])){
    $title="Members";
    include('init.php');
    $do = isset($_GET['do']) ? $_GET['do'] : "Manage";
    if($do == "Manage"){
        $query =null;
        if(isset($_GET['page']) && $_GET['page']=='Pending'){
            $query ='AND RegStatus =0';
        }
        $stmt =$db->prepare("SELECT * FROM users WHERE GroupID !=1 $query");
        $stmt->execute();
        $data = $stmt->fetchAll();
        ?>
         <h1 class="text-center">Manage Members</h1>
        <div class=container>
            <div class="table-responsive">
                <table class="manage-members main-table text-center table  ">
                    <tr>
                        <td>UID</td>
                        <td>Profile Picture</td>
                        <td>Username</td>
                        <td>Email</td>
                        <td>Full Name</td>
                        <td>Control</td>
                    </tr>
                    <?php
                    foreach($data as $user){
                        ?>
                         <tr>
                        <td><?php echo $user['UserID'] ?></td>
                        <td><img src="uploads/avatars/<?php 
                        if(!empty($user['propic'])){
                            echo $user['propic'];
                        }
                        else {
                            echo 'default.png';
                        }
                        ?>" alt=""/></td>
                        <td><?php echo $user['Username'] ?></td>
                        <td><?php echo $user['Email'] ?></td>
                        <td><?php echo $user['FullName'] ?></td>
                        <td>
                            <a href="members.php?do=Edit&ID=<?php echo $user['UserID']; ?>" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>
                            <a href="members.php?do=delete&ID=<?php echo $user['UserID']; ?>" class="btn btn-danger confirm"><i class="fa fa-close"></i> Delete</a>
                            <?php
                            if($user['RegStatus']==0){
                                ?>
                               <a href="members.php?do=Activate&ID=<?php echo $user['UserID']; ?>" class="btn btn-info"><i class="fa fa-close"></i> Activate</a>
                                <?php
                            }
                            ?>
                        </td>
                    </tr>
                        <?php
                    }
                    ?>
                </table>
            </div>
        <a href="members.php?do=Add" class="btn btn-primary">+ New Member</a>
        </div>
         
        <?php
    }
    elseif($do=='Add'){
        ?>
        <h1 class="text-center">Add New Member</h1>
    <div class=container>
        <form class="form-horizontal" action="?do=Insert" method=post action="" enctype="multipart/form-data">
            <div class="form-group-lg ">
                <lable class= "col-sm-2 control-label">Username</label>
                    <div class="col-sm-10 col-md-4">
                        <input type=text id="form_fname" name=username class="form-control"  autocomplete=off required="required" placeholder="Username to login to shop" /> 
                        <span class="error_form" id="name_error_message"></span>
        </div>
        </div>
        <div class="form-group">
                <lable class= "col-sm-2 control-label">Password</label>
                    <div class="col-sm-10 col-md-4">
                    <input type=password id="form_password" name=password class="form-control" autocomplete= new-password  required=required placeholder="Password"/>
                    <span class="error_form" id="password_error_message"></span>
        </div>
        </div>
        <div class="form-group">
                <lable class= "col-sm-2 control-label">Email</label>
                    <div class="col-sm-10 col-md-4">
                        <input type=email  id="form_email"  name=email class="form-control" required=required placeholder="Valid Email">
                        <span class="error_form" id="email_error_message"></span>
        </div>
        </div>
        <div class="form-group">
                <lable class= "col-sm-2 control-label">Full Name</label>
                    <div class="col-sm-10 col-md-4">
                        <input type=text name=name class="form-control"  required= required autocomplete=off placeholder="Your Name">
        </div>
        <div class="form-group">
                <lable class= "col-sm-2 control-label">Profile Picture</label>
                    <div class="col-sm-10 col-md-4">
                        <input type=file name=propic class="form-control"  autocomplete=off placeholder="Your Name">
        </div>
        </div>
        <br>
        <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type=submit name=Save class="btn btn-success" value="Add" id="register"/>
        </div>
        </div>
        </form>
    </div>

        <?php
    }
    elseif($do == "Insert"){
      
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            echo "<h1 class='text-center'>Insert User</h1>";
            echo "<div class='container'>";
            $user = $_POST['username'];
            $pass = $_POST['password'];
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

            
           
            $hPass = md5($pass );
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
            if(empty($pass)){
                $formerrors[]= ' Password cannot be empty! ';
            }
            if(empty($fname)){
                $formerrors[]='Name cannot be empty!';
            }
            if(empty($email)){
                $formerrors[]= 'Email cannnot be empty! ';
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
                move_uploaded_file($propicTmp, "uploads\avatars\\".$avatar);
               
                 //Check if the user already exists.
                $available = checkItem("Username", "users", $user);
                $availableMail = checkItem("Email", "users", $email);

                //Insert User info in DB.
                if ($available >0){
                    $msg= '<div class= "alert alert-danger">'."User already exists!". '</div>';
                    redirection($msg, 'back');
                }
                if ($availableMail>0){
                    $msg= '<div class= "alert alert-danger">'."User already exists with that emai!". '</div>';
                    redirection($msg, 'back');
                }
                else {
                $stmt = $db->prepare("INSERT INTO users(Username, Password, Email, FullName, RegStatus, propic, GroupID) VALUES(:iuser, :ipass, :imail, :iname, 1, :ipic, 2) ");
                $stmt->execute(array(
                    'iuser' => $user,
                    'ipass' => $hPass,
                    'imail' => $email,
                    'iname' => $fname,
                    'ipic' => $avatar
                ));
                $msg= '<div class="alert alert-success">'. '<strong align=center>Record Added!</strong> </div>';
                redirection($msg, 'back');
                }
        
                }
        }
        else {
            $msg='<div class= "alert alert-danger">Sorry, you cannot browse this page directly</div>';
            redirection($msg);
                }
        echo "</div>";
    }
    elseif($do == "Edit"){
    $userID = isset($_GET['ID']) && is_numeric($_GET['ID']) ? intval($_GET['ID']) : 0;
    $stmt = $db->prepare("SELECT * FROM users where UserID=? LIMIT 1");
   $stmt->execute(array($userID));
   $row= $stmt->fetch();
   $rs = $stmt->rowCount();
   if($rs >0){ 
       ?>
    <h1 class="text-center">Edit Profile</h1>
    <div class=container>
        <form class="form-horizontal" action="?do=Update" method=post action="" enctype="multipart/form-data">
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
    <?php
   }
   else {
       echo "<div class=container>";
       $msg ='<div class="alert alert-danger">There is no such ID</div>';
       echo "</div>";
       redirection($msg);
   }
 
    }
    elseif($do== 'Update'){
        echo "<h1 class='text-center'>Update Profile</h1>";
        echo "<div class='container'>";
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
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
            foreach($formerrors as $error){
                echo '<div class= "alert alert-danger">'.$error. '</div>';
                echo '<br>';
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
                move_uploaded_file($propicTmp, "uploads\avatars\\".$avatar);
                $check = $db->prepare("SELECT * FROM users WHERE Username=? AND UserID !=?");
                $check->execute(array($user,$id));
                $row= $check->rowCount();
                if($row ==1 ){
                    $msg= '<div class="alert alert-danger">Sorry user already exists!</div>';
                    redirection($msg, "previousPage");                }
                
                else {
                $stmt=$db->prepare("UPDATE users SET Username=?, Email=?, FullName=?, Password=?, propic=? WHERE UserID = ?");
                $stmt->execute(array($user, $email,$fname,$pass,$avatar,$id));
                $msg= '<div class="alert alert-success">'. '<strong align=center>Record Updated!</strong> </div>';
                redirection($msg);
                }
                
            }
        }
        else {
            $msg= '<div class="alert alert-danger">Sorry you cant browse this page directly</div>';
            redirection($msg, "previousPage");
        }
        echo "</div>";
    }
    elseif($do == 'delete'){
        echo "<h1 class='text-center'>Delete Member</h1>";
        echo "<div class='container'>";
        $userID = isset($_GET['ID']) && is_numeric($_GET['ID']) ? intval($_GET['ID']) : 0;
        $stmt = $db->prepare("SELECT * FROM users where UserID=? LIMIT 1");
       $stmt->execute(array($userID));
       $rs = $stmt->rowCount();
       if($rs >0){ 
           $stmt=$db->prepare(
               "DELETE FROM users WHERE UserID=?"
           );
           $stmt->execute(array($userID));
           $row= $stmt->fetch();
           $msg= '<div class="alert alert-success">'. '<strong align=center>Record Deleted!</strong> </div>';
           redirection($msg);
        }
       else {
           echo "<div class=container>";
           $msg= '<div class="alert alert-danger">No such ID</div>';
           echo "</div>";
           redirection($msg, 'back');
       }
       echo '</div>';
    }
    elseif($do=='Activate'){
        echo "<h1 class='text-center'>Activate Member</h1>";
        echo "<div class='container'>";
        $userID = isset($_GET['ID']) && is_numeric($_GET['ID']) ? intval($_GET['ID']) : 0;
        $stmt = $db->prepare("SELECT * FROM users where UserID=? LIMIT 1");
       $stmt->execute(array($userID));
       $rs = $stmt->rowCount();
       if($rs >0){ 
           $stmt=$db->prepare(
               "UPDATE users SET RegStatus=1 WHERE UserID=?"
           );
           $stmt->execute(array($userID));
           $row= $stmt->fetch();
           $msg= '<div class="alert alert-success">'. '<strong align=center>Record Updated!</strong> </div>';
           redirection($msg);
        }
       else {
           echo "<div class=container>";
           $msg= '<div class="alert alert-danger">No such ID</div>';
           echo "</div>";
           redirection($msg);
       }
       echo '</div>';    
    }
    require($tpl."footer.php"); 
}
else {
    header("Location: index.php");
}
?>