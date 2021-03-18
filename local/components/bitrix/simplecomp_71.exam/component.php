<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader,
    Bitrix\Iblock;

if(!Loader::includeModule("iblock"))
{
    ShowError(GetMessage("SIMPLECOMP_EXAM2_IBLOCK_MODULE_NONE"));
    return;
}

if(!$catalogID = (int) $arParams['CATALOG_IBLOCK_ID']) return false;
if(!$companyID = (int) $arParams['COMPANY_IBLOCK_ID']) return false;
if(!$productDetailURL = trim($arParams['PRODUCTS_DETAIL_URL'])) return false;
if(!$productPropertyCode = trim($arParams['PRODUCTS_PROPERTY_CODE'])) return false;

if($this->StartResultCache(false, $USER->GetUserGroupArray())){
    global $CACHE_MANAGER;
    $CACHE_MANAGER->StartTagCache('');

    //products query
    $arProducts = array();
    $arElem = array();

    $res = CIBlockElement::GetList(
        false,
        Array("IBLOCK_ID"=>$catalogID, "ACTIVE"=>"Y", "CHECK_PERMISSIONS" => "Y", '!PROPERTY_'.$productPropertyCode => false),
        false,
        false,
        Array("ID", "NAME", "PROPERTY_PRICE", "PROPERTY_MATERIAL", "IBLOCK_SECTION_ID", "CODE","DETAIL_PAGE_URL",
            "PROPERTY_ARTNUMBER", "PROPERTY_". $productPropertyCode,
            "PROPERTY_". $productPropertyCode . '.NAME')
    );

    $res->SetUrlTemplates($productDetailURL);
    while($productElem = $res->GetNext())
    {
        if(!isset($arProducts[$productElem['ID']])){
            $arProducts[$productElem['ID']] = array(
                'NAME' => $productElem['NAME'],
                'PRICE' => $productElem['PROPERTY_PRICE_VALUE'],
                'MATERIAL' => $productElem['PROPERTY_MATERIAL_VALUE'],
                'ARTNUMBER' => $productElem['PROPERTY_ARTNUMBER_VALUE'],
                'DETAIL_PAGE_URL' => $productElem['DETAIL_PAGE_URL'],
            );
        }

        $productCodeValue = 'PROPERTY_'.$productPropertyCode.'_VALUE';
        if(!isset($arElem[$productElem[$productCodeValue]])){
            $arElem[$productElem[$productCodeValue]] = array(
                    'NAME' => $productElem["PROPERTY_" .$productPropertyCode.'_NAME'],
                    'PRODUCTS' => array($productElem['ID']),
            );
        }else{
            $arElem[$productElem[$productCodeValue]]['PRODUCTS'][] = $productElem['ID'];
        }

    }

    $arResult['SECTIONS'] = $arElem;
    $arResult['PRODUCTS'] = $arProducts;
    $arResult['SECTIONS_COUNT'] = count($arElem);

    $this->SetResultCacheKeys(array('SECTIONS_COUNT'));

    $this->IncludeComponentTemplate();

    $CACHE_MANAGER->RegisterTag('iblock_id_'.IBLOCK_ID_SERV);
    $CACHE_MANAGER->EndTagCache();
}

$APPLICATION->SetTitle(getMessage('COMPONENT_TITLE') . $arResult['SECTIONS_COUNT']);
?>

