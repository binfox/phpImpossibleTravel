<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>phpImpossibleTravel</title>
    </head>
    <body>
<?php

  set_time_limit(600); // longer time to refresh the DB
  require_once 'datenbank.php';
  
  echo "DB connect<br/>";
  
  $db = new datenbank();
  if (file_exists('inputfiles/IP2LOCATION-LITE-DB5.CSV')) {
      echo "IPV4 input found<br/>"; 
      $handle = fopen('inputfiles/IP2LOCATION-LITE-DB5.CSV', "r");
      if ($handle) {
          $db->exec_sql('delete from ipv4;',true);
          while (($line = fgets($handle)) !== false) {
              $fields = explode(',', $line);
              if (count($fields)>7) {
                $db->exec_sql_insert('insert into ipv4 values('.$fields[0].','.$fields[1].','.$fields[6].','.$fields[7].');');
              
              }
                  
          }

        fclose($handle);
      }
  }
  
  
?>
  
  </body>
</html>