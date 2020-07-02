<?php

class Country extends Manager
{
    static public function getCountries()
    {
        $db = self::dbConnect();
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

}