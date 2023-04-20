<?php

# get correct id for plugin
$thisfile = basename(__FILE__, ".php");

# add in this plugin's language file
i18n_merge('readTime') || i18n_merge('readTime', 'en_US');

# register plugin
register_plugin(
	$thisfile, //Plugin id
	'Read Time', 	//Plugin name
	'1.1', 		//Plugin version
	'Multicolor',  //Plugin author
	'https://paypal.me/multicol0r', //author website
	i18n_r('readTime/LANG_Description'), //Plugin description
	'plugins', //page type - on which admin tab to display
	'readTimeSettings'  //main function (administration)
);

add_action('plugins-sidebar', 'createSideMenu', array($thisfile, i18n_r('readTime/LANG_Settings')));

# functions
function readTime()
{

	global $SITEURL;

	$info = file_get_contents(GSDATAOTHERPATH . 'readTime/db.json');
	$json = json_decode($info);

	echo '
		<div style="display:flex; align-items:center; margin-bottom:30px;">
			<img src="' . $SITEURL . 'plugins/readTime/img/clock.svg" style="width:20px; margin:0 5px; display:inline-block;">
			<span id="showReadTime" style="margin-right:5px;"></span> ' . $json->info . '
		</div>

		<script>
			function readingTime() {
				const text = `' . strip_tags(str_replace('`', '"', returnPageContent(return_page_slug()))) . '`;
				const wpm = 225;
				const words = text.trim().split(/\s+/).length;
				const time = Math.ceil(words / wpm);
				document.getElementById("showReadTime").innerText = time;
			}
			readingTime();
		</script>
		';
}

function readTimeSettings()
{
	$info = @file_get_contents(GSDATAOTHERPATH . 'readTime/db.json');
	$json = json_decode($info);

	$html = '
		<h3>' . i18n_r('readTime/LANG_Settings') . '</h3>

		<div style="width:100%; padding:10px; border:solid 1px #ddd; box-sizing:border-box; background:#fafafa; color:#333;  margin-bottom:30px;">
			' . i18n_r('readTime/LANG_Add_this_PHP') . '
			<code style="color:blue;">&#60;?php readTime(); ?&#62;</code>
		</div>

		<form action="#" method="post">
			<p><strong>' . i18n_r('readTime/LANG_Text_to_display') . ':</strong></p>
			<input type="text" style="padding:10px; width:100%; display:block; box-sizing:border-box;" value="' . @$json->info . '" name="readtime" class="form-control" placeholder="' . i18n_r('readTime/LANG_Placeholder') . '">
			<input type="submit" name="submit" value="' . i18n_r('BTN_SAVESETTINGS') . '" style="background:#000; padding:10px 15px; border:none; margin-top:10px; color:#fff;">
		</form>

		';

	$html .= '
		<div id="paypal" style="margin-top:10px;background: #fafafa;border:solid 1px #ddd;padding: 10px;box-sizing: border-box;text-align: center;">
			<p style="margin-bottom:10px;">' . i18n_r('readTime/LANG_PayPal') . '</p>
			<a href="https://www.paypal.com/donate/?hosted_button_id=TW6PXVCTM5A72"><img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif"  /></a>
		</div>
		';

	echo $html;

	if (isset($_POST['submit'])) {

		$readTimeInfo = $_POST['readtime'];

		$info = [];
		$info['info'] = $readTimeInfo;
		$finalJson = json_encode($info);

		$fileFolder = GSDATAOTHERPATH . 'readTime/';

		if (file_exists($fileFolder) == null) {
			mkdir($fileFolder, 0755);
			file_put_contents($fileFolder . '.htaccess', 'Deny from All');
		};

		file_put_contents($fileFolder . 'db.json', $finalJson);
		echo ("<meta http-equiv='refresh' content='0'>");
	};
}
