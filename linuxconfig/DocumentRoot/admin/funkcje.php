<?php

function strony($nazwa, $zapytanie, $ile_strona)
{
echo "<div id=\"strony\">";


$z_ile = $zapytanie; 
$w_ile = mysql_query($z_ile)or die("Wystąpił błąd w bazie danych.");
$ile = mysql_fetch_array($w_ile);
$ile_stron = $ile[0] / $ile_strona;
if ((Integer)$ile_stron != $ile_stron )
$ile_stron = (Integer)$ile_stron + 1;

if ($ile_stron == 0)
$max =1;
else
$max=$ile_stron;



/*
Skrypt pobiera ilość stron, następnie nalicza je ustawiając start i koniec.
Start i koniec uzależniony jest od zakresu stron tj. od 1 do $max.
 */

if (isset($_GET['page']) && $_GET['page'] >= 1 && $_GET['page'] <= $max)
$page = $_GET['page'];
else
$page = 1;
$zakres = 3;
$start = $page-$zakres;
$koniec= $page+$zakres;
$next = $page+1;
$previous = $page-1;

if ($start < 1)
{
$start = 1;
}
if ($koniec > $max)
{	
$koniec = $max;
}
if ($next > $max)
$next = $max;

if ($previous < $start)
$previous = 1;

echo "  <a  href='admin.php?id=$nazwa&amp;page=1' title='Pierwsza strona'>&lt;&lt;</a> \n";
echo "  <a  href='admin.php?id=$nazwa&amp;page=$previous' title='Poprzednia strona'>&lt;</a>  \n";
for ($i=$start; $i<=$koniec; $i++)
{
if ($i == $page)
echo " <b>[$i]</b>\n";
else
echo "<a href='admin.php?id=$nazwa&amp;page=$i' title='Strona ".$i."'>$i</a> \n ";
}
echo "<a href='admin.php?id=$nazwa&amp;page=$next' title='Następna strona'>&gt;</a>  \n";
echo "<a href='admin.php?id=$nazwa&amp;page=$max' title='Ostatnia strona'>&gt;&gt;</a>  <br/><br/>\n";

echo "</div>";
$poczatek = ($page * $ile_strona )- $ile_strona;
return $poczatek;
}


?>