<?php
$title="Item";
include('init.php');
if(isset($_SESSION['uid']) || isset($_SESSION['ID'])) { $USRID = $_SESSION['uid'];
}
$itemID = isset($_GET['ID']) && is_numeric($_GET['ID']) ? intval($_GET['ID']) : 0;
$stmt = $db->prepare("SELECT
                                                    items.*, categories.Name AS CNAME
                                        FROM 
                                                    items
                                        INNER JOIN
                                                    categories
                                        ON
                                                    categories.ID=items.Cat_ID
                                        where
                                                     item_ID=?");
$stmt->execute(array($itemID));
$row= $stmt->fetch();
if($stmt->rowCount()==0){
    header("Location: index.php");
}
extract($_POST);
if(isset($cart)){
    if(isset($_SESSION['cart'])){
        //check if the item already exists in the cart.
        $item_array_id = array_column($_SESSION['cart'], "item_ID");
        if (! in_array($itemID, $item_array_id)){
            $count=count($_SESSION['cart']);
            $item_array=array(
                'item_ID' =>$itemID,
                'item_name' => $hidden_name,
                'item_price' => $hidden_price,
                'item_quantity' => $quantity
            );
            $_SESSION['cart'][$count]=$item_array;

        }
        else{
            echo '<script>alert("Item already in the basket")</script>';
        }
    }
    else {
         $item_array=array(
             'item_ID' =>$itemID,
             'item_name' => $hidden_name,
             'item_price' => $hidden_price,
             'item_quantity' => $quantity
         );
         $_SESSION['cart'][0]=$item_array;
         echo '<script>alert("Item has been added to cart successfully")</script>';

    }
}
$rateQuery =$db->query("SELECT Rating, NoOfVoters FROM items WHERE item_ID=$itemID");
$rslt = $rateQuery->fetchAll();
?>
<h1 class="text-center"><?php echo $row['Name']; ?></h1>
<div class="container">
    <div class="row">
        <div class="col-md-3">
                <img class="img-fluid img-thumbnail center-block" src="cpanel/uploads/items/<?php 
                        if(!empty($row['image'])){
                            echo $row['image'];
                        }
                        else {
                            echo 'default.png';
                        }
                        ?>" alt=""/>
        </div>
        <div class="col-md-9">
                <h2>Item Name: <?php echo $row['Name'] ?></h2>
                <hr class="custom-hr">
                <p>Item Description: <br><br><strong><?php echo $row['Description'] ?></strong></p>
                
                <ul class="list-unstyled">
                <li><span>Added Date:</span> <strong><?php echo $row['Add_Date'] ?></strong></li>
                <li><span>Price:</span> <strong>$<?php echo $row['Price'] ?></strong></li>
                <li><span>Category: </span><a href="categories.php?ID=<?php echo $row['Cat_ID'] ?>&page=<?php echo $row['CNAME'] ?>" style="text-decoration: none;"><strong><?php echo $row['CNAME'] ?></strong></a></li>
                <li><span>Rating: </span><?php  if($rslt[0][1]!=0) { echo sprintf("%.2f", $rslt[0][0] / $rslt[0][1]);} else {echo "Not rated yet ";} ?>  out of 5</li>
                <li><span>Available Quantity: </span> <strong><?php if($row['quantity'] > 0) { echo $row['quantity']; } else { echo "Out of Stock!"; }?></strong></li>
                </ul> 
                <?php if((isset($_SESSION['username']) || isset($_SESSION['uname'])) && $row['quantity'] >0) {?>
                <form method=post action="">
                <lable class="form-control">
                    Quantity
                </label>
                <input type="number" name="quantity" class="" value=1 style="margin-top:5px;" min=1 max = "<?php echo $row['quantity'] ?>"/>
                <input type="hidden" name="hidden_name" value ="<?php echo $row['Name'] ?>" />
                <input type="hidden" name="hidden_price" value ="<?php echo $row['Price'] ?>" />
                <button type=submit class="btn btn-success" style="margin-top:5px;" name="cart" Value="Add To Cart">Add to Cart</button>
            </form>
            <?php } ?>

    </div>
    </div>

<?php
if(isset($_SESSION['uname']) || isset($_SESSION['username'])){
    
$checkPurchase=$db->query("SELECT * FROM orderitems WHERE ItemID=$itemID AND userID=$USRID");
$checkPurchase->execute();
extract($_POST);
if(isset($AddComment)){
    $comment=$db->prepare("INSERT INTO comments(CID, comment, date, item_id, user_id) VALUES(null, ?,NOW(), ?, ?) ");
    $comment->execute(array(
        $cmnt,
        $itemID,
        $USRID
    ));
    $msg= '<div class="container"><div class="alert alert-success">'. 'Comment Added! <a href=index.php>Return to home Page</a></div></div>';
    echo $msg;    
}
if(isset($rate)){
    $rateQuery =$db->query("SELECT Rating, NoOfVoters FROM items WHERE item_ID=$itemID");
    $rslt = $rateQuery->fetchAll();
    $newRating =$rslt[0]['Rating'] + $myrate;
    $newVoters=$rslt[0]['NoOfVoters'] + 1;
    $updateRating = $db->prepare("UPDATE items SET Rating=$newRating, NoOfVoters=$newVoters WHERE item_ID=$itemID");
    $updateRating->execute();
    echo '<script>alert("Thank you for your time :) ")</script>';

}
if ($checkPurchase->rowCount() > 0){
    if(isset($_SESSION['username']) || isset($_SESSION['uname'])) { ?>
        <hr class="custom-hr">
        <div class="row">
        <div class="col-md-offset-3">
        <div class="add-comment">
        <h3 class="text-center">Add Your comment</h3>
        <form method=post action="">
                 <textarea placeholder="Add your comment" name="cmnt" required></textarea>
                 <input type=submit name=AddComment value="Share Opinion">
        </form>
        </div>
        </div>
        </div>
        <hr class="custom-hr">
        <div class="row">
        <div class="col-md-offset-3">
        <div class="add-comment">
        <h3 class="text-center">Rate the Product</h3>
        <form method=post action="">
        <label for="customRange1" class="form-label">Example range</label>
            <input type="range" class="form-range" id="customRange1" min=1 max=5 name=myrate />
                 <input type=submit name=rate value="Rate">
        </form>
        </div>
        </div>
        </div>
  <?php      
     }
}

}
?>
    <div class="col-md-offset-3">
    <hr class="custom-hr">
    <div class="row">
    <div class="col-md-3"><strong>User</strong></div>
    <div class="col-md-9"><strong>Comment</strong></div>
    </div>
    <hr class="custom-hr">
<br>
<br>
        <?php
        $stmt = $db->prepare("SELECT comments.*,
        users.Username AS Member 
        FROM comments 
        INNER JOIN users ON users.UserID = comments.user_id 
        WHERE item_id=?
        ORDER BY CID DESC");

        $stmt->execute(array($itemID));
        $comments=$stmt->fetchAll();
        foreach($comments as $comment){
            echo '<div class="comment-box">';
            echo '<div class="row">'; ?>
            <div class="col-sm-2 text-center">
            <img class="img-responsive img-thumbnail img-circle" src="comment.png" alt =""/>
            <?php echo '<strong>'.$comment['Member'].'</strong>'; ?>
            </div>
            <?php
            
            echo '<div class="col-md-10">';
            echo '<p class="lead">'.$comment['comment'].'</p>';
            echo '</div>';
            echo '</div>';
            echo '</div>';?>
            <hr class="custom-hr">
<?php
        }
?>
        </div>
    </div>
</div>
<?php
include($tpl."footer.php");
?>
