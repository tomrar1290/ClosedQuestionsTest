<?php	
echo "<h1>Edytuj testy</h1>";	
$jest = true;
//Edycja wybranego pytania
echo "<p style='color:red;font-weight:800;font-size:12px;'>";
if (isset($_GET['ed']))
{

	while ($jest == true)
		{
		$test = $_GET['ed'];
		$z_test = "select name, pass, mail from test where testid='".$test."';";
		$w_test = mysql_query($z_test)or die("Wystąpił błąd w bazie danych.");
		$ile_test = mysql_num_rows($w_test); 		
			
		
					if ($ile_test == 0)
					{
					echo "Błąd: Nie ma takiego testu.<br/>";
					break;	
					}
		
		$wie_test = mysql_fetch_array($w_test);	 	
		
		echo "\n<form action='admin.php?id=edtest&amp;sed=".$test."' method='post'>";			
		echo "\n<table class='pytanie'>";	
		echo "\n<tr><td colspan='2' style='font-weight:800;font-size:14px;'>Nazwa nowego testu</td></tr>";
		echo "\n<tr><td colspan='2'><input type='text' name='nazwa' maxlength='30' size='30' value='".$wie_test[0]."'/></td></tr>";
		echo "\n<tr><td colspan='2' style='font-weight:800;font-size:14px;'>Hasło do nowego testu</td></tr>";
		echo "\n<tr><td colspan='2'><input type='text' name='pass' maxlength='30' size='30' value='".$wie_test[1]."'/></td></tr>";	
		echo "\n<tr><td colspan='2' style='font-weight:800;font-size:14px;'>Email do wysyłania rozwiązań</td></tr>";
		echo "\n<tr><td colspan='2'><input type='text' name='mail' maxlength='30' size='30' value='".$wie_test[2]."'/></td></tr>";	
		echo "\n<tr><td colspan='2'><input type='submit' value='Zapisz' /></td></tr>";			
		echo "\n</table>";
		echo "\n</form>";
		break;
		}	
}


//Usuwanie wybranego pytania
if (isset($_GET['sed']))
{

	while ($jest == true)
		{
				
					$test = $_GET['sed'];
					if (isset($_POST['nazwa']) && trim($_POST['nazwa']) != "")
				    {
				    $nazwa = trim($_POST['nazwa']); 			
				    }
				    else
				    {
				    echo "Błąd: Nie podano nazwy.<br/>";	
				    break;	
				    }
				    
					if (isset($_POST['pass']) && trim($_POST['pass']) != "")
				    {
				    $pass = trim($_POST['pass']); 			
				    }
				    else
				    {
				    echo "Błąd: Nie podano hasła.<br/>";	
				    break;	
				    }
				    
					$z_test = "select pass from test where pass='".$pass."' and testid!='".$test."';";
					$w_test = mysql_query($z_test)or die("Wystąpił błąd w bazie danych.");
					$ile = mysql_num_rows($w_test); 
					
					if ($ile == 1)
					{
					echo "Błąd: W bazie istnieje już takie hasło.<br/>";	
					break;
					}
				    
				    if (isset($_POST['mail']) && trim($_POST['mail']) != "")
				    {
					 $mail = $_POST['mail']; 
					 $part = explode('@', $mail);
					 if (count($part) != 2)
					 {
					 echo "Błąd: Podany e-mail jest nie prawidłowy.<br/>";
					 break;	 
					 }
				    }
				    else
				    {
				    echo "Błąd: Nie podano adresu email.<br/>";	
				    break;	
				    }
			 
		
				    
				
					
								
					
		$z_test = "update test set name='".$nazwa."', pass='".$pass."', mail='".$mail."' where testid='".$test."';";
		$w_test = mysql_query($z_test)or die("Wystąpił błąd w bazie danych.");
				    
				    
		echo "Edytowano prawidłowo test o id=$test.<br/>";	
		break;
		}
		
}

if (isset($_GET['del']))
{

	while ($jest == true)
		{
		$test = $_GET['del'];
		

		$z_pyt = "delete from pytania where testid='".$test."' ;";
        $w_pyt = mysql_query($z_pyt) or die("Wystąpił błąd w bazie danych.");
        
        $z_odp = "delete from odpowiedzi where testid='".$test."' ;";
        $w_odp = mysql_query($z_odp) or die("Wystąpił błąd w bazie danych.");
        
        $z_test = "delete from test where testid='".$test."' ;";
        $w_test = mysql_query($z_test) or die("Wystąpił błąd w bazie danych.");
        
		$z_fotki = "select name from fotki where testid='".$test."';";
		$w_fotki = mysql_query($z_fotki)or die("Wystąpił błąd w bazie danych.");
		$ile_fotki = mysql_num_rows($w_fotki); 	
		
				for($i=1;$i<=$ile_fotki;$i++)
				{
				 $fotki = mysql_fetch_array($w_fotki);
				 $fota = stripslashes($fotki[0]);
				 chdir('../');
				 $katalog = getcwd();
				 $plik = $katalog."//galeria//$fota";
                 $plik_mini = $katalog."//mini_galeria//$fota";
                 
                 if (file_exists($plik))
				 unlink($plik);
				 else
				 echo "Plik $plik_mini nie istnieje.<br/>";	
                 
				 if (file_exists($plik_mini))
				 unlink($plik_mini);
				 else
				 echo "Plik $plik_mini nie istnieje.<br/>";
				 }
				
        $z_fota = "delete from fotki where testid='".$test."' ;";
        $w_fota = mysql_query($z_fota) or die("Wystąpił błąd w bazie danych.");
        

		echo "Usunięto prawidłowo test o id=$test.<br/>";	
		break;
		}
		
}		
echo "</p>";
//Wyświetlanie listy testów
while ($jest == true)
{
$nazwa = "edtest";
$zapytanie = "select count(*) from test";
$ile_strona=50;
$poczatek = strony($nazwa, $zapytanie, $ile_strona);
$z_tescik = "select testid, name from test order by testid desc limit $poczatek, $ile_strona;";
$w_tescik = mysql_query($z_tescik)or die("Wystąpił błąd w bazie danych.");
$ile_tescik = mysql_num_rows($w_tescik);
echo "\n<table class='pytanie'>";
	for ($i =1; $i<=$ile_tescik; $i++)
	{
	$wiersz_tescik = mysql_fetch_array($w_tescik);
	echo "\n<tr><td style='width:300px;'>";
	echo stripslashes($wiersz_tescik[1]);
	echo "</td>";
	echo "\n<td><a href='admin.php?id=edtest&amp;ed=";
	echo stripslashes($wiersz_tescik[0]);
	echo "'/>Edytuj</a></td>";
	echo "\n<td><a href='admin.php?id=edtest&amp;del=";
	echo stripslashes($wiersz_tescik[0]);
	echo "'/>Usuń</a></td></tr>";
	}
echo "\n</table>";	
break;
}	
?>