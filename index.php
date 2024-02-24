<?php 
session_start();
require_once "vendor/Auth.php";
require_once "storage/UserStorage.php";
require_once "storage/CardStorage.php";

$pokemonTypes = array("összes","normal","fire","water","electric","grass","ice","fighting","poison","ground","psychic","bug","rock","ghost","dark","steel");

$storage = new CardStorage();
$characters = $storage->findAll();

if ($_SERVER["REQUEST_METHOD"] === "GET") {

    $type = trim($_GET['character_type'] ?? ""); 
    if($type=="összes"){
        header("Location: index.php ");
        exit();
    }
    if(in_array($type,$pokemonTypes)){
        $characters = $storage->findAll(['type' => $type]);
    }

}





?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKémon | Home</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/cards.css">
</head>

<body>
    <header style="display: flex; gap: 30px">
        <h1><a href="index.php">IKémon</a> > Home</h1>
        <?php if (!isset($_SESSION['user'])) : ?>
            <h1 style="margin-left: auto;"><a href="login.php">Bejelentkezés</a></h1>
        <?php endif; ?>
        <?php if (!isset($_SESSION['user'])) : ?>
            <h1><a href="register.php">Regisztráció</a></h1>
        <?php endif; ?>
        <?php if (isset($_SESSION['user']) && in_array('admin', $_SESSION['user']['roles'])) : ?>
            <h1><a href="new-card.php">Új kártya készítése</a></h1>
        <?php endif; ?>
        <?php if (isset($_SESSION['user'])&& !in_array('admin', $_SESSION['user']['roles'])) : ?>
            <h1><a href="user-details.php">Felhasználó részletek</a></h1>
        <?php endif; ?>
        <?php if (isset($_SESSION['user'])) : ?>
            <h1 style="margin-left: auto;"><a href="logout.php">Kijelentkezés</a></h1>
        <?php endif; ?>
    </header>
    <div id="content">
        <?php if (isset($_SESSION['user'])) : ?>
        <?php  $user = $_SESSION['user'];  ?>
            <?php if (!in_array('admin', $_SESSION['user']['roles'])): ?>
            <h1 id="site-description" >IKémon kártya kereskedési rendszer <br> Üdvözöllek <a class="nameLink" href="user-details.php"><?=$user['username']?></a>! Egyenleged: <?=$user['money']?> 💰  </h1>
            <?php else: ?>
                <h1 id="site-description" > IKémon kártya kereskedési rendszer <br> Üdvözöllek <?=$user['username']?>! </h1>
            <?php endif; ?>
        <?php endif; ?>

        <span id="site-description">A rendszer összes kártyája:</span>
        <form  action="" method="get">
            <label for="character_type">Szűrés típus szerint: </label>
            <select name="character_type" id="">
                <?php foreach ($pokemonTypes as $type): ?>
                <option value="<?=$type?>"><?= $type ?></option>
                <?php endforeach ?>
            </select>
            <button>Szűrés</button>
        </form>
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
                    <?php if(isset($_SESSION['user']) && !in_array('admin', $_SESSION['user']['roles'])): ?>
                        <?php if($character['ownerId']=="admin"): ?>
                            <a href="buycard.php?id=<?=$character['id']?>" style="text-decoration: none; color: inherit;" > 
                            <div class="buy">
                            <span class="card-price">BUY <span class="icon">💰</span> <?=$character['price']?></span>
                            </div>
                            </a>
                        <?php else: ?>
                            <div class="buy">
                                <span class="card-price">ALREADY SOLD <span class="icon">🤝</span></span>
                            </div>
                        <?php endif ?>
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