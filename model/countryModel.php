<?php

/****************Country Models ****************/

function getCountries()
{
	$db = dbConnect();
	$req = $db->query('
    	SELECT
            countryId,
            countryName
        FROM
            Country
        ORDER BY
            countryName
    ');
    
	return $req->fetchAll();
}

