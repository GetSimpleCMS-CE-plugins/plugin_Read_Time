<?php
 
# get correct id for plugin
$thisfile=basename(__FILE__, ".php");
 
# register plugin
register_plugin(
	$thisfile, //Plugin id
	'Read Time', 	//Plugin name
	'1.0', 		//Plugin version
	'Multicolor',  //Plugin author
	'https://paypal.me/multicol0r', //author website
	'This plugin will show estimated time for read page content', //Plugin description
	'plugins', //page type - on which admin tab to display
	'readTimeSettings'  //main function (administration)
);
 
 
 
add_action('plugins-sidebar','createSideMenu',array($thisfile,'Read Time Settings'));
 
# functions
function readTime() {

    global $SITEURL;
   
    $info = file_get_contents(GSPLUGINPATH.'readTime/RTinfo/db.json');
    $json = json_decode($info);

   


    echo '
  <div style="display:flex;align-items:center;margin-bottom:30px"><img src="'.$SITEURL.'plugins/readTime/img/clock.svg" style="width:20px;margin-right:5px;display:inline-block">
  <span id="showReadTime" style="margin-right:5px;"></span> '.$json->info.'</div>
 
    
    <script>

 function readingTime() {
 
  const text = `'.strip_tags(str_replace('`','"', returnPageContent(return_page_slug()))).'`;
  const wpm = 225;
  const words = text.trim().split(/\s+/).length;
  const time = Math.ceil(words / wpm);
  document.getElementById("showReadTime").innerText = time;
}
readingTime();
</script>
';

}
 
function readTimeSettings(){

     
  $info = file_get_contents(GSPLUGINPATH.'readTime/RTinfo/db.json');
  $json = json_decode($info);

 

    $html = '

    <div style="width:100%;padding:15px;border:solid 1px #ddd;box-sizing:border-box;background:#fafafa;color:#333; margin-bottom:10px;">
    Put this function php where you want use it on your theme
    <code>&#60;?php readTime(); ?&#62;</code>
    </div>

    <h3>Read Time Settings</h3>

    <form action="#" method="post">
    <input type="text" style="padding:10px;width:100%;display:block;box-sizing:border-box;" value="'.$json->info.'" name="readtime" class="form-control">
    <input type="submit" name="submit" value="save info" style="background:#000;padding:10px 15px;border:none;margin-top:10px;color:#fff;">
    
    </form>

';

$html .= '

<a style="text-align:center;color:#000;background:#fafafafa;
border:solid 1px #ddd;display:block;width:100%;padding:10px;margin-top:10px;
box-sizing:border-box;" target="_blank" href="https://paypal.me/multicol0r?country.x=PL&locale.x=pl_PL">
   If you like use my plugin! Buy me ☕  
<br>
<img src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" style="margin-top:10px"></a>
';


echo $html;
 

if(isset($_POST['submit'])){

  $readTimeInfo = $_POST['readtime'];

  $info = [];
  $info['info']= $readTimeInfo;
  $finalJson = json_encode($info);

  file_put_contents(GSPLUGINPATH.'readTime/RTinfo/db.json',$finalJson);
  echo("<meta http-equiv='refresh' content='0'>");

};



}
?>