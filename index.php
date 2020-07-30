<?php
date_default_timezone_set("Africa/Nairobi");
$config_destination = dirname(__FILE__)."/charts/config/auto_load.php";
require_once $config_destination;
$yii=dirname(__FILE__).'/yii_back/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';
require_once($yii);
Yii::createWebApplication($config)->run();