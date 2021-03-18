<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<p>
    <?=GetMessage('FILTER')?>
    <a href="<?=$APPLICATION->GetCurDir() . '?F=Y'?>"><?=$APPLICATION->GetCurDir() . '?F=Y'?></a>
</p>
<div>---</br></div>
<p><b><?=GetMessage("SIMPLECOMP_EXAM2_CAT_TITLE")?></b></p>
    <?
        $this->AddEditAction('iblock_'.$arResult['IBLOCK_ID'], $arResult['ADD_ELEMENT_LINK'], CIBlock::GetArrayByID($arResult['IBLOCK_ID'], "ELEMENT_ADD"));
    ?>

<ul id="<?=$this->GetEditAreaId('iblock_'.$arResult['IBLOCK_ID']);?>">
    <?foreach($arResult['NEWS'] as $newsKey => $newsElem):?>
    <?
        $sectionStr = '';
        foreach($newsElem['SECTIONS'] as $secID){
            $arRep = array_keys($arResult['SECTIONS'][$secID]['NAME']);
            $sectionStr .=  ', ' .$arResult['SECTIONS'][$secID]['NAME'];
        }
        ?>

    <?if(count($newsElem['SECTIONS']) > 0 || count($newsElem['PRODUCTS']) > 0):?>
    <li>
        <?= '<b>' . $newsElem['NAME'] . '</b>' . ' - ' . $newsElem['DATE_ACTIVE_FROM'] . ' (' . $arResult["SECTION"][$newsElem["IBLOCK_SECTION_ID"]] . substr($sectionStr, 2) . ')'?>
        <ul>
            <?
            foreach($newsElem['PRODUCTS'] as $productItem):
                $ermID = $newsKey . '_' . $productItem;
                $this->AddEditAction($ermID, $arResult['PRODUCTS'][$productItem]['EDIT_LINK'], CIBlock::GetArrayByID($arResult["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($ermID, $arResult['PRODUCTS'][$productItem]['EDIT_LINK'], CIBlock::GetArrayByID($arResult["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            ?>
                <li id="<?=$this->GetEditAreaId($ermID);?>">
                    <?=
                        $arResult['PRODUCTS'][$productItem]['NAME'] . ' - '. $arResult['PRODUCTS'][$productItem]['PRICE']
                        . ' - ' . $arResult['PRODUCTS'][$productItem]['MATERIAL'] . ' - '  . $arResult['PRODUCTS'][$productItem]['ARTNUMBER'] . ' (' . $arResult['PRODUCTS'][$productItem]['DETAIL_PAGE_URL'] . ')';
                    ?>
                </li>
            <?endforeach;?>
        </ul>
    </li>
    <?endif;?>
    <?endforeach;?>
</ul>
<?= $arResult['NAV_STRING'];?>
