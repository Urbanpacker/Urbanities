<?php

/************** Categor Models ***********/

function getCategories()
{
    $db = dbConnect();
	$req = $db->query('
    	SELECT
            catId,
            catName,
            catDescription,
            catImage
        FROM
            Categories
        ORDER BY
            catName
    ');
    
	return $req->fetchAll();
}

function getCategoryImage($catId)
{
	$catId = [$catId];
	$db = dbConnect();
	$req = $db->prepare('
    	SELECT
            catImage
        FROM
            Categories
        WHERE
        	catId = ?
    ');
    
	$req->execute($catId);
	$result = $req->fetch();
	return $result['catImage'];
}
