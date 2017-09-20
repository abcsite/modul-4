<?php

function deb($data, $stop = 1) {
    var_dump($data);
if ($stop) {die;}
}



function comments_recurs($comm_arr, $id = 0, $data = null)
{
    $arr = [];
    foreach ($comm_arr as $row) {
        if ($row['id_parent_comment'] == $id) {
            $arr[$row['id_comment']] = $row;  
            $arr[$row['id_comment']]['childs'] = comments_recurs($comm_arr, $row['id_comment'], $data);
        }
    }
    if (count($arr)) {
        return $arr;
    } else {
        return null;
    }
}



//function comments_echo($comm_arr, $data = null)
//{
//    foreach ($comm_arr as $row) {
//        echo $row['text'] . '</br></br>';
//        if ($row['childs']) {
//            comments_echo($row['childs'], $data);
//        }
//    }
//}