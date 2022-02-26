<?php
ob_start();
session_start();
if(isset($_SESSION['uname'])){
    $title="Dashboard";
    include('init.php');
//Start of the body of the page.

?>
<div class="container home-stats text-center">
<h1>Dashboard</h1>
    <div class="row">
    <?php if($_SESSION['GID']==1){ ?>
      <div class="col-md-3">
            <div class="stat st-members">
            <i class="fa fa-user"></i>
            <div class=info>
                Total Members
            <span><a href="members.php"><?php echo countItems('UserID', 'users') ?></a></span>      
                </div>
           </div>
        </div>
        <?php } ?>
        <?php if($_SESSION['GID']==1){ ?>
        <div class="col-md-3">
            <div class="stat st-pending">
            <i class="fa fa-user-plus"></i>                
            <div class=info>
            Pending Members
            <span><a href="members.php?do=Manage&page=Pending"><?php echo checkItem("Regstatus", "users",0) ?></a></span>
            </div>
            </div>
        </div>
        <?php } ?>
        <div class="col-md-3">
            <div class="stat st-items">
            <i class="fa fa-tag"></i>
                <div class=info>
            Total Items
            <span><a href="items.php"><?php echo countItems('item_ID', 'items') ?></a></span>
            </div>
            </div>
        </div>
        <?php if($_SESSION['GID']==1){ ?>
        <div class="col-md-3">
            <div class="stat st-comments">
            <i class="fa fa-comments"></i>
                <div class=info>
            Total Comments
            <span><a href="comments.php"><?php echo countItems('CID', 'comments') ?></a></span>
            </div>
            </div>
        </div>
        <?php } ?>
    </div>
    <br>
    <br>
    <div class="row">
    <div class="col-md-3">
            <div class="stat st-items">
            <i class="fas fa-box"></i>                
            <div class=info>
            Total Orders
            <span><a href="orders.php"><?php echo countItems('orderID', 'orders') ?></a></span>
            </div>
            </div>
        </div>
    </div>
</div>
<div class="latest">
<div class="container">
    <div class="row">
    <?php if($_SESSION['GID']==1){ ?>
            <div class="col-sm-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                    <i class="fa fa-users"></i> Latest Registered Users
                    <span class="toggle-info float-end"><i class="fa fa-plus fa-lg"></i></span>
                    </div>
                    <div class="panel-body">
                        <ul class="list-unstyled latest-users"> 
                    <?php
                    $lat = getLatest("*", "users", 'UserID', 5);
                    foreach($lat as $user){
                        echo '<li>';
                        echo $user['Username'];
                        ?>
                      <a href="members.php?do=Edit&ID=<?php echo $user['UserID'] ?>">  <span class="btn btn-success float-end"><i class="fa fa-edit"></i>Edit</span></a>
                        <?php
                          if($user['RegStatus']==0){
                            ?>
                           <a href="members.php?do=Activate&ID=<?php echo $user['UserID']; ?>" class="btn btn-info float-end"><i class="fa fa-close"></i> Activate</a>
                            <?php
                        }
                        echo '</li>';
                    }
                    ?>
                    </ul>
                    </div>
                </div>
            </div> 
            <?php } ?>
            <div class="col-sm-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                    <i class="fa fa-tag"></i> Latest Items
                    <span class="toggle-info float-end"><i class="fa fa-plus fa-lg"></i></span>
                    </div>
                    <div class="panel-body">
                        <ul class="list-unstyled latest-users"> 
                    <?php
                    $lateItems = getLatest("*", "items", 'item_ID', 5);
                    foreach($lateItems as $item){
                        echo '<li>';
                        echo $item['Name'];
                        ?>
                      <a href="items.php?do=Edit&ID=<?php echo $item['item_ID'] ?>">  <span class="btn btn-success float-end"><i class="fa fa-edit"></i>Edit</span></a>
                        <?php
                        echo '</li>';
                    }
                    ?>
                    </ul>
                    </div>
                </div>
            </div> 
    </div>
</div>
</div>
<?php
//End of the body of the page.
    include($tpl."footer.php"); 
}
else {
    header("Location: index.php");
}
ob_end_flush();
?>