<?php
session_start();

require('config.php');
@$connection= @mysql_connect("$dbhost", "$dblogin", "$dbpass")
or die(' Brak połączenia z serwerem MySQL.<br />Błąd: '.mysql_error());
$db = @mysql_select_db("$dbbase", $connection)
or die(' Nie mogę połączyć się z bazą danych<br />Błąd: '.mysql_error());
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" >
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<link rel="Stylesheet" type="text/css" href="style.css"/>
<link rel="stylesheet" href="lajt/css/lightbox.css" type="text/css" media="screen" />
<script type="text/javascript" src="lajt/js/prototype.js"></script>
<script type="text/javascript" src="lajt/js/scriptaculous.js?load=effects,builder"></script>
<script type="text/javascript" src="lajt/js/lightbox.js"></script>
<title>Testy</title>
</head>
<body>

<?php
$jest = true;

if (isset($_SESSION['uczen']))
{
while ($jest == true)
{
$dane = $_SESSION['uczen'];
$segment = explode('&', $dane);
$mail = $segment[0];
$testid = $segment[1];
$imie = $segment[2];
$nazwisko = $segment[3];
$klasa = $segment[4];
		if (count($segment) != 6)
		{
		unset($_SESSION['uczen']);
		session_destroy();	
		break;
		}
		$z_pytan = "select count(*) from odpowiedzi where testid='".$testid."';";
		
		$w_pytan = mysql_query($z_pytan)or die("Wystąpił błąd w bazie danych.");
		$pytan = mysql_fetch_array($w_pytan); 	
		
		if ($pytan[0] == 0)
		{
		echo '<div id="loguj">';
		echo "Błąd: Brak pytań w tym teście.<br/>";
		echo '</div>';
		unset($_SESSION['uczen']);
		session_destroy();
		break;
		}
echo "<div id='glowne'>";
echo "<form action='save_test.php' method='post'>";
echo "<div id='user'>";
//echo "<b>Twój e-mail:</b> $mail <br/>";
echo "<b>Imię:</b> $imie <br/>";
echo "<b>Nazwisko:</b> $nazwisko <br/>";
echo "<b>Klasa:</b> $klasa <br/>";
echo "</div><div id='test'>";

		$z_pytania = "select id, trescpyt, ileprawid from pytania where testid='".$testid."' order by rand();";
		
		$w_pytania = mysql_query($z_pytania)or die("Wystąpił błąd w bazie danych.");
		$ile_pytania = mysql_num_rows($w_pytania);
		for ($i=1; $i<=$ile_pytania; $i++)
		{
		$wiersz_p = mysql_fetch_array($w_pytania); 	
		$pytanie = $wiersz_p[0];
			echo "\n<table class='pytanie'>";
			echo "\n<tr><td colspan='2' style='font-weight:800;font-size:16px;'>Pytanie: $i</td></tr>";
			echo "\n<tr><td colspan='2'>"; 
			echo stripslashes($wiersz_p[1]);
			echo"</td></tr>";
				//Obrazek do pytania
				$z_fotki = "select name from fotki where nrpytid='".$pytanie."';";
				$w_fotki = mysql_query($z_fotki)or die("Wystąpił błąd w bazie danych.");
				$ile_fotki = mysql_num_rows($w_fotki); 	
				$fotki = mysql_fetch_array($w_fotki);
				if ($ile_fotki == 1)
				{
				$plik = "galeria/$fotki[0]";
				$plik_mini = "mini_galeria/$fotki[0]";
				echo "<tr><td colspan='2'>\n<a href='".$plik."' rel=".'"lightbox"'." title='".stripslashes($wiersz_p[0])." - ".stripslashes($wiersz_p[1])."' style='border:0px'><img  src='".$plik_mini."' style='border:1px solid black;margin:0px;padding:0px;' alt='Brak obrazka' /></a>";
				echo "\n</td></tr>";
				}
				    
					//Odpowiedzi
					$z_odp = "select tresc, opcja from odpowiedzi where nrpytid='".$pytanie."' order by rand();";
					$w_odp = mysql_query($z_odp)or die("Wystąpił błąd w bazie danych.");
					$ile_odp = mysql_num_rows($w_odp); 	
					for ($o = 1; $o<=$ile_odp; $o++)
					{
					$odp = mysql_fetch_array($w_odp);
					echo "\n<tr><td style='width:30px'><input type='checkbox' name='p".$pytanie."[]' value='".$odp[1]."'/></td>";
					echo "\n<td>".stripslashes($odp[0])."</td></tr>";
					}

			echo "\n<tr><td colspan='2'>Prawidłowych odpowiedzi:"; 
			echo stripslashes($wiersz_p[2]);
			echo"</td></tr>";
			echo "\n</table>";
		}
		
echo "<input type='submit' value='Wyślij!' />";
echo "</div></form></div>";
break;
}
}
else
{
echo '<div id="loguj">';
echo "Błąd: Nie jesteś zalogowany by móc rozwiązywać ten test.";
echo '</div>';

}
?>	

</body>
</html>
