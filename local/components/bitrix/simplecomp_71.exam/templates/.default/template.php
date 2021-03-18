<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?= getMessage('TIME_MARK') . time()?>
<div>---</br></div>
<p><b><?=GetMessage("SIMPLECOMP_ANOTHER_EXAM2_CAT_TITLE")?></b></p>
<ul>
    <?foreach($arResult['SECTIONS'] as $section):?>
    <li><?= '<b>' . $section['NAME'] . '</b>'?>
        <ul>

            <?foreach($section['PRODUCTS'] as $prodElem):?>
            <li>
                <?=
                $arResult['PRODUCTS'][$prodElem]['NAME'] . ' - ' . $arResult['PRODUCTS'][$prodElem]['PRICE'] . ' - '
                    . $arResult['PRODUCTS'][$prodElem]['MATERIAL'] . ' - ' . ' <a href="' . $arResult['PRODUCTS'][$prodElem]['DETAIL_PAGE_URL'] . '">' . GetMessage('PRODUCT') . '</a>'
                ?>
            </li>
            <?endforeach;?>
        </ul>
    </li>
    <?endforeach;?>
</ul>
