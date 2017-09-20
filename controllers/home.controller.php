<?php

class HomeController extends Controller
{

    public function __construct($data = array())
    {
        parent::__construct($data);  
        $this->model = new Home_page();
    }

    public function index()
    {
        $this->data['categ_articles'] = [];
        $categ_list = $this->model->getCategList(true);
        if ($categ_list) {
            
            foreach ($categ_list as $cat) {
                $this->data['categ_articles'][$cat['id']]['cat_name'] =  $cat['category_name'];
                $article_list = $this->model->getArticlesByCategId( $cat['id'] );
                
                foreach ($article_list as $art) {
                    $this->data['categ_articles'][$cat['id']]['articles'][$art['art_id']]['art_title'] =  $art['art_title'];
                    $this->data['categ_articles'][$cat['id']]['articles'][$art['art_id']]['art_id'] =  $art['art_id'];

                }
            }
        }
    }

    public function admin_index()
    {
        $this->index();
        return VIEWS_PATH.'/home/index.html';
    }


//    public function view()
//    {
//        $params = App::getRouter()->getParams();
//
//        if (isset($params[0])) {
//            $alias = strtolower($params[0]);
//            $result = $this->model->getByAlias($alias, true);
////            var_dump($result);die;
//            if ($result) {
//                $this->data['page'] = $result;
//            } else {
//                Session::setFlash('This page does not exist.');
//            }
//        }
//    }


//    public function admin_add()
//    {
//
//        if ($_POST) {
//            $result = $this->model->save($_POST);
////            print_r($_FILES);die;
//
//            if ($result) {
//                $result = $result && $this->model->saveImages($_FILES['images']);
//            }
//
////            if ( $result) {
////                Session::setFlash('Article was saved.');
////            } else {
////                Session::setFlash('Error.');
////            }
//
//            Router::redirect('/admin/articles/');
//
//        }
//    }

//    public function admin_edit()
//    {
//
////        var_dump($_POST);
////        var_dump($_FILES);  die;
//
//        if ($_POST) {
//            $id = isset($_POST['id']) ? $_POST['id'] : null;
//            $result = $this->model->save($_POST, $id);
//
//            if ($result && $_FILES['images']) {
//                $result = $this->model->saveImages($_FILES['images'], $id);
//            }
//
//            if ($result && $_FILES['image']) {
//                $result = $this->model->replaceImages($_FILES['image'], $id);
//            }
//
//            if (!$id) {
//                $newId = $this->model->getMaxValue('articles', 'id');
//                if ($newId) {
//                    Router::redirect('/admin/articles/edit/' . $newId . '/');
//                }
//            }
//
//
////            if ( $result) {
////                Session::setFlash('Article was saved.');
////            } else {
////                Session::setFlash('Error.');
////            }
//
////            Router::redirect('/admin/articles/edit/');
//
//        }
//
//        if (isset($this->params[0])) {
//            $this->data['article'] = $this->model->getById($this->params[0]);
//
//            $this->data['article_images'] = $this->model->getImgsByArticleId($this->params[0]);
//
//            $article_cat = $this->model->getCategByArticleId($this->params[0]);
//            $cat = $this->model->getCategories();
//            $this->data['article_cat'] = $article_cat;
//
//            $cat_other = [];
//            $colum = array_column($article_cat, 'cat_id');
//            foreach ($cat as $num => $row) {
//                if ( !in_array($row['cat_id'], $colum) ) {
//                    $cat_other[$num]['cat_id'] = $row['cat_id'];
//                    $cat_other[$num]['cat_name'] = $row['cat_name'];
//                }
//            }
//            $this->data['article_cat_other'] = $cat_other;
//
//        } else {
//            $this->data['article_cat_other'] = $this->model->getCategories();
//
////            Session::setFlash('Wrong article id.');
////            Router::redirect('/admin/articles/');
//        }
//    }
//
//    public function admin_delete()
//    {
//        if (isset($this->params[0])) {
//            $result = $this->model->delete($this->params[0]);
//            if ($result) {
//                Session::setFlash('Article was deleted.');
//            } else {
//                Session::setFlash('Error.');
//            }
//        }
//        Router::redirect('/admin/articles/');
//    }
//
//    public function admin_deleteimage()
//    {
//        if (isset($this->params[0])) {
//            $result = $this->model->del_image($this->params[0]);
//            if ($result) {
//                Session::setFlash('Image was deleted.');
//            } else {
//                Session::setFlash('Error.');
//            }
//        }
//        Router::redirect('/admin/articles/edit/' . $this->params[1]);
//    }
//
//    public function admin_add_cat()
//    {
//        if (isset($this->params[0]) && isset($this->params[1])) {
//            $this->model->add_cat($this->params[0], $this->params[1]);
//        }
//        Router::redirect('/admin/articles/edit/' . $this->params[1]);
//    }
//
//    public function admin_delete_cat()
//    {
//        if (isset($this->params[0]) && isset($this->params[1])) {
//            $this->model->delete_cat($this->params[0], $this->params[1]);
//        }
//        Router::redirect('/admin/articles/edit/' . $this->params[1]);
//    }
//
//    public function admin_add_cat_ajax()
//    {
////        deb($this->params[0]);
//        if (isset($this->params[0]) && isset($this->params[1])) {
//            $this->model->add_cat($this->params[0], $this->params[1]);
//        }
//        $par = [$this->params[0], $this->params[1], $this->params[2]];
//        echo(json_encode($par)); die;
//
//        Router::redirect('/admin/articles/edit/' . $this->params[1]);
//    }
//
//    public function admin_del_cat_ajax()
//    {
////        deb($this->params[0]);
//        if (isset($this->params[0]) && isset($this->params[1])) {
//            $this->model->delete_cat($this->params[0], $this->params[1]);
//        }
//        $par = [$this->params[0], $this->params[1], $this->params[2]];
//        echo(json_encode($par)); die;
//
//        Router::redirect('/admin/articles/edit/' . $this->params[1]);
//    }


}
