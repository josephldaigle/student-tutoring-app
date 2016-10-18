<?php

session_start();

$debug = false;     //set to true to print debug info on main screen when app launches

/*========== CONTSTANTS ===========*/

define('HOST', filter_input(INPUT_SERVER, 'HTTP_HOST', FILTER_SANITIZE_STRING));

/*=================================*/

/*====== ERROR REPORTING ========*/
//error_reporting(E_ALL);
/*=================================*/

/*====== AUTOLOAD CLASSES ========*/
    function __autoload($class_name)
    {
        //class directories
        $directories = array(
            './',
            'includes/',
            'model/',
            'model/dao/',
            'view/',
            'view/parts/',
            'controllers/'
        );

        //for each directory
        foreach($directories as $directory)
        {
            //see if the file exsists
            if(file_exists($directory.$class_name . '.php'))
            {
                require ($directory.$class_name . '.php');
                return;
            }
        }
    }
/*=============================*/

    
    
/*========== ROUTING ==========*/

MainController::getInstance();
MainController::route();

/*=============================*/



    
/*========== DEBUG ============*/
    if ($debug) {
        echo "<br/><br/>====== GET =======<br/>";
        if(isset($_GET)) {
            foreach($_GET as $key => $val) {
                echo "$key: $val <br/>";
            }
        }

        echo "<br/><br/>====== POST =======<br/>";
        if(isset($_POST)) {
            foreach($_POST as $key => $val) {
                echo "$key: $val <br/>";
            }
        }
        
        echo "<br/><br/>====== POST =======<br/>";
        if(isset($_SERVER)) {
            foreach($_SERVER as $key => $val) {
                echo "$key: $val <br/>";
            }
        }
        
    }
/*===========================*/