<?php
session_start();


require_once "../connect.php";
$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
if ($polaczenie->connect_errno != 0) {
    echo "Error: " . $polaczenie->connect_errno;
    exit();
}


if (!isset($_SESSION['koszyk'])) {
    $_SESSION['koszyk'] = array();
}


if (isset($_POST['dodaj_do_koszyka']) && isset($_POST['produkt_id']) && isset($_POST['ilosc']) && isset($_POST['produkt_typ'])) {
    $produkt_id = intval($_POST['produkt_id']);
    $ilosc = intval($_POST['ilosc']);
    $produkt_typ = $_POST['produkt_typ'];

    $produkt_klucz = $produkt_typ . '-' . $produkt_id;

    if (isset($_SESSION['koszyk'][$produkt_klucz])) {
        $_SESSION['koszyk'][$produkt_klucz] += $ilosc; 
    } else {
        $_SESSION['koszyk'][$produkt_klucz] = $ilosc;
    }
}


if (isset($_POST['usun_z_koszyka']) && isset($_POST['produkt_id']) && isset($_POST['produkt_typ'])) {
    $produkt_id = intval($_POST['produkt_id']);
    $produkt_typ = $_POST['produkt_typ'];

    $produkt_klucz = $produkt_typ . '-' . $produkt_id;

    if (isset($_SESSION['koszyk'][$produkt_klucz])) {
        unset($_SESSION['koszyk'][$produkt_klucz]);
    }
}


if (isset($_POST['potwierdz_zamowienie'])) {
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
                }
            } else {
                echo "Niewystarczająca ilość produktu $produkt_id: $produkt_ilosc_w_bazie w magazynie.";
            }
        } else {
            echo "Nie znaleziono produktu o ID: $produkt_id";
        }

        $result->free();
    }

    
    $_SESSION['koszyk'] = array();

    echo "Zamówienie zostało złożone. Dziękujemy!";
}


$koszyk_produkty = array();
$total_value = 0;

foreach ($_SESSION['koszyk'] as $produkt_klucz => $ilosc) {
    list($produkt_typ, $produkt_id) = explode('-', $produkt_klucz);

    $query = "SELECT nazwa, cena, ilosc FROM produkty WHERE id = $produkt_id AND typ = '$produkt_typ'";
    $result = $polaczenie->query($query);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $produkt_nazwa = htmlspecialchars($row['nazwa']);
        $produkt_cena = floatval($row['cena']);
        $produkt_ilosc = intval($row['ilosc']);

        
        $wartosc_produktu = $produkt_cena * $ilosc;
        $total_value += $wartosc_produktu;

        
        $koszyk_produkty[] = array(
            'nazwa' => $produkt_nazwa,
            'cena' => $produkt_cena,
            'ilosc' => $ilosc,
            'wartosc' => $wartosc_produktu
        );
    }

    $result->free();
}

echo '<!DOCTYPE HTML>';
echo '<html lang="pl">';
echo '<head>';
echo '<meta charset="utf-8" />';
echo '<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />';
echo '<title>Koszyk</title>';
echo '<style>';
echo '.cart-item { margin-bottom: 20px; }';
echo '</style>';
echo '</head>';
echo '<body>';

echo '<h2>Koszyk</h2>';

if (!empty($koszyk_produkty)) {
    foreach ($koszyk_produkty as $produkt) {
        echo '<div class="cart-item">';
        echo '<h3>' . $produkt['nazwa'] . '</h3>';
        echo '<p>Cena: ' . $produkt['cena'] . ' PLN</p>';
        echo '<p>Ilość: ' . $produkt['ilosc'] . '</p>';
        echo '<p>Wartość: ' . $produkt['wartosc'] . ' PLN</p>';
        echo '<form action="koszyk.php" method="post">';
        echo '<input type="hidden" name="produkt_id" value="' . $produkt_id . '">';
        echo '<input type="hidden" name="produkt_typ" value="' . $produkt_typ . '">';
        echo '<input type="submit" name="usun_z_koszyka" value="Usuń z koszyka">';
        echo '</form>';
        echo '</div>';
    }

    echo '<h3>Całkowita wartość koszyka: ' . $total_value . ' PLN</h3>';

    echo '<form action="zamowienie.php" method="post">';
    echo '<input type="submit" name="potwierdz_zamowienie" value="Potwierdź zamówienie">';
    echo '</form>';
} else {
    echo '<p>Koszyk jest pusty.</p>';
}

echo '<br><a href="stronag.php"><button>Wróć do sklepu</button></a>';

echo '</body>';
echo '</html>';

$polaczenie->close();
?>