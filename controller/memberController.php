<?php

/******** Member controllers ************/

define('MEMBER_ID', 'memberId');

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
        if(!in_array($key, ['countryId', MEMBER_ID]) && $value ===''){
            $completedForm = false;
        }
    }

    if($completedForm){
        $member = new Member();
        $existingMember = $member->getMember($memberData[MEMBER_ID]);
        if($existingMember){
            $memberId = $member->updateExistingMember($existingMember);
        } else {
            unset($memberData[MEMBER_ID]);
            $memberId = $member->recordNewMember($memberData) ;
        }
    }

    header('Location: index.php');
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
    setcookie('email', '', time() + 1, null, null, false, true);
    setcookie('password', '', time() + 1, null, null, false, true);
    header('Location: index.php');
    die;
}

function loginMember($memberDataConnection){
    
    //$memberDataConnection['password'] = password_hash(memberDataConnection['password'], PASSWORD_DEFAULT);
    
    $member = new Member();
    $currentMemberData = $member->memberConnection($memberDataConnection);

    if(!$currentMemberData){
        return false ;
    } else{
        foreach($currentMemberData as $key => $value){
            $_SESSION[$key] = htmlspecialchars($value);
        }
        return true;
    }
}

