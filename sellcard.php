<?php
require_once "storage/CardStorage.php";
require_once "storage/UserStorage.php";

session_start();


if (!isset($_SESSION['user']) || in_array('admin', $_SESSION['user']['roles'])) {
    header('Location: index.php');
    exit();
}


$storage = new CardStorage();
$character = $storage->findById($_GET['id']);

if($character!=null && $character['ownerId']==$_SESSION['user']['id']){

    $userStorage = new UserStorage();
    $user = $_SESSION['user'];
    
    $character['ownerId'] = "admin";
    $storage->update($character['id'],$character);
    $user['money'] = $user['money']+($character['price']-($character['price']*0.10));
    $user['ownedCards']--;
    $userStorage->update($user['id'],$user);
    $_SESSION['user'] = $user;
    header('Location: user-details.php');
    exit();
    
    
}else{
    header('Location: index.php');
    exit();
}






?>