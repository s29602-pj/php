<?php
session_start();


if (!isset($_SESSION['zalogowany'])) {
    header('Location: ../index.php'); 
    exit();
}

require_once "../connect.php"; 

try {
    
    $pdo = new PDO("mysql:host=$host;dbname=$db_name", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    
    $stmt = $pdo->prepare("SELECT role FROM uzytkownicy WHERE id = :id");
    $stmt->bindParam(':id', $_SESSION['id']);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    
    switch ($user['role']) {
        case 'uzytkownik':
            
            $stmt = $pdo->prepare("SELECT * FROM zamowienia WHERE user = :user");
            $stmt->bindParam(':user', $_SESSION['id']);
            break;
        case 'sprzedawca':
        case 'administrator':
            
            $stmt = $pdo->prepare("SELECT * FROM zamowienia");
            break;
        default:
            echo "Nieznana rola użytkownika.";
            exit();
    }

    
    $stmt->execute();
    $zamowienia = $stmt->fetchAll(PDO::FETCH_ASSOC);

    
    echo '<!DOCTYPE HTML>';
    echo '<html lang="pl">';
    echo '<head>';
    echo '<meta charset="utf-8" />';
    echo '<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />';
    echo '<title>Zamówienia</title>';
    echo '</head>';
    echo '<body>';

    echo '<h2>Twoje zamówienia</h2>';
    echo '<table border="1">';
    echo '<tr><th>ID Zamówienia</th><th>ID Użytkownika</th><th>Status</th><th>Akcje</th></tr>';

    foreach ($zamowienia as $zamowienie) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($zamowienie['id_p']) . '</td>';
        echo '<td>' . htmlspecialchars($zamowienie['user']) . '</td>';
        echo '<td>' . htmlspecialchars($zamowienie['status']) . '</td>';
        echo '<td>';

        
        if ($user['role'] == 'sprzedawca' || $user['role'] == 'administrator') {
            
            echo '<form action="zmien_status.php" method="post">';
            echo '<input type="hidden" name="order_id" value="' . htmlspecialchars($zamowienie['id_p']) . '">';
            echo '<select name="new_status">';
            echo '<option value="w realizacji">W realizacji</option>';
            echo '<option value="zrealizowane">Zrealizowane</option>';
            echo '</select>';
            echo '<input type="submit" name="submit" value="Zmień status">';
            echo '</form>';
        }

        echo '</td>';
        echo '</tr>';
    }

    echo '</table>';

    echo '<p><a href="stronag.php">Powrót</a></p>';
    echo '</body>';
    echo '</html>';

} catch (PDOException $e) {
    echo "Błąd połączenia z bazą danych: " . $e->getMessage();
}

$pdo = null; 
?>