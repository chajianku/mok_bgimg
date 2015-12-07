<?php
/*
Plugin Name: 更换背景
Version: 1.2
Plugin URL: http://www.longtings.com/
Description: 更换云签系统背景图片
Author: mokeyjay
Author Email: longting@longtings.com
Author URL: http://www.longtings.com/
For: 不限
*/
if (!defined('SYSTEM_ROOT')) {
    die('Insufficient Permissions');
}

function mok_bgimg_navi()
{
    ?>
    <li><a href="index.php?plugin=mok_bgimg"><span class="glyphicon glyphicon-picture"></span> 更换背景</a></li>
<?php
}

addAction('navi_4', 'mok_bgimg_navi');

function mok_bgimg_header()
{
    global $i;
    $mok_bgimg = unserialize($i['opt']['mok_bgimg']);
    $mok_bgimg_img = unserialize($i['opt']['mok_bgimg_img']);
    $mok_bgimg_bing = unserialize($i['opt']['mok_bgimg_bing']);
    $img = explode("\r\n", $mok_bgimg['img']);//图片列表
    if (count($img) > 0 || $mok_bgimg_img['bing'] == '1') {//至少要有一张图片或开启bing每日壁纸
        //自动更换
        if ($mok_bgimg['change'] != 0) {
            $change = false;//是否更换
            if ($mok_bgimg['change'] == 1) {//每天更换
                if ($mok_bgimg_img['day'] != date('d')) $change = true;
            } elseif ($mok_bgimg['change'] == 2) {//每小时更换
                if ($mok_bgimg_img['hour'] != date('H')) $change = true;
            } elseif ($mok_bgimg['change'] == 3) {//每次访问更换
                $change = true;
            }
            if ($change) {
                //记录背景更换时间
                $mok_bgimg_img['day'] = date('d');
                $mok_bgimg_img['hour'] = date('H');
                if ($mok_bgimg['bing'] == '1') {//如果使用bing每日壁纸
                    $c = new wcurl('http://www.bing.com/HPImageArchive.aspx?format=js&idx=0&n=1');
                    $data = json_decode($c->get(), true);
                    $c->close();
                    if (!empty($data['images'][0]['url'])) {
                        option::set('mok_bgimg_bing', serialize(Array('img' => $data['images'][0]['url'])));
                        $mok_bgimg_img['img'] = $data['images'][0]['url'];
                    }
                } else {
                    if ($mok_bgimg['mode'] == 0) {//顺序更换
                        $key = array_search($mok_bgimg_img['img'], $img);//在图片列表中寻找当前背景图的序号
                        if ($key !== false) {
                            if (count($img) == $key + 1) {//如果当前图片已经是最后一张
                                $mok_bgimg_img['img'] = $img[0];
                            } else {
                                $mok_bgimg_img['img'] = $img[$key + 1];
                            }
                        } else {//如果在图片列表中没有找到当前背景图，就从第一张背景图开始
                            $mok_bgimg_img['img'] = $img[0];
                        }
                    } elseif ($mok_bgimg['mode'] == 1) {//随机更换
                        $mok_bgimg_img['img'] = $img[mt_rand(0, count($img) - 1)];
                    }
                }
            }
        }
        //第一次安装插件时此值为空，自动使用第一张图
        if ($mok_bgimg_img['img'] == '') {
            $mok_bgimg_img['img'] = $img[0];
        }
        //背景重复
        if ($mok_bgimg['repeat'] == 3 || $mok_bgimg['repeat'] == 4) {//静态悬浮
            if ($mok_bgimg['repeat'] == 3) {
                echo '<div id="mok_bgimg" style="width:100%;height:100%;background-size:100% 100%;background-image:url(\'' . $mok_bgimg_img['img'] . '\');position:fixed;"></div>';
            } else {
                echo '<div id="mok_bgimg" style="width:100%;height:100%;background-size:100% auto;background-image:url(\'' . $mok_bgimg_img['img'] . '\');position:fixed;"></div>';
            }
        } else {
            if ($mok_bgimg['repeat'] == 0) {//纵向重复
                $tmp[] = 'background-repeat:repeat-y';
            } else {
                $tmp[] = 'background-repeat:no-repeat';
            }
            if ($mok_bgimg['repeat'] == 2) {//拉伸全屏
                $tmp[] = 'background-size:100% 100%';
            } else {
                $tmp[] = 'background-size:100% auto';
            }
            $tmp[] = 'background-image:url(' . $mok_bgimg_img['img'] . ')';
            $css = implode(';', $tmp) . ';';
            echo '<style>body {' . $css . '}</style>';
        }
        option::set('mok_bgimg_img', serialize($mok_bgimg_img));//保存背景图片和更换时间

        //透明选项
        $css = '<style>';
        if ($mok_bgimg['daohang'] == 1)
            $css .= '.navbar{background:none !important}';
        if ($mok_bgimg['chengxuxinxi'] == 1)
            $css .= '.panel{background:none !important}';
        if ($mok_bgimg['yonghuxinxi'] == 1)
            $css .= '.list-group-item{background:none !important}';
        if ($mok_bgimg['liebiao'] == 1)
            $css .= '.table-striped>tbody>tr:nth-of-type(odd){background:none !important}';
        if ($mok_bgimg['liebiaoxian'] == 1)
            $css .= '.table>tbody>tr>td{border-top:none !important}';
        if ($mok_bgimg['tishikuang'] == 1)
            $css .= '.alert{background:none !important}';
        if ($mok_bgimg['caidan'] == 1)
            $css .= '.nav>li>a:hover, .nav>li>a:focus{background:none !important}';
        if ($mok_bgimg['shurukuang'] == 1)
            $css .= '.form-control{background:none !important}';
        if ($mok_bgimg['anniu'] == 1)
            $css .= '.btn{background:none !important}';

        //字体颜色
        if (!empty($mok_bgimg['c_putong']))
            $css .= 'body{color:' . $mok_bgimg['c_putong'] . '}';
        if (!empty($mok_bgimg['c_zuoce']))
            $css .= '.container .nav>li>a{color:' . $mok_bgimg['c_zuoce'] . '}';
        if (!empty($mok_bgimg['c_dingbu']))
            $css .= '.navbar-default .navbar-nav>li>a,.navbar-default .navbar-brand{color:' . $mok_bgimg['c_dingbu'] . ' !important}';
        if (!empty($mok_bgimg['c_shurukuang']))
            $css .= '.form-control{color:' . $mok_bgimg['c_shurukuang'] . '}';
        $css .= '</style>';
        echo $css;
    }
}

addAction('header', 'mok_bgimg_header');
?>
