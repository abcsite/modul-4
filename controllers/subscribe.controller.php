<?php

class SubscribeController extends  Controller {

    public function __construct($data = array()) {
        parent::__construct($data);
        $this->model = new User();
    }

    
    public  function  subscribe() {

    }



}