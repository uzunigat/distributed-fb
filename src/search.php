<?php

    include("./header.php");
    require_once($_SERVER['DOCUMENT_ROOT'].'/fb-distribue/src/controller/Control_Utilisateur.php');
    $myInformation = getMyInform();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/bootstrap.min.css" >

    <title>Cherche un ami</title>
</head>
<body>
    <div class="container">
        <div class="row my-5">
        <input type="text" placeholder="http://ami.server" class="form-control col-7 " id="urlUserToSearch">
        <input type="text" placeholder="PORT 8080" class="form-control col-3" id="portNumber">
        <button class="btn btn-success col-2" id="searchButton"> Search</button>

        </div>

    </div>

    <!-- <div class="container  mt-2 bg-primary" > -->
    <div class="container  mt-2" >

        <div class="row px-2">
            <!-- <div class="col-3 bg-secondary" > -->
            <div class="col-3" >

                <div class="row">

                    <!-- <div class="col bg-info text-center" id="photoUserSearched"> -->
                    <div class="col text-center" id="photoUserSearched">
                        
                    </div>

                </div>

                <div class="row row-cols-2 mt-3" id ="friendsUserSearched">



                </div>
            </div>

            <!-- <div class="col-9 bg-success px-2 py-2" id="postsUserSearched"> -->
            <div class="col-9 px-2 py-2" id="postsUserSearched">
                

            </div>
        </div>
    </div>



<script src="js/jquery-3.4.1.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>


<script >

    document.getElementById("searchButton").addEventListener('click',
    function(){
        let urlUserToSearch = document.getElementById("urlUserToSearch").value;
        let portNum = document.getElementById("portNumber").value;
        let urlServer = "http://localhost:"+portNum+"/fb-distribue/src/endpoints/post.php";
        let firstName = "<?php echo($myInformation->get_firstName()); ?>";
        let lastName = "<?php echo($myInformation->get_lastName()); ?>";
        let urlRequestor = "<?php echo($myInformation->get_url()); ?>";
        let dataUserJson ={firstName:firstName,lastName:lastName,urlRequestor:urlRequestor}
        searchPosts(dataUserJson,urlServer,urlUserToSearch,portNum);

        

    });


    function searchPosts(dataUserJson,urlUserServer,urlUserToSearch,portNum){
        jQuery.ajax({
            type:"POST",
            dataType:"json",
            url:'./controller/General_Controller.php',
            data:{functionName:"getUserByUrl",arguments:[urlUserToSearch+":"+portNum]},
            success:function(obj,textStatus){

                let userJson = JSON.parse(obj.result)
                console.log(userJson)
                if(userJson!=false){
                    if(userJson.typeUser === "Friend"){
                        console.log("es amigo")
                        //search in database the friend's perfil 
                        getFriendPosts(userJson.url);

                    }
                    else if(userJson.typeUser === "Owner"){
                        console.log("buscar informacion en la base")
                        getFriendPosts(urlUserToSearch+":"+portNum);

                    }
                    else{
                        //launch ajax to ask to the user's server
                        getUserPostsFromServer(urlUserServer,dataUserJson.firstName,dataUserJson.lastName,dataUserJson.urlRequestor,userJson.typeUser);
                    }
                }else{
                    console.error(obj);
                    getUserPostsFromServer(urlUserServer,dataUserJson.firstName,dataUserJson.lastName,dataUserJson.urlRequestor,userJson.typeUser);

                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    alert("Status: " + textStatus); alert("Error: " + errorThrown); 
                    } 
        });
    }


    function getFriendPosts(friendUrl){
        console.log("lanzando la de mi db")

        jQuery.ajax({
            type:"POST",
            url:'./controller/General_Controller.php',
            dataType:'json',
            data:{functionName:"postsBydUrlFriend",arguments:[friendUrl]},
            success: function(obj,textStatus){
                let friendPosts = obj.result;

                if(friendPosts.length > 0){
                //show the post got on the search page
                    showPosts(friendPosts);
                
                }

                fetchListFriends();
                showProfilPhoto();


            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    alert("Status: " + textStatus); alert("Error: " + errorThrown); 
                    } 
        });

    }

    function getUserPostsFromServer (userUrl,firstName,lastName,urlRequestor,typeUser){
        jQuery.ajax({
            type:'POST',
            url:userUrl,
            dataType:'json',
            data:{
                action:'request-information',
                firstName:firstName,
                lastName:lastName,
                url:urlRequestor,
                typeUser:typeUser},
            success: function (obj, textstatus) {
                console.log(obj);
                if( !('error' in obj) ) {
                     let postsGot = obj.result;
                        //show the post got on the search page
                        showPosts(postsGot);
                        console.log("se lanzo la del server")
                        fetchListFriends();
                        showProfilPhoto();

                    }
                    else {
                         console.log(obj.error);
                    }


            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    alert("Status: " + textStatus); alert("Error: " + errorThrown); 
                    } 
        });
    }

    
    function showPosts(postsGot){
        let posts = '<div class="row justify-content-center mb-1"><h2><u>Publications</u></h2></div><div class="d-flex flex-column">';
        let name =null;
        let lastname=null;
        postsGot.forEach(function(post){
            let postJson = JSON.parse(post);
            name=postJson.firstName;
            lastName=postJson.lastName;
            posts+='<div class="container bg-light my-2">'+
                    '<div class="row">'+
                    '<div class="col-2 pt-2 align=" center"">'+
                    '<svg xmlns="http://www.w3.org/2000/svg" width="90" height="90" fill="currentColor"'+
                    'class="bi bi-person-circle" viewBox="0 0 16 16">'+
                    '<path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />'+
                    '<path fill-rule="evenodd"'+
                    'd="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z" />'+
                    '</svg></div><div class="col-10"><div class="row my-2"><div class="col">'+
                    '<h2 class="float-left">'+postJson.lastName+','+postJson.firstName+
                    '</h2></div></div><div class="row my-2"><div class="col">'+
                    '<h5>'+postJson.contentPost+'</h5></div></div><div class="row my-2"><div class="col">'+
                    '<h7 class="float-left">'+postJson.scopePost+'</h7>'+
                    '<h7 class="float-right">'+postJson.datePost+'</h7>'+
                    '</div></div></div></div></div>';

        });
        posts+="</div>";


        // console.log(posts)
        document.getElementById("postsUserSearched").innerHTML= posts;
        


    }

    function showProfilPhoto (){
        let urlUserToSearch = document.getElementById("urlUserToSearch").value;

        jQuery.ajax({
            type:'POST',
            url:'./controller/General_Controller.php',
            dataType:"json",
            data:{functionName:"getUserNameAndLastName" , arguments:[urlUserToSearch]},
            success: function (obj, textstatus) {
                let infoUser = JSON.parse(obj.informationUser)
                console.log(infoUser);
                let contentPhoto= '<svg xmlns="http://www.w3.org/2000/svg" width="150" height="150" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">'+
                '<path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>'+
                '</svg><div class="row justify-content-center"><div class="col"><h5>'+infoUser.lastName+','+infoUser.name+'</h5></div></div>'+
                '<div class="row justify-content-center mt-3"><h2><u> Amis </u></h2>'+'</div>';
                document.getElementById("photoUserSearched").innerHTML=contentPhoto;
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    alert("Status: " + textStatus); alert("Error: " + errorThrown); 
                    } 

    })
    }


    function fetchListFriends(){
        let urlUserToSearch = document.getElementById("urlUserToSearch").value;
        jQuery.ajax({
            type:'POST',
            url:'./controller/General_Controller.php',
            dataType:"json",
            data:{functionName:"fetchListFriends" , arguments:[urlUserToSearch]},
            success:function (obj, textstatus) {
                if( !('error' in obj) ) {


                     let friendsUser = JSON.parse(obj.result);
                     let friendsUserContent = '';

                     if(Array.isArray(friendsUser)){
                        friendsUser.forEach(function (friend){
                            let jsonFriend = JSON.parse(friend);
                            friendsUserContent+='<div class="col d-flex align-items-stretch my-2">'+
                            '<div class="card">'+
                            '<div class="container text-center mt-2"><svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="currentColor" class="bi bi-person-square" viewBox="0 0 16 16">'+
                            '<path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>'+
                            '<path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm12 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1v-1c0-1-1-4-6-4s-6 3-6 4v1a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12z"/>'+
                            '</svg></div><div class="card-body">'+
                            '<h6 class="card-title text-center">'+jsonFriend.lastName+', '+jsonFriend.firstName+'</h6>'+
                            '<a href="#" class="btn btn-primary text-center">See profil</a></div></div></div>';
                        });


                     document.getElementById("friendsUserSearched").innerHTML= friendsUserContent;
                     }

                    }
                    else {
                         console.log(obj.error);
                    }


            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    alert("Status: " + textStatus); alert("Error: " + errorThrown); 
                    } 
        });
    }

    if("<?php echo $_POST["idFriend"]; ?>" != '') {
        let idFriend = "<?php echo $_POST["idFriend"]; ?>";
        let firstName = "<?php echo $_POST["firstName"]; ?>";
        let lastName = "<?php echo $_POST["lastName"]; ?>";
        let url = "<?php echo $_POST["url"]; ?>";

        let tmp = url.split(":");

        document.getElementById("urlUserToSearch").value = tmp[0] + ':' + tmp[1];
        document.getElementById("portNumber").value = tmp[2];
        document.getElementById("searchButton").click();
    }

</script>
</body>
</html>