<?php
$title="Home Page";
include("init.php");
?>
<div class="container">
<h1 class="text-center">Featured Items</h1>
    <div class="row">
    <?php
    $stmt = $db->prepare("SELECT * FROM items ORDER BY item_ID DESC LIMIT 4 ");
    $stmt->execute();
    $items=$stmt->fetchAll();
    foreach($items as $item){ ?>
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
    <hr>
    </div>
    <h1 class="text-center">Categories</h1>
      <?php
      /**
       * One Cat page will be created, CAT-ID will be used to identify the items and display them properly.
       */
      ?>
    </div>
    <div class="container home-stats text-center">
      <div class="row">
      <?php
      foreach(getCategories() as $cat){ ?>
<div class="card">
<div class="card-body">
            <h5 class="card-title">
                <h3 class = "text-center"> <?php echo $cat["Name"] ?></h3>
            </h5>
            <p class="card-text"><?php echo $cat['Description'] ?></p>
            <?php
                echo '
                <a class="btn btn-primary" href="categories.php?ID='.$cat['ID'].'&page='.str_replace(' ','-',$cat['Name']).'">'."Shop Now!".'</a>

                ';
                ?>
            </div>
           </div>
<hr>
<?php
      }
?>
      </div>
    </div>
<?php
include($tpl."footer.php");
?>
