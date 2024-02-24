<?php 

session_start();

if(isset($_SESSION['user'])){
    header("Location: index.php");
    exit();
}

require_once "storage/UserStorage.php";
require_once "vendor/Auth.php";

$storage = new UserStorage();
$auth = new Auth($storage);

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"] ?? "");
    $password = trim($_POST["password"] ?? "");

    if($username=="admin" && $password=="admin"){

        $existingAdmin = $storage->findOne(['username' => "admin"]);

        if($existingAdmin!=null){
            $admin = $auth->authenticate($username, $password);
            if ($admin != null) {
                $auth->login($admin);
                header("Location: index.php");
            }
        }
        else{
            $result = $auth->register([
                "username" => $username,
                "email" => "admin",
                "password" => $password,
                "money" => -1,
                "ownedCards" => -1,
                "roles" => "admin"
            ]);
    
            if($result!=null){
                 $user = $auth->authenticate($username,$password);
                 $auth->login($user);
                header("Location: index.php");
            }
        }
        exit();

    }



    $errors = [];

    if (strlen($username) == 0) {
        $errors["username"] = "A felhasználónév megadása kötelező!";
    } else if (strlen($username) > 32) {
        $errors["username"] = "A felhasználónév maximum 32 karakter hosszú lehet.";
    }

    $passwordLength = strlen($password);
    if ($passwordLength == 0) {
        $errors["password"] = "A jelszó megadása kötelező!";
    } else if($passwordLength < 8 || $passwordLength > 16) {
        $errors["password"] = "A jelszó hossza 8 és 16 karakter közti.";
    }

    if (count($errors) === 0) {

        $user = $auth->authenticate($username, $password);


        if ($user != null) {
            $auth->login($user);
            header("Location: index.php");
            exit();
        } else {
            $errors["invalid"] = "Hibás felhasználónév vagy jelszó!";
        }


    }
}




?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKémon | Bejelentkezés</title>
    <link rel="stylesheet" href="styles/main.css">

</head>
<body>
    <header>
        <h1><a href="index.php">IKémon</a> > Bejelentkezés</h1>
    </header>
    <main>
        <h1>Bejelentkezés</h1>
        <div class="formWrapperLogin">
            <form method="post">



                <div class="loginErrors">
                    <?php if(isset($errors["username"])): ?><span class="error"><?= $errors["username"] ?></span><?php endif; ?>
                    <?php if(isset($errors["invalid"])): ?><span class="error"><?= $errors["invalid"] ?></span><?php endif; ?>
                    <?php if(isset($errors["password"])): ?><span class="error"><?= $errors["password"] ?></span><?php endif; ?>
                </div>
                
                <div class="form-row">
                    <label for="username">Felhasználónév*</label>
                    <input type="text" id="username" name="username" value="<?= $username ?? "" ?>">
                </div>

                <div class="form-row">
                    <label for="password">Jelszó*</label>
                    <input type="text" id="password" name="password" value="<?= $password ?? "" ?>" >
                </div>

                <div class="center-button">
                    <button type="submit">Bejelentkezés</button>
                </div>

            </form>
        </div>
        
    </main>
</body>
</html>