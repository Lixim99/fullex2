<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader,
	Bitrix\Iblock;

if(!Loader::includeModule("iblock"))
{
	ShowError(GetMessage("SIMPLECOMP_EXAM2_IBLOCK_MODULE_NONE"));
	return;
}

if(!$productIBlockId = (int) $arParams['PRODUCTS_IBLOCK_ID']) return false;
if(!$newsIBlockId = (int) $arParams['NEWS_IBLOCK_ID']) return false;
if(!$newsCode = trim($arParams['NEWS_CODE_IBLOCK_ADDIT'])) return false;
if(!$prodURL = trim($arParams['DETAIL_PRODUCT_URL'])) return false;
if(!$arParams["NEWS_COUNT"] = (int) $arParams["NEWS_COUNT"]) return false;

if($arParams["NEWS_COUNT"]<=0)
    $arParams["NEWS_COUNT"] = 20;

$arNavParams = array(
    "nPageSize" => $arParams["NEWS_COUNT"],
);

$arNavigation = CDBResult::GetNavParams($arNavParams);


if($this->StartResultCache(false, array($arNavigation, isset($_GET['F'])))){
    if(isset($_GET['F'])) $this->AbortResultCache();

    //products sections query
    $arProductSect = array();
    $arNewID = array();
    $productSectQuery = CIBlockSection::GetList(
        false,
        array('IBLOCK_ID' => $productIBlockId, 'ACTIVE' => 'Y', '!'.$newsCode => false),
        true,
        array('ID', 'NAME', $newsCode)
    );
    while($productSect = $productSectQuery->GetNext())
    {
        foreach($productSect[$newsCode] as $newID){
            $arNewID[] = $newID;
        }

        if($productSect['ELEMENT_CNT'] > 0){
            $arProductSect[$productSect["ID"]] = array(
                'NAME' => $productSect['NAME'],
                'NEWS' => $productSect[$newsCode],
            );
        }
    }

    $arResult['IBLOCK_ID'] = $productIBlockId;

    //news query
    $arNews = array();
    $newsQuery = CIBlockElement::GetList(
            array(),
            Array("IBLOCK_ID"=>$newsIBlockId, "ACTIVE"=>"Y",'ID' => array_unique($arNewID)),
            false,
            $arNavParams,
            Array("ID", "NAME", "DATE_ACTIVE_FROM")
    );
    while($newElem = $newsQuery->GetNext())
    {
        $arNews[$newElem['ID']] = array(
            'NAME' => $newElem['NAME'],
            'DATE_ACTIVE_FROM' => $newElem['DATE_ACTIVE_FROM'],
            'SECTIONS' => array(),
            'PRODUCTS' => array(),
        );
    }

    $arResult["NAV_STRING"] = $newsQuery->GetPageNavString(
        $arParams["PAGER_TITLE"],
        $arParams["PAGER_TEMPLATE"],
        $arParams["PAGER_SHOW_ALWAYS"],
        $this
    );
    $arResult["NAV_CACHED_DATA"] = null;
    $arResult["NAV_RESULT"] = $newsQuery;
    $arResult["NAV_PARAM"] = null;


    $maxPrice = 0;
    $minPrice = 9999999999999;

    //product query
    $arFilter = Array("IBLOCK_ID"=>$productIBlockId, "ACTIVE"=>"Y", 'SECTION_ID' => array_keys($arProductSect));
    if(isset($_GET['F'])){
        $arFilter[] = array(
            'LOGIC' => 'OR',
            array('<=PROPERTY_PRICE' => 1700, "PROPERTY_MATERIAL" => 'Дерево, ткань'),
            array('<PROPERTY_PRICE' => 1500, "PROPERTY_MATERIAL" => 'Металл, пластик')
        );
    }

    $arProducts = array();
    $prodQuery = CIBlockElement::GetList(
        array('NAME' => 'ASC', 'SORT' => 'ASC'),
        $arFilter,
        false,
        false,
        Array("ID", "NAME", "PROPERTY_PRICE", "PROPERTY_MATERIAL", "PROPERTY_ARTNUMBER",'IBLOCK_SECTION_ID','DETAIL_PAGE_URL','CODE')
    );
    $prodQuery->SetUrlTemplates($prodURL);
    while($prodElem = $prodQuery->GetNext())
    {
        $prodID = $prodElem['ID'];

        $arButtons = CIBlock::GetPanelButtons(
            $productIBlockId,
            $prodID,
            0,
            array("SECTION_BUTTONS" => false, "SESSID" => false)
        );

        if($prodElem['PROPERTY_PRICE_VALUE'] > $maxPrice) $maxPrice = $prodElem['PROPERTY_PRICE_VALUE'];
        if($prodElem['PROPERTY_PRICE_VALUE'] < $minPrice) $minPrice = $prodElem['PROPERTY_PRICE_VALUE'];

        $arProducts[$prodID] = array(
            'NAME' => $prodElem['NAME'],
            'PRICE' => $prodElem['PROPERTY_PRICE_VALUE'],
            'MATERIAL' => $prodElem['PROPERTY_MATERIAL_VALUE'],
            'ARTNUMBER' => $prodElem['PROPERTY_ARTNUMBER_VALUE'],
            'DETAIL_PAGE_URL' =>$prodElem['DETAIL_PAGE_URL'],
            "EDIT_LINK" => $arButtons["edit"]["edit_element"]["ACTION_URL"],
            "DELETE_LINK" => $arButtons["edit"]["delete_element"]["ACTION_URL"],
        );

        $IBLOCK_SECTION_ID = $prodElem['IBLOCK_SECTION_ID'];

        foreach($arProductSect[$IBLOCK_SECTION_ID]['NEWS'] as $newsID){
            if (isset($arNews[$newsID])){
                $arNews[$newsID]['PRODUCTS'][] = $prodID;

                if(!in_array($IBLOCK_SECTION_ID, $arNews[$newsID]['SECTIONS'])){
                    $arNews[$newsID]['SECTIONS'][] = $IBLOCK_SECTION_ID;
                }
            }
        }

    }

    $arResult['NEWS'] = $arNews;
    $arResult['SECTIONS'] = $arProductSect;
    $arResult['PRODUCTS'] = $arProducts;
    $arResult['PRODUCT_COUNT'] = count($arProducts);
    $arResult['MAX_PRICE'] = $maxPrice;
    $arResult['MIN_PRICE'] = $minPrice;

    $arButtons = CIBlock::GetPanelButtons(
        $productIBlockId,
        0,
        0,
        array("SECTION_BUTTONS"=>false, "SESSID"=>false)
    );
    $arResult["ADD_ELEMENT_LINK"] = $arButtons["edit"]["add_element"]["ACTION_URL"];

    $this->SetResultCacheKeys(array(
        "PRODUCT_COUNT",
        "MAX_PRICE",
        "MIN_PRICE",
        "IBLOCK_ID",
    ));

    $this->includeComponentTemplate();
}

$res = CIBlock::GetByID($arResult['IBLOCK_ID']);
$ar_res = $res->GetNext();

$this->AddIncludeAreaIcon(
    array(
        'URL'   => '/bitrix/admin/iblock_element_admin.php?IBLOCK_ID=' . $arResult['IBLOCK_ID'] . '&type='. $ar_res['IBLOCK_TYPE_ID'] .'&lang=ru&find_el_y=Y&clear_filter=Y&apply_filter=Y',
        'TITLE' => getMessage('DB_IN_ADMIN'),
        "IN_PARAMS_MENU" => true
    )
);

$APPLICATION->SetPageProperty(
    'ADDITIONAL_INFO',
    '<div style="color:#ff0000; margin: 34px 15px 35px 15px">'. getMessage('MAX_PRICE') . $arResult['MAX_PRICE'] . '</br>' . getMessage('MIN_PRICE') . $arResult['MIN_PRICE'] . '</div>'
    );
$APPLICATION->SetTitle(getMessage('COMP_TITLE') . $arResult['PRODUCT_COUNT'] . getMessage('COMP_TITLE_PRODUCT'));
?>

