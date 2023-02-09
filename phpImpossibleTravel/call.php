<?php

require_once 'calc.php';

$fkt=get_param("fkt");

switch ($fkt) {
    case "gmaps":
      $ip=get_ip('ip');
      $tmp=get_lat_lon($ip);  
      echo 'https://www.google.de/maps/@'.$tmp[0].','.$tmp[1].',11z';

      break;
    case "position":
      $ip=get_ip('ip');
      $tmp=get_lat_lon($ip);  
      header('Content-type:application/json;charset=utf-8');
      echo json_encode($tmp);
      
      break;
    case "distance":
      $ip=get_ip('ip1');
      $pos1=get_lat_lon($ip);
      $ip=get_ip('ip2');
      $pos2=get_lat_lon($ip);
      echo distance($pos1[1],$pos1[0],$pos2[1],$pos2[0]);
            
      break;
    case "impossibletravel":
      $ip=get_ip('ip1');
      $pos1=get_lat_lon($ip);
      
      $ip=get_ip('ip2');
      $pos2=get_lat_lon($ip);
      
      $utc1= get_param('utc1');
      $utc2= get_param('utc2');
      
      $dist = distance($pos1[1],$pos1[0],$pos2[1],$pos2[0]);
      
      if (distvalide($dist,$utc1,$utc2)){
        $ausgabe = 'valide';
      }else{
        $ausgabe = 'ImpossileTravel';
      }
      echo $ausgabe;
      break;
}
