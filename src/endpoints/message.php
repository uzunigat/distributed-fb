<?php

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

require_once($_SERVER['DOCUMENT_ROOT'] . '/fb-distribue/src/controller/Control_Messages.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/fb-distribue/src/controller/Control_Utilisateur.php');

if(isset($_POST["action"])) { 
    // Action Add Friend
    if($_POST["action"] === "sendMessage"){
        
        $sender = $_POST['sender'];
        $receiver = $_POST['receiver'];
        $payload = $_POST['payload'];
        $date = $_POST['date'];

        //Check if this request is different of my site
        $user = findUserByUrl($sender);

        if($user){
            if($user->get_typeUser() == "Owner"){
                echo json_encode(array("response" => "Bad Request"));
            } else {
                insertMessage($sender, $receiver, $payload, $date);
                echo json_encode(array("response" => "Message sent"));
            }
        }
    }

    if ($_POST["action"] == "newPublicPost") {
        $payload = $_POST['payload'];
        $dateMessage = $_POST['dateMessage'];
        $sender = $_POST['sender'];
        $scopePost = $_POST['scopePost'];
        
        $message = getMessage($payload, $dateMessage, $sender);
        if ($message == false) { // if the post isn't already in the db, we add it and propagate it to everyone
            insertMessage($sender, $scopePost, $payload, $date);
            propagateMessage($scopePost, $payload, $date, $sender);
            $aResult['result'] = "true: insertion and sent to everyone";
        } else {
            $aResult['result'] = "true : non insertion";
        }
    }

    if ($_POST["action"] == "newPost-FriendsOfFriends") {
        $payload = $_POST['payload'];
        $dateMessage = $_POST['dateMessage'];
        $sender = $_POST['sender'];
        $scopePost = $_POST['scopePost'];
        $message = getMessage($payload, $dateMessage, $sender);
        if ($message == false) { // if the post isn't already in the db, we add it and propagate it to the FRIENDS
            insertMessage($sender, $scopePost, $payload, $date);
            propagateMessage($scopePost, $payload, $date, $sender);
            $aResult['result'] = "true: insertion and sent to everyone";
        } else {
            $aResult['result'] = "true : non insertion";
        }
    }

    if ($_POST["action"] == "newPost-Friends") {
        $payload = $_POST['payload'];
        $dateMessage = $_POST['dateMessage'];
        $sender = $_POST['sender'];
        $scopePost = $_POST['scopePost'];
        $message = getMessage($payload, $dateMessage, $sender);
        if ($message == false) { // if the post isn't already in the db, we insert it
            insertMessage($sender, $scopePost, $payload, $date);
            $aResult['result'] = "true: insertion";
        } else {
            $aResult['result'] = "true : non insertion";
        }
    }
}
