<?php
session_start();
if(isset($_SESSION['uname'])){
    $title="Comments";
    include('init.php');
    $do = isset($_GET['do']) ? $_GET['do'] : "Manage";
    if($do == "Manage"){
        $stmt =$db->prepare("SELECT comments.*, items.Name
         AS Item_name, users.Username AS UNAME
        FROM comments
        INNER JOIN items ON items.item_ID=comments.item_id 
        INNER JOIN users ON users.UserID=comments.user_id");
        $stmt->execute();
        $data = $stmt->fetchAll();
        ?>
         <h1 class="text-center">Manage Comments</h1>
        <div class=container>
            <div class="table-responsive">
                <table class="main-table text-center table  ">
                    <tr>
                        <td>CID</td>
                        <td>Comment</td>
                        <td>Item Name</td>
                        <td>User Name</td>
                        <td>Added Date</td>
                        <td>Control</td>
                    </tr>
                    <?php
                    foreach($data as $row){
                        ?>
                         <tr>
                        <td><?php echo $row['CID'] ?></td>
                        <td><?php echo $row['comment'] ?></td>
                        <td><?php echo $row['Item_name'] ?></td>
                        <td><?php echo $row['UNAME'] ?></td>
                        <td><?php echo $row['date'] ?></td>
                        <td>
                            <a href="comments.php?do=Edit&ID=<?php echo $row['CID']; ?>" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>
                            <a href="comments.php?do=delete&ID=<?php echo $row['CID']; ?>" class="btn btn-danger confirm"><i class="fa fa-close"></i> Delete</a>
                        </td>
                    </tr>
                        <?php
                    }
                    ?>
                </table>
            </div>
        </div>
         
        <?php
}
    elseif($do == "Edit"){
    $comID = isset($_GET['ID']) && is_numeric($_GET['ID']) ? intval($_GET['ID']) : 0;
    $stmt = $db->prepare("SELECT * FROM comments where CID=? ");
   $stmt->execute(array($comID));
   $row= $stmt->fetch();
   $rs = $stmt->rowCount();
   if($rs >0){ 
       ?>
    <h1 class="text-center">Edit Comment</h1>
    <div class=container>
        <form class="form-horizontal" action="?do=Update" method=post action="">
            <input type=hidden name=cid value="<?php echo $comID; ?>">
            <div class="form-group-lg ">
                <lable class= "col-sm-2 control-label">Comment</label>
                    <div class="col-sm-10 col-md-4">
                        <textarea class="form-control" name="comment"><?php echo $row['comment']; ?></textarea>
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

            $id = $_POST['cid'];
            $comment = $_POST['comment'];

                $stmt=$db->prepare("UPDATE comments SET comment=? WHERE CID = ?");
                $stmt->execute(array($comment, $id));
                $msg= '<div class="alert alert-success">'. '<strong align=center>Record Updated!</strong> </div>';
                redirection($msg);
        }
        else {
            $msg= '<div class="alert alert-danger">Sorry you cant browse this page directly</div>';
            redirection($msg, "previousPage");
        }
        echo "</div>";
    }
    elseif($do == 'delete'){
        echo "<h1 class='text-center'>Delete Comment</h1>";
        echo "<div class='container'>";
        $cid = isset($_GET['ID']) && is_numeric($_GET['ID']) ? intval($_GET['ID']) : 0;
        $stmt = $db->prepare("SELECT * FROM comments where CID=?");
       $stmt->execute(array($cid));
       $rs = $stmt->rowCount();
       if($rs >0){ 
           $stmt=$db->prepare(
               "DELETE FROM comments WHERE CID=?"
           );
           $stmt->execute(array($cid));
           $row= $stmt->fetch();
           $msg= '<div class="alert alert-success">'. '<strong align=center>Record Deleted!</strong> </div>';
           redirection($msg, 'back', 0);
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