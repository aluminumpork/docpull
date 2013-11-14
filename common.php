<?php
function pr($v){
echo '<pre>';
  print_r($v);
  echo '</pre>';
}

function prd($v){
pr($v);
die();
}
