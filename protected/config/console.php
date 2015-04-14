<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'My Console Application',

    // preloading 'log' component
    'preload' => array('log'),

    // application components
    'components' => array(
        /*'db'=>array(
            'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
        ),
        // uncomment the following to use a MySQL database
        */
        'db' => array(
            'connectionString' => 'mysql:host=bratskas.mysql.ukraine.com.ua;dbname=bratskas_tvs',
            'emulatePrepare' => true,
            'username' => 'bratskas_tvs',
            'password' => 'u34g3vzy',
            'charset' => 'utf8',
        ),

        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CWebLogRoute',
                    'categories' => 'application',
                    'levels' => 'error, warning, trace, profile, info',
                ),
            ),
        ),
    ),
);