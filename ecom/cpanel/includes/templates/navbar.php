<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#app-nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="app-nav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="dashboard.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="orders.php">Orders</a>
        </li>
        <?php if($_SESSION['GID']==1){ ?>
        <li class="nav-item">
          <a class="nav-link" href="categories.php">Categories</a>
        </li>
        <?php } ?>

        <li class="nav-item">
          <a class="nav-link" href="items.php">Items</a>
        </li>
        <?php if($_SESSION['GID']==1){ ?>
        <li class="nav-item">
          <a class="nav-link" href="members.php">Members</a>
        </li> 
        <?php } ?>
        <li>
        <a  class="nav-link" href="../index.php">Store Home Page</a>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <?php echo    $_SESSION['uname'];
 ?>
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="members.php?do=Edit&ID=<?php echo  $_SESSION['uid'] ?>">Edit Profile</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>