

<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/fb-distribue/src/config/connection.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/fb-distribue/src/model/Post.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/fb-distribue/src/controller/Control_Utilisateur.php');


function insertPost($contentPost, $datePost, $scopePost, $urlOwner, $firstName, $lastName)
{
  $instance = Connection::getInstance();
  $mysql = $instance->getConnection();
  $query = " insert into  posts (contentPost,datePost,scopePost,urlOwner,firstName,lastName) values('" . $contentPost . "','" . $datePost . "','" . $scopePost . "','" . $urlOwner . "','" . $firstName . "','" . $lastName . "')";
  if ($mysql->query($query) != TRUE) {
    echo "Error: " . $query . "<br>" . $mysql->error;
  }

  return true;
}

function allMyPostsByType($typePost, $urlOwner)
{

  $instance = Connection::getInstance();
  $mysql = $instance->getConnection();
  $query = "select * from posts WHERE scopePost='" . $typePost . "' and urlOwner='" . $urlOwner . "'";
  $counter = 0;
  $listPosts = array();
  $result = $mysql->query($query);
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $listPosts[$counter] = new Post($row["idPost"], $row["contentPost"], $row["datePost"], $row["scopePost"], $row["urlOwner"], $row["firstName"], $row["lastName"]);
      $counter = $counter + 1;
    }
  } else {
    return false;
  }

  return $listPosts;
}

function getPost($contentPost, $datePost, $urlOwner)
{
  $instance = Connection::getInstance();
  $mysql = $instance->getConnection();
  $query = "select * from posts WHERE contentPost='" . $contentPost . "' and datePost='" . $datePost . "' and urlOwner='" . $urlOwner . "'";
  $counter = 0;
  $listPosts = array();
  $result = $mysql->query($query);
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $listPosts[$counter] = new Post($row["idPost"], $row["contentPost"], $row["datePost"], $row["scopePost"], $row["urlOwner"], $row["firstName"], $row["lastName"]);
      $counter = $counter + 1;
    }
  } else {
    return false;
  }

  return $listPosts;
}


function allMyPosts($urlOwner)
{

  $instance = Connection::getInstance();
  $mysql = $instance->getConnection();
  $query = "select * from posts where urlOwner='" . $urlOwner . "'";
  $counter = 0;
  $listPosts = array();
  $result = $mysql->query($query);
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $listPosts[$counter] = new Post($row["idPost"], $row["contentPost"], $row["datePost"], $row["scopePost"], $row["urlOwner"], $row["firstName"], $row["lastName"]);
      $counter = $counter + 1;
    }
  } else {
    return false;
  }

  return $listPosts;
}

function allPostHomePage()
{

  $instance = Connection::getInstance();
  $mysql = $instance->getConnection();
  $query = "select * from posts order by (datePost) DESC";
  $counter = 0;
  $listPosts = array();
  $result = $mysql->query($query);
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $listPosts[$counter] = new Post($row["idPost"], $row["contentPost"], $row["datePost"], $row["scopePost"], $row["urlOwner"], $row["firstName"], $row["lastName"]);
      $counter = $counter + 1;
    }
  } else {
    return false;
  }

  return $listPosts;
}

function propagatePost($contentPost, $datePost, $scopePost, $urlOwner, $firstName, $lastName)
{
  switch ($scopePost) {

    case "Publique":
      $data = array(
        'contentPost' => $contentPost,
        'datePost' => $datePost,
        'scopePost' => $scopePost,
        'firstName' => $firstName,
        'lastName' => $lastName,
        'urlOwner' => $urlOwner,
        'action' => 'newPublicPost'
      );

      $receivers = getUtilisateurs();
      $postString = http_build_query($data, '', '&');
      if ($receivers != false) {
        foreach ($receivers as $receiver) {
          $newUrl =  explode(":", $receiver->get_url());
          $urlReceiver = $newUrl[0] . ":" . $newUrl[1] . "/fb-distribue/src/endpoints/post.php";
          doPostRequest($urlReceiver, $postString);
        }
      }


      break;

    case "Amis des amis":
      $data = array(
        'contentPost' => $contentPost,
        'datePost' => $datePost,
        'scopePost' => $scopePost,
        'firstName' => $firstName,
        'lastName' => $lastName,
        'urlOwner' => $urlOwner,
        'action' => 'newPost-FriendsOfFriends'
      );

      $receivers = findUsersByType('Friend');
      $postString = http_build_query($data, '', '&');
      foreach ($receivers as $receiver) {
        $newUrl =  explode(":", $receiver->get_url());
        $urlReceiver = $newUrl[0] . ":" . $newUrl[1] . "/fb-distribue/src/endpoints/post.php";
        doPostRequest($urlReceiver, $postString);
      }

      break;

    case "Amis":
      $data = array(
        'contentPost' => $contentPost,
        'datePost' => $datePost,
        'scopePost' => $scopePost,
        'firstName' => $firstName,
        'lastName' => $lastName,
        'urlOwner' => $urlOwner,
        'action' => 'newPost-Friends'
      );

      $receivers = findUsersByType('Friend');
      $postString = http_build_query($data, '', '&');
      foreach ($receivers as $receiver) {
        $newUrl =  explode(":", $receiver->get_url());
        $urlReceiver = $newUrl[0] . ":" . $newUrl[1] . "/fb-distribue/src/endpoints/post.php";
        doPostRequest($urlReceiver, $postString);
      }
      break;
  }
}

function doPostRequest($url, $myvars)
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
