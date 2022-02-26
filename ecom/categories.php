<?php
$title="Shop";
include("init.php"); ?>
<div class="container">
    <h1 class="text-center">
    <?php echo str_replace('-', ' ', $_GET['page']); ?>
    </h1>
    <div class="row">
    <?php
    foreach(getItems($_GET['ID']) as $item){ ?>
    <div class="col-sm-6 col-md-3">
    <div class="card item-box">
    <span class="price-tag"><?php echo "$".$item['Price'] ?></span>
    <img class="img-fluid img-thumbnail center-block" src="cpanel/uploads/items/<?php 
                        if(!empty($item['image'])){
                            echo $item['image'];
                        }
                        else {
                            echo 'default.png';
                        }
                        ?>" alt=""/>
    <br>
    <div class="caption">
    <h3 class="text-center"><a class="text-center" href="items.php?ID=<?php echo $item['item_ID'] ?>"  style="text-decoration: none;"> <?php echo $item['Name']?> </a></h3>
    <p align="center"><?php echo $item['Description'] ?></p>
    </div>
    </div>
    <br>
    </div>
    <br>
    <?php
    }
    ?>
    </div>
</div>
<?php
include($tpl."footer.php");
?>