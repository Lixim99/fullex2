<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader,
    Bitrix\Iblock;

if(!Loader::includeModule("iblock"))
{
    ShowError(GetMessage("SIMPLECOMP_EXAM2_IBLOCK_MODULE_NONE"));
    return;
}

if(!$CurentUserID = $USER->GetID()){
    return;
}

if(!$newsIBlockID = (int) $arParams['IBLOCK_NEWS_ID']) return false;
if(!$authorCode = trim($arParams['AUTHOR_CODE'])) return false;
if(!$authorType = trim($arParams['AUTHOR_TYPE'])) return false;


if($this->StartResultCache(false,$CurentUserID)){

    $rsUserID = CUser::GetByID($CurentUserID);
    $arUserRes = $rsUserID->Fetch();
    $curUserProp = $arUserRes[$authorType];


    //users query
    $arUsers = array();

    $arUsersQuery = CUser::GetList(
        ($by="personal_country"),
        ($order="desc"),
        array('ACTIVE' => 'Y', $authorType => $curUserProp, '!ID' => $CurentUserID),
        array('FIELDS' => array('ID','LOGIN'))
    );

    while($UserRes = $arUsersQuery->Fetch()){
        $arUsers[$UserRes['ID']] = array(
            'LOGIN' => $UserRes['LOGIN'],
            'NEWS' => array(),
        );
    }

    // news query
    $arNews = array();

    $res = CIBlockElement::GetList(
        false,
        Array("IBLOCK_ID"=>$newsIBlockID, "ACTIVE"=>"Y",'!PROPERTY_' . $authorCode => $CurentUserID, 'PROPERTY_' . $authorCode => array_keys($arUsers)),
        false,
        false,
        Array("ID", "NAME", "DATE_ACTIVE_FROM", 'PROPERTY_' . $authorCode,'IBLOCK_ID')
    );

        while($new = $res->GetNextElement()){
            $arNew = $new->GetFields();
            $arNew['PROPERTIES'] = $new->GetProperties();
            if(!in_array($CurentUserID,$arNew['PROPERTIES']['AUTHOR']['VALUE'])){
                if(!isset($arNews[$arNew['ID']])){
                    $arNews[$arNew['ID']] = array(
                    'NEWS_NAME' => $arNew['NAME'],
                    'DATE_ACTIVE_FROM' => $arNew['DATE_ACTIVE_FROM'],
                     );
                }
                $arUsers[$arNew['PROPERTY_'. $authorCode . '_VALUE']]['NEWS'][] = $arNew['ID'];
            }
        }

    $arResult['USERS'] = $arUsers;
    $arResult['NEWS'] = $arNews;
    $arResult['NEWS_COUNT'] = count($arNews);

    $this->SetResultCacheKeys(array('NEWS_COUNT'));
    $this->IncludeComponentTemplate();
}
$APPLICATION->SetPageProperty('h1', getMessage('NEWS_H1') .  $arResult['NEWS_COUNT']);
$APPLICATION->SetPageProperty('title', getMessage('NEWS_TITLE') . $arResult['NEWS_COUNT']);