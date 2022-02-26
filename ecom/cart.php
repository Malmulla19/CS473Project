<?php
$title="Cart";
include('init.php');
date_default_timezone_set('Asia/Bahrain');
$time= date('Y-m-d H:i:s');
$basket=null;
if(isset($_SESSION['username']) || isset($_SESSION['uname'])){
    
if(isset($_SESSION['cart'])) {
    $basket = $_SESSION['cart'];
}
if($basket !=null){ 
?>
<div class="container">
    <h1 class="text-center">Basket Details</h1>
    <div class="row">
        <table class="table">
            <tr>
                  <th class="table-success">Item Name</th>
                  <th class="table-success">Quantity</th>
                  <th class="table-success">Price</th>
                  <th class="table-success">Total</th>
                  <th class="table-success">Action</th>
            </tr>
            <?php
            $total=0;
            $checkQuan = $db->prepare("SELECT quantity FROM items WHERE item_ID=?");
            foreach($basket as $keys => $values){
                $checkQuan->execute(array($values['item_ID']));
                $avaQ=$checkQuan->fetch();
                
                ?>
                <tr>
                    <td>
                        <?php echo $values['item_name']; ?>
                    </td>
                    <td>
                    <form method=post action=""> 
                        <input type=number min=1 name=NEWQUANT value="<?php echo $values['item_quantity']; ?>" min=1 max="<?php echo $avaQ[0] ?>"/>
                        <input type=hidden name=VARID value="<?php echo $values['item_ID'];?>" />
                        <input type=submit name=UPDATEQ value="Update" class="btn btn-primary" />
                    </form>
                </td>
                    <td>
                        <?php echo $values['item_price']; ?>
                    </td>
                    <td>
                        <?php 
                        $net = $values['item_price']*$values['item_quantity'];
                        echo $net;
                          ?>
                    </td>
                    <td>
                         <a href="cart.php?do=delete&id=<?php echo $values['item_ID'];?>" style="text-decoration: none;"><span class="text-danger">Remove</span></a>
                    </td>
            </tr>
                <?php
                $total+=$net;
            }
            ?>
            <tr>
                <td colspan="3" align="right">Total</td>
                <td align="right">$ <?php echo number_format($total,2); ?></td>
            </tr>
        </table>
    </div>

    <div class="row">
        <h3 class="text-center">Select Dlevery Option</h3>
<form method="post" action="">
  <select id="delivery" name="del">
    <option value="40#UltraFast"><span class="text-center"></span><?php echo date( "D-d/M", strtotime( "+1 days" ) ). "   Shipping charges: $40"; ?></option>
    <option value="30#SuperFast"><span class="text-center"></span><?php echo date( "D-d/M", strtotime( "+2 days" ) ). "   Shipping charges: $30"; ?></option>
    <option value="20#Fast"><span class="text-center"></span><?php echo date( "D-d/M", strtotime( "+3 days" ) ). "   Shipping charges: $20"; ?></option>
    <option value="10#Normal"><span class="text-center"></span><?php echo date( "D-d/M", strtotime( "+4 days" ) ). "   Shipping charges: $10"; ?></option>
  </select>
  <input type="submit" value="Submit" name="delivery" class="btn btn-primary">
</form>
    </div>
</div>


<?php

extract($_POST);
if(isset($UPDATEQ)){
    foreach($_SESSION['cart'] as $keys => $values){
        if($values['item_ID']== $VARID){
            $_SESSION['cart'][$keys]['item_quantity']=$NEWQUANT;
            echo '<script>alert("Item has been Updated!")</script>';
            echo '<script>window.location="cart.php"</script>';
        }
    }
}
if (isset($delivery)) {
$method= explode("#",$del);
$totalWD=(int)$method[0]+$total;
?>
<div class="container">
<div class="row">
    <h2 class="text-center">Total With Delivery</h2>
    <h4 class="text-center">$<?php echo $totalWD; ?></h4>
    <h3 class="text-center">Pay with ETH</h3>
    <p class="text-center">Once we recieve your payment, will will start processing your order.</p>
    <h3 class="text-center">Wallet ID: 0x16fEB70E042730475952BA361B5E82B1F1F76a99</h3>
    <div align="center">
    <img src="cpanel/uploads/addETH.PNG" class="img-thumbnail"/>
   
    <p class="text-center">After transferring the money, please hit <strong>Place Order</strong> to complete the purchase.</p>
    <div class="row form-control">
    <form method=post action="">
    <input type=hidden name=TotalWithDel value="<?php echo $totalWD; ?>"/>
    <input type=hidden name=shipMethod value="<?php echo $method[1]; ?>"/>
    <div>
    <label for="inputAddress">Address</label>
    <input type="text" class="form-control" id="inputAddress" name="Address" placeholder="1234 Main St" required>
    </div>
    <input type=submit class="btn btn-primary form-control" role="button" name=placeOrder />
    </form>
    </div>
    </div>
</div>
</div>
<?php
}
if (isset($placeOrder)){
    $USER=$_SESSION['uid'];
    $order = $db->prepare("INSERT INTO orders (orderID, userID, Address, shipMethod, total, orderDate)
    VALUES(?,?,?,?,?,?)
    ");
    $order->execute(array(
        null,
        $USER,
        $Address,
        $shipMethod,
        $TotalWithDel,
        $time
    ));
    $OID = $db->query("SELECT  orderID FROM orders WHERE userID=$USER ORDER BY orderID DESC");
    $row=$OID->fetch();
    $orderItems= $db->prepare("INSERT INTO orderitems (ID, ItemID, userID, OID, Pname, Quantity, unitPrice) VALUES(?,?,?,?,?,?,?)");
    $updateItems= $db->prepare("UPDATE items SET quantity=? WHERE item_ID =?");
    $actualQuantity = $db->prepare("SELECT quantity FROM items WHERE item_ID=?");
    foreach($basket as $keys => $values){
        $orderItems->execute(array(
            null,
            $values['item_ID'],
            $USER,
            $row[0],
            $values['item_name'],
            $values['item_quantity'],
            $values['item_price']
        ));
        $actualQuantity->execute(array($values['item_ID']));
        $avaQuan=$actualQuantity->fetch();
        $values['item_quantity']=$avaQuan[0]-$values['item_quantity'];
        $updateItems->execute(array(
            $values['item_quantity'],
            $values['item_ID']
        ));
     }
     $_SESSION['cart']= null;
    $msg= '<div class="container"><div class="alert alert-success">'. 'Your order with ID <strong align=center>#'.$row[0].' </strong>has been placed!<br> <a href=index.php>Return to home Page</a></div></div>';
    echo $msg;
}

elseif(isset($_GET['do'])){
    if($_GET['do']=="delete"){
        foreach($_SESSION['cart'] as $keys => $values){
            if($values['item_ID']== $_GET['id']){
                unset($_SESSION['cart'][$keys]);
                echo '<script>alert("Item has been removed!")</script>';
                echo '<script>window.location="cart.php"</script>';

            }
        }
    }
}

}

else {
    echo '
    <h1 class="text-center">Your shopping cart is empty!</h1>
    ';
}
}
else{
    header("Location: index.php");
}
include($tpl.'footer.php');
?>