<?php

/************** Spots models ***********/

function getLatestSpot($memberId,$memberIsAdmin = 0)
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
		INNER JOIN
			Members
		ON Members.memberId = Spots.fk_authorId
		INNER JOIN 
			Categories
		ON Categories.catId = Spots.fk_catId
        WHERE (
        	spotVisibility = 1
		OR
			Members.memberId = ?
		OR 
			? = 1)
        ORDER BY spotCreationTimestamp
        DESC
        LIMIT 0,5
    ');
    
	$req->execute([$memberId, $memberIsAdmin]);
	$result = $req->fetchAll();
	
	return $result;
}

function getSingleSpot($spotId, $memberId,$memberIsAdmin = 0)
{
	$db = dbConnect();
	$req = $db->prepare('
		SELECT
		    spotId,
		    spotName,
		    spotPostcode,
		    spotVisibility,
		    DATE_FORMAT(
		        spotCreationTimestamp,
		        "%d/%m/%Y à %Hh%i"
		    ) AS spotCreationDate,
		    spotLatitude,
		    spotLongitude,
		    spotDescription,
		    spotAdress,
		    spotAltitude,
		    spotImage,
		    Members.memberPseudo AS memberPseudo,
		    Members.memberId AS memberId,
		    Country.countryName AS countryName,
		    Country.countryId AS countryId,
		    Categories.catId AS catId,
		    Categories.catName AS catName
		FROM
		    Spots
		INNER JOIN Members ON Spots.fk_authorId = Members.memberId
		INNER JOIN Country ON Spots.fk_countryId = Country.countryId
		INNER JOIN Categories ON Spots.fk_catId = Categories.catId
		WHERE
		    spotId = ?
		AND (
			spotVisibility = 1
		OR
			Members.memberId = ?
		OR 
			? = 1)
    ');
    
	$req->execute([$spotId, $memberId, $memberIsAdmin]);
	$result = $req->fetch();
	
	return $result;
}

function getSpotsByCategory($catId, $memberId, $memberIsAdmin = 0)
{
	$db = dbConnect();
	$req = $db->prepare('
    	SELECT
    		catName,
			Spots.fk_catId,
            spotId,
            spotName,
            spotPostcode,
            spotAdress,
            spotImage
        FROM
        	Spots
    	INNER JOIN
    		Members
    	ON 
    		Members.memberId = Spots.fk_authorId
		INNER JOIN
			Categories
        ON
        	Categories.catId = Spots.fk_catId
        WHERE
        	Spots.fk_catId = ?
		AND (
			spotVisibility = 1
		OR
			Members.memberId = ?
		OR 
			? = 1)
        ORDER BY
        	spotName
        DESC
        LIMIT 0,9
    ');
    $req->execute([$catId, $memberId, $memberIsAdmin]);
	$result = $req->fetchAll();
	$req->closeCursor();
	return $result;
}

function updateExistingSpot($spotData)
{
	
$db = dbConnect();
	$req = $db->prepare(
		'UPDATE Spots
			SET 
				spotName = :name,
			    spotPostcode = :postcode,
			    spotVisibility = :visibility,
			    spotLatitude = :latitude,
			    spotLongitude = :longitude,
			    spotDescription = :description,
			    spotAdress = :adress,
			    spotAltitude = :altitude,
			    spotImage = :image,
			    fk_authorId = :memberId,
			    fk_catId = :categoryId,
			    fk_countryId = :countryId
			WHERE
				spotId = :spotId
	');
    $req->execute($spotData);
    $req->closeCursor();
    return $spotData['spotId']; 
}


function recordNewSpot($spotData)
{

	$db = dbConnect();
	$req = $db->prepare(
		'INSERT INTO Spots(
		    spotName,
		    spotPostcode,
		    spotVisibility,
		    spotLatitude,
		    spotLongitude,
		    spotDescription,
		    spotAdress,
		    spotAltitude,
		    spotImage,
		    fk_authorId,
		    fk_catId,
		    fk_countryId,
		    spotCreationTimestamp
		)
		VALUES(
        	:name,
            :postcode,
            :visibility,
            :latitude,
            :longitude,
            :description,
            :adress,
            :altitude,
            :image,
            :memberId,
            :categoryId,
            :countryId,
            NOW()
        )'
    );
    $req->execute($spotData);
    $req->closeCursor();
    
    $newSpotId = $db->lastInsertId();
    
    return $newSpotId; 
}

function deleteSpot($spotId)
{	
	$db = dbConnect();
	$req = $db->prepare(
		'DELETE FROM
			Spots
		WHERE 
		    spotId = ?
		');
    $affectedLines = $req->execute([$spotId]);
    $req->closeCursor();
	return $affectedLines;
}