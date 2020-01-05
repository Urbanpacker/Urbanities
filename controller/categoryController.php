<?php

/********** Category controllers *******/

function displayCategories()
{
    $categories = getCategories() ;
    for($i = 0, $c = count($categories); $i < $c ; ++$i){
        foreach($categories[$i] as $key => $value){
            $categories[$i][$key] = htmlspecialchars($value) ;
        }
    }
    $title = 'Liste des catégories - Projet Urbanities';
    require('views/frontend/categoryListView.php');
}

