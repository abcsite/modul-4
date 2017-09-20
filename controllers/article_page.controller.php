<?php

class Article_pageController extends Controller
{
    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->model = new Article_view();
    }


    public function view()
    {
        $params = App::getRouter()->getParams();

        if (isset($params[0])) {
            $result = $this->model->getById($params[0]);
//            var_dump($result['is_published']);die;
            if ($result) {
                if (Session::get('role') != 'admin' && $result['is_published'] == '0') {
                    Session::setFlash('This page does not exist.');
                } else {
                    $this->data['article_page'] = $result;
                    $tags = explode(',', $result['tags']);
                    $this->data['article_page']['tags'] = $tags;

                    $this->data['article_images'] = $this->model->getImgsByArticleId($this->params[0]);
                }


                $comments = $this->model->getCommentsByArticleId($this->params[0]);


                $comm_new = comments_recurs($comments, 0);

//                comments_echo($comm_new);
//                deb($comm_new);


                $this->data['comments'] = $comm_new;  
            }

        } else {
            Session::setFlash('This page does not exist.');
        }
    }


    public function admin_index()
    {
        $this->data['articles'] = $this->model->getList();
    }


}
