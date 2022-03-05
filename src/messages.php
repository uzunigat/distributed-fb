<?php

include("./header.php");
require_once "./controller/Control_Utilisateur.php";
require_once "./controller/Control_Messages.php";

$friends = allFriends();
$user = getMyInform();
$receivedMessages = allMessagesReceived($user->get_url());
$sendMessages = allMessagesSent($user->get_url());

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="./css/bootstrap.min.css">

    <title> FB Distribué </title>
</head>

<body>
    <div class="container mx-auto justify-content-center">

        <!-- Send Message Content -->
        <div class="row justify-content-center">
            <div class="text-center">
                <h2 class="mb-4 mt-3" id="send"> Envoyer un message </h2>
                <div class="form-group text-center">
                    <textarea id="payload" class="form-control mx-auto my-2" name="messageContent" id="messageContent" placeholder="Write the message here ..."></textarea>
                    <select id="receiver" class="form-control mx-auto my-2">
                        <?php foreach ($friends as $friend) { ?>
                            <option value="<?php echo $friend->get_url() ?>">
                                <?php echo $friend->get_firstName() . " " . $friend->get_lastName(); ?>
                            </option>
                        <?php } ?>

                    </select>
                    <input type="button" value="Send" class='btn btn-primary center-block' id="sendButton">
                </div>
            </div>
        </div>

        <!-- Received message -->
        <div class="row justify-content-center">
            <div class="text-center">
                <h2 class="mb-4 mt-3" id="send"> Messages reçus </h2>
                <?php if (empty($receivedMessages)) { ?>
                    <h5 class="mb-4 mt-3" id="send"> Vous n'avez pas reçu de message ! </h5>
                <?php } else { ?>

                    <div class="card-columns">
                        <?php foreach ($receivedMessages as $message) { ?>
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <?php
                                        $key = array_search($message->get_senderURL(), array_column($friends, 'url'));
                                        echo $friends[$key]->get_firstName() . " " . $friends[$key]->get_lastName();
                                        ?>
                                    </h5>
                                    <h6 class="card-subtitle mb-2 text-muted">Message :</h6>
                                    <p class="card-text"><?php echo $message->get_payload(); ?></p>
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal" data-idmessage="<?php echo $message->get_idMessage(); ?>">Transférer</button>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>

        </div>

        <!-- Send message -->
        <div class="row justify-content-center">
            <div class="text-center">
                <h2 class="mb-4 mt-3" id="send"> Messages envoyés </h2>

                <?php if (empty($sendMessages)) { ?>
                    <h5 class="mb-4 mt-3" id="send"> Vous n'avez pas envoyés de message ! </h5>
                <?php } else { ?>

                    <div class="card-columns">
                        <?php foreach ($sendMessages as $message) { ?>
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <?php
                                        $key = array_search($message->get_receiverURL(), array_column($friends, 'url'));
                                        echo $friends[$key]->get_firstName() . " " . $friends[$key]->get_lastName();
                                        ?>
                                    </h5>
                                    <h6 class="card-subtitle mb-2 text-muted">Message :</h6>
                                    <p class="card-text"><?php echo $message->get_payload(); ?></p>
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal" data-idmessage="<?php echo $message->get_idMessage(); ?>">Transférer</button>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tranférer un message </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row justify-content-center">
                        <div class="text-center">
                            <div class="form-group text-center">
                                <select id="transfert" class="form-control mx-auto my-2">
                                    <option value="Publique">Tout le monde</option>
                                    <option value="Amis des amis">Amis-Amis</option>
                                    <option value="Amis">Amis</option>
                                    <?php foreach ($friends as $friend) { ?>
                                        <option value="<?php echo $friend->get_url() ?>">
                                            <?php echo $friend->get_firstName() . " " . $friend->get_lastName(); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                                <input hidden id="idmsg">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button id="sendTransfert" type="button" class="btn btn-primary">Enregistrer</button>
                </div>
            </div>
        </div>
    </div>

    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {


            $('#modal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget) // Button that triggered the modal
                var idMessage = button.data('idmessage') // Extract info from data-* attributes
                $(this).find('#idmsg').val(idMessage)
            })

            let myUrl = "<?php echo $user->get_url(); ?>";

            // Click sendMessage
            $("#sendButton").click(function() {
                let payload = $('#payload').val();
                let receiver = $('#receiver').val();
                let port = receiver.substr(receiver.length - 4, receiver.length)

                var currentdate = new Date();
                var date = currentdate.getFullYear() + "/" +
                    (currentdate.getMonth() + 1) + "/" +
                    currentdate.getDate() + " " +
                    currentdate.getHours() + ":" +
                    currentdate.getMinutes() + ":" +
                    currentdate.getSeconds();

                sendMessage(port, payload, receiver, myUrl, date)
            });

            // Click sendMessage
            $("#sendTransfert").click(function() {

                let transfert = $('#transfert').val();
                let idMessage = $('#idmsg').val();


                let port = transfert.substr(transfert.length - 4, transfert.length)

                var currentdate = new Date();
                var date = currentdate.getFullYear() + "/" +
                    (currentdate.getMonth() + 1) + "/" +
                    currentdate.getDate() + " " +
                    currentdate.getHours() + ":" +
                    currentdate.getMinutes() + ":" +
                    currentdate.getSeconds();

                $.ajax({
                    url: "./controller/General_Controller.php",
                    method: "POST",
                    data: {
                        functionName: 'retrieveMessage',
                        arguments: [idMessage]
                    },

                    crossDomain: true,
                    dataType: 'json',

                    success: function(data) {
                        let result = JSON.parse(data["result"])

                        if (transfert == "Publique" || transfert == "Amis des amis" || transfert == "Amis") {
                            $.ajax({
                                method: "POST",
                                url: './controller/General_Controller.php',
                                dataType: 'json',
                                data: {
                                    functionName: 'transfertMessage',
                                    arguments: [transfert, result['payload'], date]
                                },
                                success: function(data) {
                                    console.log(data)
                                },
                                error: function(XMLHttpRequest, textStatus, errorThrown) {
                                    console.log("Status: " + textStatus);
                                    console.log("Error: " + errorThrown);
                                }
                            });
                        } else {
                            sendMessage(port, result["payload"], transfert, myUrl, date)
                        }
                    }
                });


            });

            function sendMessage(port, payload, receiver, myUrl, date) {
                // Send Request Friend to the site
                $.ajax({
                    url: "http://localhost:" + port + "/fb-distribue/src/endpoints/message.php",
                    method: "POST",

                    data: {
                        action: 'sendMessage',
                        sender: myUrl,
                        receiver: receiver,
                        payload: payload,
                        date: date
                    },

                    crossDomain: true,
                    dataType: 'text',
                    success: function(data) {
                        let postJson = JSON.parse(data)
                        // I have sent an invitation myself
                        if (postJson.response === 'Bad Request') {
                            alert("you can't add yourself");
                        } else {
                            // Add friend Request in My Database
                            $.ajax({
                                url: "./controller/General_Controller.php",
                                method: "POST",
                                data: {
                                    functionName: 'addMessage',
                                    arguments: [myUrl, receiver, payload, date]
                                },

                                crossDomain: true,
                                dataType: 'json',

                                success: function(data) {
                                    alert("Message Sent");
                                    location.reload();
                                }

                            });
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        alert("Status: " + textStatus);
                        alert("Error: " + errorThrown);
                    }

                });
            }
        });
    </script>
</body>

</html>