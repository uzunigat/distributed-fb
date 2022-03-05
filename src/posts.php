<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/fb-distribue/src/controller/Control_Posts.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/fb-distribue/src/controller/Control_Utilisateur.php');

    include("./header.php");
    $myInform = getMyInform();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/bootstrap.min.css" >
    <title>FB Distribué</title>
</head>
<body>

<div class = "container align-items-center mt-5">
        <div class="row justify-content-center">
            <div class="col-8 " align="center">
            <button type="button" data-toggle="modal" data-target="#myModal" class="btn btn-lg btn-outline-dark text-center" class="">Qu'est ce que tu as en tete ?</button>
            <!-- <div data-toggle="modal" data-target="#myModal" class="text-center"> Qu'est ce que tu as en tete ?</div> -->

              <!-- The Modal -->
                    <div class="modal fade" id="myModal">
                        <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                        
                            <!-- Modal Header -->
                            <div class="modal-header">
                            <h4 class="modal-title">Partage tes idees</h4>
                            <button id="modalButtonClose" type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            
                            <!-- Modal body -->
                            <div class="modal-body">
                                <div class="row">
                                <input type="text" class="form-control col-7 "  id="contentPost"placeholder="Qu'est ce que tu as en tete ?">
                                <select id="optionsPartage" class="form-control col-3"> 
                                <option value="Privee">Privee</option>
                                <option value="Publique">Publique</option>
                                <option value="Amis des amis">Amis des amis</option>
                                <option value="Amis">Amis</option>
                                </select>
                                <button id="postButton" class="btn btn-warning col-2"> Post</button>
                                </div>
                                
                            </div>
                            
                            <!-- Modal footer -->
                            <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                            
                        </div>
                        </div>
                    </div>
            </div>
        </div>

        <div class="row mt-5 justify-content-center">

            <div class="col-8" id="myPosts">
                <div class="d-flex flex-column">
                <?php
        
            $myPosts= allPostHomePage();

            if (is_array($myPosts)){
            foreach ($myPosts as $post) { ?>
                        <div class="container bg-light my-2">
                            <div class="row">
                                <div class="col-2 pt-2 " align="center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="90" height="90" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                                        <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                                    </svg>
                                </div>

                                <div class="col-10">
                                        <div class="row my-2"><div class="col"> <h2 class="float-left"><?php echo $post->get_lastName(); ?>,<?php echo $post->get_firstName(); ?></h2></div></div>
                                        <div class="row my-2"><div class="col"><h5><?php echo $post->get_contentPost(); ?></h5></div></div>
                                        <div class="row my-2"><div class="col"><h7 class="float-left"><?php echo $post->get_scopePost(); ?></h7><h7 class="float-right"><?php echo $post->get_datePost(); ?></h7></div></div>

                                </div>
                            </div>
                        </div>
                        <?php }}?>
                    </div>
                </div>
         
         </div>
</div>


<script src="js/jquery-3.4.1.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>

<script>
    document.getElementById("postButton").addEventListener('click',function(){
            let valueSelect = document.getElementById("optionsPartage").value;
            let contentPost = document.getElementById("contentPost").value;
            //let date = "<?php echo date("Y/m/d H:i:s");?>";

            var currentdate = new Date(); 
            var date = currentdate.getFullYear() + "/"  
                + (currentdate.getMonth()+1)  + "/" 
                + currentdate.getDate() + " "
                + currentdate.getHours() + ":"  
                + currentdate.getMinutes() + ":" 
                + currentdate.getSeconds(); 
            
            let firstName = "<?php echo $myInform->get_firstName();?>";
            let lastName = "<?php echo $myInform->get_lastName();?>";
            jQuery.ajax({
                type: "POST",
                url: './controller/General_Controller.php',
                dataType: 'json',
                data: {functionName: 'insertPost', arguments: [contentPost,date,valueSelect,firstName,lastName]},
                success: function (obj, textstatus) {
                            if( !('error' in obj) ) {
                                document.getElementById("modalButtonClose").click();

                                jQuery.ajax({
                                    type:"POST",
                                    url: './controller/General_Controller.php',
                                    dataType:'json',
                                    data:{functionName:'allPostHomePage',arguments: ["not value required"]},
                                    success: function (obj, textstatus) {
                                        
                                                        if( !('error' in obj) ) {
                                                            let myPost = obj.result;
                                                            let postsPage= document.getElementById("myPosts");
                                                            let newContent = "";
                                                            myPost.forEach(function(post){
                                                                let postJson = JSON.parse(post)
                                                                newContent+='<div class="container bg-light my-2">'+
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

                                                            })

                                                            postsPage.innerHTML=newContent;
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
                            else {
                                console.log(obj.error);
                            }
                        },
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
                alert("Status: " + textStatus); alert("Error: " + errorThrown); 
                     }  
                    });
    });



</script>
</body>
</html>