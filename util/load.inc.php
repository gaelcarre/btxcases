<?php

    function loadClass ($class)
    {
        //print_r($class);print_r("<br>");
        $path = __CLASS_DIR__.$class .'_class.php';
        //print_r($path);print_r("<br>");
        if(file_exists($path)){
            //print_r("OK");print_r("<br>");
            require $path;
        }
        	
        $path = __MOD_DIR__.$class .'/'.$class.'_class.php';
        //print_r($path);print_r("<br>");
        if(file_exists($path)){
            //print_r("OK");print_r("<br>");
            require $path;
        }

        $path = __MOD_DIR__.'Default_module/'.$class.'_module_class.php';
        //print_r($path);print_r("<br>");
        if(file_exists($path)){  
            //print_r("OK");print_r("<br>");  
            require $path;
        }

        $path = __PAGE_DIR__.$class.'/'.$class.'_class.php';
        //print_r($path);print_r("<br>");
        if(file_exists($path)){
            //print_r("OK");print_r("<br>");
            require $path;
        }

        $path = __MODELE_DIR__.$class.'.php';
        if(file_exists($path)){
            require $path;
        }
    }
    spl_autoload_register ('loadClass');
    require_once(__LIB_DIR__.'smarty/Smarty.class.php');
    require_once(__CLASS_DIR__.'Lang_class.php');
?>