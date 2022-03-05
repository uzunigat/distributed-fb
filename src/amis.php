<?php

    include_once("./header.php");

    require_once "./controller/Control_Utilisateur.php";

    $friendList = getUtilisateurs();
    $user = getMyInform();
    $friendsYouMayKnow = array();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="./css/bootstrap.min.css" >

    <title> FB Distribué </title>
</head>

<body>

    <div class="container mx-auto" style="padding:10px" >

        <!-- Send Invitation -->

        <div class="row my-5">

            <input id="url" type="text" class="form-control col-7" id="addFriend" placeholder="http://personalSite.com">

            <input id="port" type="text" class="form-control col-2 ml-1" id="port" placeholder="PORT: 8080">

            <button id="sendButton" type="submit" class="btn btn-success float-right col mx-5">Send Invitation</button>

        </div>

        <div class="h1 text-center my-3">
            My Friends
        </div>

        <hr>

        <!-- Show the friend List at the Database -->

        <div class="row text-center mx-auto">
            
            <?php foreach($friendList as $friend){ ?>

            <div class="card p-3 col-3" style="width: 18rem;">
            <i class="fas fa-band-aid"></i>
            <div class="card-img-top" "> 

            <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
            </svg>
            
            
            </div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $friend->get_firstName();?></h5>

                    <input type="hidden" value=<?php echo $friend->get_idFriend();?>>
                    <input type="hidden" value=<?php echo $friend->get_firstName();?>>
                    <input type="hidden" value=<?php echo $friend->get_lastName();?>>
                    <input type="hidden" value=<?php echo $friend->get_url();?>>
                    <input type="hidden" value=<?php echo $friend->get_typeUser();?>>

                    <!-- If it's already my Friend -->
                    <?php if($friend->get_typeUser() === "Friend") { ?>

                        <button  class="btn btn-success mx-1 btnViewProfile"> View Profile </button>
                        <button  class="btn btn-danger mx-1 btnDelete">Delete</button>

                    <?php } ?>

                    <!-- If Request Received -->
                    <?php if($friend->get_typeUser() === "Request Received") { ?>

                        <button class="btn btn-success mx-1 btnAccept">Accept</button>
                        <button class="btn btn-danger mx-1 btnDelete">Reject</button>

                    <?php } ?>

                    <!-- If Request Sent -->
                    <?php if($friend->get_typeUser() === "Request Sent") { ?>

                        <button href="#" class="btn btn-danger mx-1 btnDelete">Cancel</button>

                    <?php } ?>

                     <!-- If it's an Stranger -->
                     <?php if($friend->get_typeUser() === "Stranger") { ?>

                        <button href="#" class="btn btn-success m-1 btnViewProfile">View Profile</button>
                        <button href="#" class="btn btn-primary m-1 btnSendInvitation">Send Invitation</button>

                    <?php } ?>


                </div>
            </div>

            <?php } ?>

        </div>

        <div class="h1 text-center my-3">
            Friends you may know
        </div>

        <hr>

        <div class="row text-center mx-auto" id ="friendsYouMayKnow">



        </div>
    
    </div>
    

<script src="js/jquery-3.4.1.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>

<script type="text/javascript">

    $(document).ready(function() {

        let firstName = "<?php echo $user->get_firstName(); ?>";
        let lastName = "<?php echo $user->get_lastName(); ?>";
        let myUrl = "<?php echo $user->get_url(); ?>";

        let friends = <?php echo json_encode($friendList); ?>;

        fillListFriends(friends)   
        
        // Click to add Friend
        $("#sendButton").click(function(){

             // Read url from input
            let url = $("#url").val();
            let port = $("#port").val();

            alert(url + ":" + port);

            // Send Request Friend to the site
            $.ajax({

                url: "http://localhost:"+ port +"/fb-distribue/src/endpoints/amis.php",
                method: "POST",

                data: {
                    action: 'addFriend', 
                    firstName: firstName,
                    lastName: lastName,
                    url: myUrl
                },

                crossDomain: true,
                dataType: 'text',
                success: function(data){

                    let postJson = JSON.parse(data)
                    
                    // If I can't send invitation to the friend
                    if(typeof postJson.response !== 'undefined'){

                        // I have sent an invitation myself
                        if(postJson.response === 'Bad Request'){
                            alert("you can't add yourself");
                        }

                        if(postJson.response === 'User already on the DB'){
                            alert("It's already your friend");
                        }

                        location.reload();
                       

                    } else {

                         // Add friend Request in My Database
                         $.ajax({

                            url: "./controller/General_Controller.php",
                            method: "POST",
                            data: {
                                functionName: 'addFriend', 
                                arguments: [postJson.firstName, postJson.lastName, url + ":" + port]
                            },

                            crossDomain: true,
                            dataType: 'json',

                            success: function(data){

                                alert("Invitation Sent");

                                location.reload();

                            }

                            });

                    }

                },

                error: function(XMLHttpRequest, textStatus, errorThrown){

                    alert("Status: " + textStatus); alert("Error: " + errorThrown); 

                }


            });

        });

        // Click View Profile
        $(".btnViewProfile").click(function(){

        // Read data from card
        let parent = $(this).parent().get(0);
        let dataTag = $(parent).find("input");

        let idFriend = $(dataTag).get(0);
        let firstName = $(dataTag).get(1);
        let lastName = $(dataTag).get(2);
        let url = $(dataTag).get(3);

            $('<form action="search.php" method="post">' +
                '<input id="idFriend" name="idFriend" type="hidden" value='+$(idFriend).val()+'>' +
                '<input id="firstName" name="firstName" type="hidden" value='+$(firstName).val()+'>' +
                '<input id="lastName" name="lastName" type="hidden" value='+$(lastName).val()+'>' +
                '<input id="url" name="url" type="hidden" value='+$(url).val()+'>' +
                '</form>').appendTo('body').submit();

        alert(`
            ---- VIEW PROFILE ----

            idFriend = ${$(idFriend).val()}
            firstName = ${$(firstName).val()}
            lastName = ${$(lastName).val()}
            url = ${$(url).val()}
        `)

        });

        // Click Delete
        $(".btnDelete").click(function(){

            // Read data from card
            let parent = $(this).parent().get(0);
            let dataTag = $(parent).find("input");

            let idFriend = $(dataTag).get(0);
            let firstName = $(dataTag).get(1);
            let lastName = $(dataTag).get(2);
            let url = $(dataTag).get(3);


            alert(`
                 ---- DELETE ----
                idFriend = ${$(idFriend).val()}
                firstName = ${$(firstName).val()}
                lastName = ${$(lastName).val()}
                url = ${$(url).val()}
            `);

            // Delete Friend on the other Site

            alert("http://localhost:" + $(url).val().split(":")[2] +"/fb-distribue/src/endpoints/amis.php");
     

            $.ajax({

                url: "http://localhost:" + $(url).val().split(":")[2] +"/fb-distribue/src/endpoints/amis.php",
                method: "POST",
                data: {
                    action: 'deleteFriend', 
                    url: myUrl
                },

                crossDomain: true,
                dataType: 'text',

                success: function(data){

                    console.log(data)
                    let postJson = JSON.parse(data);

                    if(typeof postJson.response !== 'undefined'){

                        // Delete Friend in my Site
                        if(postJson.response === 'Friend Deleted'){

                            $.ajax({

                                url: "./controller/General_Controller.php",
                                method: "POST",
                                data: {
                                    functionName: 'deleteFriend', 
                                    arguments: [$(url).val()]
                                },

                                crossDomain: true,
                                dataType: 'text',

                                success: function(data){

                                    location.reload();
                                    
                                },

                                error: function(XMLHttpRequest, textStatus, errorThrown){

                                    alert("Status: " + textStatus); alert("Error: " + errorThrown); 

                                }

                            });
      
                        
                        }

                    } 

                },

                error(){

                    alert("User Disconected !");

                }

            });

        });

        // Click Cancel
        $(".btnCancel").click(function(){

            // Read data from card
            let parent = $(this).parent().get(0);
            let dataTag = $(parent).find("input");

            let idFriend = $(dataTag).get(0);
            let firstName = $(dataTag).get(1);
            let lastName = $(dataTag).get(2);
            let url = $(dataTag).get(3);

            alert(`
                ---- CANCEL ----
                idFriend = ${$(idFriend).val()}
                firstName = ${$(firstName).val()}
                lastName = ${$(lastName).val()}
                url = ${$(url).val()}
            `)

        });

        // Click Accept
        $(".btnAccept").click(function(){

            // Read data from card
            let parent = $(this).parent().get(0);
            let dataTag = $(parent).find("input");

            let idFriend = $(dataTag).get(0);
            let firstName = $(dataTag).get(1);
            let lastName = $(dataTag).get(2);
            let url = $(dataTag).get(3);

            alert(`
                 ---- Accept ----
                 idFriend = ${$(idFriend).val()}
                 firstName = ${$(firstName).val()}
                 lastName = ${$(lastName).val()}
                 url = ${$(url).val()}
             `);

            // Accept Friend on the other Site
            $.ajax({

                url: "http://localhost:" + $(url).val().split(":")[2] +"/fb-distribue/src/endpoints/amis.php",
                method: "POST",
                data: {
                    action: 'acceptInvitation', 
                    firstName: $(firstName).val(),
                    lastName: $(lastName).val(),
                    url: myUrl
                },

                crossDomain: true,
                dataType: 'text',

                success: function(data){  

                    console.log(data);
                    let postJson = JSON.parse(data);

                    if(typeof postJson.response !== 'undefined'){

                        // Accept Friend in my Site
                        if(postJson.response === 'Invitation Accepted'){

                            alert("Invitacion aceptada en el otro sitio");

                            $.ajax({

                                url: "./controller/General_Controller.php",
                                method: "POST",
                                data: {
                                    functionName: 'acceptInvitation', 
                                    arguments: [$(url).val(), postJson.firstName, postJson.lastName]
                                },

                                crossDomain: true,
                                dataType: 'text',

                                success: function(data){

                                    location.reload();
                                    
                                },

                                error: function(XMLHttpRequest, textStatus, errorThrown){

                                    alert("Status: " + textStatus); alert("Error: " + errorThrown); 

                                }

                            })

                        }

                    }
                    
                },

                error: function(){

                    alert("User Disconected !");

                }

            });

        });

         // Click to add Friend
         $(".btnSendInvitation").click(function(){

            // Read data from card
            let parent = $(this).parent().get(0);
            let dataTag = $(parent).find("input");

            let idFriend = $(dataTag).get(0);
            let firstName = $(dataTag).get(1);
            let lastName = $(dataTag).get(2);
            let url = $(dataTag).get(3);

            // Send Request to the other Site
            $.ajax({

                url: "http://localhost:" + $(url).val().split(":")[2] +"/fb-distribue/src/endpoints/amis.php",
                method: "POST",
                data: {
                    action: 'addFriend', 
                    firstName: $(firstName).val(),
                    lastName: $(lastName).val(),
                    url: myUrl
                },

                crossDomain: true,
                dataType: 'text',

                success: function(data){  

                    console.log(data);
                    let postJson = JSON.parse(data)
                    
                    // If I can't send invitation to the friend
                    if(typeof postJson.response !== 'undefined'){

                        // I have sent an invitation myself
                        if(postJson.response === 'Bad Request'){
                            alert("you can't add yourself");
                        }

                        if(postJson.response === 'User already on the DB'){
                            alert("It's already your friend");
                        }

                        location.reload();
                       

                    } else {

                        alert(postJson)

                         $.ajax({

                            url: "./controller/General_Controller.php",
                            method: "POST",
                            data: {
                                functionName: 'sendInvitation', 
                                arguments: [postJson.firstName, postJson.lastName, $(url).val()]
                            },

                            crossDomain: true,
                            dataType: 'json',

                            success: function(data){

                                alert("Invitation Sent");

                                location.reload();

                            }

                            });

                    }

                },

             });


         });

         function fillListFriends(friends){

        let friendsYouMayKnow = []; 
        let myUrl = "<?php echo $user->get_url(); ?>";

        let urls = friends.map(friend => {

            return friend.url;

        });

        // Make a request friends to every friend
        urls.forEach(url => {

            let split_friend = url.split(":");

            $.ajax({

                url: "http://localhost:" + split_friend[2] + "/fb-distribue/src/endpoints/amis.php",
                method: "POST",
                data: {
                    action: 'fetch-friends', 
                },

                crossDomain: true,
                dataType: 'json',

                success: function(data){
                    
                    console.log(data);

                    // if(Array.isArray(data)) {
                        data.forEach(element => {



                            let user = JSON.parse(element);
                            console.log(user);

                            (urls.includes(user.url) ||  user.url === myUrl) ? true  :  friendsYouMayKnow.push(user);

                        })

                        showRecommendation(friendsYouMayKnow);

                    // }

                },

                error: function(XMLHttpRequest, textStatus, errorThrown){

                    alert("Status: " + textStatus); alert("Error: " + errorThrown); 

                }

            });
            


        });

        }

    function showRecommendation(friendsYouMayKnow){

        let div = $("#friendsYouMayKnow");

        // div.append("<p> Test </p>")
        friendsYouMayKnow.forEach(friend => {

            // console.log(friend);

            let insertHTML = `

            <div class="card p-3 col-3" style="width: 18rem;">
                <i class="fas fa-band-aid"></i>
                <div class="card-img-top" "> 

                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                        <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                    </svg>
                
                
                    </div>
                        <div class="card-body">
                            <h5 class="card-title">${friend.firstName}</h5>

                            <input type="hidden" value=${friend.firstName}>
                            <input type="hidden" value=${friend.firstName}>
                            <input type="hidden" value=${friend.lastName}>
                            <input type="hidden" value=${friend.url}>

                            <button class="btn btn-success m-1 btnViewProfile">View Profile</button>
                            <button class="btn btn-primary m-1 btnSendInvitation">Send Invitation</button>
                        
                        </div>
                    </div>

                </div>
            </div>
            
            `

            div.append(insertHTML);

            $('#friendsYouMayKnow').on('click', '.btnViewProfile', viewProfile);
            $('#friendsYouMayKnow').on('click', '.btnSendInvitation', sendInvitation);

        });

        }

        function viewProfile(){


            let parent = $(this).parent().get(0);
            let dataTag = $(parent).find("input");

            let idFriend = $(dataTag).get(0);
            let firstName = $(dataTag).get(1);
            let lastName = $(dataTag).get(2);
            let url = $(dataTag).get(3);

                $('<form action="search.php" method="post">' +
                    '<input id="idFriend" name="idFriend" type="hidden" value='+$(idFriend).val()+'>' +
                    '<input id="firstName" name="firstName" type="hidden" value='+$(firstName).val()+'>' +
                    '<input id="lastName" name="lastName" type="hidden" value='+$(lastName).val()+'>' +
                    '<input id="url" name="url" type="hidden" value='+$(url).val()+'>' +
                    '</form>').appendTo('body').submit();

            alert(`
                ---- VIEW PROFILE ----

                idFriend = ${$(idFriend).val()}
                firstName = ${$(firstName).val()}
                lastName = ${$(lastName).val()}
                url = ${$(url).val()}
            `)

        }

        function sendInvitation(){

            let parent = $(this).parent().get(0);
            let dataTag = $(parent).find("input");

            let idFriend = $(dataTag).get(0);
            let firstName = "<?php echo $user->get_firstName(); ?>";
            let lastName = "<?php echo $user->get_lastName(); ?>";
            let url = $(dataTag).get(3);

            alert(`
                ---- VIEW PROFILE ----

                idFriend = ${$(idFriend).val()}
                firstName = ${$(firstName).val()}
                lastName = ${$(lastName).val()}
                url = ${$(url).val()}
            `)

            // Send Request Friend to the site
            $.ajax({

                url: "http://localhost:"+ $(url).val().split(":")[2] +"/fb-distribue/src/endpoints/amis.php",
                method: "POST",

                data: {
                    action: 'addFriend', 
                    firstName: firstName,
                    lastName: lastName,
                    url: myUrl
                },

                crossDomain: true,
                dataType: 'text',
                success: function(data){

                    let postJson = JSON.parse(data)
                    
                    // If I can't send invitation to the friend
                    if(typeof postJson.response !== 'undefined'){

                        // I have sent an invitation myself
                        if(postJson.response === 'Bad Request'){
                            alert("you can't add yourself");
                        }

                        if(postJson.response === 'User already on the DB'){
                            alert("It's already your friend");
                        }

                        location.reload();
                    

                    } else {

                        // Add friend Request in My Database
                        $.ajax({

                            url: "./controller/General_Controller.php",
                            method: "POST",
                            data: {
                                functionName: 'addFriend', 
                                arguments: [postJson.firstName, postJson.lastName, $(url).val()]
                            },

                            crossDomain: true,
                            dataType: 'json',

                            success: function(data){

                                alert("Invitation Sent");

                                location.reload();

                            }

                            });

                    }

                },

                error: function(XMLHttpRequest, textStatus, errorThrown){

                    alert("Status: " + textStatus); alert("Error: " + errorThrown); 

                }


            });

        }


    });

    
    
</script>

</body>
</html>

