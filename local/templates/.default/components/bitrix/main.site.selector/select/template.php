<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<select onchange="location.href=this.value">
    <?foreach($arResult['SITES'] as $site):?>
    <option value="<?=$site['DIR']?>" <?=($site['CURRENT'] == "Y")? 'selected' : ''?> ><?=$site['LANG']?></option>
    <?endforeach;?>
</select>