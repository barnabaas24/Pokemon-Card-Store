<?php 

session_start();

require_once "storage/UserStorage.php";
require_once "vendor/Auth.php";

if(isset($_SESSION['user'])){
    header("Location: index.php");
    exit();
}

$storage = new UserStorage();

if($_SERVER["REQUEST_METHOD"] === "POST"){
    $errors = [];

    $username = trim($_POST["username"] ?? "");
    if (strlen($username) == 0) {
        $errors["username"] = "A felhasználónév megadása kötelező!";
    } else if (strlen($username) > 32) {
        $errors["username"] = "A felhasználónév maximum 32 karakter hosszú lehet.";
    }
    else if($username=="admin"){
        $errors['username'] = 'A megadott felhasználónév már foglalt!';
    }
    else{
        $existingUser = $storage->findOne(['username' => $username]);
        if($existingUser!=null){
            $errors['username'] = 'A megadott felhasználónév már foglalt!';
        }
    }

    $email = trim($_POST['email'] ?? '');
    if (strlen($email) === 0) {
        $errors['email'] = 'Az email megadása kötelező!';
    } else {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Az email formátuma nem megfelelő.';
        }
        else{           
            $existingUser = $storage->findOne(['email' => $email]);
            if($existingUser!=null){
                $errors['email'] = 'A megadott email már regisztrált az oldalon.';
            }
        }
    }


    $password = trim($_POST["password"] ?? "");
    $passwordLength = strlen($password);
    if ($passwordLength == 0) {
        $errors["password"] = "A jelszó megadása kötelező!";
    } else if($passwordLength < 8 || $passwordLength > 16) {
        $errors["password"] = "A jelszó hossza 8 és 16 karakter között kell legyen!";
    }


    $rePassword = trim($_POST["repassword"] ?? "");
    if($rePassword!=$password){
        $errors["repassword"] = "A két jelszó nem egyezik meg!";
    }



    if (count($errors) === 0) {

        $auth = new Auth($storage);

        $result = $auth->register([
            "username" => $username,
            "email" => $email,
            "password" => $password,
            "money" => 1000,
            "ownedCards" => 0
        ]);

        if($result!=null){
             $user = $auth->authenticate($username,$password);
             $auth->login($user);
        }

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
    <title>IKémon | Regisztráció</title>
    <link rel="stylesheet" href="styles/main.css">
</head>
<body>
    <header>
        <h1><a href="index.php">IKémon</a> > Regisztráció</h1>
    </header>
    <main>

    <div class="formWrapper">
        <form class="regform" method="post">
            <div class="form-row">
                <label for="username">Név*</label>
                <input type="text" id="username" name="username" value="<?= $username ?? "" ?>">
                <?php if(isset($errors["username"])): ?><span class="error"><?= $errors["username"] ?></span>
                <?php else: ?>
                    <span class="error"></span>
                <?php endif ?>
            </div>
                <br>
            <div class="form-row">
                <label for="email">E-mail*</label>
                <input type="text" id="email" name="email" value="<?= $email ?? "" ?>">
                <?php if(isset($errors["email"])): ?><span class="error"><?= $errors["email"] ?></span>
                    <?php else: ?>
                <span class="error"></span>
                <?php endif ?>
            </div>
                <br>
            <div class="form-row">
                <label for="password">Jelszó*</label>
                <input type="text" id="password" name="password" value="<?= $password ?? "" ?>" >
                <?php if(isset($errors["password"])): ?><span class="error"><?= $errors["password"] ?></span>
                    <?php else: ?>
                <span class="error"></span>
                <?php endif ?>
            </div>
            <br>
            <div class="form-row">
                <label for="repassword">Jelszó újra*</label>
                <input type="text" id="repassword" name="repassword" value="<?= $rePassword ?? "" ?>" >
                <?php if(isset($errors["repassword"])): ?><span class="error"><?= $errors["repassword"] ?></span>
                    <?php else: ?>
                <span class="error"></span>
                <?php endif ?>
            </div>
                <br>
            <div class="center-button">
                <button type="submit">Regisztráció</button>
            </div>

        </form>
    </div>
    </main>
    
</body>
</html>