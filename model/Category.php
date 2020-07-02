<?php

class Category extends Manager
{
    static public function getCategories()
    {
        $db = self::dbConnect();
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

    public function getCategoryImage($catId)
    {
        $catId = [$catId];
        $db = $this->dbConnect();
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
}