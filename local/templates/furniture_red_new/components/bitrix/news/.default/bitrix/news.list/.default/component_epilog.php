<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if(isset($arResult["SPECIAL_DATE_VALUE"])){
    $APPLICATION->SetPageProperty('specialdate',$arResult["SPECIAL_DATE_VALUE"]);
}
?>