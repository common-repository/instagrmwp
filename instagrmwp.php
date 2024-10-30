<?php
defined( 'ABSPATH' ) OR exit;
/*
Plugin Name: InstagrmWP
Plugin URI: http://wordpress.org/plugins/instagrmwp/
Description: InstagrmWP is a plugin for everybody like photo from Instagram and want buid-in wordpress. this plugin display Tab, Information, Feed photo from admin of InstagrmWP and setting on admin page. This plugin requires PHP curl extension.
Version: 1.0.0
Author: Pro CSci
Author URI: http://instagrmwp.com
License: GPL v2

Copyright 2013-2014  InstagramWP  (email : admin@instagrmwp.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
define( 'INSTAGRMWP_VERSION', '1.0.0' );
define( 'INSTAGRMWP_CD', '<a href="http://instagrmwp.com" title="Instagram for your Wordpress" art="Instagram for your Wordpress">InstagrmWP</a>' );
define( 'INSTAGRAM_API_CD', '<em>This InstagrmWP uses the Instagram(tm) API and is not endorsed or certified by Instagram. All Instagram(tm) logoes and trademarks displayed on this InstagrmWP are property of Instagram</em>' );
#function create database for get instragram token insert table
function instagrmwp_install() {
	        if ( ! current_user_can( 'activate_plugins' ) ){
               wp_die('You do not have sufficient permissions to access this page');
			}else{
            global $wpdb;
			$tableinswp = $wpdb->prefix."instagrmwp";
			if($wpdb->get_var("show tables like '$tableinswp'") != $tableinswp) {
			   $sqlins = "CREATE TABLE $tableinswp (
			              id tinyint(4) NOT NULL AUTO_INCREMENT,
						  id_token int(11) NOT NULL,
	    				  token varchar(150) COLLATE utf8_unicode_ci NOT NULL,
	    				  id_admin int(11) NOT NULL,
						  PRIMARY KEY ( id )
					)";
		      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	          dbDelta($sqlins);
			}
            add_option("instagrmwp_table", 'instagrmwp-setting', '', 'yes');
			}
}
#function deactivation plugin
function instagrmwp_deactivation() {
	       if ( ! current_user_can( 'activate_plugins' ) ){
               wp_die('You do not have sufficient permissions to access this page');
			}else{
		   require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
           delete_option('instagrmwp_table');
		   }
} 
#function remove database instagrmwp
function instagrmwp_remove() {
	       if ( ! current_user_can( 'activate_plugins' ) ){
               wp_die('You do not have sufficient permissions to access this page');
			}else{
		   global $wpdb;
		   $tableinswp = $wpdb->prefix."instagrmwp";
           $wpdb->query("DROP TABLE IF EXISTS $tableinswp");
		   require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
           delete_option('instagrmwp_table');
		   }
} 
register_activation_hook(__FILE__,'instagrmwp_install');
register_deactivation_hook(__FILE__,'instagrmwp_deactivation'); 
register_uninstall_hook( __FILE__, 'instagrmwp_remove' );
function load_inswp_plugin() {
    if ( is_admin() && get_option( 'instagrmwp_table' ) == 'instagrmwp-setting' ) {
        delete_option( 'instagrmwp_table' );
        /* do stuff once right after activation */
        // example: add_action( 'init', 'my_init_function' );
    }
}
add_action('admin_init', 'load_inswp_plugin' ); 
add_action('admin_menu', 'instagrmwp_admin_menu');
function instagrmwp_admin_menu() {
           add_menu_page('InstagrmWP', 'InstagrmWP', 'administrator',
'instagrmwp', 'instagrmwp_adminpage',plugins_url('images/minilogo.png', __FILE__));
           add_submenu_page('instagrmwp', 'InstagrmWP Setting', 'Setting', 'administrator', 'instagrmwp-setting', 'instagrmwp_adminpage');
		   add_submenu_page('instagrmwp', 'InstagrmWPPro', 'InstagrmWPPro', 'administrator', 'instagrmwppro-version', 'instagrmwp_proversion');
}
function instagrmwp_adminpage() {
	   global $wpdb; 
       if (is_admin()){
			if(! current_user_can('manage_options')){
            wp_die( 'Insufficient privileges!' );
			}else{
				/*insert token and id user to database*/
				if(!empty($_GET['page']) && $_GET['page'] = 'instagrmwp' && !empty($_GET['access_tokeninswppro_admin'])){
			       $current_user = wp_get_current_user();
                   $userid =$current_user->ID;
                   $sql = $wpdb->prepare("INSERT INTO `".$wpdb->prefix."instagrmwp` (`id`,`id_token`,`token`,`id_admin`) values (%d,%d,%s,%d)", '',$_GET['iduserinswppro_admin'], $_GET['access_tokeninswppro_admin'],$userid);
                   $wpdb->query($sql);
                   $wpdb->flush();
				   $link = admin_url('options-general.php?page=instagrmwp');?>
				   <script type="text/javascript">
	                 //<![CDATA[
                     window.location.href = '<?php echo $link ?>';
                     });
		             //]]>
                   </script>
			  <?php 
			    } /*query data from inswp_dbtoken function*/
				  $rowsdata = inswp_dbtoken();
		          if(!empty($rowsdata)){
		             $idadu = $rowsdata[0]->id_token;
		             $access = $rowsdata[0]->token;
		          }else{
			         $idadu = '';
		             $access = '';
		          }	
				  /*feed user name from inswp_user funtion*/
				  if(!empty($access)){	
				     $inswphello = inswp_user($idadu,$access);
				     $inswpsay = inswp_curl($inswphello);
				  }
?>     <div style="width:720px;height:150px">
       <section style="margin:10px 0;float:left">
       <img src="<?php echo plugins_url('images/instawp-logo.jpg',__FILE__); ?>">
       </section>
       <section style="margin:25px 0 0 0;float: right;width:400px">
	   <a  class='tooltip' href='http://instagrmwp.com/manual-instagrmwp-free-version/' target='_blank'><img  src="<?php echo plugins_url('images/manual_icon.jpg',__FILE__); ?>" style="float:right;"><span id="inswptiptitle">InstagrmWP User Manual</span></a>
	   <?php if(!empty($access) && !empty($inswpsay)) { ?>
       <div id="inswpsay"><?php echo 'Hello ! '.$inswpsay['data']['username'].', If you like this plugin';?>
	      <a href="http://wordpress.org/plugins/instagrmwp"><img border="0" src="<?php echo plugins_url('images/rating.jpg',__FILE__); ?>"></a><?php echo 'or';?>
         <span id="donateinswp">  
          <form action="https://www.paypal.com/cgi-bin/webscr" target="_blank" method="post">  
            <input type="hidden" name="business" value="travelinthailandco@hotmail.com">  
            <input type="hidden" name="cmd" value="_donations"> 
            <input type="image" name="submit" border="0" src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" alt="PayPal - The safer, easier way to pay online">  
            <img alt="" border="0" width="1" height="1" src="https://www.paypal.com/en_US/i/scr/pixel.gif" >
         </form>
         </span>
       </div>
       <?php } ?>
       </section>
       </div>
        <fieldset class="inswpset">
          <legend id="btlogin">Login</legend>
         <?php echo '<div id="messsign"></div>'; ?>
         <?php /* check acess_token for display button login or logout */
		       if(empty($access)) { ?>
                  <a href=" <?php echo login_inswp( '?return_uri='.base64_encode( get_admin_url() . 'options-general.php?page=instagrmwp')) ?> "><img src="<?php echo plugins_url('images/instawp-login.png', __FILE__);?>"></a>
         <?php }else{ ?>
                  <a href="#" onClick="logout_inswp('<?php echo $idadu;?>');return false;"><img src="<?php echo plugins_url('images/instawp-logout.png', __FILE__);?>"></a>  <a class="tooltip"><img src="<?php echo plugins_url('images/discuss_icon.png', __FILE__);?>"><span id="inswptiptitle">When you log out, your token is lost, the default display all disappear.</span></a>
          <?php } ?>
       </fieldset>
          <?php 
		  /* check acess_token for display setting tab image and post form*/
		  if(!empty($access)){	?>
       <form name="forminsadmin" id="forminsadmin" action="" method="post">
        <fieldset class="inswpset">
         <legend id="btprof">Tab Profile</legend>
            <dl id="inswpdlback">
        	<dt id="inswptitleconf"><label id="lback" for="infor">Information:</label></dt>
        	<dd id="inswpddback">
            <input name="imgprof" type="checkbox" id="imgprof" /><label id="lback" for="imgprof"><span></span>Image Profile</label>
            <input name="biog" type="checkbox" id="biog" /><label id="lback" for="biog"><span></span>Biography</label>
            <input name="cmedia" type="checkbox" id="cmedia" /><label id="lback" for="cmedia"><span></span>Media</label>
            <input name="cfing" type="checkbox" id="cfing" /><label id="lback" for="cfing"><span></span>Following</label>
            <input name="cfer" type="checkbox" id="cfer" /><label id="lback" for="cfer"><span></span>Follower</label>
            </dd>
            </dl>
            <dl id="inswpdlback">
            <dt id="inswptitleconf"><label id="lback" for="selecttab">Tab:</label></dt>
            <dd id="inswpddback">
            <input type="radio" value="0" name="inswptab" id="nonerab"><label id="lback" for="nonerab"><span></span>None</label>
            <input type="radio" value="1" name="inswptab" id="tabcolor"><label id="lback" for="tabcolor"><span></span>Color</label>
            </dd>
            </dl>
            <div id="selectcolor" style="display:none">
            <dl id="inswpdlback">
            <dt id="inswptitleconf"><label id="lback" for="color">Color:</label></dt>
            <dd id="inswpddback">
            <input type="radio" value="e6e6e6" name="inscolor" id="inscolorgrey"><label id="lback" for="inscolorgrey"><span></span><img src="<?php echo plugins_url('images/tabgrey.jpg', __FILE__);?>"></label>
            <input type="radio" value="1A64AB" name="inscolor" id="inscolorblue"><label id="lback" for="inscolorblue"><span></span><img src="<?php echo plugins_url('images/tabblue.jpg', __FILE__);?>"></label>
            <input type="radio" value="96701B" name="inscolor" id="inscolorgold"><label id="lback" for="inscolorgold"><span></span><img src="<?php echo plugins_url('images/tabgold.jpg', __FILE__);?>"></label>
            <input type="radio" value="1C983A" name="inscolor" id="inscolorgreen"><label id="lback" for="inscolorgreen"><span></span><img src="<?php echo plugins_url('images/tabgreen.jpg', __FILE__);?>"></label>
            <input type="radio" value="C40EB1" name="inscolor" id="inscolorpink"><label id="lback" for="inscolorpink"><span></span><img src="<?php echo plugins_url('images/tabpink.jpg', __FILE__);?>"></label>
            <input type="radio" value="971C3B" name="inscolor" id="inscolorred"><label id="lback" for="inscolorred"><span></span><img src="<?php echo plugins_url('images/tabred.jpg', __FILE__);?>"></label>
            </dd>
            </dl>
            </div>
      </fieldset>
      <fieldset class="inswpset">
        <legend id="btimg">Images</legend>
            <dl id="inswpdlback">
            <dt id="inswptitleconf"><label id="lback" for="infor">Setting:</label></dt>
            <dd id="inswpddback">
             <input name="linkins" type="checkbox" id="linkins" /><label id="lback" for="linkins"><span></span>Link to Instagram</label>
             <input name="ccomment" type="checkbox" id="ccomment" /><label id="lback" for="ccomment"><span></span>Count Comments</label>
             <input name="clikes" type="checkbox" id="clikes" /><label id="lback" for="clikes"><span></span>Count Likes</label>
             <input name="userinphoto" type="checkbox" id="userinphoto" /><label id="lback" for="userinphoto"><span></span>User in photo</label>
             <a class="tooltip"><img src="<?php echo plugins_url('images/discuss_icon.png', __FILE__);?>">
             <span id="inswptiptitle">More than zero</span>
             </a>
            </dd>
            </dl>
            <dl id="inswpdlback">
            <dt id="inswptitleconf"><label id="lback" for="sizepic">Size:</label></dt>
            <dd id="inswpddback">      
             <input name="imgsz" type="radio" id="sizesmall" value="small" /><label id="lback" for="sizesmall"><span></span>Small ( 155 * 155 )</label>
             <input name="imgsz" type="radio" id="sizemiddle" value="middle" /><label id="lback" for="sizemiddle"><span></span>Middle ( 220 * 220 )</label>
             <input name="imgsz" type="radio" id="sizelarge" value="large" /><label id="lback" for="sizelarge"><span></span>Large ( 100% * auto )</label>
            </dd>
            </dl>
            <dl class="adnumimg">
            <dt id="inswptitleconf"><label id="lback" for="countimg">Number:</label></dt>
            <dd><input name="feedcount" type="text" id="feedcount" style="width:50px" maxlength="3"/></dd>
            </dl>
            <dl id="inswpdlback">
            <dt id="inswptitleconf"><label id="lback" for="credit">Credit:</label></dt>
            <dd id="ddback">
             <input name="inswpcdinswp" type="checkbox" id="cdinswp" /><label id="lback" for="cdinswp"><span></span>InstagrmWP</label>
             <input name="inswpcdinsapi" type="checkbox" id="cdinsapi" /><label id="lback" for="cdinsapi"><span></span>Instagram API</label>
            </dd>
            </dl>
            <dl id="inswpdlback"> 
            <dt id="inswptitleconf"></dt>
            <dd><input name="insactive" type="checkbox" id="insactive" value="1"/><label id="lback" style="color:red" for="insactive"><span></span>Active all config</label>
            </dd>
            </dl>		
         </fieldset>  
         <input name="adid" type="hidden" id="adid" value="<?php echo $idtes ;?>"/>
         </form>  
      <?php # End form config display front page
            # Form admin Post ?>
     
        <fieldset class="inswpset">
         <legend id="btpost">Post</legend>
         <form name="forminspost" id="formpostins" action="" method="post">
         <div id="messpost"></div>
            <dl id="inswpdlback">
            <dt id="inswptitleconf"><label id="lback" for="cat">Category:</label></dt>
            <?php 
 			$args2 = array(		
					'selected'                => '',
					'include_selected'        => true,
					'hide_empty'			  => 0,
					'orderby'				  => 'name',
					'order'					  => 'ASC',
					'name'                    => 'cats'
					);
			?>							
		    <dd id="inswpddback"><?php wp_dropdown_categories( $args2 );?></dd>
            </dl> 
            <dl id="inswpdlback">
        	<dt id="inswptitleconf"><label id="lback" for="adtitle">Title:</label></dt>
            <dd id="inswpddback"><input name="inswptitle" type="text" id="inswptitle" value="" /></dd>
            </dl> 
            <dl id="inswpdlback">
        	<dt id="inswptitleconf"><label id="lback" for="adcontent">Content:</label></dt>
            <dd id="inswpddback"><textarea id="inswpconts" name="inswpconts" cols="95" rows="5" /></textarea></dd>
            </dl> 
            <dl id="inswpdlback">
            <dd id="inswpddback"><input type="button" value="Save" class="btonpost" onClick="admin_ins_post_conf();return false;"/></dd>
            </dl>
          </form> 
          </fieldset>
         <script type="text/javascript">
	   //<![CDATA[
        jQuery( function() {
			/*ganarate short code to textarea */
           inswp_admin_setting('<?php echo $idadu ;?>');
        });
		//]]>
       </script>
        <?php   # End form config 
		       }
			}
       }
}
/* function post form admin */
function admin_inswp_post_config(){
	        global $wpdb;
		    $postconfig = array(
				             'post_title' => $_POST['inswptitle'],
				             'post_content' => $_POST['inswpcons'],
				             'post_author' => 1,
				             'post_category' => array($_POST['inswpcats']),
				             'post_status' => 'publish', 
				             'post_type' => 'post',
				             'post_date' => date('Y-m-d H:i:s'),
				             'post_date_gmt' => date('Y-m-d H:i:s')
		 	       ); 
		    wp_insert_post( $postconfig );
		    $wpdb->flush(); 
            die();     
}
add_action('wp_ajax_admin_inswp_config', 'admin_inswp_post_config');// If ajax called from admin panel
/* function admin logout by data from database */
function access_logout_inswp(){
	        global $wpdb;
            $idlogout = $_POST['idlogout'];
            $sql = $wpdb->prepare("DELETE FROM `".$wpdb->prefix."instagrmwp`  WHERE id_token= %d",$idlogout);
            $wpdb->query($sql);
            $wpdb->flush();
            die();
}
add_action('wp_ajax_message_logout_inswp', 'access_logout_inswp'); // If ajax called from admin panel
/* function link to instragram */
function login_inswp( $return_uri ){
	        $urlins = "https://api.instagram.com/oauth/authorize/";
	        $client_id = "22ba9db0d46c4f079133ad31595ddab3";
	        $redirect_uri = "http://instagrmwp.com/instagrmwp-features/";
	        $response = "token";
	        $scope = "likes+comments+relationships";
	        return $urlins . '?client_id=' . $client_id . '&redirect_uri=' . $redirect_uri .$return_uri.'&response_type=' . $response . '&scope=' . $scope;
}
/* function photo display admin page of InstagrmWPPRO */
function instagrmwp_proversion(){
	        global $wpdb; 
               if (is_admin()){
			      if(! current_user_can('manage_options')){
                    wp_die( 'Insufficient privileges!' );
			       }else{ 
				   echo '<div id="subscribeinswppro"><a href="http://instagrmwp.com/category/download/" traget="_blank">Subscribe Pro version</a></div>';
				   echo '<div id="exinswppro">Example admin page</div>';
?>                 <img src="<?php echo plugins_url('images/setting-permission.jpg', __FILE__);?>">
                   <img src="<?php echo plugins_url('images/setting-permission-list-catagory.jpg', __FILE__);?>">              
                   <img src="<?php echo plugins_url('images/setting-tab.jpg', __FILE__);?>">
                   <img src="<?php echo plugins_url('images/setting-images-list.jpg', __FILE__);?>">
                   <?php echo '<div id="exinswppro">Example video media</div>';?>
                   <img src="<?php echo plugins_url('images/feature-zoom-video.jpg', __FILE__);?>">
					   
<?php				   }
			   }
}
/* jquery for admin page */  
add_action('init', 'instagrmwp_admin_js');
function instagrmwp_admin_js(){
	         wp_register_script('jsinswpadmin', plugins_url('js/instagrmwpadmin.js', __FILE__),array("jquery"));   
	         wp_localize_script( 'jsinswpadmin', 'inswp_admin_call_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ),'inswp_redirect_admin_url' => admin_url('options-general.php?page=instagrmwp'),'inswp_admin_images_url' => plugins_url('images/', __FILE__) ) );
			 wp_register_script('jsniceforms', plugins_url('js/niceforms.js', __FILE__),array("jquery"));
			 wp_enqueue_script('jquery');
			 wp_enqueue_script('jsinswpadmin');
             wp_enqueue_script('jsniceforms');
}
/* stylesheet for admin page */
function admin_css_instagrmwp() {
			 wp_register_style( 'cssniceforms', plugins_url('css/niceforms.css', __FILE__) );
             wp_enqueue_style( 'cssniceforms' );
			 wp_register_style( 'csschra', plugins_url('css/stylechra.css', __FILE__) );
             wp_enqueue_style( 'csschra' );
			 wp_register_style( 'cssfonts', 'http://fonts.googleapis.com/css?family=Kaushan+Script');
			 wp_enqueue_style( 'cssfonts' );
			 wp_register_style('instagrmwpcss', plugins_url('css/instagrmwp.css', __FILE__));
             wp_enqueue_style('instagrmwpcss');
}
add_action( 'admin_enqueue_scripts', 'admin_css_instagrmwp' );
# End Is Admin
/* stylesheet for front page */
add_action( 'wp_enqueue_scripts', 'css_instagrmwp' );
function css_instagrmwp() {
             wp_register_style('instagrmwpcss', plugins_url('css/instagrmwp.css', __FILE__));
             wp_enqueue_style('instagrmwpcss');
			 wp_register_style( 'cssniceforms', plugins_url('css/niceforms.css', __FILE__) );
             wp_enqueue_style( 'cssniceforms' );
			 wp_register_style( 'cssfonts', 'http://fonts.googleapis.com/css?family=Kaushan+Script');
			 wp_enqueue_style( 'cssfonts' );
			 wp_register_style( 'chracss', plugins_url('css/stylechra.css', __FILE__) );
             wp_enqueue_style( 'chracss' );
} 
/* jquery for front page */  
add_action('init', 'instagrmwp_front_js');
function instagrmwp_front_js(){
             wp_register_script('jsinswpfornt', plugins_url('js/instagrmwpfornt.js', __FILE__),array("jquery"));  
			 wp_localize_script( 'jsinswpfornt', 'inswp_frontpage_call_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ),'imageload' =>  plugins_url('images/loading.gif', __FILE__)));
             wp_enqueue_script('jquery');
			 wp_enqueue_script('jsinswpfornt');}
/* function query data from database */
function inswp_dbtoken(){
			 global $wpdb;
			 $query = $wpdb->get_results("select id_token, token, id_admin from " . $wpdb->prefix . "instagrmwp");
			 $wpdb->flush();
			 return $query ;	
}
/*function link url information user instagram api*/
function inswp_user($idins,$token){
			$url = 'https://api.instagram.com/v1/users/'.$idins.'/?access_token='.$token;
			return $url;	
}
/*function link url feed media instagram api by count*/
function inswp_feed($idins,$token,$count){
			$url = 'https://api.instagram.com/v1/users/'.$idins.'/media/recent/?access_token='.$token.'&count='.$count;
			return $url;
}
/*function link url get media instagram api by id media*/
function inswp_getmedia($mid,$idtoken){
			$url = 'https://api.instagram.com/v1/media/'.$mid.'?access_token='.$idtoken;
			return $url;
}
/*function json decode url instagram api*/
function inswp_curl($url){
			$curl= curl_init();    
            curl_setopt($curl, CURLOPT_URL,$url); 
            curl_setopt($curl, CURLOPT_HEADER, 0);
			curl_setopt($curl, CURLOPT_FAILONERROR, 1); 
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);// if you error SSL VERIFY change 1 to 0
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1);// if you error SSL VERIFY change 1 to 0
			curl_setopt($curl, CURLOPT_SSLVERSION, 3);// if you error SSL VERIFY close this line
			curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.110 Safari/537.36");
			curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
			curl_setopt($curl, CURLOPT_TIMEOUT, 30);
			$rescurl= json_decode(curl_exec($curl),1);
			curl_close($curl);
			return $rescurl;
}  
/* function shortcode */  
add_shortcode('instagrmwp', 'inswp_shortcode');
function inswp_shortcode($prs,$allcontent = null){   
		    global $wpdb;
			global $wp_query;
			/* extract array from your content by short code [instagrmwp] */
           extract(shortcode_atts(array(
										'userid' => '0',
										'imageprofile' => '0',
										'biography' => '0',
										'media' => '0',
										'following' => '0',
										'follower' => '0',
										'linkto' => '0',
										'countcomment' => '0',
										'countlikes' => '0',
										'userinphoto' => '0',
										'count' => '',
										'color' => '',
										'size' => '',
										'cdinswp' => '',
										'cdapi' => ''
									), 
									$prs));
			
        ob_start();
		$aid = get_the_ID();
		/*query data from database for use access token to display curl function */
		$rowsinspro = inswp_dbtoken();
			if(!empty($rowsinspro)){
	             $idinsadmin  = $rowsinspro[0]->id_token;
		         $tokenadmin = $rowsinspro[0]->token;
			     $iduserad = $rowsinspro[0]->id_admin;
			}
		$userins = inswp_user($userid,$tokenadmin);
		$data = inswp_curl($userins);
		if(!empty($data)){
		echo '<div id="inswbox"></div>';
		echo '<section class="inswpcontent">';
		/* check permission color extract array shortcode */
		if(empty($color)){}
		else if(!empty($color)){
            echo '<section class="tabinswp" style="background:#'.$color.'">';
			/* check permission imageprofile extract array shortcode */
            if($imageprofile == '1'){
	        echo '<img src="'.$data['data']['profile_picture'].'" id="bprof" />
			      <div id="fnuser">'.$data['data']['username'].
				  '</div>';
            }
			/* check permission media extract array shortcode */
            if($media == '1'){
	        echo '<div id="bmff"><div>'.$data['data']['counts']['media'].'</div><div id="ftab">Media</div></div>';
            }
			/* check permission following extract array shortcode */
            if($following == '1'){
	        echo '<div id="bmff"><div>'.$data['data']['counts']['followed_by'].'</div><div id="ftab">Follower</div></div>';
            }
			/* check permission follower extract array shortcode */
            if($follower == '1'){
	        echo '<div id="bmff"><div>'.$data['data']['counts']['follows'].'</div><div id="ftab">Following</div></div>';
            }
            echo '</section>';
		}/* check permission biography extract array shortcode */
         if($biography	== '1'){
	      echo '<div id="fnbio">'.$data['data']['bio'].'</div><div id="linkweb"><a href="'.$data['data']['website'].'">'.$data['data']['website'].'</a></div>';
         }  
          echo'<div class="viewmedia">';
	             $countfeed = $count;
				  if($countfeed != '0' && !empty($countfeed)){
					 echo '<ul id="imgins">';
					    /* feed media from instagram */
		                $getmedia = inswp_feed($userid,$tokenadmin,$countfeed);
			            $listimg = inswp_curl($getmedia);
						/* check data from link feed from instagram */
						if(!empty($listimg)){
			             foreach($listimg['data'] as $img){
							      /* json decode */
		                          $title = $img['caption']['text'];
                                  $linkins = $img['link'];
		                          $images = $img['images']['standard_resolution']['url'];
								  $mimg = $img['images']['low_resolution']['url'];
		                          $comment = $img['comments']['count'];
		                          $limg = $img['images']['thumbnail']['url'];
								  $type = $img['type'];
			                      $avatar = $img['user']['profile_picture'];
		                          $user = $img['user']['username'];
		                          $like = $img['likes']['count'];
		                          $dlike = $img['likes']['data'];
		                          $userlid = $img['user']['id'];
		                          $lid = $img['id'];
								  $people = $img['users_in_photo'];
			                      if(!empty($imgds)){
								    $imgds = $imgds;
								  }else{
									$imgds = 'tbdef';
								  }
					/* check permission size photo extract array shortcode */			  
                   if(!empty($size)){
	                  echo'<li class="'.$size.'" id="'.$imgds.'">'; 
                   }else if(empty($size)){
	                  echo'<li class="smallinswp" id="'.$imgds.'">'; 
                   }
                      echo '<div align="center" id="ctdiv">';
				   /* display media and function click to zoom photo by ajax */  
				   if($size == 'largeinswp'){ ?>
			           <div id="listmedia"><a class="tooltip" onClick="inswp_zoom('<?php echo $lid;?>','<?php echo $aid;?>');return false;"><?php if(!empty($title)){?><span id="inswptiptitle"><?php echo $title;?></span><?php } ?><img src="<?php echo $images;?>" id="immed"/></a></div>
				   <?php }else if($size != 'large'){ ?>
					  <div id="listmedia"><a class="tooltip" onClick="inswp_zoom('<?php echo $lid;?>','<?php echo $aid;?>');return false;"><?php if(!empty($title)){?><span id="inswptiptitle"><?php echo $title;?></span><?php } ?><img src="<?php echo $images;?>" id="immed"/></a></div>
				    <?php  
				   }
				   /* check permission countcomment extract array shortcode */
                   if($countcomment == '1'){						  
		              echo '<span id="buttoncomm"><img src="'.plugins_url( 'images/comment.jpg' , __FILE__ ).'" width="20" height="20">'.$comment.'</span>';
                   }
				   /* check permission countcomment extract array shortcode */
                   if($linkto == '1'){
	                 echo '<span id="buttonlinkto"><a href="'.$linkins.'" target="_blank"> <img src="'.plugins_url( 'images/inst-logo.png' , __FILE__ ).'" width="20" height="20"></a></span>';
	               }
				   /* check permission countlikes extract array shortcode */		
                   if($countlikes == '1'){
?>                     <img id="prmlike" src="<?php echo plugins_url( 'images/unlikes.jpg' , __FILE__ );?>" alt="Like" width="20" height="20" /><?php echo $like;?></span>
<?php
			       }
				   /* check permission userinphoto extract array shortcode */
		      if($userinphoto == 'Y'){				 
		         if(count($people) > 0){				 
		           echo '<span id="uinph"><img src="'.plugins_url( 'images/people.png' , __FILE__ ).'"  height="20" alt="User in photo"/> '. count($people) .'</span>';
		         }
		      }
		      echo '</div>';		
              echo '</li>'; 
			  /* display zoom photo */
			  echo '<div class="zoomimginswp'.$lid.$aid.'" id="zoomdetailinswp"></div>'; //zoom
		      }
			} echo '<ul>';
		}	
		
        echo'</div>';
		 /* check permission cdinswp extract array shortcode */
		     if($cdinswp == '1'){
			 echo '<p>'.INSTAGRMWP_CD.'</p>'; 
			 }
		 /* check permission cdapi extract array shortcode */
			 if($cdapi == '1'){
			 echo '<p>'.INSTAGRAM_API_CD.'</p>'; 
			 }
		}else{
			echo '<div id="imgemptyuser" align="center">Have not this user id or user status private</br><img id="prmlike" src="'.plugins_url( 'images/lock.png' , __FILE__ ).'" alt="emtpe user id"/></div>';
		}
		echo '</section>';	 
        $allcontent = ob_get_contents();
        ob_end_clean();
        return $allcontent;
}
/* process data fron ajax to zoom photo */
function inswp_zoom_media(){
			  $mid = $_POST['mediainswp'];
			  $artid = $_POST['artid'];
			  /* query access token form database */
			  $rowsinspro = inswp_dbtoken();
              $admintk = $rowsinspro[0]->token;
			  /* call data from link instagram api and decode by json */
			  $exdata = inswp_getmedia($mid,$admintk);
			  $data = inswp_curl($exdata);
			  $like = $data['data']['likes']['count'];
			  $type = $data['data']['type'];
			  $images = $data['data']['images']['standard_resolution']['url'];
			  $dlike = $data['data']['likes']['data'];
			  /*display zoom photo */
			  echo '<span id="inswpimgzoom">';
?>			  <div style="width:100%;height:100%;" id="obmedia<?php echo $mid.$artid; ?>">
              <a onClick="inswp_close_zoom('<?php echo $mid.$artid;?>');return flase;"><img src="<?php echo plugins_url( 'images/closed.png' , __FILE__ );?>" alt="Close" id="closedzoominswp" width="35" height="35"/></a>
              <img src="<?php echo $images; ?>" id="inswpimgzoom"/>
              </div>
<?php 		echo '</span>';
            die();
}
add_action('wp_ajax_zoom_media_inswp', 'inswp_zoom_media');
add_action('wp_ajax_nopriv_zoom_media_inswp', 'inswp_zoom_media'); // If ajax called from front end
		
    