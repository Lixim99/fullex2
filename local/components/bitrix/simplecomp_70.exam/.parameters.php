<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule("iblock"))
    return;


$arComponentParameters = array(
	"PARAMETERS" => array(
		"PRODUCTS_IBLOCK_ID" => array(
			"NAME" => GetMessage("SIMPLECOMP_EXAM2_CAT_IBLOCK_ID"),
			"TYPE" => "STRING",
		),
        "NEWS_IBLOCK_ID" => array(
			"NAME" => GetMessage("SIMPLECOMP_EXAM2_CAT_IBLOCK_ID_NEWS"),
			"TYPE" => "STRING",
		),
        "NEWS_CODE_IBLOCK_ADDIT" => array(
            "NAME" => GetMessage("SIMPLECOMP_EXAM2_CAT_IBLOCK_ID_NEWS_CODE"),
            "TYPE" => "STRING",
        ),
        "DETAIL_PRODUCT_URL" => array(
            "NAME" => GetMessage("DETAIL_PRODUCT_URL"),
            "TYPE" => "STRING",
        ),
        "NEWS_COUNT" => array(
            'NAME' => GetMessage('NEWS_COUNT'),
            'TYPE' => 'STRING',
            'DEFAULT' => '2',
        ),
        "CACHE_TIME"  =>  array("DEFAULT"=>36000000),
	),
);

CIBlockParameters::AddPagerSettings(
    $arComponentParameters,
    GetMessage("T_IBLOCK_DESC_PAGER_NEWS"), //$pager_title
    true, //$bDescNumbering
    true, //$bShowAllParam
    true, //$bBaseLink
    $arCurrentValues["PAGER_BASE_LINK_ENABLE"]==="Y" //$bBaseLinkEnabled
);
