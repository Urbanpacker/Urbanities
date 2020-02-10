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

    if(!isset($_SESSION['memberId'])){
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
        $currentMemberId = $_SESSION['memberId'];
        if(!isset($_GET['page'])){
            displayMemberProfile($_SESSION['memberId'], $currentMemberId);
        } else {
            if(isset($_GET['spotId']) && (checkRequiredData([$_GET['spotId'], $currentMemberId]))){
                switch($_GET['page']){
                    case 'addToFav' :
                        addToFavController($_GET['spotId'], $currentMemberId);
                        break;
                    case 'editSpotForm':
                        displayEditSpotForm($_GET['spotId'], $currentMemberId, $memberIsAdmin);
                        break;
                    case 'spotDetail' ;
                        displaySingleSpotController($_GET['spotId'], $currentMemberId, $memberIsAdmin);
                        break;
                    case 'removeFromFav':
                        removeFromFavController($_GET['spotId'], $currentMemberId);
                        break;
                    case 'deleteSpot':
                        deleteSpotController($_GET['spotId'], $currentMemberId, $memberIsAdmin);
                        break;
                    case 'home':
                        displayMemberProfile($_SESSION['memberId'], $currentMemberId) ;
                        break;
                    default :
                        displayMemberProfile($_SESSION['memberId'], $currentMemberId) ;
                }
            } else{
                switch($_GET['page']){
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
                        displayMemberProfile($_SESSION['memberId'], $currentMemberId) ;
                }
            }
        }
    }