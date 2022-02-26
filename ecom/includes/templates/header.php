<?php  
session_start();
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php getTitle() ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">    <link rel="stylesheet" href="<?php echo $css; ?>fontawesome.css"/>
    <link rel="stylesheet" href="<?php echo $css; ?>front.css"/>
    <link rel="stylesheet" href="<?php echo $css; ?>jquery-ui.css"/>
    <script src="https://code.jquery.com/jquery-3.2.1.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/cesiumjs/1.78/Build/Cesium/Cesium.js"></script>
    
    <style>
    body{
        font-family: Arail, sans-serif;
    }
    /* Formatting search box */
    .search-box{
        width: 300px;
        position: relative;
        display: inline-block;
        font-size: 14px;
    }
    .search-box input[type="text"]{
        height: 32px;
        padding: 5px 10px;
        border: 1px solid #CCCCCC;
        font-size: 14px;
    }
    .result{
        position: absolute;        
        z-index: 999;
        top: 100%;
        left: 0;
    }
    .search-box input[type="text"], .result{
        width: 100%;
        box-sizing: border-box;
    }
    /* Formatting result items */
    .result p{
        margin: 0;
        padding: 7px 10px;
        border: 1px solid #CCCCCC;
        border-top: none;
        cursor: pointer;
    }
    .result p:hover{
        background: #f2f2f2;
    }
</style>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
$(document).ready(function(){
    $('.search-box input[type="text"]').on("keyup input", function(){
        /* Get input value on change */
        var inputVal = $(this).val();
        var resultDropdown = $(this).siblings(".result");
        if(inputVal.length){
            $.get("backend-search.php", {term: inputVal}).done(function(data){
                // Display the returned data in browser
                resultDropdown.html(data);
            });
        } else{
            resultDropdown.empty();
        }
    });
    
    // Set search input value on click of result item
    $(document).on("click", ".result p", function(){
        $(this).parents(".search-box").find('input[type="text"]').val($(this).text());
        $(this).parent(".result").empty();
    });
});
</script>
</head>
<body>
<div class="upper-bar">
		<div class="container">
			<?php 
      
				if (isset($_SESSION['username']) || isset($_SESSION['uname'])) { 
          $uidPro=$_SESSION['uid'];
          $proPic=$db->prepare("SELECT propic FROM users WHERE UserID =$uidPro");
          $proPic->execute();
          $image=$proPic->fetch();
          ?>
    <ul class="navbar-nav me-auto mb-2 mb-lg-0">

				<img class="my-image img-thumbnail img-circle" src="cpanel/uploads/avatars/<?php 
                        if(!empty($image['propic'])){
                            echo $image['propic'];
                        }
                        else {
                            echo 'default.png';
                        }
                        ?>
        " alt="" />
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            My Account
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
          <li><a  class="dropdown-item" href="index.php">Home Page</a></li>
          <?php if(!isset($_SESSION['uname'])) {?>
            <li><a class="dropdown-item" href="profile.php?do=Edit&ID=5">Edit Profile</a></li>
            <?php } ?>
            <?php if(isset($_SESSION['uname'])){
              ?>
              <li><a class="dropdown-item" href="cpanel/dashboard.php">Dashboard</a></li>

              <?php
            } ?>
            <li><a class="dropdown-item" href="cart.php">Cart</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
          </ul>
        </li>
</ul>
				<?php

				} else {
			?>
			<a href="login.php">
				<span class="pull-right">Login/Signup</span>
			</a>
<?php 
} 
?>
</div>
</div>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container">
    <a class="navbar-brand" href="index.php"><strong>Homepage</strong></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#app-nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse " id="app-nav">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0 ">
      </ul>
      <div class="search-box">
      <input type="text" autocomplete="off" placeholder="Search Product..." />
      <div class="result">
      </div>
      </div>
  </div>
</nav>
