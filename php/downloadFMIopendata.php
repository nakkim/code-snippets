#!/usr/bin/php
<?php

# Download data from Smartmet server

$producer = 'harmonie_scandinavia_surface';
$params   = 'Temperature,WindDirection,WindSpeedMS,TotalCloudCover,LowCloudCover,MediumCloudCover,HighCloudCover';
$endpoint = 'https://opendata.fmi.fi';
$out      = '/home/ilkkav/koodia/Git/tuulikartta.info/php';
$tmp      = '/tmp';

system("mkdir -p $out $tmp");

// chekck whether lock file exists
if (mkdir("${tmp}/${producer}.lock", 0700)) {
  $remotetime = pollDataTimes($endpoint, $producer, $tmp, false);
  $servertime = pollDataTimes("http://nakkim.dy.fi", "harmonie_scandinavia_surface", $tmp, true);

  if($remotetime === $servertime) {
    print "« ${harmonie_scandinavia_surface} is up to date »\n";
    rmdir("${tmp}/${producer}.lock");
  } else {
    $remotetime = str_replace(' ','T',$remotetime);
    dowloadData($endpoint, $producer, $params, $remotetime, $tmp, $out);
    rmdir("${tmp}/${producer}.lock");
  }

} else {
  print "« Lock file exists or another download is running »\n";
}



// functions

// Poll available data to check current origin time
function pollDataTimes($endpoint, $producer, $tmp, $continue) {
  $data = file_get_contents("${endpoint}/admin?what=qengine&producer=${producer}&format=serial");
  if(empty(unserialize($data))) {
    print "« No data to return »\n";
    if(!$continue) {
      rmdir("${tmp}/${producer}.lock") ;
      exit();
    }
    return false;
  }
  return (unserialize($data))[0]['OriginTime'];
}

// Download data from SmartMet server
function dowloadData($endpoint, $producer, $params, $origintime, $tmp, $out) {
  $url = "$endpoint/download?producer=$producer&param=$params&origintime=$origintime&timestep=data&format=qd";
  $date = new DateTime();
  $date = $date->format('YmdHi');
  system("wget -O ${tmp}/harmonie-surface.sqd -S \"$url\" -q --show-progress");
  system("rsync ${tmp}/harmonie-surface.sqd ${out}/${date}_harmonie_scandinavia_surface.sqd");
  system("rm -f $(ls -1t ${out}/*.sqd | tail -n +3");
}