<?php
session_start();

?>
<!DOCTYPE HTML>
<html lang="pl">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Spożywczak - sklep online</title>
</head>

<body>

    <?php
    if (isset($_SESSION['zalogowany']) && $_SESSION['zalogowany'] === true) {
        echo "<p>Witaj " . htmlspecialchars($_SESSION['user']) . '! [ <a href="../logout.php">Wyloguj się!</a> ]</p>';
        echo '<a href="Dodatki/administracja.php">Administracja</a>';
    } else {
        echo '<p>Witaj Kliencie! [ <a href="../zaloguj.php">Zaloguj się!</a> ]</p>';
    }
    echo '<form action="zamowione.php" method="post">';
    echo '<input type="submit" name="zloz_zamowienie" value="Zamówienie">';
    echo '</form>';
    ?>
    
    <div class="categories">
        <h3>Kategorie:</h3>
        <ul>
            <li><a href="owoce.php">Owoce</a></li>
            <li><a href="warzywa.php">Warzywa</a></li>
        </ul>
    </div>

    <div class="icons">
        <div class="icon">
            <img src="https://m.media-amazon.com/images/I/81bM9KUg0SS._AC_UF1000,1000_QL80_.jpg" alt="Ikona koszyka">
        </div>

        <div class="icon">
            <img src="https://i.wpimg.pl/1200x/d.wpimg.pl/1575736591--2141298452/nabial.jpg" alt="Ikona koszyka">
        </div>

        <div class="icon">
            <img src="https://cdn.galleries.smcloud.net/t/galleries/gf-VWe1-f9GG-fZZb_owoce-1920x1080-nocrop.jpg"
                alt="Ikona koszyka">
        </div>
    </div>

</body>

</html>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f0f0f0;
        padding: 20px;
        margin: 0;
        background-color: #FFD700;
    }

    .categories {
        background-color: #ffffcc;
        
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 10px;
        width: 100%;
        overflow-x: auto;
        white-space: nowrap;
        margin-bottom: 20px;
        text-align: center;
    }

    .categories h3 {
        margin-top: 0;
        font-size: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #ccc;
    }

    .categories ul {
        list-style-type: none;
        padding: 0;
        margin: 0;
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
    }

    .categories li {
        padding: 12px 20px;
        border-right: 1px solid #ccc;
        flex: 1 1 20%;
    }

    .categories li:last-child {
        border-right: none;
    }

    .categories li a {
        text-decoration: none;
        color: #333;
        font-size: 16px;
        font-weight: bold;
        transition: background-color 0.3s ease;
        display: block;
        text-align: center;
    }

    .categories li:hover {
        background-color: #FFD700;
    }

    .categories li a:hover {
        background-color: #FFD700;
    }

    .icons {
        display: flex;
        justify-content: flex-start;
        flex-wrap: wrap;
        background-color: #ffffcc;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .icon {
        margin-right: 20px;
        margin-bottom: 20px;
        flex: 1 1 20%;
        text-align: center;
    }

    .icon img {
        max-width: 100%;
        height: 600px;
        border-radius: 5px;
    }