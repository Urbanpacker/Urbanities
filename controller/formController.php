<?php

/************** Form controllers *************/

function displayLoginForm($invalidLogin = false)
{
    $title = 'Projet Urbanities - Connexion à l\'espace membre';
    require('views/frontend/loginFormView.php');
}


function displayEditSpotForm($spotId, $memberId, $memberIsAdmin)
{
    $spotToEdit = getSingleSpot($spotId, $memberId, $memberIsAdmin);
    
    if(!$spotToEdit){
        header('Location: index.php');
        die;    
    }
    
    foreach($spotToEdit as $key => $value){
        $spotToEdit[$key] = htmlspecialchars($value) ;
    }
    
    displaySpotForm($memberId, $spotToEdit);
}

function displaySpotForm($memberId, $spotData = null)
{
    $categories = getCategories() ;
    for($i = 0, $c = count($categories); $i < $c ; ++$i){
        foreach($categories[$i] as $key => $value){
            $categories[$i][$key] = htmlspecialchars($value) ;
        }
    }
    
    $countries = getCountries();
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

