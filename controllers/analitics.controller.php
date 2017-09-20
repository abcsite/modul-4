<?php

class AnaliticsController extends Controller
{

    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->model = new Article();
    }


    public function analitics()
    {

        if ($_GET) {

            if ($_GET['to_page']) {
                $currentPage = $_GET['to_page'];
            } else {
                $currentPage = 1;
            }

            if ($_GET['filter_url']) {
                $filter = $_GET['filter_url'];
            } else {
                $filter = $_GET;
            }


            $filter['order_by'] = 'art_date';


            $catByName = $this->model->getCategoryByName('Analytics');
            $filter['categ'][0] = (int) $catByName[0]['id'];

//            deb($filter['categ'][0]);

            $itemsPerPage = 5;

            $filter['limit_start'] = null;
            $filter['limit_offset'] = null;
            $article_to_count = $this->model->getArticlesFilter($filter, true);
            $articles_count = count($article_to_count);

            $pagination = new Pagination($articles_count, $itemsPerPage, $currentPage);
            $pagin = $pagination->result;
            $filter['limit_start'] = $pagin['itemsStart'];
            $filter['limit_offset'] = $pagin['itemsEnd'] - $pagin['itemsStart'] + 1;

            $this->data['pagination'] = $pagin;


            $article_list = $this->model->getArticlesFilter($filter, true);

            if ($article_list) {
                if ( Session::get('role') == 'user' || Session::get('role') == 'admin') {
                    $this->data['articles'] = $article_list;
                } else {
                    foreach ($article_list as $num => $row) {
                        $arr = explode('.', $row['art_text']);
                        $new_arr = [];
                        $count = count($arr);
                        for ($i = 0; ($i < $count) && ($i < 5) ; $i++ ) {
                            $new_arr[$i] = $arr[$i];
                        }
                        $new_text = implode('.', $new_arr);
                        $article_list[$num]['art_text'] = $new_text;
                    }
                }


                $this->data['articles'] = $article_list;

                $categories = $this->model->getCategoryByIds($_GET['categ']);
                $this->data['filter']['categ'] = $categories;
                $this->data['filter']['tags'] = $_GET['tags'];
                $this->data['filter']['date_min'] = $_GET['date_min'];
                $this->data['filter']['date_max'] = $_GET['date_max'];

                $filter_arr = [
                    'categ' => $filter['categ'],
                    'tags' => $_GET['tags'],
                    'date_min' => $_GET['date_min'],
                    'date_max' => $_GET['date_max']
                ] ;
                $filter_url = http_build_query($filter_arr);
                $this->data['filter_url'] = $filter_url;
            }
        }


    }


}
