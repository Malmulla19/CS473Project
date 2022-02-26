<?php
session_start();
if(isset($_SESSION['uname'])){
    $title="Categories";
    include('init.php');
    $do = isset($_GET['do']) ? $_GET['do'] : "Manage";
    if($do == "Manage"){
        $sort = 'ASC';
        $sort_array=array('ASC', 'DESC');
        if(isset($_GET['sort'])&& in_array($_GET['sort'], $sort_array)){
            $sort=$_GET['sort'];
        }
        $stmt = $db->prepare("SELECT * FROM categories ORDER BY Ordering $sort ");
        $stmt->execute();
        $cat = $stmt->fetchAll();
        ?>
        <h1 class="text-center">Manage Categories</h1>
        <div class="container categories">
        <div class="panel panel-default">
        <div class="panel-heading">
        Manage Categories
        <div class="option float-end">
            Ordering:
            <a class="<?php if($sort =='ASC') {echo 'active';} ?>" href="?sort=ASC">Asc</a> |
            <a class="<?php if($sort =='DESC') {echo 'active';} ?>" href=?sort=DESC>Desc</a>
            View:
            <span class="active" data-view="full">Full</span> |
            <span data-view="classic">Classic</span>
        </div>
        </div>
        <div class="panel-body">
        <?php
        foreach($cat as $category){
            echo "<div class='cat'>";
            echo "<div class='hidden-buttons'>";
            echo "<a href='categories.php?do=Edit&ID=".$category['ID']."'class='btn btn-sx btn-primary'><i class='fa fa-edit'></i>Edit</a>";
            echo "<a href='categories.php?do=delete&ID=".$category['ID']. "'class='confirm btn btn-sx btn-danger'><i class='fa fa-closecategories'></i>Delete</a>";
            echo "</div>";
                 echo "<h3>".$category['Name']."</h3>";
                 echo '<div class="full-view">';
                  echo "<p>"; if($category['Description']==""){ echo "No description given";}
                  else {echo $category['Description'];} echo"</p>";
                  if($category['Visibility']==1) {echo '<span class="visibility cat-span"><i class="fa fa-eye"></i> Hidden </span>';}
                  if($category['Allow_Comment']==1){echo '<span class="commenting cat-span"><i class="fa fa-close"></i> Comments Disabled</span>';}
                  echo "</div>";
            echo "</div>";
            echo "<hr>";
        }
        ?>
        </div>
        </div>
        <a href="categories.php?do=Add"  class="add-category btn btn-primary">+ New Category</a>
    </div>
    <?php
    }
    elseif($do=='Add'){ ?>
	 <h1 class="text-center">Add New Categorie</h1>
    <div class=container>
        <form class="form-horizontal" action="?do=Insert" method=post action="">
            <div class="form-group-lg ">
                <lable class= "col-sm-2 control-label">Name</label>
                    <div class="col-sm-10 col-md-4">
                        <input type=text name=name class="form-control"  autocomplete=off required="required" placeholder="Name of the categorie" /> 
        </div>
        </div>
        <div class="form-group">
                <lable class= "col-sm-2 control-label">Description</label>
                    <div class="col-sm-10 col-md-4">
                    <input type=text name=Description class="form-control" autocomplete= off  placeholder="Description of the categorie"/>
        </div>
        </div>
        <div class="form-group">
                <lable class= "col-sm-2 control-label">Ordering</label>
                    <div class="col-sm-10 col-md-4">
                        <input type=text name=ordering class="form-control" placeholder="Number to arrange the categories">
        </div>
        </div>
        <div class="form-group">
                <lable class= "col-sm-2 control-label">Visibile</label>
                    <div class="col-sm-10 col-md-4">
                        <div>
							<input id=y type=radio name=visibility value=0 checked>
							<label for="y">Yes</label>
						</div>
						<div>
							<input id=n type=radio name=visibility value=1>
							<label for="n">No</label>
						</div>
        </div>
        </div>
		<div class="form-group">
                <lable class= "col-sm-2 control-label">Allow Comments</label>
                    <div class="col-sm-10 col-md-4">
                        <div>
							<input id=cy type=radio name=comments value=0 checked>
							<label for="cy">Yes</label>
						</div>
						<div>
							<input id=cn type=radio name=comments value=1>
							<label for="cn">No</label>
						</div>
        </div>
        </div>
        <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type=submit name=Save class="btn btn-success" value="Add"/>
        </div>
        </div>
        </form>
    </div>
<?php
    }
    elseif($do == "Insert"){

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            echo "<h1 class='text-center'>Insert Category</h1>";
            echo "<div class='container'>";

            $name = $_POST['name'];
            $Desc = $_POST['Description'];
            $ordering = $_POST['ordering'];
            $vis = $_POST['visibility'];
            $comments = $_POST['comments'];

			//Check if the category already exists.
			$available = checkItem("Name", "categories", $name);
			//Insert Category info in DB.
			if ($available >0){
				$msg= '<div class= "alert alert-danger">'."Category already exists!". '</div>';
				redirection($msg, 'back');
			}
			else {
			$stmt = $db->prepare("INSERT INTO categories(name, Description, Ordering, Visibility, 
			Allow_Comment) VALUES(:iname, :idesc, :iorder, :ivis, :icomm) ");
			$stmt->execute(array(
				'iname' => $name,
				'idesc' => $Desc,
				'iorder' => $ordering,
				'ivis' => $vis,
				'icomm' => $comments
			));
			$msg= '<div class="alert alert-success">'. '<strong align=center>Record Added!</strong> </div>';
			redirection($msg, 'back');
			}
                
        }
        else {
            $msg='<div class= "alert alert-danger">Sorry, you cannot browse this page directly</div>';
            redirection($msg, "previousPage");
                }
        echo "</div>";
    }
    elseif($do == "Edit"){
    $catID = isset($_GET['ID']) && is_numeric($_GET['ID']) ? intval($_GET['ID']) : 0;
    $stmt = $db->prepare("SELECT * FROM categories where ID=?");
    $stmt->execute(array($catID));
    $row= $stmt->fetch();
    $rs = $stmt->rowCount();
   if($rs >0){ 
       ?>
       	 <h1 class="text-center">Edit Categorie</h1>
    <div class=container>
        <form class="form-horizontal" action="?do=Update" method=post action="">
        <input type=hidden name=catID value="<?php echo $catID; ?>">

            <div class="form-group-lg ">
                <lable class= "col-sm-2 control-label">Name</label>
                    <div class="col-sm-10 col-md-4">
                        <input type=text name=name class="form-control" required="required" placeholder="Name of the categorie"  value= "<?php echo $row['Name']; ?>"/> 
        </div>
        </div>
        <div class="form-group">
                <lable class= "col-sm-2 control-label">Description</label>
                    <div class="col-sm-10 col-md-4">
                    <input type=text name=Description class="form-control"  placeholder="Description of the categorie" value= "<?php echo $row['Description']; ?>"/>
        </div>
        </div>
        <div class="form-group">
                <lable class= "col-sm-2 control-label">Ordering</label>
                    <div class="col-sm-10 col-md-4">
                        <input type=text name=ordering class="form-control" placeholder="Number to arrange the categories" value= "<?php echo $row['Ordering']; ?>"/>
        </div>
        </div>
        <div class="form-group">
                <lable class= "col-sm-2 control-label">Visibile</label>
                    <div class="col-sm-10 col-md-4">
                        <div>
							<input id=y type=radio name=visibility value="0" <?php if($row['Visibility']==0){echo "checked";} ?>>
							<label for="y">Yes</label>
						</div>
						<div>
							<input id=n type=radio name=visibility value="1" <?php if($row['Visibility']==1){echo "checked";} ?>>
							<label for="n">No</label>
						</div>
        </div>
        </div>
		<div class="form-group">
                <lable class= "col-sm-2 control-label">Allow Comments</label>
                    <div class="col-sm-10 col-md-4">
                        <div>
							<input id=cy type=radio name="comments" value="0" <?php if($row['Allow_Comment']==0){echo "checked";} ?> >
							<label for="cy">Yes</label>
						</div>
						<div>
							<input id=cn type=radio name="comments"  value="1" <?php if($row['Allow_Comment']==1){echo "checked";} ?>>
							<label for="cn">No</label>
						</div>
        </div>  
        </div>
        <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type=submit name=Save class="btn btn-success" value="Save"/>
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
        echo "<h1 class='text-center'>Update Category</h1>";
        echo "<div class='container'>";
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['catID'];
            $name = $_POST['name'];
            $descr = $_POST['Description'];
            $order = $_POST['ordering'];
            $visibility=$_POST['visibility'];
            $comments=$_POST['comments'];
            $stmt=$db->prepare("UPDATE categories SET Name= ?, Description=?, Ordering=?, Visibility=?, Allow_Comment=? WHERE ID=?");
            $stmt->execute(array($name, $descr, $order, $visibility, $comments, $id));
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
        echo "<h1 class='text-center'>Delete Category</h1>";
        echo "<div class='container'>";
        $catID = isset($_GET['ID']) && is_numeric($_GET['ID']) ? intval($_GET['ID']) : 0;
       $rs = checkItem('ID', 'categories', $catID);
       if($rs >0){ 
           $stmt=$db->prepare(
               "DELETE FROM categories WHERE ID=?"
           );
           $stmt->execute(array($catID));
           $row= $stmt->fetch();
           $msg= '<div class="alert alert-success">'. '<strong align=center>Record Deleted!</strong> </div>';
           redirection($msg,'back');
        }
       else {
           echo "<div class=container>";
           $msg= '<div class="alert alert-danger">No such ID</div>';
           echo "</div>";
           redirection($msg,'back');
       }
       echo '</div>';
    }
    require($tpl."footer.php"); 
}
else {
    header("Location: index.php");
}
?>