<?php
session_start();

if (!isset($_SESSION['zalogowany'])) {
    header('Location: index.php');
    exit();
}

require_once "connect.php";

$polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);

if ($polaczenie->connect_errno != 0) {
    echo "Error: " . $polaczenie->connect_errno;
} else {
    $stare_haslo = $_POST['stare_haslo'];
    $nowe_haslo1 = $_POST['nowe_haslo1'];
    $nowe_haslo2 = $_POST['nowe_haslo2'];

    $id = $_SESSION['id'];

    if ($nowe_haslo1 != $nowe_haslo2) {
        $_SESSION['blad'] = "Nowe hasła nie są identyczne!";
        header('Location: zmien_haslo.php');
        exit();
    }

    $rezultat = $polaczenie->query("SELECT * FROM uzytkownicy WHERE id='$id'");
    $wiersz = $rezultat->fetch_assoc();

    if ($wiersz && $wiersz['pass'] == $stare_haslo) {
        if ($polaczenie->query("UPDATE uzytkownicy SET pass='$nowe_haslo1' WHERE id='$id'")) {
            echo "Hasło zostało zmienione!";
        } else {
            throw new Exception($polaczenie->error);
        }
    } else {
        $_SESSION['blad'] = "Stare hasło jest niepoprawne!";
        header('Location: zmien_haslo.php');
        exit();
    }

    $polaczenie->close();
}
?>