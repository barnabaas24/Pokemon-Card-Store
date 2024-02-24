<?php 

session_start();

require_once "storage/CardStorage.php";
require_once "storage/UserStorage.php";

if (!isset($_SESSION['user']) || in_array('admin', $_SESSION['user']['roles'])) {
    header('Location: index.php');
    exit();
}

$user = $_SESSION['user'];

$cardstorage = new CardStorage();
$characters = $cardstorage->findMany(function ($card) use ($user) {
    return $card['ownerId'] == $user['id'];
});


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKémon | Saját kártyáim </title>
 </title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/cards.css">
</head>

<body>
     <header style="display: flex; gap: 30px">
        <h1><a href="index.php">IKémon</a> > Felhasználó részletek </h1>
        <?php if (isset($_SESSION['user'])) : ?>
            <h1 style="margin-left: auto;"><a href="logout.php">Kijelentkezés</a></h1>
        <?php endif; ?>
    </header>
    <div id="content">
        <?php if (isset($_SESSION['user'])) : ?>
        <?php  $user = $_SESSION['user'];  ?>
            <h1 id="site-description" ><?=$user['username']?> | <?=$user['money']?> 💰 | <?=$user['email']?></h1>
        <?php endif; ?>
        <div id="card-list">
            <?php foreach($characters as $character): ?>
                <div class="pokemon-card">
                    <div class="image clr-<?=$character['type']?>">
                        <?php if(strlen($character['image'])==0): ?>
                            <img src="assets/noimg.png" alt="custom-card">
                        <?php else: ?>
                            <img src="<?=$character['image']?>" alt="">
                        <?php endif ?>
                    </div>
                    <div class="details">
                        <h2><a href="details.php?id=<?=$character['id']?>"><?= $character["name"] ?></a></h2>
                        <span class="card-type"><span class="icon">🏷</span> <?=$character['type']?></span>
                        <span class="attributes">
                            <span class="card-hp"><span class="icon">❤</span> <?=$character['hp']?></span>
                            <span class="card-attack"><span class="icon">⚔</span> <?=$character['attack']?></span>
                            <span class="card-defense"><span class="icon">🛡</span><?=$character['defense']?></span>
                        </span>
                    </div>
                    <?php if(isset($_SESSION['user'])): ?>
                        <a href="sellcard.php?id=<?=$character['id']?>" style="text-decoration: none; color: inherit;" > 
                        <div class="buy">
                           <span class="card-price">SELL <span class="icon">💰</span> <?=round($character['price']-($character['price']*0.10)) ?></span>
                        </div>
                        </a>
                    <?php endif ?>
                </div>
            <?php endforeach ?>
        </div>
    </div>
    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>
</html>