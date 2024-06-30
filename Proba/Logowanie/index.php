<?php
session_start();

if ((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany'] == true)) {
	header('Location:stronag.php');
	exit();
}
?>
<!DOCTYPE HTML>
<html lang="pl">

<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Spożywczak - sklep online</title>
	
</head>

<body>

	<h1>Sklep online 24/7</h1>

	<p>Witamy w naszym sklepie online kupisz tutaj wszystkie produkty o ktorych ci sie tylko marzy</p>

	<form action="zaloguj.php" method="post">
		Login: <br />
		<input type="text" name="login" /><br />
		Hasło: <br />
		<input type="password" name="haslo" /><br /><br />

		<input type="submit" value="Zaloguj się" />
	</form>

	<form action="rejestracja.php" method="post">
		<input type="submit" value="Zarejestruj się" />
	</form>

	<a href="strona/stronag.php" class="continue-button">Kontynuuj bez logowania</a>
	

	<?php
	if (isset($_SESSION['blad'])) {
		echo '<div class="error-msg">' . $_SESSION['blad'] . '</div>';
	}
	?>

</body>

</html>
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

		.error-msg {
			color: red;
			margin-top: 10px;
		}

		.continue-button {
			display: block;
			width: 300px;
			margin: 20px auto;
			padding: 10px;
			background-color: #007bff;
			color: white;
			text-decoration: none;
			text-align: center;
			border-radius: 4px;
			box-sizing: border-box;
		}

		.continue-button:hover {
			background-color: #0056b3;
		}
	</style>