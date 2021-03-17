<?php
session_start();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" >
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<link rel="Stylesheet" type="text/css" href="style.css"/>
<title>Test Logowanie Admin</title>
</head>
<body>
<?php
if ( isset($_SESSION['user']) )
{
 $adres = "admin.php";
 echo "<script type='text/javascript'>location = '".$adres."';</script>";
 exit;
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


if ( empty($_POST['login']) || empty($_POST['pass']) )
{
?>
<div id="loguj">
<form action="index.php" method="post">
<table><tr>
<td>Nazwa:</td>
<td><input type="text" name="login" maxlength="15" size="15"/></td>
</tr><tr>
<td>Hasło:</td>
<td><input type="password" name="pass" maxlength="15" size="15" /></td></tr>
<tr><td colspan="2"><input type="submit" value="Zaloguj!" /></td></tr></table>
</form>
Kliknij <a href="../">tutaj</a> aby przejść na stronę główną.
</div>
<?php
}
else {
 $login = $_POST['login'];
 $password = $_POST['pass'];
 $passwordz = sha1($password);
 if ($login == $dane[0] and $passwordz == $dane[1])
 {
 	
 $_SESSION['user'] = $login;
 $adres = "admin.php";
 echo "<script type='text/javascript'>location = '".$adres."';</script>";
 }
  else
 {
 ?>
 <div id="loguj">

Podano błędny login lub hasło<br/>
Kliknij <a href="index.php">tutaj</a> aby zalogować się ponownie.<br/>
Kliknij <a href="../">tutaj</a> aby przejść na stronę główną.

</div>
 
 <?php
 }
}
?>
</body>
</html>