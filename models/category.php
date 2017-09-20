<?php

class Category extends Model{

    public  function getList($only_published = false){
        $sql = 'select * from categories where 1';
        if ( $only_published) {
            $sql .= ' and displayed = 1';
        }
        $sql .= ' order by category_name ';
        return $this->db->query($sql);
    }

    public  function  save($data, $id = null){
        if ( !isset($data['category_name']) ) {
            return false;
        }

        $id = (int)$id;
        $category_name = $this->db->escape($data['category_name']);

        if ( !$id) {  // Add new record
            $sql = "
                insert into categories
                  set category_name = '{$category_name}'
            ";
        } else {  // Update existing record
            $sql = "
                update categories
                  set category_name = '{$category_name}'
                  where id = {$id} 
            ";
        }

        return $this->db->query($sql);
    }

    public function delete($id) {
        $id = (int)$id;
        $sql = "delete from categories where id = {$id}";
        return $this->db->query($sql);

    }

}