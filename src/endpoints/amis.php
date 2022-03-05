<?php
    
    header('Content-Type: application/text');
    header("Access-Control-Allow-Origin: *");


    session_start();

    include ($_SERVER['DOCUMENT_ROOT'].'/fb-distribue/src/controller/Control_Utilisateur.php');

    if(isset($_POST["action"])) {
        
        // Action Add Friend
        if($_POST["action"] === "addFriend"){

            // Insert new friend into the table
            $firstName = $_POST["firstName"];
            $lastName = $_POST["lastName"];
            $url = $_POST["url"];

            //Check if this request is different of my site
            $user = findUserByUrl($url);

            if($user){

                if($user->get_typeUser() == "Owner"){

                    echo json_encode(array("response" => "Bad Request"));

                }

                else if($user->get_typeUser() == "Stranger") {

                    changeTypeUtilisateur($url, "Request Received", $firstName, $lastName);
                    echo json_encode($user);

                }

                else {

                    echo json_encode(array("response" => "User already on the DB"));

                }

            } else {


                insertUser($firstName,$lastName,$url,'Request Received');

                // Return this Friend
                $user = getMyInform();

                echo json_encode($user);


            }

        }

        if($_POST["action"] === "deleteFriend"){

            $url = $_POST["url"];

            deleteUtilisateurByUrl($url);

            echo json_encode(array("response" => "Friend Deleted"));

        }

        if($_POST["action"] === "acceptInvitation"){

            $url = $_POST["url"];
            $firstName = $_POST["firstName"];
            $lastName = $_POST["lastName"];

            changeTypeUtilisateur($url, "Friend", $firstName, $lastName);

            echo json_encode(array("response" => "Invitation Accepted"));

        }

        if($_POST["action"] === "fetch-friends"){
            $myFriends = getMyFriends();
            $friendsJson = array();

            if(is_array($myFriends)){
                foreach($myFriends as $friend){
                    array_push($friendsJson,json_encode($friend));
                }
            }

            echo json_encode($friendsJson);
        }

        if($_POST["action"] ==="give-my-information"){
            $myInformation = getMyInform();
            $data = array(
                'name'=>$myInformation->get_firstName(),
                'lastName'=>$myInformation->get_lastName()
            );

            echo json_encode($data);

        }
    }

?>