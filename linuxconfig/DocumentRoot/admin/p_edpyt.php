<?php
echo "Aktualnie wybrany test: <b> $test_ak </b><br/>";		
echo "<h1>Edytuj pytania</h1>";	
$jest = true;
//Edycja wybranego pytania
echo "<p style='color:red;font-weight:800;font-size:12px;'>";
if (isset($_GET['ed']))
{

	while ($jest == true)
		{
		$pytanie = $_GET['ed'];
		$z_pytanie = "select trescpyt from pytania where id='".$pytanie."';";
		$w_pytanie = mysql_query($z_pytanie)or die("Wystąpił błąd w bazie danych.");
		$ile_pytanie = mysql_num_rows($w_pytanie); 		
		$wie_pytanie = mysql_fetch_array($w_pytanie);	
					if (empty($testid))
					{
					echo "Błąd: Nie został wybrany test.<br/>";
					break;	
					}
					if ($ile_pytanie != 1)
					{
					echo "Błąd: Nie ma takiego pytania.<br/>";
					break;	
					}
		$z_ile_odp = "select tresc, prawid from odpowiedzi where nrpytid='".$pytanie."'  order by opcja;";
		$w_ile_odp = mysql_query($z_ile_odp)or die("Wystąpił błąd w bazie danych.");
		$ile_odp = mysql_num_rows($w_ile_odp); 		
		
					if ($ile_odp == 0)
					{
					echo "Błąd: Nie ma odpowiedzi do tego pytania.<br/>";
					break;	
					}
		
		$z_prawid = "select count(*) from odpowiedzi where nrpytid='".$pytanie."' and prawid='1';";
		$w_prawid = mysql_query($z_prawid)or die("Wystąpił błąd w bazie danych.");
		$prawid = mysql_fetch_array($w_prawid);
		
		echo "\n<form action='admin.php?id=edpyt&amp;sed=".$pytanie."' method='post'>";			
		echo "\n<table class='pytanie'>";	
		echo "\n<tr><td colspan='2' style='font-weight:800;font-size:16px;'>Pytanie</td></tr>";
		echo "\n<tr><td colspan='2'><input type='hidden' value='".$ile_odp."' name='end' id='end'/><input type='text' name='trescp' maxlength='200' size='80' value='".stripslashes($wie_pytanie[0])."'/></td></tr>";
		
				//Obrazek do pytania
				$z_fotki = "select name from fotki where nrpytid='".$pytanie."';";
				$w_fotki = mysql_query($z_fotki)or die("Wystąpił błąd w bazie danych.");
				$ile_fotki = mysql_num_rows($w_fotki); 	
				$fotki = mysql_fetch_array($w_fotki);
				if ($ile_fotki == 1)
				{
				echo "\n<tr><td colspan='2'><img src='../mini_galeria/".stripslashes($fotki[0])."' alt='".stripslashes($fotki[0])."'/></td></tr>";
				}
		echo "\n<tr><td colspan='2'>Ile prawidłowych?<input type='text' name='ilep' value='".$prawid[0]."' size='2' maxlength='2'/></td></tr>";		
		for ($i =1; $i <= $ile_odp; $i++)
		{
		$wie_ile_odp = mysql_fetch_array($w_ile_odp);	
		echo "\n<tr><td><input type='checkbox' name='p[]' value='".$i."'"; 
		
			if ($wie_ile_odp[1] == '1')
			echo " checked='checked' ";
			 
		echo"/></td>";
		echo "\n<td><input type='text' value='".stripslashes($wie_ile_odp[0])."' name='pp".$i."' maxlength='200' size='50'/></td></tr>";	
		}
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
				
				if (isset($_POST['end']) && $_POST['p'] && isset($_POST['ilep']) && $_POST['end'] > 0)
				{
				$ipo = $_POST['end'];
				$ilep = $_POST['ilep'];
				$prawidlowe = $_POST['p']; 
				$prawrzecz = count($prawidlowe);
				
					if ($prawrzecz != $ilep || $ilep<0 || $ilep >= $ipo)
					{
					echo "Błąd: Nieprawidłowe ustawienia ilości prawidłowych.<br/>";
					break;	
					}
					
				}
				else
				{
			    echo "Błąd: Brak pól odpowiedzi lub pola ilości prawidłowych.<br/>";
				break;	
				}	
				
				$pytanie = $_GET['sed'];
				
				$z_pytanie = "select count(*) from pytania where id='".$pytanie."';";
				$w_pytanie = mysql_query($z_pytanie)or die("Wystąpił błąd w bazie danych.");
				//$ile_pytanie = mysql_num_rows($w_pytanie); 	
				$ile_pyt = mysql_fetch_array($w_pytanie);
				if ($ile_pyt == 0)
				{
		        echo "Błąd: Brak pytania wybranego do edycji w bazie.<br/>";
				break;	
				}
					
				
		//Sprawdzanie dostępności pól odpowiedzi
	    for ($o=1; $o <=$ipo; $o++)
	    {
	    
	    		if(isset($_POST["pp$o"]) && trim($_POST["pp$o"]) != "")
	    		{
				$treco[$o] = trim($_POST["pp$o"]); 
	    		 		 
						 if (!get_magic_quotes_gpc())
			  			 {
			  			 	$treco[$o] = addslashes($treco[$o]);  
			  			 }
	
	    		}
	    		else
	    		{
	    		echo "Błąd: Nie podano treści opcji numer $o. <br/>";	
	    		break 2;
	    		}
	    }
				    if (isset($_POST['trescp']) && trim($_POST['trescp']) != "")
				    {
				    $trescp = trim($_POST['trescp']); 

				    	 if (!get_magic_quotes_gpc())
			  			 {
			  			 	$trescp = addslashes($trescp);  
			  			 }
				    }
				    else
				    {
				    echo "Błąd: Nie podano pytania.<br/>";	
				    break;	
				    }
	    
	    		    
				    //Dodawanie wszystkiego do bazy danych
				    $z_pytanie= "update pytania set trescpyt='".$trescp."', ileprawid='".$ilep."' where id='".$pytanie."'";
					$w_pytanie= mysql_query($z_pytanie)or die("1Wystąpił błąd w bazie danych.");
					
				    
				    //Opcje odpowiedzi
				    
					for ($o=1; $o <=$ipo; $o++)
				    {
					$z_odp= "update odpowiedzi set prawid='0', tresc='".$treco[$o]."' where nrpytid='".$pytanie."' and opcja='".$o."'";
					$w_odp= mysql_query($z_odp)or die("2Wystąpił błąd w bazie danych.");		
				    }
				    
				    //Oznaczanie prawidłowych odpowiedzi
				    for ($pr=0; $pr <$prawrzecz; $pr++)
				    {
				    
				    $z_odp= "update odpowiedzi set prawid='1' where nrpytid='".$pytanie."' and opcja='".$prawidlowe[$pr]."'";
					$w_odp= mysql_query($z_odp)or die("3Wystąpił błąd w bazie danych.");	    	
				    }
				    
				    
		echo "Edytowano prawidłowo pytanie o id=$pytanie.<br/>";	
		break;
		}
		
}

if (isset($_GET['del']))
{

	while ($jest == true)
		{
		$pytanie = $_GET['del'];
		$z_pyt = "delete from pytania where id='".$pytanie."' ;";
        $w_pyt = mysql_query($z_pyt) or die("Wystąpił błąd w bazie danych.");
        
        $z_odp = "delete from odpowiedzi where nrpytid='".$pytanie."' ;";
        $w_odp = mysql_query($z_odp) or die("Wystąpił błąd w bazie danych.");
        
		$z_fotki = "select name from fotki where nrpytid='".$pytanie."';";
		$w_fotki = mysql_query($z_fotki)or die("Wystąpił błąd w bazie danych.");
		$ile_fotki = mysql_num_rows($w_fotki); 	
		$fotki = mysql_fetch_array($w_fotki);
				if ($ile_fotki == 1)
				{
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
				
        
        

		echo "Usunięto prawidłowo pytanie o id=$pytanie.<br/>";	
		break;
		}
		
}		
echo "</p>";
//Wyświetlanie listy pytań
while ($jest == true)
{
			if (empty($testid))
			{
			echo "Błąd: Nie został wybrany test.<br/>";
			break;	
			}
			
$nazwa = "edpyt";
$zapytanie = "select count(*) from pytania where testid='".$testid."'";
$ile_strona=50;
$poczatek = strony($nazwa, $zapytanie, $ile_strona);
$z_pyt = "select  id, trescpyt from pytania where testid='".$testid."' order by id limit $poczatek, $ile_strona;";
$w_pyt = mysql_query($z_pyt)or die("Wystąpił błąd w bazie danych.");
$ile_pyt = mysql_num_rows($w_pyt);
echo "\n<table class='pytanie'>";
	for ($i =1; $i<=$ile_pyt; $i++)
	{
	$wiersz_pyt = mysql_fetch_array($w_pyt);
	echo "\n<tr><td style='font-weight:800'>ID: ";
	echo stripslashes($wiersz_pyt[0]);
	echo "</td><td style='width:260px;'>";
	echo stripslashes($wiersz_pyt[1]);
	echo "</td>";
	echo "\n<td><a href='admin.php?id=edpyt&amp;ed=";
	echo stripslashes($wiersz_pyt[0]);
	echo "'/>Edytuj</a></td>";
	echo "\n<td><a href='admin.php?id=edpyt&amp;del=";
	echo stripslashes($wiersz_pyt[0]);
	echo "'/>Usuń</a></td></tr>";
	}
echo "\n</table>";	
break;
}	
?>