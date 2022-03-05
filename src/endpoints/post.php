<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
require_once($_SERVER['DOCUMENT_ROOT'] . '/fb-distribue/src/model/Post.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/fb-distribue/src/controller/Control_Posts.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/fb-distribue/src/controller/Control_Utilisateur.php');

if (isset($_POST["action"])) {
    if ($_POST["action"] == "newPublicPost") {
        $contentPost = $_POST['contentPost'];
        $datePost = $_POST['datePost'];
        $scopePost = $_POST['scopePost'];
        $urlOwner = $_POST['urlOwner'];
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];

        $post = getPost($contentPost, $datePost, $urlOwner);
        if ($post == false) { // if the post isn't already in the db, we add it and propagate it to everyone
            insertPost($contentPost, $datePost, $scopePost, $urlOwner, $firstName, $lastName);
            propagatePost($contentPost, $datePost, $scopePost, $urlOwner, $firstName, $lastName);
            $aResult['result'] = "true: insertion and sent to everyone";
        } else {
            $aResult['result'] = "true : non insertion";
        }
    }

    if ($_POST["action"] == "newPost-FriendsOfFriends") {
        $contentPost = $_POST['contentPost'];
        $datePost = $_POST['datePost'];
        $scopePost = $_POST['scopePost'];
        $urlOwner = $_POST['urlOwner'];
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $post = getPost($contentPost, $datePost, $urlOwner);
        if ($post == false) { // if the post isn't already in the db, we add it and propagate it to the FRIENDS
            insertPost($contentPost, $datePost, $scopePost, $urlOwner, $firstName, $lastName);
            propagatePost($contentPost, $datePost, "Amis", $urlOwner, $firstName, $lastName);
            $aResult['result'] = "true: insertion and sent to friends";
        } else {
            $aResult['result'] = "true : non insertion";
        }
    }

    if ($_POST["action"] == "newPost-Friends") {
        $contentPost = $_POST['contentPost'];
        $datePost = $_POST['datePost'];
        $scopePost = $_POST['scopePost'];
        $urlOwner = $_POST['urlOwner'];
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $post = getPost($contentPost, $datePost, $urlOwner);
        if ($post == false) { // if the post isn't already in the db, we insert it
            insertPost($contentPost, $datePost, $scopePost, $urlOwner, $firstName, $lastName);
            $aResult['result'] = "true: insertion";
        } else {
            $aResult['result'] = "true : non insertion";
        }
    }


    if ($_POST["action"] == "request-information") {

        $myInformation = getMyInform();
        $firstName = $_POST["firstName"];
        $lastName = $_POST["lastName"];
        $urlSender = $_POST["url"];
        $typeUser = $_POST["typeUser"];


        $demandeur = findUserByUrl($urlSender);

        if ($typeUser == "Owner") {
            $demandeur = getMyInform();
        }
        if ($demandeur == false) {
            insertUser($firstName, $lastName, $urlSender, 'Stranger');
            $listPublicPosts = allMyPostsByType('Publique', $myInformation->get_url());

            $myPublicPostJson = array();
            foreach ($listPublicPosts as $post) {
                array_push($myPublicPostJson, json_encode($post));
            }
            $aResult['result'] = $myPublicPostJson;
        } else {
            switch ($demandeur->get_typeUser()) {
                case "Stranger":
                    $listPublicPosts = allMyPostsByType('Publique', $myInformation->get_url());
                    $myPublicPostJson = array();
                    foreach ($listPublicPosts as $post) {
                        array_push($myPublicPostJson, json_encode($post));
                    }
                    $aResult['result'] = $myPublicPostJson;
                    break;

                case "Friend":
                    $listPublicPosts = allMyPostsByType('Publique', $myInformation->get_url());
                    $myPublicAndFriendsPostsJson = array();
                    foreach ($listPublicPosts as $post) {
                        array_push($myPublicAndFriendsPostsJson, json_encode($post));
                    }

                    $listFriendPosts = allMyPostsByType('Amis', $myInformation->get_url());
                    foreach ($listFriendPosts as $post) {
                        array_push($myPublicAndFriendsPostsJson, json_encode($post));
                    }
                    $aResult['result'] = $myPublicAndFriendsPostsJson;

                    break;

                case "Owner":
                    $allMyPosts = array();
                    $listPublicPosts = allMyPostsByType('Publique', $myInformation->get_url());

                    foreach ($listPublicPosts as $post) {
                        array_push($allMyPosts, json_encode($post));
                    }

                    $listFriendPosts = allMyPostsByType('Amis', $myInformation->get_url());
                    foreach ($listFriendPosts as $post) {
                        array_push($allMyPosts, json_encode($post));
                    }

                    $listPrivatePosts = allMyPostsByType('Privee', $myInformation->get_url());
                    foreach ($listPrivatePosts as $post) {
                        array_push($allMyPosts, json_encode($post));
                    }

                    $listPostFriendsofFriends = allMyPostsByType('Amis des amis', $myInformation->get_url());
                    foreach ($listPostFriendsofFriends as $post) {
                        array_push($allMyPosts, json_encode($post));
                    }
                    $aResult['result'] = $allMyPosts;
                    break;
            }
        }

        echo json_encode($aResult);
    }
}
