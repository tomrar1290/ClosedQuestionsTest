<?php
if (isset($_GET['ed']) && $_GET['ed'] == 1)
{
echo "<p style='color:red;font-weight:800;font-size:12px;'>";
$jest = true;
		while ($jest == true)
		{
			
			//Sprawdzanie czy jest wybrany test
			if (empty($testid))
			{
			echo "Błąd: Nie został wybrany test.<br/>";
			break;	
			}
			
			//Ustawienia ilości pól odpowiedzi i zgodności ilo,ści prawidłowych z wcześniejszym zadeklarowaniem
			if (isset($_POST['end']) && isset($_POST['p']) && isset($_POST['ilep']) && $_POST['end'] > 0)
			{
			$ipo = $_POST['end'];
			$ilep = $_POST['ilep'];
			$prawidlowe =  $_POST['p'];
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
			
		
			//Dodawanie pliku grafiki
	        	if(isset($_POST['pliczek']) && $_POST['pliczek'] == 1)
	        	{
								if ($_FILES['plik']['error'] > 0)
								  {
								    echo 'Problem: ';
								    
								    switch ($_FILES['plik']['error'])
								    {
								      case 1: echo 'Rozmiar pliku przekroczył wartość upload_max_filesize'; break;
								      case 2: echo 'Rozmiar pliku przekroczył wartość max_file_size'; break;
								      case 3: echo 'Plik wysłany tylko częściowo'; break;
								      case 4: echo 'Nie wysłano żadnego pliku'; break;
								    }
								    break;
								  }
								
								   			$error = $_FILES['plik']['error'];			
											$p_pojemnosc=$_FILES['plik']['size'];//pojemnosc pliku
											$p_typ=$_FILES['plik']['type']; // typ pliku
											$p_nazwa=$_FILES['plik']['name']; // nazwa pliku
											$zrodlo=$_FILES['plik']['tmp_name']; // chwilowa nazwa pliku
															
								  //sprawdzamy typ pliku
									if ($p_typ=='image/jpeg')
									{ $rozsz='jpg'; }
								    elseif ($p_typ=='image/png')
								    { $rozsz='gif'; }
                                    else 
                                    { echo 'Błąd: Niepoprawny format obrazu.<br/>'; break;}
							                if (isset($rozsz))
							                {
		
											$data = date("jmYGis");
											$numer = "$data";
											chdir('../');
											$katalog = getcwd();
											$docelowa = $katalog."//galeria//".$numer.".$rozsz";
											$docelowa_mini = $katalog."//mini_galeria//".$numer.".$rozsz";
											$plik = $numer.".".$rozsz;
											
									        //Docelowa ścieżka plików
									        
											if (is_uploaded_file($zrodlo))
											  {
											     if (!move_uploaded_file($zrodlo, $docelowa))
											     {
											        echo 'Problem: Plik nie może być skopiowany do katalogu.<br/>';
											        break;
											     }
											  }
											  else
											  {
											    echo 'Problem: możliwy atak podczas wysyłania pliku. Nazwa pliku:';
											    
											    echo "<br/>";
											    break;
											  } 
									  		  
											  //Tworzenie miniaturki
											  if ($rozsz=='jpg') $img=imagecreatefromjpeg($docelowa);
							                  elseif ($rozsz=='png') $img=imagecreatefrompng($docelowa);
							                  $x = imagesx($img);
							                  $y = imagesy($img);
							                  
								                  if($x > $y){
								                        $nx = 400;
								                        $ny = 400 * ($y / $x);
								
								                  }
								                  elseif($y > $x){
								                            $nx = 400 * ($x / $y);
								                            $ny = 400;
								                  }
								                 else
								                  {
								                       $nx = 400;
								                       $ny = 400;
												  }
								                  if ($x <= 400 && $y<= 400 )
								                  {
								                  	$nx = $x;
								                  	$ny = $y;
								                  }
						
							                  $new_img = imagecreatetruecolor($nx, $ny);
							                  imagecopyresampled($new_img, $img, 0, 0, 0, 0, $nx, $ny, $x, $y);
							                  if ($rozsz=='jpg') imagejpeg($new_img, $docelowa_mini);
											  elseif ($rozsz=='png') imagepng($new_img, $docelowa_mini);
											  $pliczek = $plik;
							                  }
							                  else
							                  {
							                  echo "Błąd: Nieustawione rozszerzenie pliku.<br/>";
							                  break;	
							                  }
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
	    $z_pytanie= "INSERT INTO pytania (testid, id, trescpyt, ileprawid) VALUES('".$testid."', NULL, '".$trescp."', '".$ilep."');";
		$w_pytanie= mysql_query($z_pytanie)or die("Wystąpił błąd w bazie danych.");
		$id =  mysql_insert_id();
		
		//Plik jeśli ustawiony
	    if (isset($pliczek ))
	    {
	    	
	    $z_plik= "insert INTO fotki (name, nrpytid, testid) VALUES('".$pliczek."', '".$id."', '".$testid."');";
		$w_plik= mysql_query($z_plik)or die("Wystąpił błąd w bazie danych.");	
	    	
	    }
	    
	    //Opcje odpowiedzi
	    
		for ($o=1; $o <=$ipo; $o++)
	    {
		$z_odp= "INSERT INTO odpowiedzi (testid, nrpytid, opcja, prawid, tresc) VALUES('".$testid."', '".$id."', '".$o."', '0', '".$treco[$o]."');";
		$w_odp= mysql_query($z_odp)or die("Wystąpił błąd w bazie danych.");		
	    }
	    
	    //Oznaczanie prawidłowych odpowiedzi
	    for ($pr=0; $pr <$prawrzecz; $pr++)
	    {
	    $z_odp= "update odpowiedzi set prawid='1' where nrpytid='".$id."' and opcja='".$prawidlowe[$pr]."'";
		$w_odp= mysql_query($z_odp)or die("Wystąpił błąd w bazie danych.");	    	
	    }
	    
	    
		echo "Dodano wszystko prawidłowo.<br/>";
		break;			
     	}
     	
echo "</p>";
}

echo "Aktualnie wybrany test: <b> $test_ak </b><br/>";		
?>
<h1>Dodaj pytanie</h1>
			<form name="addpyt" action='admin.php?id=addpyt&amp;ed=1' method='post' enctype='multipart/form-data'>
			<table class='pytanie'>
			
			<tr><td colspan='2' style='font-weight:800;font-size:16px;'>Pytanie</td></tr>
			<tr><td colspan='2'><input type='hidden' value='5' name='end' id='end'/><input type="text" name="trescp" maxlength="200" size="80"/></td></tr>
			<tr><td colspan='2' style='font-weight:800;font-size:12px;'>Obrazek, zaznacz jeśli potrzebujesz (jpeg/png): <input type="checkbox" name="pliczek" value="1" onclick="Check()"/></td></tr>
			<tr><td colspan='2'><input type="file" name="plik" disabled="disabled"/></td></tr>
			<tr><td colspan='2'>Ile prawidłowych?
					 <input type="text" name="ilep" value="1" size="2" maxlength="2"/>
			</td></tr>
				<tr><td colspan='2'>Ile opcji odpowiedzi?</td></tr>
				<tr><td><button type="button" onclick="Dodaj()">Dodaj</button></td><td><button type="button"  onclick="Usun()">Usuń</button>
				</td></tr>
			<tr><td colspan='2'>
			<table id='polao'>			
			<tr><td><input type="checkbox" name="p[]" value="1"/></td>
			<td><input type="text" name="pp1" maxlength="200" size="50"/></td></tr>
			<tr><td><input type="checkbox" name="p[]" value="2"/></td>
			<td><input type="text" name="pp2" maxlength="200" size="50"/></td></tr>
			<tr><td><input type="checkbox" name="p[]" value="3"/></td>
			<td><input type="text" name="pp3" maxlength="200" size="50"/></td></tr>
			<tr><td><input type="checkbox" name="p[]" value="4"/></td>
			<td><input type="text" name="pp4" maxlength="200" size="50"/></td></tr>
			<tr><td><input type="checkbox" name="p[]" value="5"/></td>
			<td><input type="text" name="pp5" maxlength="200" size="50"/></td></tr>
			</table>
			</td></tr>
			<tr><td colspan='2'><input type='submit' value='Dodaj' /></td></tr>
			</table>
			</form>
