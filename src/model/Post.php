<?php

class Post {

    public $idPost;
    public $contentPost;
    public $datePost;
    public $scopePost;
    public $firstName;
    public $lastName;

    public function __construct($idPost,$contentPost,$datePost,$scopePost,$urlOwner,$firstName,$lastName){
        $this->idPost=$idPost;
        $this->contentPost=$contentPost;
        $this->datePost=$datePost;
        $this->scopePost=$scopePost;
        $this->urlOwner=$urlOwner;
        $this->firstName=$firstName;
        $this->lastName=$lastName;


    }



    public function get_firstName(){
        return $this->firstName;
    }

    public function set_firstName($firstName){
        $this->firstName=$firstName;

    }

    public function get_lastName(){
        return $this->lastName;
    }

    public function set_lastName($lastName){
        $this->lastName=$lastName;

    }

    public function get_urlOwner(){
        return $this->urlOwner;
    }

    public function set_urlOwner($urlOwner){
         $this->urlOwner=$urlOwner;
    }
    public function get_idPost(){
    return $this->idPost;

    }

    public function get_contentPost(){
        return $this->contentPost;

    }
    public function get_datePost(){
        return $this->datePost;

    }
    public function get_scopePost(){
        return $this->scopePost;

    }

    public function set_idPost($idPost){
        $this->idPost=$idPost;

    }

    public function set_contentPost($contentPost){
        $this->contentPost=$contentPost;

    }
    public function set_datePost($datePost){
        $this->datePost=$datePost;

    }
    public function set_scopePost($scopePost){
        $this->scopePost;
    }

}
?>