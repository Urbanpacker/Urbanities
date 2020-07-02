<?php

/******** Spot controllers ************/

function displaySpotsByCategory($catId, $memberId, $memberIsAdmin)
{
    $spot = new Spot();
    $spotsCategorized = $spot->getSpotsByCategory($catId, $memberId, $memberIsAdmin);
    
    if(!$spotsCategorized){
        $h1 = 'Aucun spot ne correspond a votre recherche';
    } else{
        for($i = 0, $c = count($spotsCategorized); $i < $c ; ++$i){
            foreach($spotsCategorized[$i] as $key => $value){
                $spotsCategorized[$i][$key] = htmlspecialchars($value) ;
            }
        }
        $h1 = 'Spots du type : '.$spotsCategorized[0]['catName'] ;
    }
    $title = $h1.' - Projet Urbanities' ;
    require('views/frontend/spotsByCategoryView.php');
}

function recordSpotController($spotData, $memberId, $memberIsAdmin)
{
    $numberValues = ['memberId', 'categoryId', 'postcode', 'countryId', 'visibility'];
	$optionalData = ['postcode', 'latitude', 'longitude', 'altitude', 'description'];
	foreach($spotData as $key => $value){
		if(in_array($key, $numberValues)){
			$spotData[$key] = intval($value);
			continue;
		}
		if(in_array($key, $optionalData) && $value == ''){
			$spotData[$key] = ($key == 'postcode') ? 'Inconnu' : 'Inconnue';
		    continue;
		}
		$spotData[$key] = $value;
	}
    
    $category = new Category();
    $spotData['image'] = $category->getCategoryImage($spotData['categoryId']);

    $spot = new Spot();
    $existingSpot = $spot->getSingleSpot($spotData['spotId'], $memberId, $memberIsAdmin);

    if($existingSpot){
        $spotId = $spot->updateExistingSpot($spotData);
    } else {
        $spotId = $spot->recordNewSpot($spotData) ;
    }
    header('Location: index.php?page=spotDetail&spotId='.$spotId);
}

function deleteSpotController($spotId, $memberId, $memberIsAdmin)
{
    $spot = new Spot();
    $spotDetail = $spot->getSingleSpot($spotId, $memberId, $memberIsAdmin) ;
    
    if(!$spotDetail){
        header('Location: index.php');
        die;    
    }
    
    $favorite = new Favorite();
    $favoriteSpot = $favorite->checkIfFavoriteByAnyone($spotId) ;

    if((!intval($spotDetail['spotVisibility']) && $spotDetail['memberId'] == $memberId ) || $memberIsAdmin){
        if($favoriteSpot){
            $favorite->removeFromFav($favoriteSpot['spotId'], $favoriteSpot['memberId']);
        }
        $spot->deleteSpot($spotId);
        header('Location: index.php');
    } else {
        header('Location: index.php?page=spotDetail&spotId='.$spotId);
    }
}

function displaySingleSpotController($spotId, $memberId, $memberIsAdmin=0)
{  
    $spot = new Spot();
    $spotDetail = $spot->getSingleSpot($spotId, $memberId, $memberIsAdmin);
   
    if(!$spotDetail){
        header('Location: index.php');
        die;
    }

    foreach($spotDetail as $key => $value){
        $spotDetail[$key] = htmlspecialchars($value) ;
    }
 
    $favorite = new Favorite();    
    $spotInFavorites = $favorite->checkIfFavoriteExists($spotId, $memberId) ? true : false ;
    
    $h1 = $spotDetail['spotName'];
	$title = $h1.' - Projet Urbanities' ;
	$visibility = intval($spotDetail['spotVisibility']) ? 'Tout le monde' : 'Moi uniquement' ; 

// A member can modify a spot only if he is an admin OR if he is the original author AND if the spot visibility is "private"
// If he is an admin, the visibility of the spot does not matter
// Same rule is set for the deletability of a spot
    $updatable = ($spotDetail['memberId'] == $memberId && !intval($spotDetail['spotVisibility']) || $memberIsAdmin == 1) ;
    $deletable = $updatable ;
    require('views/frontend/displaySingleSpotView.php');
}