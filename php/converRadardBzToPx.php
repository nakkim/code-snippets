#!/opt/homebrew/bin/php
<?php

# Convert and output radar style related stuff and so on
# Z[dBz] = 0.5 * Z[pixel value] - 32

$values = [
  "8"  => ["#0A9BE1", "heikko"],
  "12" => ["#06CDAA", ""],
  "18" => ["#8CE614", "kohtalainen"],
  "24" => ["#F0F014", ""],
  "30" => ["#FFCD14", ""],
  "34" => ["#FF9632", "voimakas"],
  "40" => ["#FF503C", ""],
  "50" => ["#FA78FF", ""],
];

foreach($values as $key => $hex) {
  $px = 2*(floatval($key)+32);
  print '<ColorMapEntry color="'.$hex[0].'" quantity="'.$px.'" label="'.$hex[1].'" opacity="1" />'."\n";
}

