<?php

class Article extends Model
{

    public function getList($only_published = false)
    {
        $sql = 'select * from articles where 1';
        if ($only_published) {
            $sql .= ' and is_published = 1';
        }
        $sql .= ' order by title ';
        return $this->db->query($sql);
    }

    public function getById($id)
    {
        $id = (int)$id;
        $sql = "select * from articles where id = '{$id}' limit 1";
        $result = $this->db->query($sql);
        return isset($result[0]) ? $result[0] : null;
    }

    public function getCategoryByIds($ids)
    {
        if (is_array($ids)) {
            $in_ids = '';
            foreach ($ids as $id) {
                $in_ids = $in_ids . ',' . $id ;
            }
            $in_ids = substr( $in_ids, 1 );
        } else { return false;}
//        deb($sql);

        $sql = "select * from categories where id IN ({$in_ids}) ";
        $result = $this->db->query($sql);
        return $result;
    }

    public function getCategories()
    {
        $sql = "SELECT DISTINCT  *  FROM  categories c WHERE 1 ORDER BY c.category_name ";
        $result = $this->db->query($sql);
        return  $result ;
    }
    
    public function getCategoryByName($name)
    {
        $sql = "SELECT DISTINCT  *  FROM  categories c WHERE c.category_name = '{$name}' ORDER BY c.category_name ";
        $result = $this->db->query($sql);
        return  $result ;
    }
    
    public function getTagsAll()
    {
        $sql = $sql = "SELECT tags FROM articles  ";
        $result = $this->db->query($sql);
        return  $result ;
    }
    
    public function getCategByArticleId($id)
    {
        $id = (int)$id;
        $sql = "SELECT DISTINCT  c.id cat_id, c.category_name  cat_name  FROM categories_of_article ca JOIN categories c  
                ON ca.id_article = '{$id}' AND ca.id_category = c.id ORDER BY c.category_name
         ";
        $result = $this->db->query($sql);
        return  $result ;
    }



    public function getArticlesFilter($filter, $only_published = false )
    {
        if (is_array($filter)) {

            if ( is_array($filter['categ']) )  {
                $in_cat = '';
                foreach ($filter['categ'] as $cat) {
                    $in_cat = $in_cat . ',' . $cat ;
                }
                $in_cat = substr( $in_cat, 1 );
                $in_cat = " cat_id IN ('{$in_cat}')";
            } else { $in_cat = '1 = 1'; }

            if ( is_array($filter['tags']) )  {
                $in_tag = '';
                foreach ($filter['tags'] as $tag) {
                    $str = " AND art_tags LIKE '%" . $tag . "%' ";
                    $in_tag = $in_tag . $str ;
                }
                $in_tag = substr( $in_tag, 4 );
            } else { $in_tag = '1 = 1'; }

            if ($filter['date_min']) {
                $date_min_str = " art_date >= '{$filter['date_min']}' "  ;
            } else {
                $date_min_str = " 1 "  ;
            }

            if ($filter['date_max']) {
                $date_max_str = " art_date <= '{$filter['date_max']}' "  ;
            } else {
                $date_max_str = " 1 "  ;
            }

            if ($filter['order_by']) {
                $order_by = $filter['order_by'];
                if ( substr($order_by, 0, 1) == '-' ) {
                    $order_by = substr($order_by, 1, 1 - strlen($order_by));
                    $order_by = " ORDER BY  {$order_by} DESC  ";
                } else {
                    $order_by = " ORDER BY {$order_by} ";
                }

            } else {
                $order_by = ''  ;
            }

            if ($filter['limit_start']) {
                $limit_start = " LIMIT {$filter['limit_start']} "  ;
            } else {
                $limit_start = ''  ;
            }

            if ($filter['limit_offset']) {
                $limit_offset = " , {$filter['limit_offset']} "  ;
            } else {
                $limit_offset = ''  ;
            }

            $is_published = ($only_published) ? ' AND is_published = 1 ' : '';

            $select = "SELECT DISTINCT  ca.id_category cat_id,  
                    a.id art_id,  a.title art_title, a.description art_desc,  a.author art_author, a.text art_text,  
                    a.is_published art_publish, a.date_created art_creat, a.date_published art_date, a.category art_categ, a.tags art_tags
                    FROM categories_of_article ca JOIN articles a  ON  ca.id_article = a.id
                 ";
            $sql = "SELECT * FROM ({$select}) as sel WHERE {$in_cat}  AND  {$in_tag} AND {$date_min_str} AND {$date_max_str}
                    {$order_by}  {$limit_start} {$limit_offset} ";
        } else { return false;}
//deb($sql);
        $result = $this->db->query($sql);
        return  $result ;
    }



    public function getMaxValue($table, $field, $whereField = '1', $whereValue = '1')
    {
        $sql = "select max({$field}) as max from {$table} where {$whereField} = '{$whereValue}' ";
        $res = $this->db->query($sql);

        return $res[0]['max'];
    }

    public function save($data, $id = null)
    {
        if (!isset($data['author']) || !isset($data['title']) || $data['title'] == '' || !isset($data['description'])
            || !isset($data['text']) || !isset($data['tags'])) {
            return false;
        }

        $id = (int)$id;
        $author = $this->db->escape($data['author']);
        $title = $this->db->escape($data['title']);
        $description = $this->db->escape($data['description']);
        $text = $this->db->escape($data['text']);
        $tags = $this->db->escape($data['tags']);
        $is_published = isset($data['is_published']) ? 1 : 0;

        if (!$id) {  // Add new record
            $date_created = date("Y-m-d H:i:s");

            $sql = "
                insert into articles
                  set author = '{$author}',
                      title = '{$title}',
                      description = '{$description}',
                      text = '{$text}',
                      is_published = '{$is_published}',
                      date_created = '{$date_created}',
                      tags = '{$tags}'
            ";
            $sql = ($is_published) ? $sql . ", date_published = '" . date("Y-m-d H:i:s") . "'" : $sql;

        } else {  // Update existing record

            if (isset($data['date_created'])) {
                $date_created = $data['date_created'];
            } else {
                return false;
            }

            if (isset($data['date_published']) && isset($data['is_published_old'])) {
                if (isset($data['is_published']) && $data['is_published_old'] == 0) {
                    $date_published = date("Y-m-d H:i:s");
                } else {
                    $date_published = $data['date_published'];
                }
            } else {
                return false;
            }

            $sql = "
                update articles
                  set author = '{$author}',
                      title = '{$title}',
                      description = '{$description}',
                      text = '{$text}',
                      is_published = '{$is_published}',
                      date_created = '{$date_created}',
                      date_published = '{$date_published}',
                      tags = '{$tags}'
                  where id = {$id}
            ";
        }

        return $this->db->query($sql);
    }

    public function delete($id)
    {
        $id = (int)$id;
        $result = true;

        $resArr = $this->getImgsByArticleId($id);
        if ($resArr) {
            foreach ($resArr as $img) {
                $uploadfile = ARTICLES_IMG_PATH . DS . $img['full_name'];
                $result = $result && unlink($uploadfile);
            }
        }

        $sql = "delete from articles where id = {$id}";
        $result = $result && $this->db->query($sql);

        $sql = "delete from images_of_article where id_article = {$id}";
        $result = $result && $this->db->query($sql);

        return $result;
    }

    public function getImgsByArticleId($id)
    {
        $id = (int)$id;
        $sql = "select * from images_of_article where id_article = '{$id}' order by id ";
        $result = $this->db->query($sql);
        return $result;
    }

    public function saveImages($data, $id = null)
    {
        $id = (int)$id;
        $maxId = $this->getMaxValue('articles', 'id');

        if ($id) {
            $maxNum = $this->getMaxValue('images_of_article', 'num', 'id_article', $id);
            $maxNum = ($maxNum === null) ? 0 : $maxNum + 1;
        } else {
            $maxNum = 0;
        }

        $maxImgId = $this->getMaxValue('images_of_article', 'id');
        $maxImgId = ($maxImgId === null) ? 0 : $maxImgId;

        $countImgs = count($data['name']);
        $result = true;

        for ($i = 0; $i < $countImgs; $i++) {
            $num = $i + $maxNum + 1;
            $imgId = $maxImgId + $i + 1;

            if ($data['name'][$i] == '') {
                break;
            } else {
                $name = $this->db->escape(basename($data['name'][$i]));
                $full_name = $imgId . '_' . $name;
                $uploadfile = ARTICLES_IMG_PATH . DS . $full_name;
                if (!move_uploaded_file($data['tmp_name'][$i], $uploadfile)) {
                    $result = false;
                    break;
                }
            }
            if ($result) {
                if (!$id) {
                    $sql = "
                        insert into images_of_article
                          set id = '{$imgId}',
                              id_article = '{$maxId}',
                              num = '{$num}',
                              name = '{$name}',
                              full_name = '{$full_name}'
                    ";
                } else {
                    $sql = "
                        insert into images_of_article
                          set id = '{$imgId}',
                              id_article = '{$id}',
                              num = '{$num}',
                              name = '{$name}',
                              full_name = '{$full_name}'
                    ";
                }

                if (!$this->db->query($sql)) {
                    $result = false;
                    break;
                }
            }
        }
        return $result;

    }

    public function replaceImages($data, $id)
    {
        $id = (int)$id;
        $result = true;
//var_dump($data['name']);die;
        
        foreach ($data['name'] as $imgId => $imgName) {
            if ($imgName != '') {
                $name = $this->db->escape($imgName);
                $full_name = $imgId . '_' . $name;

                $sql = "select full_name from images_of_article where id = {$imgId} ";
                $res = $this->db->query($sql);

                if ($res) {
                    $old_full_name = $res[0]['full_name'];
                    $result = $result && true;
                } else $result = false;

                $uploadfile = ARTICLES_IMG_PATH . DS . $full_name;

                if ($result) {
                    $result = $result && unlink(ARTICLES_IMG_PATH . DS . $old_full_name);
                    $result = $result && move_uploaded_file($data['tmp_name'][$imgId], $uploadfile);
                }
                if ($result) {
                    $sql = "
                            update images_of_article
                              set name = '{$imgName}',
                                  full_name = '{$full_name}'
                              where id = {$imgId}
                            ";
                    $result = $result && $this->db->query($sql);
                }
            }
        }
        return $result;
    }

    public function del_image($full_name)
    {
        $sql = "delete from images_of_article where full_name = '{$full_name}' ";
        $result = $this->db->query($sql);

        $uploadfile = ARTICLES_IMG_PATH . DS . $full_name;
        $result = $result && unlink($uploadfile);

        return $result;
    }

    public function add_cat($catId, $id)
    {
        $id = (int)$id;
        $catId = (int)$catId;

        $sql = "
                insert into categories_of_article
                  set id_article = '{$id}',
                      id_category = '{$catId}'
            ";
        return $this->db->query($sql);
    }
    
    public function delete_cat($catId, $id)
    {
        $id = (int)$id;
        $catId = (int)$catId;

        $sql = " delete from categories_of_article  where id_article = '{$id}' and id_category = '{$catId}' ";
        return $this->db->query($sql);
    }




}


