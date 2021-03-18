<?
    use Bitrix\Main\Grid\Declension;

    define('NEW_USERS_MAIL_TEMP', 32);
    define('ADMIN_GROUP', 1);

    function CheckUserCount(){
        $last_user_id = COption::GetOptionInt("main", "last_user_id", 0);
        $rsUsers = CUser::GetList(
            ($by="id"),
            ($order="desc"),
            array('>ID' => $last_user_id),
            array('FIELDS' => array('ID','DATE_REGISTER'))
        );

        $sDateNow = new DateTime();

        if($countUsers = $rsUsers->SelectedRowsCount()){
           $ar_last_user = $rsUsers->Fetch();
           $day_cheker = COption::GetOptionInt("main", "day_cheker", 1346506620);
           $dateFromTim = \Bitrix\Main\Type\DateTime::createFromTimestamp($day_cheker);
           $sDatePast = DateTime::createFromFormat('Y-m-d H:i:s', $dateFromTim->format('Y-m-d H:i:s'));
           $days = $sDateNow->diff($sDatePast)->days;
           if(!$days) $days = 1;

            $countDeclension = new Declension(' слово', ' слова', ' слов');
            $days .= $countDeclension->get($days);

            $arAns = array(
                'COUNT' => $countUsers,
                'DAYS' => $days
            );

            $rsAdmins = CUser::GetList(
                ($by="id"),
                ($order="desc"),
                array('GROUPS_ID' => ADMIN_GROUP),
                array('FIELDS' => array('ID','EMAIL'))
            );
            while($rsAdmin = $rsAdmins->Fetch()){
                $arAns['EMAIL'] = $rsAdmin['EMAIL'];
                CEvent::Send('NEW_USERS', 's1', $arAns, "N", NEW_USERS_MAIL_TEMP);
            }
        COption::SetOptionInt("main", "last_user_id", $ar_last_user['ID']);
        }

        COption::SetOptionInt("main", "day_cheker",$sDateNow->getTimestamp());

        return 'CheckUserCount();';
    }
?>