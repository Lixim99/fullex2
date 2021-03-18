<?
    CJSCore::Init(array("jquery"));
    if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/constants.php')){
        require_once($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/constants.php');
    }
    if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/agents.php')){
        require_once($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/agents.php');
    }
    if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/events.php')){
            require_once($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/events.php');
    }

?>