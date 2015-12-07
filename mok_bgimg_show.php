<?php
if (!defined('SYSTEM_ROOT')) { die('Insufficient Permissions'); } 
if (ROLE != 'admin') { msg('权限不足！'); }

//post传来img表示前台点击保存
if(isset($_POST['img'])){
	option::set('mok_bgimg',serialize($_POST));
	$mok_bgimg = $_POST;

	//如果之前设定的背景图不在了，那就更换第一张图
	if(file_exists(unserialize($i['opt']['mok_bgimg_img']))){
		$mok_bgimg_img['img'] = unserialize($i['opt']['mok_bgimg_img']);
	} else {
		$img = explode("\r\n", $_POST['img']);
		$mok_bgimg_img['img'] = $img[0];
	}

	$mok_bgimg_img['day'] = date('d');
	$mok_bgimg_img['hour'] = date('H');
	option::set('mok_bgimg_img',serialize($mok_bgimg_img));

	Redirect('index.php?plugin=mok_bgimg');//刷新一下才能生效
}else{
	$mok_bgimg = unserialize($i['opt']['mok_bgimg']);
}

function opt($key,$value=0){
	GLOBAL $mok_bgimg;
	if ($key=='img') {
		echo isset($mok_bgimg['img'])?$mok_bgimg['img']:'';
	} elseif ($key=='input') {
		echo 'value="'.$mok_bgimg[$value].'"';
	} elseif (isset($mok_bgimg[$key])) {
		if($mok_bgimg[$key]==$value){echo 'checked';}
	}
}

loadhead();
?>

<script type="text/javascript">
	function scan(){
		if(confirm("扫描前会清空上面输入框内的所有内容，确定？")){
			$('#mok_bgimg_scanBtn').attr("disabled","true").text('正在扫描...');
			$.get("plugins/mok_bgimg/apis.php",{"do":"scan"},function(data){
				if(data['msg'] == undefined){
					$('textarea[name=img]').text(data['img']);
					$('#mok_bgimg_scanBtn').text('扫描完毕');
				} else {
					$('#mok_bgimg_scanBtn').removeAttr("disabled").text('扫描img文件夹内的图片');
					alert(data['msg']);
				}
			},"json");
		}
	}
</script>
<div>
	<form action="index.php?plugin=mok_bgimg" method="post">
		<table class="table table-striped">
			<thead>
				<tr>
					<th style="width:40%">参数</th>
					<th style="width:60%">值</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>背景图片URL<br/><br/>将图片放入plugins/mok_bgimg里面，然后在右边填入该图片路径，例如plugins/mok_bgimg/sora.jpg<br/><br/>当然你也可以填写外部图片的网址<br/>一行仅限一张图片<br/>是/不是\，分清楚<br/><br/>只能使用英文+数字文件名，否则将一直卡在“正在扫描...”</td>
					<td>
						<textarea name="img" class="form-control" style="height:250px"><?php opt('img') ?></textarea>
					</td>
				</tr>
				<tr>
					<td>扫描图片</td>
					<td>
						<button id="mok_bgimg_scanBtn" type="button" class="btn btn-primary" value="扫描img文件夹内的图片" onclick="scan()">扫描img文件夹内的图片</button>
					</td>
				</tr>
                <tr>
                    <td>自动调用<a href="//cn.bing.com/" target="_blank">Bing的每日壁纸</a></td>
                    <td>
                        <input type="checkbox" name="bing" value="1" <?php opt('bing',1) ?>> 开启（开启后无视上面的设置，请将下面设为每天更换）
                    </td>
                </tr>
				<tr>
					<td>自动更换</td>
					<td>
						<input type="radio" name="change" value="0" <?php opt('change') ?>> 不更换<br/>
						<input type="radio" name="change" value="1" <?php opt('change',1) ?>> 每天更换<br/>
						<input type="radio" name="change" value="2" <?php opt('change',2) ?>> 每小时更换<br/>
						<input type="radio" name="change" value="3" <?php opt('change',3) ?>> 每次访问更换
					</td>
				</tr>
				<tr>
					<td>自动更换模式</td>
					<td>
						<input type="radio" name="mode" value="0" <?php opt('mode') ?>> 顺序更换<br/>
						<input type="radio" name="mode" value="1" <?php opt('mode',1) ?>> 随机更换
					</td>
				</tr>
				<tr>
					<td>背景重复</td>
					<td>
						<input type="radio" name="repeat" value="0" <?php opt('repeat') ?>> 纵向重复<br/>
						<input type="radio" name="repeat" value="1" <?php opt('repeat',1) ?>> 不重复（不推荐）<br/>
						<input type="radio" name="repeat" value="2" <?php opt('repeat',2) ?>> 不重复，拉伸全页面（不推荐）<br/>
						<input type="radio" name="repeat" value="3" <?php opt('repeat',3) ?>> [推荐]静态悬浮（拉伸全屏）<br/>
						<input type="radio" name="repeat" value="4" <?php opt('repeat',4) ?>> [推荐]静态悬浮（自适应纵向重复）
					</td>
				</tr>
				<tr>
					<td>透明选项<br/><br/>将一些元素的背景设为透明<br/>可以更好地展示背景图片<br/>可能不兼容某些模板</td>
					<td>
						<input type="checkbox" name="daohang" value="1" <?php opt('daohang',1) ?>> 导航栏<br/>
                        <input type="checkbox" name="chengxuxinxi" value="1" <?php opt('chengxuxinxi',1) ?>> （首页）程序信息<br/>
                        <input type="checkbox" name="yonghuxinxi" value="1" <?php opt('yonghuxinxi',1) ?>> （首页）用户信息、管理面板<br/>
						<input type="checkbox" name="liebiao" value="1" <?php opt('liebiao',1) ?>> 列表
						<input type="checkbox" name="liebiaoxian" value="1" <?php opt('liebiaoxian',1) ?>> 列表线<br/>
						<input type="checkbox" name="tishikuang" value="1" <?php opt('tishikuang',1) ?>> 提示框<br/>
						<input type="checkbox" name="caidan" value="1" <?php opt('caidan',1) ?>> 左侧菜单<br/>
						<input type="checkbox" name="shurukuang" value="1" <?php opt('shurukuang',1) ?>> 输入框（建议配合下面的输入框字体颜色）<br/>
                        <input type="checkbox" name="anniu" value="1" <?php opt('anniu',1) ?>> 按钮（丧心病狂，建议配合深色背景使用）
					</td>
				</tr>
				<tr>
					<td>字体颜色<br/><br/>自定义设定字体颜色<br/>可以防止默认字体在某些背景下看不清的问题<br/>留空则为恢复默认<br/><br/>请填写颜色值（包括#）：<a href="http://rgb.phpddt.com/" title="获取颜色值" target="_blank">获取颜色值</a><br/>或者填写css支持的英文颜色单词<br/>例如：<a href="http://rgb.phpddt.com/#english_color" title="获取英文颜色单词" target="_blank">red</a></td>
					<td>
						普通字体颜色：<input name="c_putong" type="text" class="form-control" <?php opt('input','c_putong') ?>><br/>
						左侧菜单栏字体颜色<input name="c_zuoce" type="text" class="form-control" <?php opt('input','c_zuoce') ?>><br/>
						顶部导航栏字体颜色<input name="c_dingbu" type="text" class="form-control" <?php opt('input','c_dingbu') ?>><br/>
						输入框字体颜色<input name="c_shurukuang" type="text" class="form-control" <?php opt('input','c_shurukuang') ?>>
					</td>
				</tr>
			</tbody>
		</table>
		<input type="submit" class="btn btn-primary" value="提交更改">
	</form>
</div>

<?php loadfoot(); ?>