<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
    if(strlen($arParams["CANONICAL_REL_VALUE"])>0){
        $arSelect = Array("NAME","ID");
        $arFilter = Array("IBLOCK_ID"=>$arParams["CANONICAL_REL_VALUE"], "PROPERTY_NEWS_UNDER" => $arResult["ID"]);
        $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
        if($Name = $res->GetNext()){
            $this->getComponent()->SetResultCacheKeys(array("CANONICAL_REL_VALUE"));
            $arResult["CANONICAL_REL_VALUE"] = $Name;
        }
    }
?>

