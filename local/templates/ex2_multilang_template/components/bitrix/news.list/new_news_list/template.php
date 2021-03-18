<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?foreach($arResult['ITEMS'] as $item):?>
<p> <?=$item['DATE_ACTIVE_FROM'] .' <b>'. $item['PROPERTIES']['NEWS_ENG']['VALUE']
    . '</b></br>' . $item['PROPERTIES']['PREW_ENG']["VALUE"]['TEXT'] ?></p>
<?endforeach;?>
