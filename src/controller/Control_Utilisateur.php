

<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/fb-distribue/src/config/connection.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/fb-distribue/src/model/Utilisateur.php');

function getMyInform()
{
  $instance = Connection::getInstance();
  $mysql = $instance->getConnection();
  $query = "select * from utilisateur where typeUser = 'Owner'";
  $result = $mysql->query($query);
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $user = new Utilisateur($row["id"], $row["firstName"], $row["lastName"], $row["url"], $row["typeUser"]);
    }
  } else {
    return false;
  }
  return $user;
}

function insertUser($firstName, $lastName, $url, $typeUser)
{
  $instance = Connection::getInstance();
  $mysql = $instance->getConnection();
  $query = " insert into  utilisateur(firstName,lastName,url ,typeUser) values('" . $firstName . "','" . $lastName . "','" . $url . "','" . $typeUser . "')";
  if ($mysql->query($query) !== TRUE) {
    echo "Error: " . $query . "<br>" . $mysql->error;
  }
}

function allFriends()
{

  $instance = Connection::getInstance();
  $mysql = $instance->getConnection();

  $counter = 0;
  $listeUsers = array();
  $query = "select * from utilisateur where typeUser='Friend'";
  $result = $mysql->query($query);
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $listeUsers[$counter] = new Utilisateur($row["id"], $row["firstName"], $row["lastName"], $row["url"], $row["typeUser"]);
      $counter = $counter + 1;
    }
  } else {
    echo "0 results";
  }

  return $listeUsers;
}

function  findUsersByType($userType)
{

  $instance = Connection::getInstance();
  $mysql = $instance->getConnection();

  $counter = 0;
  $listeUsers = array();
  $query = "select * from utilisateur where typeUser='" . $userType . "'";
  $result = $mysql->query($query);
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $listeUsers[$counter] = new Utilisateur($row["id"], $row["firstName"], $row["lastName"], $row["url"], $row["typeUser"]);
      $counter = $counter + 1;
    }
  } else {
    return false;
  }

  return $listeUsers;
}

function  findUserByUrl($urlSender)
{

  $instance = Connection::getInstance();
  $mysql = $instance->getConnection();
  $userInformation = false;
  $counter = 0;
  $query = "select * from utilisateur where url='" . $urlSender . "'";
  $result = $mysql->query($query);
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $userInformation = new Utilisateur($row["id"], $row["firstName"], $row["lastName"], $row["url"], $row["typeUser"]);
    }
  } else {
    $userInformation = false;
  }

  return $userInformation;
}




function  findUsersByTypeAndByUrl($userType, $urlSender)
{

  $instance = Connection::getInstance();
  $mysql = $instance->getConnection();
  $userInformation = false;
  $counter = 0;
  $query = "select * from utilisateur where typeUser='" . $userType . "' and url='" . $urlSender . "'";
  $result = $mysql->query($query);
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $userInformation = new Utilisateur($row["id"], $row["firstName"], $row["lastName"], $row["url"], $row["typeUser"]);
    }
  } else {
    $userInformation = false;
  }

  return $userInformation;
}

function getUtilisateurs()
{
  $counter = 0;
  $instance = Connection::getInstance();
  $mysql = $instance->getConnection();

  $listeUsers = array();
  $query = "select * from utilisateur where typeUser <> 'Owner'";

  $result = $mysql->query($query);

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $listeUsers[$counter] = new Utilisateur($row["id"], $row["firstName"], $row["lastName"], $row["url"], $row["typeUser"]);
      $counter = $counter + 1;
    }
  } else {
    return $listeUsers;
  }

  return $listeUsers;
}

function deleteUtilisateurByUrl($url)
{

  $instance = Connection::getInstance();
  $mysql = $instance->getConnection();
  $query = "DELETE FROM utilisateur
            WHERE url = '" . $url . "'";

  if ($mysql->query($query) !== TRUE) {
    echo "Error: " . $query . "<br>" . $mysql->error;
  }
}

function changeTypeUtilisateur($url, $type, $firstName, $lastName)
{

  $instance = Connection::getInstance();
  $mysql = $instance->getConnection();
  $query = "UPDATE utilisateur
            SET typeUser = '" . $type . "'
            WHERE url = '" . $url . "'
            ";
  if ($mysql->query($query) !== TRUE) {
    echo "Error: " . $query . "<br>" . $mysql->error;
  }

  if ($mysql->affected_rows == 0) {

    insertUser($firstName, $lastName, $url, "Request Sent");
  }
}

function getMyFriends()
{

  $instance = Connection::getInstance();
  $mysql = $instance->getConnection();
  $query = "select * from utilisateur where typeUser = 'Friend'";
  $result = $mysql->query($query);
  $counter = 0;
  $listeUsers = array();
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $listeUsers[$counter] = new Utilisateur($row["id"], $row["firstName"], $row["lastName"], $row["url"], $row["typeUser"]);
      $counter = $counter + 1;
    }
  } else {
    return false;
  }

  return $listeUsers;
}


?>
