<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arTemplateParameters = array(
    "SPECIAL_DATE_VALUE" => Array(
        "NAME" => 'specialdate',
        "TYPE" => "CHECKBOX",
        "DEFAULT" => "N",
    ),
    "CANONICAL_REL" => Array(
        "NAME" => 'ID информационного блока для rel=canonical',
        "TYPE" => "STRING",
        "DEFAULT" => "",
    ),
    "AJAX_FEEDBACK" => Array(
        "NAME" => getMessage('NAME_AJAX'),
        "TYPE" => "CHECKBOX",
        "DEFAULT" => "N",
    ),
);
?>
