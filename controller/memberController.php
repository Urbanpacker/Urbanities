<?php

/******** Member controllers ************/

function displayMemberProfile($memberId, $memberIsAdmin)
{
    $latestSpots = getLatestSpot($memberId, $memberIsAdmin);
    for($i = 0, $c = count($latestSpots); $i < $c ; ++$i){
        foreach($latestSpots[$i] as $key => $value){
            $latestSpots[$i][$key] = htmlspecialchars($value) ;
        }
    }
    
    $favoritesSpots = getFavorites($_SESSION['memberId']);
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

function loginMember($memberDataConnection)
{
    $currentMemberData = memberConnection($memberDataConnection);
    if(!$currentMemberData){
        return false ;
    } else{
        foreach($currentMemberData as $key => $value){
            $_SESSION[$key] = htmlspecialchars($value);
        }
        return true;
    }
}

