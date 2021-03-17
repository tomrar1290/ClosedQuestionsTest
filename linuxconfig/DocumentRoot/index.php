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
<div id="loguj">
<?php
function imie($tekst)
{
return preg_match('/^[a-zA-ZąćęłńóśźżĄĆĘŁŃÓŚŹŻ]+$/', $tekst);
}

function nazwisko($tekst)
{
return preg_match('/^[a-zA-ZąćęłńóśźżĄĆĘŁŃÓŚŹŻ\-]+$/', $tekst);
}

function grupa($tekst)
{
return preg_match('/^[a-z0-9\/]+$/', $tekst);
}

if (isset($_SESSION['uczen']))
{
 	 $adres = "rozwiaz.php";
     //echo "<script type='text/javascript'>location = '".$adres."';</script>";
     echo "Kliknij <a href='rozwiaz.php'>tutaj</a>, aby zacząć rozwiązywać test.<br/>";

}
else
{

if (empty($_POST['imie']) || empty($_POST['pass']) || empty($_POST['nazwisko']) || empty($_POST['klasa']))
{
?>

<form action="index.php" method="post">
<table>
<tr><td colspan='2' style="text-align:center;font-size:18px;font-weight:800;">TEST</td></tr>
<tr><td colspan='2' style="text-align:center;font-size:10px;padding-bottom:10px">ZALOGUJ SIĘ BY MÓC ROZWIĄZAĆ!</td></tr>
<tr><td>Hasło do testu:</td>
<td><input type="password" name="pass" maxlength="20" size="15" /></td></tr>
<tr><td>Imię</td>
<td><input type="text" name="imie" maxlength="20" size="15"/></td></tr>
<tr><td>Nazwisko</td>
<td><input type="text" name="nazwisko" maxlength="20" size="15"/></td></tr>
<tr><td>Klasa/Grupa:</td>
<td><input type="text" name="klasa" maxlength="8" size="6"/></td></tr>
<tr><td>E-mail*</td>
<td><input type="text" name="mail" maxlength="30" size="15"/></td></tr>
<tr><td colspan="2">*nieobowiązkowy do podania</td></tr>
<tr><td colspan="2" style="text-align:center;"><input type="submit" value="Zaloguj!" /></td></tr>
</table>
</form>

<?php
}
else {

	$jest = true;
	while($jest == true)
	{
		if (isset($_POST['mail']) && $_POST['mail']!="")
		{
			 $mail = $_POST['mail'];
			 $part = explode('@', $mail);
			 if (count($part) < 2)
			 {
			 echo "Błąd: Podany e-mail jest nie prawidłowy.<br/>";
			 echo "Kliknij <a href='index.php'>tutaj</a>, aby powrócić do logowania.<br/>";
			 break;
			 }

		}
		else
		$mail = "brak";


	 $pass = $_POST['pass'];
	 $z_pass = "select testid from test where pass='".$pass."';";
     $w_pass = mysql_query($z_pass)or die("Wystąpił błąd w bazie danych.");
	 $ile_pass = mysql_num_rows($w_pass);
	 $wie_pass = mysql_fetch_array($w_pass);

	 if ($ile_pass != 1)
	 {
	 echo "Błąd: Podane hasło nie pasuje.<br/>";
	 echo "Kliknij <a href='index.php'>tutaj</a>, aby powrócić do logowania.<br/>";
	 break;
	 }


	 $testid = $wie_pass[0];
	 $imie = $_POST['imie'];
	 $nazwisko = $_POST['nazwisko'];
	 $klasa = $_POST['klasa'];

	 if(!imie($imie))
	 {
	  echo "Błąd: Imię powinno składać się z samych liter.";
	  break;
	 }

	 if(!nazwisko($nazwisko))
	 {
	  echo "Błąd: Nazwisko powinno składać się z samych liter lub znaku '-'.";
	  break;
	 }

	 if(!grupa($klasa))
	 {
	  echo "Błąd: Nazwa grupy powinna składać się z samych liter, cyfr lub znaków '/'.";
	  break;
	 }



	 $nazwa[] = $mail;
	 $nazwa[] = $testid;
	 $nazwa[] = $imie;
	 $nazwa[] = $nazwisko;
	 $nazwa[] = $klasa;
	 $nazwa[] = date('d.m.Y H:i:s');
	 $sesyja = implode('&', $nazwa);

	 $_SESSION['uczen'] = $sesyja;
 	 $adres = "rozwiaz.php";
     echo "<script type='text/javascript'>location = '".$adres."';</script>";
     echo "Kliknij <a href='rozwiaz.php'>tutaj</a>, aby zacząć rozwiązywać test.<br/>";

	break;
    }


}
}
?>
</div>
<div id="stopka">
&copy; 2010 <a href='mailto:ganondorf999@gmail.com'>Tomasz Rarok v.2</a>
</div>
</body>
</html>
