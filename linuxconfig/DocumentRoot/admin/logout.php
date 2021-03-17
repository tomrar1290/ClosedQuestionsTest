<?php
session_start();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" >
<head>
<meta http-equiv="Content-type" content="application/xhtml+xml; charset=UTF-8" />
<link rel="Stylesheet" type="text/css" href="style.css"/>
<title>Test Logowanie Admin</title>
</head>
<body>

<div id="loguj">
<?php
if ( isset($_SESSION['user']) )
{
unset($_SESSION['user']);
session_destroy();
}
?>
Wylogowano!<br/>
Kliknij <a href="index.php">tutaj</a> aby zalogować się ponownie.<br/>
Kliknij <a href="../">tutaj</a> aby przejść na stronę główną.

</div>

</body>
</html>