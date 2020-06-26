<?php
session_start();

require('controller/frontendController.php');

$db = dbConnect();

function checkRequiredData($dataRequired){
    for($i = 0, $c = count($dataRequired); $i < $c ; ++$i) {
        if(!isset($dataRequired[$i]) || empty($dataRequired[$i])){
            return false;
        }
    }
    return true;
}

/*******************/

define("SESSION_MEMBER_ID", $_SESSION['memberId']);
define("SPOT_ID", $_GET['spotId']);
define("REQUIRED_PAGE", $_GET['page']);


if(NULL === SESSION_MEMBER_ID){
    if(isset($_POST['email']) && isset($_POST['password'])){
        if(loginMember($_POST)){
           header('Location: index.php');
        } else {
            displayLoginForm(true);    
        }
    } else {
        displayLoginForm(false);    
    }
} else {
    $memberIsAdmin = intval($_SESSION['memberIsAdmin']) ;
    $currentMemberId = SESSION_MEMBER_ID;
    if((NULL !== SPOT_ID) && (checkRequiredData([SPOT_ID, $currentMemberId]))){
        switch(REQUIRED_PAGE){
            case 'addToFav' :
                addToFavController(SPOT_ID, $currentMemberId);
                break;
            case 'editSpotForm':
                displayEditSpotForm(SPOT_ID, $currentMemberId, $memberIsAdmin);
                break;
            case 'spotDetail' ;
                displaySingleSpotController(SPOT_ID, $currentMemberId, $memberIsAdmin);
                break;
            case 'removeFromFav':
                removeFromFavController(SPOT_ID, $currentMemberId);
                break;
            case 'deleteSpot':
                deleteSpotController(SPOT_ID, $currentMemberId, $memberIsAdmin);
                break;
            default :
                displayMemberProfile(SESSION_MEMBER_ID, $currentMemberId) ;
        }
    } else{
        switch(REQUIRED_PAGE){
            case 'addNewSpot':
                displaySpotForm($currentMemberId);
                break;
            case 'recordSpot':
                recordSpotController($_POST, $currentMemberId, $memberIsAdmin);
                break;
            case 'categoryList' ;
                displayCategories();
                break;
            case 'spotsByCategory':
                displaySpotsByCategory($_GET['catId'], $currentMemberId, $memberIsAdmin);
                break;
            case 'logout' ;
                logoutMember();
                break;
            default :
                displayMemberProfile(SESSION_MEMBER_ID, $currentMemberId) ;
        }
    }
}
