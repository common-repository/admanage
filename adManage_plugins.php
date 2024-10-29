<?php  
/*
Plugin Name: ad Manage
Plugin URI: http://www.ipanx.net/?p=252
Description: 可以将广告和版权等等信息添加到页面头部和页面底部，可以把各种声明添加到网站底部。
Version: 1.0  
Author: 爱盘网
Author URI: http://www.ipanx.net/  
License: GPL2 
*/ 
 
/* 注册激活插件时要调用的函数 */ 
register_activation_hook( __FILE__, 'footer_info_install');   
/* 注册停用插件时要调用的函数 */ 
register_deactivation_hook( __FILE__, 'footer_info_remove' );  
 
function footer_info_install() {  

    /* 在数据库的 wp_options 表中添加一条记录，第二个参数为默认值 */ 
    add_option("display_footerinfo_text", "添加底部信息支持HTML語言", '', 'yes');
    add_option("down_ad_text","页面下部广告",'','yes');
    add_option("up_ad_text","页面上部广告",'','yes');
    add_option("web_keyword","设置关键字",'','yes');
    add_option("web_description","设置网站描述",'','yes');
    add_option("ad_postion","",'','yes');
  // add_option("marge_text_show_ad","",'','yes');
}  
 
function footer_info_remove() {  
    /* 删除 wp_options 表中的对应记录 */ 
    delete_option('display_footerinfo_text');  
    delete_option('down_ad_text');
    delete_option('up_ad_text');
    delete_option("web_keyword");
    delete_option("web_description");
    delete_option("ad_postion");
   // delete_option("marge_text_show_ad");
}  
 
if( is_admin() ) {  
    /*  利用 admin_menu 钩子，添加菜单 */ 
    add_action('admin_menu', 'display_footer_info_menu');  
}  

function display_footer_info_menu() {  
    /* add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function);  */ 
    /* 页名称，菜单名称，访问级别，菜单别名，点击该菜单时的回调函数（用以显示设置页面） */ 
    add_options_page('页面广告管理', '设置广告位置', 'administrator','display_footer_info', 'display_footer_info_html_page');  
}  
function display_footer_info_html_page() {  
?>  
<div>  
        <h2>设置信息</h2>  
        <form method="post" action="options.php">  
            <?php /* 下面这行代码用来保存表单中内容到数据库 */ ?>  
            <?php wp_nonce_field('update-options'); ?>  
            <p>
                设置网站底部信息
            </p>
            <p>  
                <textarea  
                    name="display_footerinfo_text" 
                    id="display_footerinfo_text" 
                    cols="100" 
                    rows="5"><?php echo get_option('display_footerinfo_text'); ?></textarea>  
            </p>  
            <p>
                  页面底部广告或信息
            </p>
            <p>
                    <textarea  name="down_ad_text" id="down_ad" cols ="100" rows="10"><?php echo get_option('down_ad_text'); ?></textarea>
            </p>
            <p>
                  页面上部广告或信息
            </p>
            <p>
                    <textarea  name="up_ad_text" id="up_ad" cols ="100" rows="10"><?php echo get_option('up_ad_text'); ?></textarea>
            </p> 
            <p>
            <?php  
                   if(get_option("ad_postion") =="top")
			  echo "<input type=radio name=\"ad_postion\" value=\"top\" checked>页面顶部&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		   else
		          echo "<input type=radio name=\"ad_postion\" value=\"top\" >页面顶部&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";  
		   if(get_option("ad_postion") =="left") 	  
			   echo "<input type=radio name=\"ad_postion\" value=\"left\" checked>文字左边&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	           else
			    echo "<input type=radio name=\"ad_postion\" value=\"left\" >文字左边&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	           if(get_option("ad_postion") == "right")
			    echo "<input type=radio name=\"ad_postion\" value=\"right\" checked>文字右边&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	           else
	                    echo "<input type=radio name=\"ad_postion\" value=\"right\" >文字右边<br>";
	          ?>
		  <!--
		  <input type=radio name="ad_postion" value="input">经过<input type=text name="marge_text_show_ad">字显示广告<br>
		   -->
		  
            </p>
            <p>
                 设置关键字
            </p>
            <p>
                  <textarea name="web_keyword"  id="web_keyword" cols = "100" rows = "5"><?php echo get_option('web_keyword');?></textarea>
            </p>
            <p>
		  设置网站描述
            </p>
            <p>
                  <textarea name="web_description"  id="web_description" cols = "100" rows = "5"><?php echo get_option('web_description');?></textarea>
           </p>
            <p>  
                <input type="hidden" name="action" value="update" />  
                <input type="hidden" name="page_options" value="display_footerinfo_text,down_ad_text,up_ad_text,web_keyword,web_description,ad_postion,marge_text_show_ad" />  
                <input type="submit" value="保存设置" class="button-primary" />  
            </p>  
        </form>  
    </div>  
<?php  
}  

/*
function hello_custom_box(){
echo '<ul>';
echo '<li><a href="http://www.google.com" target="_blank">google</a></li>';
echo '</ul>';
}
function testCB(){
add_meta_box('bunengba', 'upload', 'hello_custom_box', 'post', 'side', 'high');
}
add_action('add_meta_boxes', 'testCB');
*/
function display_up_ad_text( $content ) {  
     $up_ad_text = get_option('up_ad_text');
     if(empty($up_ad_text))
	return $content;
    if( is_single() )  
    {
	  $ad_postion = get_option("ad_postion");
	  if($ad_postion == "top")
	      $content =  get_option('up_ad_text').$content ;
	  else if($ad_postion == "left")
	  {
	      $leftdiv ="<div style=\"float: left;margin: 10px 0 0 0;\">";
	      $rightdiv="</div>";
	      $content=   $leftdiv.get_option('up_ad_text').$rightdiv.$content;
	  }
	  else if($ad_postion == "right")
	  {
	      $leftdiv ="<div style=\"float: right;margin: 10px 0 0 0; \">";
	      $rightdiv="</div>";
	      $content=   $leftdiv.get_option('up_ad_text').$rightdiv.$content ;
	  }  
     }
    return $content;  
}  

function display_down_ad_text($content){
    if(is_single())
      $content = $content.get_option('down_ad_text');
    return $content;
}

function display_footerinfo( ) {  
	echo get_option("display_footerinfo_text");
}  

if (!function_exists('utf8Substr')) {
     function utf8Substr($str, $from, $len)
     {
         return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$from.'}'.
              '((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$len.'}).*#s',
              '$1',$str);
     }
}

function setKeywordandDescription()
{
     if (is_home()){
        $keywords = get_option("web_keyword");
        $description = get_option("web_description");
    } elseif (is_single()){
    
          $pageID =  get_the_ID();
          $postInfo = &get_post($pageID);
        if ($postInfo->post_excerpt) {
            $description  = $postInfo->post_excerpt;
        } else {
       if(preg_match('/<p>(.*)<\/p>/iU',trim(strip_tags($postInfo->post_content,"<p>")),$result)){
        $post_content = $result['1'];
       } else {
        $post_content_r = explode("\n",trim(strip_tags($postInfo->post_content)));
        $post_content = $post_content_r['0'];
       }
             $description = utf8Substr($post_content,0,220);   
      }
     
        $keywords = "";      
        $tags = wp_get_post_tags($postInfo->ID);
        foreach ($tags as $tag ) {
            $keywords = $keywords . $tag->name . ",";
        }
    }
    echo  "<meta name=\"keywords\" content=\"". rtrim($keywords,',')."\"   />\n";
    echo  "<meta name=\"description\" content=\"". trim($description)."\"   />\n";              
}
add_filter( 'the_content',  'display_down_ad_text' );
add_filter( 'the_content','display_up_ad_text');
add_filter('wp_footer','display_footerinfo');
add_filter('wp_head','setKeywordandDescription',0);
?>