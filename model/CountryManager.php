<?php

class CountryManager extends Manager
{
    public function getCountries()
    {
        $db = $this->dbConnect();
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