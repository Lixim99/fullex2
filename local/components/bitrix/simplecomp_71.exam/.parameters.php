<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentParameters = array(
    "PARAMETERS" => array(
        "CATALOG_IBLOCK_ID" => array(
            'NAME' => getMessage('CATALOG_IBLOCK_ID'),
            'TYPE' => 'STRING',
        ),
        "COMPANY_IBLOCK_ID" => array(
            'NAME' => getMessage('COMPANY_IBLOCK_ID'),
            'TYPE' => 'STRING',
        ),
        "PRODUCTS_DETAIL_URL" => array(
            'NAME' => getMessage('PRODUCTS_DETAIL_URL'),
            'TYPE' => 'STRING',
        ),
        "PRODUCTS_PROPERTY_CODE" => array(
            'NAME' => getMessage('PRODUCTS_PROPERTY_CODE'),
            'TYPE' => 'STRING',
        ),
        "CACHE_TIME"  =>  array("DEFAULT"=>36000000),
    ),
);