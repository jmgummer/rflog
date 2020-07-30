<?php

// define("JS_LIB_PATH", "../../charts/js/");

defined('JS_LIB_PATH') or define('JS_LIB_PATH', '../../charts/js/');

/**
* Paths and names for the javascript libraries needed by higcharts/highstock charts
*/
$jsFiles = array(
	'jQuery' => array(
		'name' => 'jquery-2.1.3.min.js',
		'path' => ''
	),

	'mootools' => array(
		'name' => 'mootools-yui-compressed.js',
		'path' => JS_LIB_PATH
	),

	'prototype' => array(
		'name' => 'prototype.js',
		'path' => JS_LIB_PATH
	),

	'highcharts' => array(
		'name' => 'highcharts.js',
		'path' => JS_LIB_PATH
	),

	'highchartsMootoolsAdapter' => array(
		'name' => 'mootools-adapter.js',
		'path' => JS_LIB_PATH.'/adapters/'
	),

	'highchartsPrototypeAdapter' => array(
		'name' => 'prototype-adapter.js',
		'path' => JS_LIB_PATH.'/adapters/'
	),

	//Extra scripts used by Highcharts 3.0 charts
	'extra' => array(
		'highcharts-more' => array(
			'name' => 'highcharts-more.js',
			'path' => JS_LIB_PATH
		),
		'exporting' => array(
			'name' => 'exporting.js',
			'path' => JS_LIB_PATH.'/modules/'
		),
	)
);