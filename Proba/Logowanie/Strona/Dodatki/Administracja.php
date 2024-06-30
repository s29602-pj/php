<?php
session_start();

if (!isset($_SESSION['zalogowany'])) {
    header('Location: ../index.php');
    exit();
}

require_once "../../connect.php"; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    
    $stmt = $pdo->prepare("SELECT * FROM uzytkownicy WHERE id = :id");
    $stmt->bindParam(':id', $_SESSION['id']);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    
    if ($user) {
        echo "<!DOCTYPE HTML>";
        echo "<html lang='pl'>";
        echo "<head>";
        echo "<meta charset='utf-8' />";
        echo "<meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1' />";
        echo "<title>Panel administracyjny</title>";
        echo "<style>";
        echo "body {";
        echo "    font-family: Arial, sans-serif;";
        echo "    line-height: 1.6;";
        echo "    padding: 20px;";
        echo "    background-color: #f2f2f2;";
        echo "    text-align: center;";
        echo "}";
        echo "h1 {";
        echo "    color: #333;";
        echo "}";
        echo "p {";
        echo "    margin: 10px 0;";
        echo "}";
        echo "ul {";
        echo "    list-style-type: none;";
        echo "    padding: 0;";
        echo "}";
        echo "li {";
        echo "    margin-bottom: 8px;";
        echo "}";
        echo ".container {";
        echo "    max-width: 800px;";
        echo "    margin: 0 auto;";
        echo "    background-color: #fff;";
        echo "    padding: 20px;";
        echo "    border-radius: 5px;";
        echo "    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);";
        echo "}";
        echo ".logout-link {";
        echo "    float: right;";
        echo "    margin-top: -30px;";
        echo "}";
        echo ".password-form {";
        echo "    max-width: 400px;";
        echo "    margin: 20px auto;";
        echo "    padding: 20px;";
        echo "    background-color: #fff;";
        echo "    border-radius: 5px;";
        echo "    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);";
        echo "}";
        echo ".password-form input[type='password'],";
        echo ".password-form input[type='submit'] {";
        echo "    width: 100%;";
        echo "    padding: 10px;";
        echo "    margin: 8px 0;";
        echo "    display: inline-block;";
        echo "    border: 1px solid #ccc;";
        echo "    border-radius: 4px;";
        echo "    box-sizing: border-box;";
        echo "}";
        echo ".password-form input[type='submit'] {";
        echo "    background-color: #4CAF50;";
        echo "    color: white;";
        echo "    border: none;";
        echo "    cursor: pointer;";
        echo "}";
        echo ".password-form input[type='submit']:hover {";
        echo "    background-color: #45a049;";
        echo "}";
        echo "</style>";
        echo "</head>";
        echo "<body>";
        echo "<div class='container'>";

        echo "<h1>Panel administracyjny</h1>";
        echo "<p>Witaj, {$user['user']}! </p>";

        switch ($user['role']) {
            case 'administrator':
                echo "<p>Jesteś administratorem. Możesz wszystko:</p>";
                echo "<ul>";
                echo "<li>Dodawać, usuwać, edytować przedmioty</li>";
                echo "<li>Przeglądać, edytować i usuwać zamówienia</li>";
                echo "<li>Przeglądać logi</li>";
                echo "</ul>";
                break;
            case 'sprzedawca':
                echo "<p>Jesteś sprzedawcą. Twoje uprawnienia to:</p>";
                echo "<ul>";
                echo "<li>Dodawać, usuwać, edytować przedmioty</li>";
                echo "<li>Przeglądać zamówienia</li>";
                echo "<li>Oznaczać zamówienia jako zrealizowane</li>";
                echo "<li>Możesz zresetować swoje hasło</li>";
                echo "</ul>";
                break;
            case 'uzytkownik':
                echo "<p>Jesteś użytkownikiem. Możesz:</p>";
                echo "<ul>";
                echo "<li>Zamawiać przedmioty</li>";
                echo "<li>Przeglądać swoje zamówienia oraz ich status</li>";
                echo "<li>Możesz zresetować swoje hasło</li>";
                echo "</ul>";
                break;
            default:
                echo "<p>Nieznany typ konta.</p>";
                break;
        }

        echo "<div class='password-form'>";
        echo "<h2>Zmień hasło</h2>";
        echo "<form action='zmien_haslo.php' method='post'>";
        echo "<input type='password' name='stare_haslo' placeholder='Stare hasło' required><br>";
        echo "<input type='password' name='nowe_haslo1' placeholder='Nowe hasło' required><br>";
        echo "<input type='password' name='nowe_haslo2' placeholder='Powtórz nowe hasło' required><br>";
        echo "<input type='submit' value='Zmień hasło'>";
        echo "</form>";
        echo "</div>";

        echo "</div>"; 
        echo "</body>";
        echo "</html>";

    } else {
        echo "<p>Nie można pobrać informacji o użytkowniku.</p>";
    }

} catch (PDOException $e) {
    echo "Błąd bazy danych: " . $e->getMessage();
}
?>