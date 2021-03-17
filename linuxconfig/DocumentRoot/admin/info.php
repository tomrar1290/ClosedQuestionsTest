<html>
   <head>
      <title>Connecting MySQL Server</title>
   </head>
   <body>


      <?php
         $dbhost = 'mariadb';
         $dbuser = 'root';
         $dbpass = 'rootpwd';
         $conn = mysql_connect($dbhost, $dbuser, $dbpass);

         if(! $conn ) {
            die('Could not connect: ' . mysql_error());
         }
         echo 'Connected successfully';
         mysql_close($conn);
         ?>
   </body>
</html>
