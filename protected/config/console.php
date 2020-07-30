<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'My Console Application',

	// preloading 'log' component
	'preload'=>array('log'),
	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.extensions.input.components.*',
		'application.modules.cal.*',
		'application.modules.cal.models.*',
		'application.extensions.phpexcel.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'huhuhaha',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1','192.168.0.234'),
			'generatorPaths'=>array('bootstrap.gii'),
		),
		'clientservice',
		
	),

	// application components
	'components'=>array(
		//twitter bootstrap extension
		'bootstrap'=>array(
        	'class'=>'ext.bootstrap.components.Bootstrap', // assuming you extracted bootstrap under extensions
    	),
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		
		'db'=>array(
			'class'=>'CDbConnection',
			'connectionString' => 'mysql:host=192.168.0.5;dbname=app_settings',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => 'Pambazuka08',
			'charset' => 'utf8',
		),

		'db2'=>array(
			'class'=>'CDbConnection',
			'connectionString' => 'mysql:host=192.168.0.5;dbname=reelmedia',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => 'Pambazuka08',
			'charset' => 'utf8',
		),

		'db3'=>array(
			'class'=>'CDbConnection',
			'connectionString' => 'mysql:host=192.168.0.5;dbname=forgedb',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => 'Pambazuka08',
			'charset' => 'utf8',
		),

		'geopolldb'=>array(
			'class'=>'CDbConnection',
			'connectionString' => 'mysql:host=192.168.0.5;dbname=geopoll_api',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => 'Pambazuka08',
			'charset' => 'utf8',
		),

		'stocksdb'=>array(
			'class'=>'CDbConnection',
			'connectionString' => 'mysql:host=192.168.0.5;dbname=stocks_api',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => 'Pambazuka08',
			'charset' => 'utf8',
		),
		
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
		//Input filter
        'input'=>array(
            'class'         => 'CmsInput',
            'cleanPost'     => false,
            'cleanGet'      => false,
        ),

        'ePdf2' => array(
	        'class'         => 'ext.yii-pdf.EYiiPdf',
	        'params'        => array(
	            'mpdf'     => array(
	                'librarySourcePath' => 'application.vendors.mpdf.*',
	                'constants'         => array(
	                    '_MPDF_TEMP_PATH' => Yii::getPathOfAlias('application.runtime'),
	                ),
	                'class'=>'mpdf', // the literal class filename to be loaded from the vendors folder
	                'defaultParams'     => array( // More info: http://mpdf1.com/manual/index.php?tid=184
	                    'mode'              => '', //  This parameter specifies the mode of the new document.
	                    'format'            => 'A3', // format A4, A5, ...
	                    'default_font_size' => 11, // Sets the default document font size in points (pt)
	                    'default_font'      => 'Arial', // Sets the default font-family for the new document.
	                    'mgl'               => 15, // margin_left. Sets the page margins for the new document.
	                    'mgr'               => 15, // margin_right
	                    'mgt'               => 16, // margin_top
	                    'mgb'               => 16, // margin_bottom
	                    'mgh'               => 9, // margin_header
	                    'mgf'               => 9, // margin_footer
	                    'orientation'       => 'L', // landscape or portrait orientation
	                ),
	            ),
	        ),
	    ),
		//PHPMailer Wrapper
		'mailer' => array(
	      'class' => 'application.extensions.mailer.EMailer',
	      'pathViews' => 'application.views.email',
	      'pathLayouts' => 'application.views.email.layouts'
	   ),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),
	),
);