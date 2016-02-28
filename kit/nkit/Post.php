<?php
namespace orangelab;
/**
 * Created by PhpStorm.
 * User: 재혁
 * Date: 2/11/2016
 * Time: 1:55 PM
 */


class Post
{
    public $board_no, $module_no, $title, $date;

    function __construct($board_no, $module_no, $title, $date)
    {
        $this->board_no = $board_no;
        $this->module_no = $module_no;
        $this->title = $title;
        $this->date = $date;
    }
}