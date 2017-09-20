<?php

class ContactsController extends Controller {

    public function __construct( $data = array() ){
        parent::__construct($data);
        $this->model = new Message();
    }

    public function index() {
        if ( $_POST) {
            if ( $this->model->save( $_POST)) {
                Session::setFlash('Thank you! Your message was sent successfully!');
            }
        }
    }

    public function admin_index() {
        $this->data = $this->model->getList();
        if ( Session::get('flash_message_change') ) {
            $flash = Session::get('flash_message_change');
            Session::delete('flash_message_change');
            Session::setFlash( $flash );
        }
    }

    public function admin_edit(){

        if ( $_POST ) {
            $id = isset($_POST['id']) ? $_POST['id'] : null ;
            $result = $this->model->save($_POST, $id);
            if ( $result) {
                Session::set('flash_message_change', 'Message was saved.');
                Router::redirect('/admin/contacts/');
            } else {
                Session::setFlash('Error.');
            }
        } else {
            if ( isset($this->params[0]) ) {
                $this->data['message'] = $this->model->getById($this->params[0]);
            } else {
                Session::set('flash_message_change', 'Wrong message id.');
                Router::redirect('/admin/contacts/');
            }
        }
    }

    public function admin_delete() {
        if ( isset( $this->params[0])) {
            $result = $this->model->delete($this->params[0]);
            if ( $result) {
                Session::set('flash_message_change', 'Message was deleted.');
            } else {
                Session::set('flash_message_change', 'Error!');
            }
        }
        Router::redirect('/admin/contacts/');
    }

}