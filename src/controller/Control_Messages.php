
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/fb-distribue/src/config/connection.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/fb-distribue/src/model/Message.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/fb-distribue/src/controller/Control_Utilisateur.php');

function insertMessage($senderURL, $receiverURL, $payload, $date)
{
  $instance = Connection::getInstance();
  $mysql = $instance->getConnection();
  $query = " insert into  messages(senderURL,receiverURL,payload, dateMessage) values('" . $senderURL . "','" . $receiverURL . "','" . $payload . "','" . $date . "')";
  if ($mysql->query($query) !== TRUE) {
    echo "Error: " . $query . "<br>" . $mysql->error;
  }
  return true;
}

function allMessagesSent($person)
{
  $instance = Connection::getInstance();
  $mysql = $instance->getConnection();
  $query = "select * from messages WHERE senderURL='" . $person . "'";
  $counter = 0;
  $listMessages = array();
  $result = $mysql->query($query);
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $listMessages[$counter] = new Message($row["idMessage"], $row["senderURL"], $row["receiverURL"], $row["payload"]);
      $counter = $counter + 1;
    }
  } else {
    echo "0 results";
  }

  return $listMessages;
}

function allMessagesReceived($person)
{

  $instance = Connection::getInstance();
  $mysql = $instance->getConnection();
  $query = "select * from messages WHERE receiverURL = '" . $person . "'";
  $counter = 0;
  $listMessages = array();
  $result = $mysql->query($query);
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $listMessages[$counter] = new Message($row["idMessage"], $row["senderURL"], $row["receiverURL"], $row["payload"]);
      $counter = $counter + 1;
    }
  } else {
    echo "0 results";
  }

  return $listMessages;
}


function getAllMessage()
{
  $instance = Connection::getInstance();
  $mysql = $instance->getConnection();
  $query = "select * from messages";
  $counter = 0;
  $listMessages = array();
  $result = $mysql->query($query);
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $listMessages[$counter] = new Message($row["idMessage"], $row["senderURL"], $row["receiverURL"], $row["payload"]);
      $counter = $counter + 1;
    }
  } else {
    echo "0 results";
  }
  return $listMessages;
}

function getMessageById($id)
{
  $instance = Connection::getInstance();
  $mysql = $instance->getConnection();
  $query = "select * from messages where idMessage = '" . $id . "'";
  $result = $mysql->query($query);
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $message = new Message($row["idMessage"], $row["senderURL"], $row["receiverURL"], $row["payload"]);
    }
  } else {
    return false;
  }
  return $message;
}

function getMessage($payload, $dateMessage, $senderURL)
{
  $instance = Connection::getInstance();
  $mysql = $instance->getConnection();
  $query = "select * from messages WHERE payload='" . $payload . "' and dateMessage='" . $dateMessage . "' and senderURL='" . $senderURL . "'";
  $counter = 0;
  $listMessages = array();
  $result = $mysql->query($query);
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $listMessages[$counter] = new Message($row["idMessage"], $row["senderURL"], $row["receiverURL"], $row["payload"]);
      $counter = $counter + 1;
    }
  } else {
    return false;
  }

  return $listMessages;
}

function propagateMessage($scopePost, $payload, $date, $sender)
{
  switch ($scopePost) {
    case "Publique":
      $data = array(
        'payload' => $payload,
        'dateMessage' => $date,
        'sender' => $sender,
        'scopePost' => $scopePost,
        'action' => 'newPublicPost'
      );
      $receivers = getUtilisateurs();
      $postString = http_build_query($data, '', '&');
      if ($receivers != false) {
        foreach ($receivers as $receiver) {
          $newUrl =  explode(":", $receiver->get_url());
          $urlReceiver = $newUrl[0] . ":" . $newUrl[1] . "/fb-distribue/src/endpoints/message.php";
          doPostRequestMessage($urlReceiver, $postString);
        }
      }
      return true;
      break;

    case "Amis des amis":
      $data = array(
        'payload' => $payload,
        'dateMessage' => $date,
        'sender' => $sender,
        'scopePost' => $scopePost,
        'action' => 'newPost-FriendsOfFriends'
      );

      $receivers = findUsersByType('Friend');
      $postString = http_build_query($data, '', '&');
      foreach ($receivers as $receiver) {
        $newUrl =  explode(":", $receiver->get_url());
        $urlReceiver = $newUrl[0] . ":" . $newUrl[1] . "/fb-distribue/src/endpoints/message.php";
        doPostRequestMessage($urlReceiver, $postString);
      }
      break;

      case "Amis":
        $data = array(
          'payload' => $payload,
          'dateMessage' => $date,
          'sender' => $sender,
          'scopePost' =>$scopePost,
          'action' => 'newPost-Friends'
        );

        $receivers = findUsersByType('Friend');
        $postString = http_build_query($data, '', '&');
        foreach ($receivers as $receiver) {
          $newUrl =  explode(":", $receiver->get_url());
          $urlReceiver = $newUrl[0] . ":" . $newUrl[1] . "/fb-distribue/src/endpoints/message.php";
          doPostRequestMessage($urlReceiver, $postString);
        }
        break;
  }
}

function doPostRequestMessage($url, $myvars)
{
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $myvars);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $response = curl_exec($ch);
}
?>
