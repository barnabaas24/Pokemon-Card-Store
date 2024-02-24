<?php 
session_start();

require_once "storage/CardStorage.php";



$storage = new CardStorage();
$character = $storage->findById($_GET['id']);


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKémon | <?= $character['name'] ?></title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/details.css">
</head>

<body>
    <header style="display: flex; gap: 30px">
        <h1><a href="index.php">IKémon</a> > <?= $character["name"] ?> </h1>
        <?php if (isset($_SESSION['user'])) : ?>
            <h1 style="margin-left: auto;"><a href="logout.php">Kijelentkezés</a></h1>
        <?php endif; ?>
    </header>
    <div id="content">
        <div id="details">
            <div class="image clr-<?=$character['type']?>">
                <?php if(strlen($character['image'])==0): ?>
                            <img src="assets/noimg.png" alt="custom-card">
                <?php else: ?>
                    <img src="<?=$character['image']?>" alt="">
                <?php endif ?>
            </div>
            <div class="info">
                <span> Name: <?=$character['name']?></span>
                <div class="description">
                    Description: 
                <?=$character['description']?> 
                </div>
                <span class="card-type"><span class="icon">🏷</span> Type: <?=$character['type']?>  </span>
                <div class="attributes">
                    <div class="card-hp"><span class="icon">❤</span> Health: <?=$character['hp']?> </div>
                    <div class="card-attack"><span class="icon">⚔</span> Attack: <?=$character['attack']?> </div>
                    <div class="card-defense"><span class="icon">🛡</span> Defense: <?=$character['defense']?> </div>
                </div>
            </div>
        </div>
    </div>
    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>
</html>