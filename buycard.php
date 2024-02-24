<?php
require_once "storage/CardStorage.php";
require_once "storage/UserStorage.php";

session_start();

if (!isset($_SESSION['user']) || in_array('admin', $_SESSION['user']['roles'])) {
    header('Location: index.php');
    exit();
}


$characterStorage = new CardStorage();
$character = $characterStorage->findById($_GET['id']);

if($character != null){
    $userStorage = new UserStorage();
    $user = $_SESSION['user'];
    
    
    if($user['money']>=$character['price'] && $user['ownedCards'] < 5 && $character['ownerId']=="admin"){
        $character['ownerId'] = $user['id'];
        $characterStorage->update($character['id'],$character);
        $user['money'] = $user['money']-$character['price'];
        $user['ownedCards']++;
        $userStorage->update($user['id'],$user);
        $_SESSION['user'] = $user;
        header('Location: user-details.php');
        exit();
    }
    else{
        header('Location: index.php');
        exit();
    }
    
}
else{
    header('Location: index.php');
    exit();
}







?>