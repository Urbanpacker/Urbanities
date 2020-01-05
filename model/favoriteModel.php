<?php

/**************** Favorites models **************/

function addToFav($spotId, $memberId)
{	
	$db = dbConnect();
	$req = $db->prepare(
		'INSERT INTO Favorites(
		    fk_spotId,
		    fk_memberId
		)
		VALUES(
        	?,
        	?
        )'
    );
    $affectedLines = $req->execute([$spotId, $memberId]);
    $req->closeCursor();
	return $affectedLines;
}

function removeFromFav($spotId, $memberId)
{	
	$db = dbConnect();
	$req = $db->prepare(
		'DELETE FROM
			Favorites
		WHERE 
		    fk_spotId = ?
		AND
			fk_memberId = ?
		');
    $affectedLines = $req->execute([$spotId, $memberId]);
    $req->closeCursor();
	return $affectedLines;
}

function getFavorites($memberId)
{
	$db = dbConnect();
	$req = $db->prepare('
		SELECT
            spotId,
            spotName,
            spotPostcode,
            spotImage,
            spotAdress,
            catName
		FROM
		    Spots
		INNER JOIN Favorites
		ON
			Favorites.fk_spotId = Spots.spotId
		INNER JOIN Members
		ON
			Favorites.fk_memberId = Members.memberId
		INNER JOIN Categories
		ON
			Categories.catId = Spots.fk_catId
		WHERE
		    Favorites.fk_memberId = ?
    ');
    
	$req->execute([$memberId]);
	$result = $req->fetchAll();
	return $result;
}

function checkIfFavoriteByAnyone($spotId)
{
	$db = dbConnect();
	$req = $db->prepare('
		SELECT
            favoriteId,
            fk_memberId AS memberId,
            fk_spotId AS spotId
 		FROM
		    Favorites
		WHERE
		    Favorites.fk_spotId = ?
	');
    
	$req->execute([$spotId]);
	$result = $req->fetch();
	return $result;
}

function checkIfFavoriteExists($spotId, $memberId)
{
	$db = dbConnect();
	$req = $db->prepare('
		SELECT
            favoriteId
 		FROM
		    Favorites
		WHERE
		    Favorites.fk_spotId = ?
		AND
			Favorites.fk_memberId = ?
    ');
    
	$req->execute([$spotId, $memberId]);
	$result = $req->fetch();
	return $result;
}