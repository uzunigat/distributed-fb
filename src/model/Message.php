<?php 
class Message {
    private $idMessage;
    private $senderURL;
    private $receiverURL;
    private $payload;

    public  function  __construct($idMessage,$senderURL,$receiverURL,$payload){
        $this->idMessage=$idMessage;
        $this->senderURL=$senderURL;
        $this->receiverURL=$receiverURL;
        $this->payload=$payload;
    }

        public function set_idMessage($idMessage){
            $this->idMessage=$idMessage;

        }
        public function set_senderURL($senderURL){
        $this->senderURL=$senderURL;


        }
        public function set_receiverURL($receiverURL){
        $this->receiverURL=$receiverURL;


        }
        public function set_payload($payload){
            $this->payload=$payload;

        }

        public function get_idMessage(){
            return $this->idMessage;

        }
        public function get_senderURL(){
        return $this->senderURL;


        }
        public function get_receiverURL(){
        return $this->receiverURL;


        }
        public function get_payload(){
            return $this->payload;

        }
    
}
?>