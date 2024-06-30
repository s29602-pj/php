<?php

require_once "../connect.php";

session_start();

$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
if ($polaczenie->connect_errno != 0) {
    echo "Error: " . $polaczenie->connect_errno;
} else {
    
    $query = "SELECT * FROM produkty WHERE typ = 'owoc'";
    $result = $polaczenie->query($query);

    echo '<!DOCTYPE HTML>';
    echo '<html lang="pl">';
    echo '<head>';
    echo '<meta charset="utf-8" />';
    echo '<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />';
    echo '<title>Owoce - Sklep</title>';
    echo '</head>';
    echo '<body>';

    echo '<a href="stronag.php"><h2>Owoce</h2>';
    echo '<div class="cart">';
    echo '<a href="koszyk.php">Koszyk (<span id="cart-count">' . (isset($_SESSION['koszyk']) ? count($_SESSION['koszyk']) : 0) . '</span>)</a>';
    echo '</div>';
    echo '<div class="fruit-container">';

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="fruit">';
            echo '<img src="' . htmlspecialchars($row['nazwa']) . '">';
            echo '<h4>' . htmlspecialchars($row['nazwa']) . '</h4>';
            echo '<p>Cena: ' . htmlspecialchars($row['cena']) . ' PLN</p>';
            echo '<p>Ilość: ' . htmlspecialchars($row['ilosc']) . '</p>';
            echo '<p>Opis: ' . htmlspecialchars($row['opis']) . '</p>';
            
            echo '<form action="koszyk.php" method="post">';
            echo '<label for="ilosc">Ilość:</label>';
            echo '<input type="number" id="ilosc" name="ilosc" min="1" max="' . htmlspecialchars($row['ilosc']) . '" value="1">';
            echo '<input type="hidden" name="produkt_id" value="' . htmlspecialchars($row['id']) . '">';
            echo '<input type="hidden" name="produkt_typ" value="owoc">'; 
            echo '<input type="submit" name="dodaj_do_koszyka" value="Dodaj do koszyka">';
            echo '</form>';
            echo '</div>';
        }
        $result->free();
    } else {
        echo "Error: " . $polaczenie->error;
    }
 

    echo '</div>'; 
    echo '</body>';
    echo '</html>';

    $polaczenie->close();
}
?>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #fff0b3;
        padding: 20px;
        margin: 0;
    }

    .fruit-container {
        display: flex;
        justify-content: space-around;
        flex-wrap: wrap;
        background-color: #fff;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }



    .fruit {
        margin: 10px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #f9f9f9;
        flex: 1 1 20%;
        text-align: center;
    }



    .fruit img {
        max-width: 100%;
        height: auto;
        border-radius: 5px;
    }



    .fruit h4 {
        margin: 10px 0;
        font-size: 18px;
    }


    .fruit p {
        margin: 5px 0;
    }



    .form-container {
        margin-top: 10px;
    }



    .form-container form {
        margin-bottom: 10px;
    }



    .cart {
        text-align: center;
        margin-bottom: 20px;
    }



    .cart a {
        text-decoration: none;
        color: #333;
        font-weight: bold;
        font-size: 18px;
    }



    .cart a:hover {
        color: #555;
    }



    .cart-items {
        display: inline-block;
        background-color: #4CAF50;
        color: white;
        padding: 5px 10px;
        border-radius: 50%;
        font-size: 14px;
        margin-left: 5px;
    }
</style>