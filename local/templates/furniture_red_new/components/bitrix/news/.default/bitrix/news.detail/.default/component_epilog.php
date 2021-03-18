<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(isset($arResult["CANONICAL_REL_VALUE"])){
    $APPLICATION->SetPageProperty('canonical',$arResult["CANONICAL_REL_VALUE"]["NAME"]);
}

if($newsID = (int)$_REQUEST['newsID']){

    define('IBLOCK_FOR_COMPLAINS', 8);

    $name = 'NEWS_' . $newsID;

    if($curUserID = $USER->GetID()){
        $rsUser = CUser::GetByID($curUserID);
        $arUser = $rsUser->Fetch();
        $ansForUser = $curUserID . ', ' . $arUser['LOGIN'] . ', ' .
            $arUser['LAST_NAME'] . ', ' . $arUser['NAME'] .', ' . $arUser['SECOND_NAME'];
    }else{
        $ansForUser = getMessage('NOT_AUTH');
    }

    $objDateTime = new DateTime();
    $el = new CIBlockElement;
    if($newComp = $el->Add(array(
        "IBLOCK_ID"=>IBLOCK_FOR_COMPLAINS,
        'NAME'=>$name,
        'ACTIVE_FROM' => $objDateTime->format("d.m.Y H:i:s"),
        'PROPERTY_VALUES' => array(
            'USER_AUTH'=> $ansForUser,
            'NEWS'=> $newsID,
        ),
    ))){
        $ans = getMessage('ACCEPT_SEND') . $newComp;
    }else{
        $ans = getMessage('ERROR');
    }
    if($_REQUEST['ajax'] == 1):
        $APPLICATION->RestartBuffer();
        echo json_encode($newComp);
        exit;
    ?>
    <?else:?>
    <script>
        $('#send_denied').html('<?=$ans?>').show();
    </script>
    <?
    endif;
}
?>