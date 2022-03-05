<?php
session_start();
include("./header.php");
require_once($_SERVER['DOCUMENT_ROOT'] . '/fb-distribue/src/controller/Control_Utilisateur.php');

$_SESSION['userData'] = getMyInform();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="./css/bootstrap.min.css">

    <title>FB Distribué</title>
</head>

<body>

    <div class="container">

        <div class="row mt-2 text-center">

            <div class="col h3 my-3 font-weight-bold"> Welcome: <?php echo $_SESSION["userData"]->get_firstName() . " " . $_SESSION["userData"]->get_lastName(); ?></div>

        </div>

        <div class="row mt-3">

            <div class="col text-center"> Ce petit réseau Social est distribué. Chaque utilisateur doit être connecté dans un serveur different.</div>

        </div>

        <div class="row row-cols-3 my-3">

            <div class="col mx-auto my-auto">

                <div class="card text-center" style="width: 18rem;">
                    <div class="card-img-top my-3">

                        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor" class="bi bi-person-plus-fill" viewBox="0 0 16 16">
                            <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                            <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z" />
                        </svg>

                    </div>

                    <div class="card-body">
                        <h5 class="card-title">Add Friends</h5>
                        <p class="card-text">Ajoutez vos amis en écrivant leur adresse url. Construire votre propre réseau</p>
                        <a href="./amis.php" class="btn btn-primary">Go Friends</a>
                    </div>
                </div>

            </div>

            <div class="col mx-auto my-auto">

                <div class="card text-center" style="width: 18rem;">
                    <div class="card-img-top my-3">

                        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor" class="bi bi-file-post" viewBox="0 0 16 16">
                            <path d="M4 3.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5v-8z" />
                            <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2zm10-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1z" />
                        </svg>

                    </div>

                    <div class="card-body">
                        <h5 class="card-title">Make Posts</h5>
                        <p class="card-text"> Créer des messages et décider de la privacité de chacun. Commence ton premier post </p>
                        <a href="./posts.php" class="btn btn-primary">Go Posts</a>
                    </div>
                </div>

            </div>

            <div class="col mx-auto my-auto">

                <div class="card text-center" style="width: 18rem;">
                    <div class="card-img-top my-3">

                        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor" class="bi bi-chat-left-text" viewBox="0 0 16 16">
                            <path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H4.414A2 2 0 0 0 3 11.586l-2 2V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z" />
                            <path d="M3 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM3 6a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 6zm0 2.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z" />
                        </svg>

                    </div>

                    <div class="card-body">
                        <h5 class="card-title">Send Messages</h5>
                        <p class="card-text"> Comme pour les publications, envoyez des messages à vos contacts </p>
                        <a href="./messages.php" class="btn btn-primary">Go Messages</a>
                    </div>
                </div>

            </div>


        </div>

        <hr class="my-3" />

        <div class="row my-3 text-center">

            <div class="col h3 font-weight-bold text-center"> CARACTERISTIQUES </div>

        </div>

        <div class="row row-cols-3 my-3">

            <div class="col mx-auto my-auto">

                <div class="card text-center" style="width: 18rem;">
                    <div class="card-img-top my-3">

                        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor" class="bi bi-server" viewBox="0 0 16 16">
                            <path d="M1.333 2.667C1.333 1.194 4.318 0 8 0s6.667 1.194 6.667 2.667V4c0 1.473-2.985 2.667-6.667 2.667S1.333 5.473 1.333 4V2.667z" />
                            <path d="M1.333 6.334v3C1.333 10.805 4.318 12 8 12s6.667-1.194 6.667-2.667V6.334a6.51 6.51 0 0 1-1.458.79C11.81 7.684 9.967 8 8 8c-1.966 0-3.809-.317-5.208-.876a6.508 6.508 0 0 1-1.458-.79z" />
                            <path d="M14.667 11.668a6.51 6.51 0 0 1-1.458.789c-1.4.56-3.242.876-5.21.876-1.966 0-3.809-.316-5.208-.876a6.51 6.51 0 0 1-1.458-.79v1.666C1.333 14.806 4.318 16 8 16s6.667-1.194 6.667-2.667v-1.665z" />
                        </svg>

                    </div>

                    <div class="card-body">
                        <h5 class="card-title">Safety</h5>
                        <p class="card-text">Avec ce réseau social, vous êtes propriétaire de vos informations. Comme chaque serveur contient sa propre base de données, chaque utilisateur est propriétaire de ses informations contrairement aux autres réseaux sociaux. </p>
                    </div>
                </div>

            </div>


            <div class="row my-3">
                <div class="col mx-auto my-auto">

                    <div class="card text-center" style="width: 18rem;">
                        <div class="card-img-top my-3">

                            <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor" class="bi bi-wifi" viewBox="0 0 16 16">
                                <path d="M15.384 6.115a.485.485 0 0 0-.047-.736A12.444 12.444 0 0 0 8 3C5.259 3 2.723 3.882.663 5.379a.485.485 0 0 0-.048.736.518.518 0 0 0 .668.05A11.448 11.448 0 0 1 8 4c2.507 0 4.827.802 6.716 2.164.205.148.49.13.668-.049z" />
                                <path d="M13.229 8.271a.482.482 0 0 0-.063-.745A9.455 9.455 0 0 0 8 6c-1.905 0-3.68.56-5.166 1.526a.48.48 0 0 0-.063.745.525.525 0 0 0 .652.065A8.46 8.46 0 0 1 8 7a8.46 8.46 0 0 1 4.576 1.336c.206.132.48.108.653-.065zm-2.183 2.183c.226-.226.185-.605-.1-.75A6.473 6.473 0 0 0 8 9c-1.06 0-2.062.254-2.946.704-.285.145-.326.524-.1.75l.015.015c.16.16.407.19.611.09A5.478 5.478 0 0 1 8 10c.868 0 1.69.201 2.42.56.203.1.45.07.61-.091l.016-.015zM9.06 12.44c.196-.196.198-.52-.04-.66A1.99 1.99 0 0 0 8 11.5a1.99 1.99 0 0 0-1.02.28c-.238.14-.236.464-.04.66l.706.706a.5.5 0 0 0 .707 0l.707-.707z" />
                            </svg>

                        </div>

                        <div class="card-body">
                            <h5 class="card-title"> Network </h5>
                            <p class="card-text"> Choisissez la privacité de vos messages. Vous pouvez les partager en privé, publiquement ou simplement pour vos amis ! </p>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>

    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>


</body>

</html>