<?php
session_start();
if ( empty($_SESSION['user']) )
{
 $adres = "index.php";
 echo "<script type='text/javascript'>location = '".$adres."';</script>";
 exit;
}
require('config.php');
@$connection= @mysql_connect("$dbhost", "$dblogin", "$dbpass")
or die(' Brak połączenia z serwerem MySQL.<br />Błąd: '.mysql_error());
$db = @mysql_select_db("$dbbase", $connection)
or die(' Nie mogę połączyć się z bazą danych<br />Błąd: '.mysql_error());


$z_test = "select testid from test limit 1;";
$w_test = mysql_query($z_test)or die("Wystąpił błąd w bazie danych.");
$ile = mysql_num_rows($w_test);

if (isset($_COOKIE['TEST']))
{
		$idt = $_COOKIE['TEST'];

		$z_testor = "select count(*) from test where testid='".$idt."';";
		$w_testor = mysql_query($z_testor)or die("Wystąpił błąd w bazie danych.");
		$ile_testor = mysql_fetch_array($w_testor);
		if ($ile_testor[0] == 0)
		{
		$wiersz_test = mysql_fetch_array($w_test);
		$testid = $wiersz_test[0];
		setcookie ("TEST", $testid);
		}
}
if (isset($_GET['id']))
$id=$_GET['id'];
else
$id = "index";

if ($ile == 0)
{
	$id="addtest";
}
else
{



        if (empty($_COOKIE['TEST']))
		{
		$wiersz_test = mysql_fetch_array($w_test);
		$testid = $wiersz_test[0];
		setcookie ("TEST", $testid);
		}
		else if ($id == "wybor")
		{

		$testid = $_POST['tescik'];
		setcookie ("TEST", $testid);
		}
		else
		{

		$testid = $_COOKIE['TEST'];
		}


}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" >
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<link rel="Stylesheet" type="text/css" href="style.css"/>
<title>Test Admin</title>
<script type="text/javascript"  src="skrypt.js">

</script>

</head>
<body>
<noscript>
<p>Wyłączona obsługa JavaScript :(</p>
</noscript>
<div id="glowne">
		<div id='test'>
		<form action="admin.php?id=wybor" method="post">
		 <table><tr><td>
		 <?php
		$zapytanie = "select testid, name from test;";
		$wynik = mysql_query($zapytanie)or die("Wystąpił błąd w bazie danych.");
		$ilewierszy = mysql_num_rows($wynik);
		if ($ilewierszy > 0)
		{
		echo "Wybierz test</td><td><select name='tescik'>";
		 for ($i = 1; $i <= $ilewierszy; $i++)
		 {
		 $wiersz = mysql_fetch_array($wynik);
		 echo "<option value='".stripslashes($wiersz[0])."'";

		 	if (stripslashes($wiersz[0]) == $testid)
		 	{
			echo " selected='selected' ";
			$test_ak = stripslashes($wiersz[1]);
		 	}

		 echo ">";
		 echo stripslashes($wiersz[1]);
		 echo "</option>";
		 }
		echo "</select>";

		}

		else
		{
		echo "<p><b>Brak testów w bazie!</b></p>";
		$disable = true;
		}
		?>
					</td>
					<td colspan="2"><input type="submit" value="Wybierz!"
		<?php
		if (isset($disable) && $disable ==true)
		{
			echo "disabled=\"disabled\"";
		}
		?>
		/>
		</td></tr></table>
		</form>

		</div>
		<div id="menu">
			<ul>
			<li><a href='admin.php?id=addpyt'>Dodaj pytanie</a></li>
			<li><a href='admin.php?id=addtest'>Dodaj test</a></li>
			<li><a href='admin.php?id=edpyt'>Edytuj pytania</a></li>
			<li><a href='admin.php?id=edtest'>Edytuj testy</a></li>
			<li><a href='admin.php?id=wyniki'>Zobacz wyniki</a></li>
			<li><a href='admin.php?id=set'>Ustawienia</a></li>
			<li><a href='logout.php'>Wyloguj</a></li>
			</ul>
		</div>

		<div id="tresc">
		<?php
require('funkcje.php');

switch ($id) {

//DODAJ PYTANIE
case "addpyt":
require('p_addpyt.php');
break;

//DODAJ TEST
case "addtest":
require('p_addtest.php');
break;

//EDYTUJ PYTANIA
case "edpyt":
require('p_edpyt.php');

break;

//EDYTUJ TESTY
case "edtest":
require('p_edtest.php');
break;

//WYNIKI
case "wyniki":
require('p_wyniki.php');
break;


//USTAWIENIA
case "set":
require('p_set.php');
break;

default:
echo "Aktualnie wybrany test: <b> $test_ak </b><br/>";

break;
}
			?>
		</div>

</div>
</body>
</html>
