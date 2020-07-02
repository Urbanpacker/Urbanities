<?php

/******** Favorite controllers **********/

function addToFavController($spotId, $memberId){
    $favoriteManager = new Favorite();
    if(!$favoriteManager->checkIfFavoriteExists($spotId, $memberId)){
        $favoriteManager->addToFav($spotId, $memberId);
    }
    header('Location: index.php?page=spotDetail&spotId='.$spotId);
}

function removeFromFavController($spotId, $memberId){
    $favoriteManager = new Favorite();
    if($favoriteManager->checkIfFavoriteExists($spotId, $memberId)){
        $favoriteManager->removeFromFav($spotId, $memberId);
    }
    header('Location: index.php?page=spotDetail&spotId='.$spotId);
}
