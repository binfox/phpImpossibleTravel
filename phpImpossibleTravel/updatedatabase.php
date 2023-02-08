<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>phpImpossibleTravel</title>
    </head>
    <body>
<?php
  $Now = new DateTime('now', new DateTimeZone(date_default_timezone_get()));

  set_time_limit(600); // longer time to refresh the DB
  require_once 'datenbank.php';
  
  echo "DB connect<br/>";
  
  $db = new datenbank();
  if (file_exists('inputfiles/IP2LOCATION-LITE-DB5.CSV')) {
    echo "IPV4 input found<br/>"; 
    $handle = fopen('inputfiles/IP2LOCATION-LITE-DB5.CSV', "r");
    if ($handle) {
      $db->exec_sql('delete from ipv4;',true);
      $values =''; 
      $i=0;
      while (($line = fgets($handle)) !== false) {
        $fields = explode('"', $line);
        if (count($fields)>15) {
          $i++;
          if (($i % 100 ) == 99) {
            $db->exec_sql_insert('insert into ipv4 values'.$values.';');
            $values =''; 
          }else{
            if ($i > 1) {
              $values = $values.',';
            }
          }
          
          $values = $values. '('.intval($fields[1]).','.intval($fields[3]).','.$fields[13].','.$fields[15].')';
          
            
        }
                
      }
      $db->exec_sql_insert('insert into ipv4 values'.$values.';');
      fclose($handle);
      rename('inputfiles/IP2LOCATION-LITE-DB5.CSV','inputfiles/ipv4.'.$Now->format('Y-m-d-H-i-s').'.csv');
      
      echo 'IPV4 finished<br/>';
    }
  }
  set_time_limit(1500); // longer time to refresh the DB
  if (file_exists('inputfiles/IP2LOCATION-LITE-DB5.IPV6.CSV')) {
    echo "IPV6 input found<br/>"; 
    $handle = fopen('inputfiles/IP2LOCATION-LITE-DB5.IPV6.CSV', "r");
    if ($handle) {
      $db->exec_sql('delete from ipv6;',true);
      $values =''; 
      $i=0;
      while (($line = fgets($handle)) !== false) {
        $fields = explode('"', $line);
        if (count($fields)>15) {
          $i++;
          if (($i % 100 ) == 99) {
            $db->exec_sql_insert('insert into ipv6 values'.$values.';');
            $values =''; 
          }else{
            if ($i > 1) {
              $values = $values.',';
            }
          }
          if (is_numeric($fields[1]) and is_numeric($fields[3])){
            $values = $values. '('.$fields[1].','.$fields[3].','.$fields[13].','.$fields[15].')';
          }
            
        }
                
      }
      $db->exec_sql_insert('insert into ipv6 values'.$values.';');
      fclose($handle);
      rename('inputfiles/IP2LOCATION-LITE-DB5.IPV6.CSV','inputfiles/ipv6.'.$Now->format('Y-m-d-H-i-s').'.csv');
      echo 'IPV6 finished<br/>';
    }
  }
  
  
?>
  
  </body>
</html>