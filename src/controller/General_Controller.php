<?php

header('Content-Type: application/json');
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/fb-distribue/src/controller/Control_Posts.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/fb-distribue/src/controller/Control_Utilisateur.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/fb-distribue/src/controller/Control_Messages.php');

$aResult = array();

if (!isset($_POST['functionName'])) {
    $aResult['error'] = 'Nom de fonction pas donnee';
}

if (!isset($_POST['arguments'])) {
    $aResult['error'] = 'Insufissant nombre d\'arguments';
}

if (!isset($aResult['error'])) {

    switch ($_POST['functionName']) {
        case 'insertPost':
            $myInformation = getMyInform();
            $aResult['result'] = insertPost($_POST['arguments'][0], $_POST['arguments'][1], $_POST['arguments'][2], $myInformation->get_url(), $_POST['arguments'][3], $_POST['arguments'][4]);
            if ($_POST['arguments'][2] != "Privee") {
                propagatePost($_POST['arguments'][0], $_POST['arguments'][1], $_POST['arguments'][2], $myInformation->get_url(), $_POST['arguments'][3], $_POST['arguments'][4]);
            }
            break;

        case "getUserByUrl":
            $user = findUserByUrl($_POST['arguments'][0]);
            if ($user == false) {
                $aResult['result'] = $user;
            } else {
                $aResult['result'] = json_encode($user);
            }
            break;

        case 'allPostHomePage':
            // $myInformation = getMyInform();
            $myPosts =  allPostHomePage();
            // allMyPosts($myInformation->get_url());
            $myPostJson = array();
            foreach ($myPosts as $post) {
                array_push($myPostJson, json_encode($post));
            }
            $aResult['result'] = $myPostJson;
            break;

            //search friend's posts in db 
        case 'postsBydUrlFriend':
            //we pass the friend's url 
            $postsFriend = allMyPosts($_POST['arguments'][0]);
            $postsFriendJson = array();

            if (is_array($postsFriend)) {
                foreach ($postsFriend as $post) {
                    array_push($postsFriendJson, json_encode($post));
                }
            }

            $aResult['result'] = $postsFriendJson;

            break;

        case 'addFriend':

            $firstName = $_POST['arguments'][0];
            $lastName = $_POST['arguments'][1];
            $url = $_POST['arguments'][2];

            insertUser($firstName, $lastName, $url, "Request Sent");

            break;

        case 'deleteFriend':

            $url = $_POST['arguments'][0];

            deleteUtilisateurByUrl($url);

            echo json_encode(array("response" => "Friend Deleted"));


            break;

        case 'acceptInvitation':

            $url = $_POST['arguments'][0];
            $firstName = $_POST['arguments'][1];
            $lastName = $_POST['arguments'][2];

            changeTypeUtilisateur($url, "Friend", $firstName, $lastName);

            echo json_encode(array("response" => "Invitation Accepted"));


            break;

        case 'rejectInvitation':

            $firstName = $_POST['arguments'][0];
            $lastName = $_POST['arguments'][1];
            $url = $_POST['arguments'][2];

            break;


        case 'cancelInvitation':

            $firstName = $_POST['arguments'][0];
            $lastName = $_POST['arguments'][1];
            $url = $_POST['arguments'][2];

            break;

        case "fetchListFriends":
            $urlPerson = $_POST['arguments'][0];
            $urlPerson = $urlPerson . "/fb-distribue/src/endpoints/amis.php";
            $data = array(
                'action' => 'fetch-friends'
            );
            $postString = http_build_query($data, '', '&');
            $ch = curl_init($urlPerson);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec($ch);
            $aResult['result'] = $response;

            break;

        case 'getUserNameAndLastName':
            $urlPerson = $_POST['arguments'][0];
            $urlPerson = $urlPerson . "/fb-distribue/src/endpoints/amis.php";
            $data2 = array(
                'action' => 'give-my-information'
            );
            $postString2 = http_build_query($data2, '', '&');
            $ch2 = curl_init($urlPerson);
            curl_setopt($ch2, CURLOPT_POST, 1);
            curl_setopt($ch2, CURLOPT_POSTFIELDS, $postString2);
            curl_setopt($ch2, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch2, CURLOPT_HEADER, 0);
            curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
            $response2 = curl_exec($ch2);
            $aResult['informationUser'] = $response2;

            break;


        case 'sendInvitation':
            $firstName = $_POST['arguments'][0];
            $lastName = $_POST['arguments'][1];
            $url = $_POST['arguments'][2];
            changeTypeUtilisateur($url, "Request Sent", $firstName, $lastName);
            break;

        case 'addMessage':
            $sender = $_POST['arguments'][0];
            $receiver = $_POST['arguments'][1];
            $payload = $_POST['arguments'][2];
            $date = $_POST['arguments'][3];
            insertMessage($sender, $receiver, $payload, $date);
            $aResult['result'] = json_encode(array("response" => "Message saved"));
            break;

        case 'retrieveMessage':
            $message = getMessageById($_POST['arguments'][0]);

            $aResult['result'] =
                json_encode(
                    [
                        "idMessage" => $message->get_idMessage(),
                        "sender" => $message->get_senderURL(),
                        "receiver" => $message->get_receiverURL(),
                        "payload" => $message->get_payload()

                    ]
                );;
            break;

        case 'transfertMessage':
            $user = getMyInform();
            $aResult['result'] = insertMessage($user->get_url(), $_POST['arguments'][0], $_POST['arguments'][1], $_POST['arguments'][2]);
            propagateMessage($_POST['arguments'][0], $_POST['arguments'][1], $_POST['arguments'][2], $user->get_url());
            break;


        default:
            $aResult['error'] = 'Fonction pas trouvee ' . $_POST['functionName'] . '!';
            break;
    }
}

echo json_encode($aResult);
