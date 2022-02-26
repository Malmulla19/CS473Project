<?php
ob_start();
//Get Items from DB.
//returns all categories in DESC order!
function getCategories(){
    global $db;
    $stmt = $db->prepare("SELECT * FROM categories ORDER BY ID ASC");
    $stmt->execute();
    $rs=$stmt->fetchAll();
    return $rs;
}
//Get Items Functions:
/**
 * Used in Frontend to fetch items from db.
 * Category ID is given to this function in order to fetch all items correctly.
 */
function getItems($ID){
    global $db;
    $stmt = $db->prepare("SELECT * FROM items WHERE Cat_ID=$ID ORDER BY item_ID DESC");
    $stmt->execute();
    $rs=$stmt->fetchAll();
    return $rs;
}
/**
 * This function is used to check the status of the user.
 */
function getStatus($user){
    global $db;
    $stmt = $db->prepare("SELECT Username, RegStatus FROM users where Username=? AND RegStatus=0");
    $stmt->execute(array($user));
    $rs = $stmt->rowCount();
    return $rs;
}
/**
 * GET_ALL_FROM FUNCTION
 * Returns all item.
 * To be used in index.php
 */
function getAllFrom($table){
    global $db;
    $allitems = $db->prepare("SELECT * FROM $table");
    $allitems->execute();
    $items=$allitems->fetchAll();
    return $items;
}

/**
 * All functions below may and may not be used in frontend.
 * This file has been copied from Backend.
 * All above functions has been used in frontend!
 */
//Get Page Title
function getTitle(){
    global $title;
    if (isset($title)){
        echo $title;
    }
    else {
        echo "Default";
    }
}
//Redirection Function
function redirection($Msg, $url=null, $seconds = 3){
    if ($url === null){
        $url="index.php";
    }
    else {
        if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !=''){
        $url = $_SERVER['HTTP_REFERER'];
    }
    else {
        $url='index.php';
    }
    }
    echo "<div class=container>";
    echo $Msg;
    echo "<div class='aler alert-info'>About to be redirected in $seconds seconds to $url</div>";
    echo "</div>";
    header("refresh: $seconds;url=$url");
    exit();
}
//Check item in DB
function checkItem($Select, $From, $Value){
    global $db;
    $Query = $db->prepare("SELECT $Select FROM $From WHERE $Select=?");
    $Query->execute(array($Value));
    return $Query->rowCount();
}

//Count No. of Items in DB (Dynamic)
/**
 * Counting No. of items for anything in the DB
 */
function countItems($item, $table){
    global $db;
    $stmt = $db->prepare("SELECT COUNT($item) FROM $table");
    $stmt->execute();
    return $stmt->fetchColumn();
}
//GetLatest Function
/**
 * This Function is to fetch latest itmes from DB.
 */
function getLatest($select, $table, $order, $limit=5){
    global $db;
    $stmt = $db->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
    $stmt->execute();
    $rs=$stmt->fetchAll();
    return $rs;
}
ob_end_flush();
?>