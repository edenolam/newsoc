<?php
function autoload($classname)
{
    if (file_exists($file = dirname(__FILE__) . '/' .$classname. '.php')){
        require $file;
    }
}
spl_autoload_register('autoload');
