<?php

/************** Form controllers *************/

function displayLoginForm($invalidLogin = false)
{
    $title = 'Projet Urbanities - Connexion à l\'espace membre';
    require('views/frontend/loginFormView.php');
}


function displayEditSpotForm($spotId, $memberId, $memberIsAdmin)
{
    $spot = new Spot();
    $spotToEdit = $spot->getSingleSpot($spotId, $memberId, $memberIsAdmin);
    
    if(!$spotToEdit){
        header('Location: index.php');
        die;    
    }
    
    foreach($spotToEdit as $key => $value){
        $spotToEdit[$key] = htmlspecialchars($value) ;
    }

    displaySpotForm($memberId, $spotToEdit);
}

function displayEditMemberForm($memberId)
{
    $member = new Member();
    $memberToEdit = $member->getMember(array('id' => $memberId));
    
    if(!$memberToEdit){
        header('Location: index.php');
        die;    
    }
    
    foreach($memberToEdit as $key => $value){
        $memberToEdit[$key] = htmlspecialchars($value) ;
    }
    
    displayMemberForm($memberToEdit);
}

function displaySpotForm($memberId, $spotData = null)
{
    
    $categories = Category::getCategories() ;
    for($i = 0, $c = count($categories); $i < $c ; ++$i){
        foreach($categories[$i] as $key => $value){
            $categories[$i][$key] = htmlspecialchars($value) ;
        }
    }
    
    $countries = Country::getCountries();
    for($i = 0, $c = count($countries); $i < $c ; ++$i){
        foreach($countries[$i] as $key => $value){
            $countries[$i][$key] = htmlspecialchars($value) ;
        }
    }
    
    if(isset($spotData)){
        foreach($spotData as $key => $value){
            if($value == "Inconnu" || $value == "Inconnue" ){
                $spotExistingData[$key] = '' ;        
                continue;
            }
            $spotExistingData[$key] = $value ;
        }
    } else {
        $spotExistingData= [
            'spotId' => '',
            'spotName' => '',
            'spotPostcode' => '',
            'spotVisibility' => '',
            'spotCreationDate' => '',
            'spotLatitude' => '',
            'spotLongitude' => '',
            'spotDescription' => '',
            'spotAdress' => '',
            'spotAltitude' => '',
            'spotImage' => '',
            'memberPseudo' => '',
            'countryName' => '',
            'catName' => ''
        ];
    }
    $h1 = isset($spotData) ? 'Mise à jour d\'un spot existant' : 'Ajout d\'un nouveau spot' ;
    $authorId = $memberId;
    $title = 'Projet Urbanities - '.$h1;
	require('views/frontend/spotFormView.php');
}


function displayMemberForm($memberData = null)
{   
    $countries = Country::getCountries();
    for($i = 0, $c = count($countries); $i < $c ; ++$i){
        foreach($countries[$i] as $key => $value){
            $countries[$i][$key] = htmlspecialchars($value) ;
        }
    }
    
    if(isset($memberData)){
        foreach($memberData as $key => $value){
            if($value == "Inconnu" || $value == "Inconnue" ){
                $memberExistingData[$key] = '' ;        
                continue;
            }
            $memberExistingData[$key] = $value ;
        }
    } else {
        $memberExistingData= [
            'memberId' => '',
            'memberName' => '',
            'memberPostcode' => '',
            'memberPseudo' => '',
            'memberEmail'  => '',
            'memberPassword' => '',
            'countryName' => ''
        ];
    }
    $h1 = isset($memberData) ? 'Mise à jour du profil d\'un membre' : 'Création d\'un nouveau membre' ;
    $title = 'Projet Urbanities - '.$h1;
	require('views/frontend/memberFormView.php');
}


