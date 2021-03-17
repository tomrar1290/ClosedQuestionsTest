<?php
echo "<h1>Ustawienia</h1>";	

if (isset($_GET['e']) && $_GET['e'] == 1)
{
$jest = true;
while ($jest == true)
{
			@ $file = fopen("config.txt", "r");
			if (!$file)
			{
			echo "<p class=blad> Błąd dostępu do pliku!! Sorry!!</p>";
			exit;
			}
			$zawartosc = fread($file, 200);
			fclose($file);
			$dane = explode(':', "$zawartosc");
			if (empty($_POST['login']))
			{
			echo "Nie podano loginu!";
			break;	
			}
			if (empty($_POST['email']))
			{
			echo "Nie podano adresu E-MAIL!";
			break;	
			}
			$login = $_POST['login'];
			$email = $_POST['email'];
			//Hasło panel
			 if ( empty($_POST['nowe']) || empty($_POST['nowep']) )
			 {
			 $pass = $dane[1];
			 }
			 else
			 {
			   if ( $_POST['nowe'] == $_POST['nowep'] )
			   {
			   $pass = sha1($_POST['nowe']);
			   }
			   else
			   {
			   echo "<p class=blad>Hasła do panelu admina nie sa takie same!!";
			   break;
			   }
			 }
			
			$ciagwyjsciowy = $login.':'.$pass.':'.$email;
			@ $fp = fopen("config.txt", 'w');
			if (!$fp)
			{
			echo "Błąd dostępu do pliku!!";
			exit;
			}
			fputs($fp, $ciagwyjsciowy, strlen($ciagwyjsciowy));
			fclose($fp);
			echo "Zapisano pomyślnie!!";
	
break;	
}
	
}
@ $file = fopen("config.txt", "r");
if (!$file)
{
echo "<p class=blad> Błąd dostępu do pliku!! Sorry!!</p>";
exit;
}
 $zawartosc = fread($file, 200);
 fclose($file);
 $dane = explode(':', "$zawartosc");
 echo "<form action='admin.php?id=set&amp;e=1' method='post'>";
  echo "<table><tr><td>Login do panelu admina</td><td>\n";
 echo "<input type=text name=login size=20 maxlength=20 value='".$dane[0]."'></td></tr>\n";
 echo "<tr><td colspan='2' style='font-weight:700;'>Hasło do panelu admina</td></tr>\n";
 echo "<tr><td>Nowe:</td><td> <input type=password name=nowe size=20 maxlength=20> </td></tr> \n";
 echo "<tr><td>Powtórz:</td><td> <input type=password name=nowep size=20 maxlength=20></td></tr>\n";
 echo "<tr><td>E-Mail administratora</td>\n";
 echo "<td><input type=text name=email size=30 maxlength=30 value='".$dane[2]."'></td></tr>";
 echo "<tr><td colspan='2'><input type='submit' value='Zapisz'/></td></tr>";
 echo "</table></form>\n";
?>