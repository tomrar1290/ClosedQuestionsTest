<?php
if (isset($_GET['ed']) && $_GET['ed'] == 1)
{
echo "<p style='color:red;font-weight:800;font-size:12px;'>";
$jest = true;

		while ($jest == true)
		{

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
				    echo "Błąd: Nie podano pytania.<br/>";	
				    break;	
				    }
				    
				    $z_test = "select pass from test where pass='".$pass."';";
					$w_test = mysql_query($z_test)or die("Wystąpił błąd w bazie danych.");
					$ile = mysql_num_rows($w_test); 
					
					if ($ile > 0)
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
					
					
		$z_test = "insert into test (testid, name, pass, mail) values (NULL, '".$nazwa."', '".$pass."', '".$mail."');";
		$w_test = mysql_query($z_test)or die("Wystąpił błąd w bazie danych.");			
		
		echo "Dodano wszystko prawidłowo.<br/>";
		break;			
     	}
     	
echo "</p>";
}


?>
<h1>Dodaj test</h1>
			<form action='admin.php?id=addtest&amp;ed=1' method='post' enctype='multipart/form-data'>
			<table class='pytanie'>
			
			<tr><td colspan='2' style='font-weight:800;font-size:14px;'>Nazwa nowego testu</td></tr>
			<tr><td colspan='2'><input type="text" name="nazwa" maxlength="30" size="30"/></td></tr>
			<tr><td colspan='2' style='font-weight:800;font-size:14px;'>Hasło do nowego testu</td></tr>
			<tr><td colspan='2'><input type="text" name="pass" maxlength="30" size="30"/></td></tr>
			<tr><td colspan='2' style='font-weight:800;font-size:14px;'>Email do wysyłania rozwiązań</td></tr>
			<tr><td colspan='2'><input type="text" name="mail" maxlength="30" size="30"/></td></tr>
			<tr><td colspan='2'><input type='submit' value='Dodaj' /></td></tr>
			</table>
			</form>
