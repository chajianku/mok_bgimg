<?php
if (!defined('SYSTEM_ROOT')) {
    die('Insufficient Permissions');
}

function callback_init()
{
    //一些初始化数据
    $ary = Array('img' => '', 'change' => '0', 'mode' => '0', 'repeat' => '0');
    option::set('mok_bgimg', serialize($ary));
    //上次自动更换日期、上次自动更换时间、现行背景图片
    $ary = Array('day' => '0', 'hour' => '0', 'img' => '');
    option::set('mok_bgimg_img', serialize($ary));
    //bing每日壁纸
    option::set('mok_bgimg_bing', serialize(Array('img' => '')));
}

function callback_remove()
{
    option::pdel('mok_bgimg');
    option::pdel('mok_bgimg_img');
    option::del('mok_bgimg');
    option::del('mok_bgimg_img');
    option::del('mok_bgimg_bing');
}