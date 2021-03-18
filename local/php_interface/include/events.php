<?
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", Array("IBlockHandler", "OnBeforeIBlockElementUpdateHandler"));


class IBlockHandler
{
    function OnBeforeIBlockElementUpdateHandler($arFields)
    {
        if($arFields['ACTIVE'] == 'N' && $arFields["IBLOCK_ID"] == PRODUCT_IBLOCK)
        {
            $arSelect = Array("ID", "SHOW_COUNTER");
            $arFilter = Array("IBLOCK_ID"=>PRODUCT_IBLOCK, "ACTIVE"=>"Y", "ID"=>$arFields["ID"], ">SHOW_COUNTER" => 2);
            $res = CIBlockElement::GetList(false, $arFilter, false, false, $arSelect);
            if($Elem = $res->GetNext()){
                global $APPLICATION;
                $APPLICATION->throwException('Товар невозможно деактивировать, у него ' . $Elem["SHOW_COUNTER"] . ' просмотров');
                return false;
            }
        }
    }
}

AddEventHandler("main", "OnEpilog", 'Ans404',1);

function Ans404(){
    if(defined('ERROR_404') && ERROR_404=='Y'){
        CEventLog::Add(array(
            "SEVERITY" => "INFO",
            "AUDIT_TYPE_ID" => "ERROR_404",
            "MODULE_ID" => "main",
            "DESCRIPTION" => $_SERVER['REQUEST_URI'],
        ));
    }
}

AddEventHandler("main", "OnBeforeEventAdd", array("MyClass", "OnBeforeEventAddHandler"));
class MyClass
{
    function OnBeforeEventAddHandler(&$event, &$lid, &$arFields)
    {
        if ($event == 'FEEDBACK_FORM'){
            GLOBAL $USER;
            if($USER->IsAuthorized()){
                $arFields["AUTHOR"] = "Пользователь авторизован: id-" .  $USER->GetID() . " (" . $USER->GetLogin() .  ") " . $USER->GetFirstName() . ", данные из формы:" . $arFields["AUTHOR"];
            }else{
                $arFields["AUTHOR"] = "Пользователь не авторизован, данные из формы:" . $arFields["AUTHOR"];
            }
        }

        CEventLog::Add(array(
            "SEVERITY" => "INFO",
            "AUDIT_TYPE_ID" => "FEEDBACK_FORM",
            "MODULE_ID" => "main",
            "DESCRIPTION" => "Замена данных в отсылаемом письме – " . $arFields["AUTHOR"],
        ));
    }
}

AddEventHandler("main", "OnBuildGlobalMenu", "OnBuildGlobalMenuHandler");
function OnBuildGlobalMenuHandler(&$aGlobalMenu,&$aModuleMenu)
{
    global $USER;

    if(in_array(CONT_REW_GROUP,$USER->GetUserGroupArray())){
        foreach($aGlobalMenu as $k=>$v){
            if($k != 'global_menu_content') unset($aGlobalMenu[$k]);
        }
        foreach($aModuleMenu as $k=>$v){
            if($v['items_id'] != 'menu_iblock_/news') unset($aModuleMenu[$k]);
        }
    }
}

AddEventHandler("main", "OnPageStart", "OnPageStartHandler");

function OnPageStartHandler()
{
    GLOBAL $APPLICATION;
    $CurDir = $APPLICATION->GetCurDir();
    if(CModule::IncludeModule("iblock")){
        $res = CIBlockElement::GetList(
            false,
            array("IBLOCK_ID" => META_IBLOCK, "ACTIVE" => "Y", 'NAME' => $CurDir),
            false,
            false,
            array("ID", "NAME", "PROPERTY_META_TITLE", "PROPERTY_META_DESCRIPTION")
        );
        if ($Item = $res->GetNext()) {
           $APPLICATION->SetPageProperty('title', $Item["PROPERTY_META_TITLE_VALUE"]);
           $APPLICATION->SetPageProperty('description', $Item["PROPERTY_META_DESCRIPTION_VALUE"]);
        }
    }
}
?>