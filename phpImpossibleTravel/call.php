<?php


function get_lat_lon($ip) {
  require_once 'datenbank.php';
  
  $ausgabe=[null,null];
  
  $db = new datenbank();
  
  $tab = 'ipv4';
  if ($ip[1]){
    $tab = 'ipv6';
  }
  
  $lonlat = $db->exec_sql('select lon,lat from '.$tab.' where fromip <= '.$ip[0].' and toip >= '.$ip[0]);
  while ($Row = $lonlat->fetch_row()) {
            $ausgabe = [$Row[0],$Row[1]];
  }

  return $ausgabe;
  
}

/* Man, I stole your Code.
* https://stackoverflow.com/questions/18276757/php-convert-ipv6-to-number
* boen_robot
*/
function ipv62numeric($ip)
{
    $str = '';
    foreach (unpack('C*', inet_pton($ip)) as $byte) {
        $str .= str_pad(decbin($byte), 8, '0', STR_PAD_LEFT);
    }
    $str = ltrim($str, '0');
    if (function_exists('bcadd')) {
        $numeric = 0;
        for ($i = 0; $i < strlen($str); $i++) {
            $right  = base_convert($str[$i], 2, 10);
            $numeric = bcadd(bcmul($numeric, 2), $right);
        }
        $str = $numeric;
    } else {
        $str = base_convert($str, 2, 10);
    }

    return $str;
}

function get_ip($paramname){
  $tryip = get_param($paramname);
  $ip=null;
  $ipv6=false;
  if (filter_var($tryip,FILTER_VALIDATE_IP,FILTER_FLAG_IPV4)){
    $ip=ip2long($tryip);     
  }elseif (filter_var($tryip,FILTER_VALIDATE_IP,FILTER_FLAG_IPV6)){
    $ip=ipv62numeric($tryip);
    $ipv6 = true;
  }else{
    die('IP not valide');
  }
  if (isset($ip)){
    return [$ip,$ipv6];
  }else{
    return null;
  }
  
}


function get_param($paramname){
  $ausgabe = null;
  if (isset($_GET[$paramname])){
    $ausgabe = $_GET[$paramname];
  }
  if (isset($_POST[$paramname])){
    $ausgabe = $_POST[$paramname];
  }
  return $ausgabe;
}

/*
 * https://stackoverflow.com/questions/27928/calculate-distance-between-two-latitude-longitude-points-haversine-formula
 */

function distance($lat1, $lon1, $lat2, $lon2) {

    $pi80 = M_PI / 180;
    $lat1 *= $pi80;
    $lon1 *= $pi80;
    $lat2 *= $pi80;
    $lon2 *= $pi80;

    $r = 6372.797; // mean radius of Earth in km
    $dlat = $lat2 - $lat1;
    $dlon = $lon2 - $lon1;
    $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlon / 2) * sin($dlon / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    $km = $r * $c;

    //echo '<br/>'.$km;
    return $km;
}

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
      
      $ausgabe = "valid";
      if ($dist > 700){ //throu a change of the Provider in Germany there are easyly 600 km in 3 Seconds, without traveling. so we only check > 700km
        $timedelta = abs($utc1-$utc2)/60; 
        //Max speed of Flight: 850km/h. --> 14 km/minute
        if ($dist >($timedelta*14)){
          $ausgabe = "impossibleTravel";
        }
      }  
      echo $ausgabe;
      break;
}
