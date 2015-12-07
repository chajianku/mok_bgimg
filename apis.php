<?php
require '../../init.php';

if(!empty($_GET['do'])){
    switch ($_GET['do']){
        case 'scan':
            if(file_exists('img')){
                $file = scandir('img');
                foreach ($file as $value) {
                    if($value != '.' && $value != '..') $files[] = 'plugins/mok_bgimg/img/'.$value;
                }
                $files = implode("\r\n",$files);
                echo json_encode(Array('img'=>$files));
            } else {
                echo json_encode(Array('msg'=>'img文件夹不存在！请在plugins/mok_bgimg下创建img文件夹<br/>然后把背景图片放进去'));
            }
            break;
    }
}
?>