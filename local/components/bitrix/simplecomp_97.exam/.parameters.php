<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentParameters = array(
    "PARAMETERS" => array(
        "IBLOCK_NEWS_ID" => array(
            "NAME" => getMessage('IBLOCK_NEWS_ID'),
            "TYPE" => 'STRING',
        ),
        "AUTHOR_CODE" => array(
            "NAME" => getMessage('AUTHOR_CODE'),
            "TYPE" => 'STRING',
        ),
        "AUTHOR_TYPE" => array(
            "NAME" => getMessage('AUTHOR_TYPE'),
            "TYPE" => 'STRING',
        ),
        "CACHE_TIME"  =>  array("DEFAULT"=>36000000),
    )
);
