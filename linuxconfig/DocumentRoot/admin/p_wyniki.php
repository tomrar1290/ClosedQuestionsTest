<?php
echo "<h1>Wyniki</h1>";	
if (isset($_GET['w']))
{
	$jest = true;	
	while ($jest == true)
	{	
$z_wyn = "select  uczen, tresc, razem from wyniki where id='".$_GET['w']."';";
$w_wyn = mysql_query($z_wyn)or die("Wystąpił błąd w bazie danych.");
$ile_wyn = mysql_num_rows($w_wyn);
if ($ile_wyn!=1)
{
break;	
}


	$wiersz_wyn = mysql_fetch_array($w_wyn);
	$uczen = stripslashes($wiersz_wyn[0]);
		
	$segment = explode('&', $uczen);
	$ile_ucz = count($segment);
	if ($ile_ucz != 10)
	{	
	echo "Błąd w danych.<br/>";
	break;	
	}
	$mail = $segment[0];
	$testid = $segment[1];
	$imie = $segment[2];
	$nazwisko = $segment[3];
	$klasa = $segment[4];
	$rozp = $segment[5];
	$zak = $segment[6];
	$ip = $segment[7];
	$wew = $segment[8];
	$test = $segment[9];
	$wyniczki= stripslashes($wiersz_wyn[1]);	
	$wyniki = explode('&', $wyniczki);
	$razem= stripslashes($wiersz_wyn[2]);	
    echo "<table style='font-size:14px;'><tr><td style='padding:10px;vertical-align:top;'>";
	echo "<b>Imię:</b> $imie<br/>\n";
	echo "<b>Nazwisko:</b> $nazwisko<br/>\n";
	echo "<b>Klasa:</b> $klasa<br/>\n";
	echo "<b>E-mail:</b> $mail<br/>\n";
	echo "<b>Rozpoczęto:</b> $rozp<br/>\n";
	echo "<b>Zakończono</b> $zak<br/>\n";
	echo "<b>IP zew.</b>: $ip<br/>\n";
	echo "<b>IP wew:</b> $wew<br/>\n";
	echo "<b>Nazwa testu:</b> $test<br/><br/>\n";

	echo "<span style='font-size:16px;'><b>Razem PKT:</b> $razem</span><br/>\n";
	$skl = explode('/', stripslashes($wiersz_wyn[2])) ;
	$procent = ($skl[0] * 100) / $skl[1]; 
	echo "<span style='font-size:16px;'><b>Procentowo: </b>";
	printf ("%.2f %%", $procent);
	echo "</span><br/>\n";
	echo "</td><td style='padding:10px;vertical-align:top;'>";
	$ile_p = count($wyniki);
	 for ($i=0;$i<$ile_p;$i++)
	 {
	 $pyt = $i + 1;
	 echo "<b>Pytanie: $pyt </b><br/>";
	 echo $wyniki[$i]."<br/>";	
	 }
	echo "</td></tr></table>";
	break;
	}	
}


if (isset($_GET['delw']))
{
$jest = true;	
	while ($jest == true)
	{ 
	 if (isset($_POST['wybr']))
	 {
	 $ile = count($_POST['wybr']);	
	 $pole = $_POST['wybr'];
	 			for($d = 0; $d<$ile; $d++)
	 			{
	 				$id = $pole[$d];
	 				$z_del = "delete from wyniki where id='".$id."';";
					$w_del = mysql_query($z_del)or die("Wystąpił błąd w bazie danych.");	
	 			}
	 }
	 else
	 {
	 echo "Błąd: Nie wybrano nic do usunięcia";	
	 	
	 }
	break;
	}	
}
$nazwa = "wyniki";
$zapytanie = "select count(*) from wyniki";
$ile_strona=50;
$poczatek = strony($nazwa, $zapytanie, $ile_strona);
$z_wyn = "select  uczen, razem, id from wyniki order by id desc limit $poczatek, $ile_strona;";
$w_wyn = mysql_query($z_wyn)or die("Wystąpił błąd w bazie danych.");
$ile_wyn = mysql_num_rows($w_wyn);
echo "<form action='admin.php?id=wyniki&amp;delw=1' method='post'>";
echo "\n<table class='pytanie'>";
	for ($i =1; $i<=$ile_wyn; $i++)
	{
	$wiersz_wyn = mysql_fetch_array($w_wyn);
$uczen = stripslashes($wiersz_wyn[0]);	
$segment = explode('&', $uczen);
$mail = $segment[0];
$testid = $segment[1];
$imie = $segment[2];
$nazwisko = $segment[3];
$klasa = $segment[4];
	echo "\n<tr><td>";
	echo "<input type='checkbox' name='wybr[]' value='".stripslashes($wiersz_wyn[2])."'/>";
	echo "</td><td style='width:200px;'>";
	echo "$imie $nazwisko $klasa";
	echo "</td>";
	echo "</td><td style='width:100px;'>";
	echo "Punktów: ";
	echo  stripslashes($wiersz_wyn[1]);
	$skl = explode('/', stripslashes($wiersz_wyn[1])) ;
	echo "</td><td style='width:100px;'><b>";
	$procent = ($skl[0] * 100) / $skl[1]; 
	printf ("%.2f %%", $procent);
	echo "</b></td>";
	echo "\n<td><a href='admin.php?id=wyniki&amp;w=";
	echo stripslashes($wiersz_wyn[2]);
	echo "'/>Zobacz</a></td>";
	echo "</tr>";
	}
echo "\n<tr><td colspan='5' style='padding-top:20px;'><input type='submit' value='Usuń wybrane'/></td></tr></table>";
echo "</form>";
?>