<?php
session_start();
if(isset($_SESSION['uname'])){
    $title="Orders";
    include('init.php');
extract($_POST);
if(isset($update)) {
    $updateStatement= $db->prepare("UPDATE orders SET status='$UpdateStatus' WHERE orderID=$OID");
    $updateStatement->execute();
    echo '<script>alert("Record Updated!")</script>';
}
    $stmt =$db->prepare("SELECT * FROM orders");
    $stmt->execute();
    $data = $stmt->fetchAll();
    ?>
        <h1 class="text-center">Manage Orders</h1>
    <div class=container>
        <div class="table-responsive">
            <table class="main-table text-center table  ">
                <tr>
                    <td>OID</td>
                    <td>Name#ID</td>
                    <td>Address</td>
                    <td>Delivery Method</td>
                    <td>Items</td>
                    <td>Total</td>
                    <td>Status</td>
                </tr>
                <?php
                foreach($data as $item){
                    $UID38=$item['userID'];
                    $UID39=$item['orderID'];
                    $q = $db->query("SELECT FullName FROM users WHERE UserID=$UID38");
                    $rowUser=$q->fetch();
                    $q2 = $db->query("SELECT Pname FROM orderitems WHERE OID=$UID39");
                    $rowItems=$q2->fetchAll();
                    ?>
                        <tr>
                    <td><?php echo $item['orderID'] ?></td>
                    <td><?php echo$rowUser[0] ?> #<?php echo $item['userID'] ?></td>
                    <td><?php echo $item['Address'] ?></td>
                    <td><?php echo $item['shipMethod'] ?></td>
                    <td><?php foreach($rowItems as $itemx){ echo "- ".$itemx['Pname']."<br>"; } ?></td>
                    <td><?php echo "$".$item['total'] ?></td>
                    <td>
                    <div class="input-group mb-3">
                    <form method=post action="">
                        <input type="hidden" name=OID value="<?php echo $item['orderID'] ?>" />
                        <select class="custom-select" id="inputGroupSelect02" name="UpdateStatus">
                            <option <?php if($item['status']=="Acknowledged"){ echo "selected"; } ?>>Acknowledged</option>
                            <option <?php if($item['status']=="In Process"){ echo "selected"; } ?> >In Process</option>
                            <option <?php if($item['status']=="In Transit"){ echo "selected"; } ?> >In Transit</option>
                            <option <?php if($item['status']=="Delivered"){ echo "selected"; } ?>>Delivered</option>
                        </select>
                        <input type=submit class="btn btn-outline-secondary" name="update" Value="Update" />
                    </form>
                        </div>
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
    include($tpl."footer.php");
?>