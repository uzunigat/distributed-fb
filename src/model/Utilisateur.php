<?php
class Utilisateur {

  public $idFriend; 
  public $firstName;
  public $lastName;
  public $url;
  public $typeUser;

  public function __construct($idFriend,$firstName,$lastName,$url,$typeUser){
    $this->idFriend=$idFriend;
    $this->firstName=$firstName;
    $this->lastName=$lastName;
    $this->url=$url;
    $this->typeUser=$typeUser;

  }


  public function set_idFriend ($idFriend){
    $this->idFriend=$idFriend;
  }
  public function set_firstName ($firstName){
    $this->firstName=$firstName;

  }
  public function set_lastName ($lastName){
    $this->lastName=$lastName;

  }
  public function set_url ($url){
    $this->url=$url;
  }
  public function set_typeUser($typeUser){
    $this->typeUser=$typeUser;
  }

  public function get_idFriend (){
    return $this->idFriend;
  }
  public function get_firstName (){
    return $this->firstName;

  }
  public function get_lastName (){
    return $this->lastName;

  }
  public function get_url (){
    return $this->url;
  }
  public function get_typeUser(){
    return $this->typeUser;
  }
}
?>