<?php 


session_start();
require_once "storage/CardStorage.php";
$storage = new CardStorage();

if (!isset($_SESSION['user']) || !in_array('admin', $_SESSION['user']['roles'])) {
    header('Location: index.php');
    exit();
}


if($_SERVER["REQUEST_METHOD"] === "POST"){
    $errors = [];

    $name = trim($_POST["character_name"] ?? "");
    if (strlen($name) == 0) {
        $errors["name"] = "Név megadása kötelező!";
    } else if (strlen($name) > 32) {
        $errors["name"] = "A karakter nevének hossza maximum 32 karakter lehet.";
    }

    $type = trim($_POST['character_type'] ?? '');
    if (strlen($type) == 0) {
        $errors["type"] = "Karakter típus megadása kötelező!";
    } else if (strlen($type) > 32) {
        $errors["type"] = "A karakter típus hossza maximum 32 karakter lehet.";
    }

    $health = trim($_POST['health'] ?? '');
    if(strlen($health)==0){
        $errors['health']="Életerő megadása kötelező!";
    }else{
        if (!is_numeric($health)) {
            $errors['health'] = "Életerőnek csak szám adható meg!";
        }
        else{
            if($health<20){
                $errors['health'] = "Az életerő nagyobb kell legyen, mint 20!";
            }
            if($health>100){
                $errors['health'] = "Az életerő nem lehet több mint 100!";
            }
        }

    }

    $damage = trim($_POST['damage'] ?? '');
    if(strlen($damage)==0){
        $errors['damage']="Sebzés megadása kötelező!";
    }else{
        if (!is_numeric($damage)) {
            $errors['damage'] = "Sebzésnek csak szám adható meg!";
        }
        else{
            if($damage<20){
                $errors['damage'] = "A sebzés nagyobb kell legyen, mint 20!";
            }
            if($damage>100){
                $errors['damage'] = "A sebzés nem lehet több mint 100!";
            }
        }
    }

    $armor = trim($_POST['armor'] ?? '');
    if(strlen($armor)==0){
        $errors['armor']="Páncél megadása kötelező!";
    }else{
        if (!is_numeric($armor)) {
            $errors['armor'] = "Páncélnak csak szám adható meg!";
        }
        else{
            if($armor<20){
                $errors['armor'] = "A páncél nagyobb kell legyen, mint 20!";
            }
            if($armor>100){
                $errors['armor'] = "A páncél nem lehet nagyobb mint 100!";
            }
        }
    }

    $price = trim($_POST['price'] ?? '');
    if(strlen($price)==0){
        $errors['price']="Ár megadása kötelező!";
    }else{
        if (!is_numeric($price)) {
            $errors['price'] = "Árként csak szám adható meg!";
        }
        else{
            if($price<100){
                $errors['price'] = "Az ár több kell hogy legyen mint 100!";
            }
            if($price>1000){
                $errors['price'] = "Az ár nem lehet több mint 1000!";
            }
        }
    }


    $description = trim($_POST['description'] ?? '');
    if(strlen($description) > 150) {
        $errors["description"] = "A karakter leírása maximum 150 karakter lehet!";
    }
    if(strlen($description)==0){
        $description = "Ehhez a karakterhez nem tartozik leírás.";
    }

    $image = trim($_POST['image'] ?? '');


    if (count($errors) === 0) {

        $character=[
            "name" => $name,
            "type" => $type,
            "hp"=>$health,
            "attack"=>$damage,
            "defense"=>$armor,
            "price"=>$price,
            "description"=>$description,
            "image"=>$image,
            "ownerId" => "admin"
        ];

        $storage->add($character);
        unset($storage);

        header("Location: index.php");
        exit();
    }

}



?>

 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Új kártya</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/cards.css">
</head>
<body>
    <header style="display: flex; gap: 30px">
        <h1><a href="index.php">IKémon</a> > Új kártya</h1>
        <?php if (!isset($_SESSION['user'])) : ?>
            <h1><a href="login.php">Bejelentkezés</a></h1>
        <?php endif; ?>
        <?php if (!isset($_SESSION['user'])) : ?>
            <h1><a href="register.php">Regisztráció</a></h1>
        <?php endif; ?>
        <?php if (isset($_SESSION['user'])&& !in_array('admin', $_SESSION['user']['roles'])) : ?>
            <h1><a href="user-details.php">Felhasználó részletek</a></h1>
        <?php endif; ?>
        <?php if (isset($_SESSION['user'])) : ?>
            <h1 style="margin-left: auto;"><a href="logout.php">Kijelentkezés</a></h1>
        <?php endif; ?>
    </header>
    <div id="content">
        <h1 id="site-description">Új kártya készítése</h1>
        <form action="" method="post">
            <label for="character_name">Karakter neve: </label>
            <input name="character_name" type="text" value="<?= $name ?? "" ?>">
            <?php if(isset($errors["name"])): ?><span class="error"><?= $errors["name"] ?></span><?php endif; ?>
            <br>
            <label for="character_type">Típus 🏷 </label>
            <?php $pokemonTypes = array(" normal","fire","water","electric","grass","ice","fighting","poison","ground","psychic","bug","rock","ghost","dark","steel"); ?>
            <select name="character_type" id="">
                <?php foreach ($pokemonTypes as $type): ?>
                <option value="<?=$type?>"><?= $type ?></option>
                <?php endforeach ?>
            </select>
            <br>
            <label for="health">Életerő ❤ </label>
            <input name="health" type="text" value="<?= $health ?? "" ?>">
            <?php if(isset($errors["health"])): ?><span class="error"><?= $errors["health"] ?></span><?php endif; ?>
            <br>
            <label for="damage">Sebzés ⚔ </label>
            <input name="damage" type="text" value="<?= $damage ?? "" ?>">
            <?php if(isset($errors["damage"])): ?><span class="error"><?= $errors["damage"] ?></span><?php endif; ?>
            <br>
            <label for="armor">Páncél 🛡 </label>
            <input name="armor" type="text" value="<?= $armor ?? "" ?>">
            <?php if(isset($errors["armor"])): ?><span class="error"><?= $errors["armor"] ?></span><?php endif; ?>
            <br>
            <label for="price">Ár 💰 </label>
            <input name="price" type="text" value="<?= $price ?? "" ?>">
            <?php if(isset($errors["price"])): ?><span class="error"><?= $errors["price"] ?></span><?php endif; ?>
            <br>
            <label for="description">Leírás </label>
            <textarea name="description" rows="3" cols="25" style="resize: none;" placeholder="Adj egy rövid leírás a karakterről." value="<?= $description ?? "" ?>"></textarea>
            <?php if(isset($errors["description"])): ?><span class="error"><?= $errors["description"] ?></span><?php endif; ?>
            <br>
            <label for="image">Karakter képe: </label>
            <input name="image" type="text" placeholder="Add meg a kép URL-jét" value="<?= $image ?? "" ?>">
            <br>
            <button>Karakter létrehozása</button>
        </form>
    </div>
</body>
</html>