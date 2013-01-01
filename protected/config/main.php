<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
Yii::setPathOfAlias('bootstrap', dirname(__FILE__).'/../extensions/bootstrap');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'StrettoFotoContest',
	'theme'=>'bootstrap',
	'language'=>'it',
	// preloading 'log' component
	'preload'=>array(
		'log',
		//'less',
	),

	// autoloading model and component classes
	'import'=>array(
		'application.helpers.*',
		'application.models.*',
		'application.components.*',
		// Carico Yii User
		'application.modules.user.models.*',
        'application.modules.user.components.*',
		//Carico Rights
		'application.modules.rights.*', 
		'application.modules.rights.components.*',
		// Carico Social Login
		'application.modules.hybridauth.controllers.*',
		//Carico Ajax Upload
		'ext.EAjaxUpload.*',
	),
	
	'modules'=>array(
		'gii'=>array(
		'class'=>'system.gii.GiiModule',
			'password'=>'bcl81t64A@',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('*.*.*.*','::1'),
            'generatorPaths'=>array(
                'bootstrap.gii',
            ),
        ),
		// Carico Yii Rights
		'rights'=>array(
			'install'=>false, // Enables the installer.
			/*
                        'userIdColumn'=>'id', // Il nome della colonna user id nel database. 
                        'userNameColumn'=>'username', // Il nome della colonna name nel database. 
                        'superuserName'=>'Admin', // Name of the role with super user privileges. 
                        'authenticatedName'=>'Authenticated', // Name of the authenticated user role. 
                        'enableBizRule'=>true, // Whether to enable authorization item business rules. 
                        'enableBizRuleData'=>false, // Per abilitare data for business rules. 
                        'displayDescription'=>true, // Per usare la descrizione dell'oggetto al posto del nome. 
                        'flashSuccessKey'=>'RightsSuccess', // Key to use for setting success flash messages. 
                        'flashErrorKey'=>'RightsError', // Key to use for setting error flash messages. 
                        'baseUrl'=>'/rights', // Base URL for Rights. Change if module is nested. 
                        'layout'=>'rights.views.layouts.main', // Layout to use for displaying Rights. 
                        'appLayout'=>'application.views.layouts.main', // Application layout. 
                        'cssFile'=>'rights.css', // Style sheet file to use for Rights. 
*/			'enableBizRule'=>false, // Whether to enable authorization item business rules.
			'debug' => true,
			'authenticatedName'=> array(
				'Authenticated',
				'Guest',
			),
		),
		// Carico Accesso Tramite Social
		'hybridauth' => array(
            'baseUrl' => 'http://'. $_SERVER['SERVER_NAME'] . '/contest/index.php/hybridauth', 
            'withYiiUser' => true, // Set to true if using yii-user
            "providers" => array (  
                "facebook" => array ( 
                    "enabled" => true,
                    "keys"    => array ( "id" => "398543673556669", "secret" => "5a5e2dccdf45d454cabd5446281ada6c" ),
                    "scope"   => "email, publish_stream", 
                    "display" => "popup" 
                ),
            )
        ),
		// Carico Yii - User
		'user'=>array(
                # codifico la password in MD5
                'hash' => 'md5',				
				'captcha' => array(
					'registration' => false,
				),
                # Attivo l'invio di email
                'sendActivationMail' => true,
                # Disabilito l'accesso agli utenti con confermati
                'loginNotActiv' => false,
                # activate user on registration (only sendActivationMail = false)
                'activeAfterRegister' => false,
                # Abilito l'auto login
                'autoLogin' => true,
                # registration path
                'registrationUrl' => array('/user/registration'),
                # recovery password path
                'recoveryUrl' => array('/user/recovery'),
                # login form path
                'loginUrl' => array('/user/login'),
                # page after login
                'returnUrl' => array('/user/profile'),
                # page after logout
                'returnLogoutUrl' => array('/user/login'),
            ),
	),
	// application components
	'components'=>array(
		// Carico Bootstrap per il tema grafico
		'bootstrap'=>array(
            'class'=>'bootstrap.components.Bootstrap',
        ),
		// Carico libreria per le immagini
		'image'=>array(
          'class'=>'application.extensions.image.CImageComponent',
            // GD or ImageMagick
            'driver'=>'GD',
            // ImageMagick setup path
            'params'=>array('directory'=>'/usr/bin'),
        ),
		// Carico sistema di gestione email
		'mail' => array(
			'class' => 'ext.yii-mail.YiiMail',
			'transportType'=>'smtp',
			'transportOptions'=>array(
				'host'=>'smtp.gmail.com',
				'encryption'=>'ssl',
				'username'=>'francescoloddo@gmail.com',
				'password'=>'bcl81t64A@',
				'port'=>'465',
			),
			'viewPath' => 'application.views.mail',
			'logging' => true,
			'dryRun' => false
		),
		// Carico Yii User
		'user'=>array(
                // enable cookie-based authentication
				'class'=>'RWebUser',
				//'class' =>'WebUser',
                'allowAutoLogin'=>true,
                'loginUrl' => array('/user/login'),
        ),
		// Carico Yii Rights
		'authManager'=>array(
                'class'=>'RDbAuthManager',
                'connectionID'=>'db',
                'defaultRoles'=>array('Guest'),
        ),
		// uncomment the following to enable URLs in path-format
		'urlManager'=>array(
			'urlFormat'=>'path',
            'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
                '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
            ),
        ),
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=contest',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => 'assist',
			'charset' => 'utf8',
			'tablePrefix'=> '',
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
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>require(dirname(__FILE__).'/params.php'),
);
