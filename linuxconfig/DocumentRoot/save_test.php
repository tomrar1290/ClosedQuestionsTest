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
		if (count($segment) != 6)
		{
		unset($_SESSION['uczen']);
		session_destroy();	
		break;
		}
$mail = $segment[0];
$testid = $segment[1];
$imie = $segment[2];
$nazwisko = $segment[3];
$klasa = $segment[4];


	 $z_test= "select name from test where testid='".$testid."';";
     $w_test = mysql_query($z_test)or die("Wystąpił błąd w bazie danych.");
	 $ile_test = mysql_num_rows($w_test); 		
	 $wie_test = mysql_fetch_array($w_test);
		 
	 if ($ile_test != 1)
	 {
	 echo "Błąd: Podane hasło nie pasuje.<br/>";
	 echo "Kliknij <a href='index.php'>tutaj</a>, aby powrócić do logowania.<br/>";
	 break;		
	 }
	 $nazwa_test = $wie_test[0];
	 
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
echo "<div id='user'>";
//echo "<b>Twój e-mail:</b> $mail <br/>";
echo "<b>Imię:</b> $imie <br/>";
echo "<b>Nazwisko:</b> $nazwisko <br/>";
echo "<b>Klasa:</b> $klasa <br/>";
echo "</div><div id='test'>";

//echo "\n<tr><td>";
		$z_pytania = "select id, trescpyt, ileprawid from pytania where testid='".$testid."' order by id;";
		
		$w_pytania = mysql_query($z_pytania)or die("Wystąpił błąd w bazie danych.");
		$ile_pytania = mysql_num_rows($w_pytania);
		$razem = 0;
		$maxrazem = 0;
		for ($i=1; $i<=$ile_pytania; $i++)
		{
		$wiersz_p = mysql_fetch_array($w_pytania); 	
		$pytanie = $wiersz_p[0];
		$prawid = $wiersz_p[2];
		$tresc =  $wiersz_p[1];
	    $suma[$i] = 0;
	    
	    $max[$i] = $prawid;
	    
		echo "<br/><b>Pytanie $i: $tresc</b><br/>"; 
				if (isset($_POST["p$pytanie"]))
				{
				$odp[$i] =  $_POST["p$pytanie"];
				
				$ile_z = count($odp[$i]);
				     if ($ile_z <= $prawid)
				     {       
							for ($o = 0; $o<$ile_z; $o++)
							{
							//Odpowiedzi
							$z_odp = "select prawid from odpowiedzi where nrpytid='".$pytanie."' and opcja='".$odp[$i][$o]."';";
							$w_odp = mysql_query($z_odp)or die("Wystąpił błąd w bazie danych.");
							$ile_odp = mysql_num_rows($w_odp); 	
							$wie_odp = mysql_fetch_array($w_odp);
							  if ($ile_odp > 1)
							  {
							  	echo "Błąd w bazie danych. Zgłoś to! Pytanie $i. Opcja $odp[$i][$o]";
							  	break 2;
							  }
							  if ( $wie_odp[0] == 1)
							  {
							  $suma[$i] +=1;	  
							  }
							}	
						
				     }
				     else
				     {
					  echo "Liczba zaznaczonych odpowiedzi jest większa niż dozwolona.<br/>";					     		
				     }
					
				}
				else
				{
				echo "Przy tym pytaniu nie zaznaczono żadnych odpowiedzi.<br/>";	
					
				}

		 echo "Za to pytanie masz ".$suma[$i]."/".$max[$i]." punktów.<br/>";
		 $razem += $suma[$i];	
		 $maxrazem += $max[$i];		    
		}

for ($w=1; $w<=$ile_pytania; $w++)
{
$wynik[$w] = $suma[$w]."/".$max[$w];
$odp[$w] = "<b>Pytanie ".$w."</b>: ".$wynik[$w];
}


$wyniki = implode('&', $wynik);
$wynik_mail = implode('<br/>', $odp);
$maxymalnie = $razem."/".$maxrazem;
$procent = ($razem * 100) / $maxrazem;
$procent = round($procent,2);
$wynik_mail .= "<br/><br/><b>Razem $maxymalnie - $procent%</b>";
			  if (!get_magic_quotes_gpc())
			  {  
			    $maxymalnie = addslashes($maxymalnie);
			    $wyniki = addslashes($wyniki);
			  }

			  
$dane = $_SESSION['uczen'];
$segment = explode('&', $dane);

$segment[6] = date('d.m.Y H:i:s');
$segment[7] = $_SERVER['REMOTE_ADDR'];
if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
$segment[8] = $_SERVER['HTTP_X_FORWARDED_FOR'];
else
$segment[8] = '127.0.0.1';
$segment[9] = "$nazwa_test";

$zapis_dane = implode('&', $segment);
$z_wynik = "insert into wyniki (uczen, tresc, razem) values ('".$zapis_dane."', '".$wyniki."', '".$maxymalnie."');";
$w_wynik = mysql_query($z_wynik)or die("Wystąpił błąd w bazie danych.");
//mail z odpowiedziami
$rozp = $segment[5];
$zak = $segment[6];
$ip = $segment[7];
$wew = $segment[8];
$temat = "Wyniki $imie $nazwisko $klasa";
$temat .= ", Pkt: $maxymalnie";
$wynik_mail = "Witam, oto wyniki z testu <b>$nazwa_test</b><br/>należą one do <b>$imie $nazwisko $klasa</b><br/><br/></br/>$wynik_mail";
$wynik_mail .= "<br/><br/> Data rozpoczęcia testu: $rozp<br/>";
$wynik_mail .= "Data zakończenia testu: $zak<br/>";
$wynik_mail .= "IP zew: $ip<br/>IP wew: $wew";
$wynik_mail .= "<br/><br/>-----<br/>Pozdrawiam i życzę miłego dnia :)";

//echo "WYNIKI MAIL <br/>";
//echo $temat;
//echo "<br/>";
//echo $wynik_mail;
$naglowki  = "MIME-Version: 1.0\r\n";
$naglowki .= "Content-type: text/html; charset=utf-8\r\n";

$jest = true;
while ($jest == true)
{
	
		$z_test = "select mail from test where testid='".$testid."';";
		$w_test = mysql_query($z_test)or die("Wystąpił błąd w bazie danych.");
		$ile_test = mysql_num_rows($w_test); 		
			
		
		if ($ile_test == 0)
		{
		echo "Błąd: Nie ma takiego testu.<br/>";
		break;	
		}
		
		$wie_test = mysql_fetch_array($w_test);	
		
		if($wie_test[0] != "")
		{
		$mail=$wie_test[0];	
		}
		else
		{
			@ $file = fopen("admin/config.txt", "r");
			if (!$file)
			{
			break;
			}
			$zawartosc = fread($file, 200);
			fclose($file);
			$dane = explode(':', "$zawartosc");	
			$wynik_mail .= "<br/><br/>P.S. Wyniki wysłane na email administratora.<br/>Email w teście nie został podany.";
			$mail = $dane[2];	
		}
		
mail("$mail", "$temat", "$wynik_mail", "$naglowki");



break;
}
		
unset($_SESSION['uczen']);
session_destroy();
echo "<br/><br/><b>Łącznie uzyskałaś/eś ".$maxymalnie." punktów.</b><br/>";
echo "</div></div>";
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
