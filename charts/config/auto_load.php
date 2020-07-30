<?php
function my_autoload ($pClassName) {
  $base_dir = $_SERVER['DOCUMENT_ROOT']."/charts/components/HighchartsPHP/";
  $pClassName =  $base_dir.$pClassName. ".php";
  if (file_exists($pClassName)){
    require $pClassName;
  }
}
spl_autoload_register("my_autoload");