<?php
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
?>