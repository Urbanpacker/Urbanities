<?php

class CategoryManager extends Manager
{
    public function getCategories()
    {
        $db = $this->dbConnect();
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