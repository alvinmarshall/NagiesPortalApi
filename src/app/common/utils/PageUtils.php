<?php


namespace App\common\utils;


class PageUtils
{
    function setPagination($page, $total_rows, $records_per_page, $page_url)
    {
        if ($records_per_page == 0) return null;
        $paging_arr = [];
        $paging_arr['first'] = $page > 1 ? "{$page_url}page=1" : "";
        $total_pages = ceil($total_rows / $records_per_page);
        $range = 2;
        $initial_num = $page - $range;
        $condition_limit_num = ($page + $range) + 1;
        $paging_arr['pages'] = [];
        $page_count = 0;
        for ($i = $initial_num; $i < $condition_limit_num; $i++) {
            if (($i > 0) && ($i <= $total_pages)) {
                $paging_arr['pages'][$page_count]['page'] = $i;
                $paging_arr['pages'][$page_count]['url'] = "{$page_url}page={$i}";
                $paging_arr['pages'][$page_count]['current_page'] = $i == $page ? "yes" : "no";
                $page_count++;
            }
        }
        $paging_arr['last'] = $page < $total_pages ? "{$page_url}page={$total_pages}" : "";
        return $paging_arr;
    }

}