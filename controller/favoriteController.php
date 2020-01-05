<?php

/******** Favorite controllers **********/

function addToFavController($spotId, $memberId){
    if(!checkIfFavoriteExists($spotId, $memberId)){
        addToFav($spotId, $memberId);
    }
    header('Location: index.php?page=spotDetail&spotId='.$spotId);
}

function removeFromFavController($spotId, $memberId){
    if(checkIfFavoriteExists($spotId, $memberId)){
        removeFromFav($spotId, $memberId);
    }
    header('Location: index.php?page=spotDetail&spotId='.$spotId);
}
