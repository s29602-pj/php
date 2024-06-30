<?php
session_start();


require_once "../connect.php";
$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
if ($polaczenie->connect_errno != 0) {
    echo "Error: " . $polaczenie->connect_errno;
    exit();
}

if (!isset($_SESSION['zalogowany'])) {
    header('Location: ../index.php'); 
    exit();
}

$user = $_SESSION['user']; 


if (isset($_POST['potwierdz_zamowienie']) && !empty($_SESSION['koszyk'])) {
    $total_value = 0;

    foreach ($_SESSION['koszyk'] as $produkt_klucz => $ilosc) {
        list($produkt_typ, $produkt_id) = explode('-', $produkt_klucz);

        $query = "SELECT cena, ilosc FROM produkty WHERE id = $produkt_id AND typ = '$produkt_typ'";
        $result = $polaczenie->query($query);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $produkt_cena = floatval($row['cena']);
            $produkt_ilosc_w_bazie = intval($row['ilosc']);

            if ($produkt_ilosc_w_bazie >= $ilosc) {
                
                $update_query = "UPDATE produkty SET ilosc = ilosc - $ilosc WHERE id = $produkt_id AND typ = '$produkt_typ'";
                $update_result = $polaczenie->query($update_query);

                if (!$update_result) {
                    echo "Error updating quantity for product id: $produkt_id";
                } else {
                    
                    $total_value += $produkt_cena * $ilosc;
                }
            } else {
                echo "Niewystarczająca ilość produktu $produkt_id: $produkt_ilosc_w_bazie w magazynie.";
            }
        } else {
            echo "Nie znaleziono produktu o ID: $produkt_id";
        }

        $result->free();
    }

    if ($total_value > 0) {
        
        $status = 'realizowane'; 
        $insert_order_query = "INSERT INTO zamowienia (user, status) VALUES ('$user', 'realizowane')";
        if ($polaczenie->query($insert_order_query)) {
            $order_id = $polaczenie->insert_id;

            
            $_SESSION['koszyk'] = array();

            
            echo "<h2>Zamówienie zostało złożone. Dziękujemy!</h2>";
            echo "<p>ID Zamówienia: $order_id</p>";
            echo "<p>Użytkownik: $user</p>";
            echo "<p>Status Zamówienia: $status</p>";
        } else {
            echo "Error: Could not create order.";
        }
    }
}

$polaczenie->close();
?>

<!DOCTYPE HTML>
<html lang="pl">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Potwierdzenie Zamówienia</title>
</head>

<body>
    <p><a href="koszyk.php"><button>Wróć do koszyka</button></a></p>
    <p><a href="stronag.php"><button>Wróć do sklepu</button></a></p>
</body>

</html>