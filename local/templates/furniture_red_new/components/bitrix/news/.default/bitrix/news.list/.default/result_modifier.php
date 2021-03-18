<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if(isset($arParams["SPECIAL_DATE_VALUE"]) && $arParams["SPECIAL_DATE_VALUE"] == 'Y'){
    $arResult["SPECIAL_DATE_VALUE"] = $arResult['ITEMS'][0]["ACTIVE_FROM"];
    $this->getComponent()->SetResultCacheKeys(array("SPECIAL_DATE_VALUE"));
};
?>