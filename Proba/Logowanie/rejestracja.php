<?php
session_start();

if (isset($_POST['email'])) {
    
    $wszystko_OK = true;

    
    $login = $_POST['login'];

    
    if ((strlen($login) < 3) || (strlen($login) > 20)) {
        $wszystko_OK = false;
        $_SESSION['e_login'] = "Nick musi posiadać od 3 do 20 znaków!";
    }

    if (ctype_alnum($login) == false) {
        $wszystko_OK = false;
        $_SESSION['e_login'] = "Nick może składać się tylko z liter i cyfr (bez polskich znaków)";
    }

    
    $email = $_POST['email'];
    $emailB = filter_var($email, FILTER_SANITIZE_EMAIL);

    if ((filter_var($emailB, FILTER_VALIDATE_EMAIL) == false) || ($emailB != $email)) {
        $wszystko_OK = false;
        $_SESSION['e_email'] = "Podaj poprawny adres e-mail!";
    }

    
    $haslo1 = $_POST['haslo1'];
    $haslo2 = $_POST['haslo2'];

    if ((strlen($haslo1) < 8) || (strlen($haslo1) > 20)) {
        $wszystko_OK = false;
        $_SESSION['e_haslo'] = "Hasło musi posiadać od 8 do 20 znaków!";
    }

    if ($haslo1 != $haslo2) {
        $wszystko_OK = false;
        $_SESSION['e_haslo'] = "Podane hasła nie są identyczne!";
    }

    
    $_SESSION['fr_login'] = $login;
    $_SESSION['fr_email'] = $email;
    $_SESSION['fr_haslo1'] = $haslo1;
    $_SESSION['fr_haslo2'] = $haslo2;

    require_once "connect.php";
    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
        $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
        if ($polaczenie->connect_errno != 0) {
            throw new Exception(mysqli_connect_errno());
        } else {
            
            $rezultat = $polaczenie->query("SELECT id FROM uzytkownicy WHERE email='$email'");

            if (!$rezultat)
                throw new Exception($polaczenie->error);

            $ile_takich_maili = $rezultat->num_rows;
            if ($ile_takich_maili > 0) {
                $wszystko_OK = false;
                $_SESSION['e_email'] = "Istnieje już konto przypisane do tego adresu e-mail!";
            }

            
            $rezultat = $polaczenie->query("SELECT id FROM uzytkownicy WHERE user='$login'");

            if (!$rezultat)
                throw new Exception($polaczenie->error);

            $ile_takich_nickow = $rezultat->num_rows;
            if ($ile_takich_nickow > 0) {
                $wszystko_OK = false;
                $_SESSION['e_nick'] = "Istnieje już gracz o takim nicku! Wybierz inny.";
            }

            if ($wszystko_OK == true) {
                
                if ($polaczenie->query("INSERT INTO uzytkownicy VALUES (NULL, '$login', '$haslo1', '$email')")) {
                    $_SESSION['udanarejestracja'] = true;
                    header('Location: witamy.php');
                } else {
                    throw new Exception($polaczenie->error);
                }
            }

            $polaczenie->close();
        }

    } catch (Exception $e) {
        echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie!</span>';
        echo '<br />Informacja developerska: ' . $e;
    }
}
?>

<!DOCTYPE HTML>
<html lang="pl">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Spożywczak - sklep online</title>


    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            padding: 20px;
            background-color: #f2f2f2;
            text-align: center;
        }

        form {
            margin: 0 auto;
            width: 300px;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        form input[type="text"],
        form input[type="password"],
        form input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        form input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        form input[type="submit"]:hover {
            background-color: #45a049;
        }

        .error {
            color: red;
            margin-top: 10px;
            margin-bottom: 10px;
            text-align: left;
        }

        .error-message {
            color: red;
        }
    </style>
</head>

<body>

    <form method="post">

        Login: <br /> <input type="text" value="<?php
        if (isset($_SESSION['fr_login'])) {
            echo $_SESSION['fr_login'];
            unset($_SESSION['fr_login']);
        }
        ?>" name="login" /><br />

        <?php
        if (isset($_SESSION['e_login'])) {
            echo '<div class="error">' . $_SESSION['e_login'] . '</div>';
            unset($_SESSION['e_login']);
        }
        ?>

        E-mail: <br /> <input type="text" value="<?php
        if (isset($_SESSION['fr_email'])) {
            echo $_SESSION['fr_email'];
            unset($_SESSION['fr_email']);
        }
        ?>" name="email" /><br />

        <?php
        if (isset($_SESSION['e_email'])) {
            echo '<div class="error">' . $_SESSION['e_email'] . '</div>';
            unset($_SESSION['e_email']);
        }
        ?>

        Twoje hasło: <br /> <input type="password" value="<?php
        if (isset($_SESSION['fr_haslo1'])) {
            echo $_SESSION['fr_haslo1'];
            unset($_SESSION['fr_haslo1']);
        }
        ?>" name="haslo1" /><br />

        <?php
        if (isset($_SESSION['e_haslo'])) {
            echo '<div class="error">' . $_SESSION['e_haslo'] . '</div>';
            unset($_SESSION['e_haslo']);
        }
        ?>

        Powtórz hasło: <br /> <input type="password" value="<?php
        if (isset($_SESSION['fr_haslo2'])) {
            echo $_SESSION['fr_haslo2'];
            unset($_SESSION['fr_haslo2']);
        }
        ?>" name="haslo2" /><br />
        <input type="submit" value="Zarejestruj się" />

    </form>

</body>

</html>