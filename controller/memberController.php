<?php

/******** Member controllers ************/

define('MEMBER_ID', 'memberId');
define('PASSWORD', 'password');
define('EMAIL','email');

function editMemberController($memberData)
{    
    $member = new Member();
    $memberData[MEMBER_ID] = $_SESSION[MEMBER_ID];

    $existingMember = $member->getMember(array('id' => $memberData[MEMBER_ID]));
    $existingEmailOwner = $member->getMember(array('email' => $memberData[EMAIL]));

    $memberData[PASSWORD] = password_hash($memberData[PASSWORD], PASSWORD_DEFAULT);

    if($existingEmailOwner && ($existingEmailOwner[MEMBER_ID] !== $existingMember[MEMBER_ID])){
        displayEditMemberForm($memberData[MEMBER_ID]);
    } else {
        $memberId = $member->updateExistingMember($memberData);
        header('Location: index.php');
    }
}

function recordMemberController($memberData)
{    
    $numberValues = ['postcode', 'countryId'];
	foreach($memberData as $key => $value){
		if(in_array($key, $numberValues)){
			$memberData[$key] = intval($value);
			continue;
		}
		$memberData[$key] = $value;
	}
    
    $completedForm = true;
    foreach($memberData as $key => $value){
        if(!in_array($key, ['countryId']) && $value ===''){
            $completedForm = false;
        }
    }

    $memberData[PASSWORD] = password_hash($memberData[PASSWORD], PASSWORD_DEFAULT);
    
    $member = new Member();
    
    $existingEmailOwner = $member->getMember(array($memberData[EMAIL]));
    
    if(!$existingEmailOwner && $completedForm){
        $memberId = $member->recordNewMember($memberData) ;
        header('Location: index.php');
    } else {
        displayMemberForm($memberData);
    }
    
}

function displayMemberProfile($memberId, $memberIsAdmin)
{
    $latestSpots = Spot::getLatestSpot($memberId, $memberIsAdmin);
    for($i = 0, $c = count($latestSpots); $i < $c ; ++$i){
        foreach($latestSpots[$i] as $key => $value){
            $latestSpots[$i][$key] = htmlspecialchars($value) ;
        }
    }
    
    $favoritesSpots = Favorite::getFavorites($_SESSION[MEMBER_ID]);
    for($i = 0, $c = count($favoritesSpots); $i < $c ; ++$i){
        foreach($favoritesSpots[$i] as $key => $value){
            $favoritesSpots[$i][$key] = htmlspecialchars($value) ;
        }
    }
    $title = 'Accueil - Projet Urbanities' ;
    require('views/frontend/memberProfileView.php');
}

function logoutMember()
{
    unset($_SESSION);
    session_destroy();
    header('Location: index.php');
    die;
}

function loginMember($memberDataConnection){
    
    $member = new Member();

    $currentMemberData = $member->memberConnection($memberDataConnection[EMAIL]);

    $authSuccess = password_verify($memberDataConnection[PASSWORD], $currentMemberData['memberPassword']);

    if(!$authSuccess){
        return false ;
    } else{
        $member->recordConnectionTimestamp($currentMemberData[MEMBER_ID]);
        foreach($currentMemberData as $key => $value){
            $_SESSION[$key] = htmlspecialchars($value);
        }
        return true;
    }
}