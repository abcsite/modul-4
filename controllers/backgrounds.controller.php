<?php

class BackgroundsController extends Controller
{

    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->model = new Background();
    }

    public function admin_edit()
    {

//        var_dump($_POST);
//        var_dump($_FILES);  die;

        $bg_head_name = 'bg_head';
        $bg_page_name = 'bg_page';

        $result = true;

        if($_POST) {
            $result = $this->model->set_bg_color($bg_head_name, $_POST[$bg_head_name . '_color']);
            $result = $this->model->set_bg_color($bg_page_name, $_POST[$bg_page_name . '_color']);

        }
        
        if (!$_FILES[$bg_head_name]['name'] == '') {
            $result = $this->model->replaceImages($bg_head_name, $_FILES[$bg_head_name]);
        }
        if (!$_FILES[$bg_page_name]['name'] == '') {
            $result = $this->model->replaceImages($bg_page_name, $_FILES[$bg_page_name]);
        }
        $this->data[$bg_head_name] = $bg_head_name;
        $this->data[$bg_page_name] = $bg_page_name;

        $this->data[$bg_head_name . '_color'] =  $this->model->get_bg_color($bg_head_name . '_color');
        $this->data[$bg_page_name . '_color'] =  $this->model->get_bg_color($bg_page_name . '_color');

//            if ( $result) {
//                Session::setFlash('Article was saved.');
//            } else {
//                Session::setFlash('Error.');
//            }

//            Router::redirect('/admin/articles/edit/');


    }


    public function admin_delete()
    {
        if (isset($this->params[0])) {
            $result = $this->model->deleteImages($this->params[0]);

            if ($result) {
                Session::setFlash('Image was deleted.');
            } else {
                Session::setFlash('Error.');
            }
        }
        Router::redirect('/admin/backgrounds/edit/' );
    }
}