<?php

function debug($var){
    echo "<pre>";
    var_dump($var);
    echo "</pre>";
    die();
}


try{

    session_start();

    /*******************/

    define("SESSION_MEMBER_ID", $_SESSION['memberId']);
    define("SPOT_ID", $_GET['spotId']);
    define("REQUIRED_PAGE", $_GET['page']);

    require('controller/frontendController.php');

    function checkRequiredData($dataRequired){
        for($i = 0, $c = count($dataRequired); $i < $c ; ++$i) {
            if(!isset($dataRequired[$i]) || empty($dataRequired[$i])){
                return false;
            }
        }
        return true;
    }

    function accessMemberArea(){
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
                case 'editMemberForm':
                    displayEditMemberForm($currentMemberId);
                    break;
                case 'editMember':
                    editMemberController($_POST);
                    break;
                case 'recordMember':
                    recordMemberController($_POST);
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

    if(NULL === SESSION_MEMBER_ID){
        if($_GET ['page'] === 'addNewMember'){
            displayMemberForm();
        } elseif($_REQUEST['page'] === 'recordMember'){
            recordMemberController($_POST);    
        } else {
            if(NULL === $_SESSION['pageAfterLogin']){
                $_SESSION['pageAfterLogin'] = $_GET['page'];
            }

            if(NULL === $_SESSION['spotAfterLogin']){
                $_SESSION['spotAfterLogin'] = $_GET['spotId'];
            }

            if(NULL === $_SESSION['catAfterLogin']){
                $_SESSION['catAfterLogin'] = $_GET['catId'];
            }

            if(NULL !== $_POST['email'] && NULL !== $_POST['password']){
                $memberDataConnection['email'] = $_POST['email'] ;
                $memberDataConnection['password'] = $_POST['password'] ;
            } else {
                displayLoginForm(false);
                die();
            }

            if(loginMember($memberDataConnection)){    
                $requiredPage = $_SESSION['pageAfterLogin'];
                unset($_SESSION['pageAfterLogin']);
                $requiredSpot = $_SESSION['spotAfterLogin'];
                unset($_SESSION['spotAfterLogin']);
                $catAfterLogin = $_SESSION['catAfterLogin'];
                unset($_SESSION['catAfterLogin']);
                
                $page = $requiredPage === 'login' ? 'page=home' : 'page='.$requiredPage ;
            
                $spot = $requiredSpot === NULL ? '' : '&spotId='.$requiredSpot;

                $category = $catAfterLogin === NULL ? '' : '&catId='.$catAfterLogin;

                $adress = $page.$spot.$category ;

                header('Location: index.php?'.$adress);
                die();
            } else {
                displayLoginForm(true);
                die();
            }     

        }    
    } else {
            accessMemberArea();    
    }

} catch(Exception $e) {
    die('Erreur : '.$e->getMessage());
}
