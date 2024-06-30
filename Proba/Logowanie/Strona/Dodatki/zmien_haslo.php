<?php
session_start();



require_once "../../connect.php";


session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (isset($_POST['stare_haslo'], $_POST['nowe_haslo1'], $_POST['nowe_haslo2'])) {
        $stare_haslo = $_POST['stare_haslo'];
        $nowe_haslo1 = $_POST['nowe_haslo1'];
        $nowe_haslo2 = $_POST['nowe_haslo2'];

        
        if (empty($stare_haslo) || empty($nowe_haslo1) || empty($nowe_haslo2)) {
            $_SESSION['error'] = "Proszę wypełnić wszystkie pola formularza.";
        } elseif ($nowe_haslo1 !== $nowe_haslo2) {
            $_SESSION['error'] = "Nowe hasła nie są identyczne.";
        } else {
            
            require_once "../../connect.php"; 
            try {
                $pdo = new PDO("mysql:host=$host;dbname=$db_name", $db_user, $db_password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                
                $stmt = $pdo->prepare("SELECT pass FROM uzytkownicy WHERE id = :id");
                $stmt->bindParam(':id', $_SESSION['id']);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result && password_verify($stare_haslo, $result['pass'])) {
                    
                    $hashed_password = password_hash($nowe_haslo1, PASSWORD_DEFAULT);
                    $update_stmt = $pdo->prepare("UPDATE uzytkownicy SET pass = :pass WHERE id = :id");
                    $update_stmt->bindParam(':pass', $hashed_password);
                    $update_stmt->bindParam(':id', $_SESSION['id']);
                    $update_stmt->execute();

                    $_SESSION['success'] = "Hasło zostało pomyślnie zmienione.";
                } else {
                    $_SESSION['error'] = "Nieprawidłowe stare hasło.";
                }

            } catch (PDOException $e) {
                $_SESSION['error'] = "Błąd bazy danych: " . $e->getMessage();
            }
        }
    } else {
        $_SESSION['error'] = "Nie można przetworzyć żądania.";
    }

    header('Location: administracja.php'); 
    exit();
} else {
    
    header('Location: ../index.php'); 
    exit();
}
?>