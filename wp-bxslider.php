<?php
/*
Plugin Name: WP BxSlider
Plugin URI: http://weblumia.com/weblumias-bxslider/
Description: Creates a slider using bxslider. WordPress plugin develop by Jinesh.P.V
Version: 2.3
Author: Jinesh.P.V
Author URI: http://weblumia.com/weblumias-bxslider/
License: GPL2
/*  Copyright 2013  WP BxSlider - Jinesh.P.V  (email : jinuvijay5@gmail.com)

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



add_action('admin_menu', 'wpbx_add_menu');
add_action('admin_init', 'wpbx_reg_function' );
add_action('wp_enqueue_scripts', 'wpbx_add_scripts' );
register_activation_hook( __FILE__, 'wpbx_activate' );

add_theme_support( 'post-thumbnails' );
function wpbx_add_menu() {
    $page = add_menu_page('WP bxSlider', 'WP bxSlider', 'administrator', 'wpbx_menu', 'wpbx_menu_function');
}
function wpbx_reg_function() {
	register_setting( 'wpbx-settings-group', 'wpbx_category' );
	register_setting( 'wpbx-settings-group', 'wpbx_prev' );
	register_setting( 'wpbx-settings-group', 'wpbx_next' );
	register_setting( 'wpbx-settings-group', 'wpbx_pause' );
	register_setting( 'wpbx-settings-group', 'wpbx_speed' );
	register_setting( 'wpbx-settings-group', 'wpbx_auto' );
	register_setting( 'wpbx-settings-group', 'wpbx_pager' );
	register_setting( 'wpbx-settings-group', 'wpbx_width' );
	register_setting( 'wpbx-settings-group', 'wpbx_height' );
	register_setting( 'wpbx-settings-group', 'wpbx_pager_path' );
	register_setting( 'wpbx-settings-group', 'wpbx_pager_hover' );
}

function wpbx_activate() {
	add_option('wpbx_category','1');
	add_option('wpbx_effect','random');
	add_option('wpbx_slices','5');	
}

function wpbx_add_scripts() {
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script('wpbx-js', plugins_url('js/jquery.bxslider.js', __FILE__), array('jquery'), '3.2' );
}
function show_bxslider() {

?>
<style type="text/css">
.bxslider_wrap {
	position: relative;
 width:<?php echo get_option( 'wpbx_width' )?>px;
 height:<?php echo get_option( 'wpbx_height' )?>px;
}
.bxslider_wrap .prev, .bxslider_wrap .next {
	width:25px;
	height:88px;
	position: absolute;
	top: 140px;
	z-index:99999;
}
.bxslider_wrap .prev {
	left:32px;
}
.bxslider_wrap .next {
	right:32px;
}
.bx_pager {
	position:absolute;
	bottom:-30px;
	left:50%;
	z-index:99999;
}
.bx_pager a {
	float: left;
 background:url(<?php if ( get_option( 'wpbx_pager_path' ) != '' ) {
echo get_option( 'wpbx_pager_path' );
}
else {
echo WP_PLUGIN_URL . "/wp-bxslider/images/slide_nav.png";
}
?>) no-repeat left top;
 width: 13px;
 height: 13px;
 color: #fff;
 margin-right:5px;
 text-indent:1000px;
 overflow:hidden;
}
.bx_pager a:hover, .bx_pager a.active {
 background:url(<?php if ( get_option( 'wpbx_pager_hover' ) != '' ) {
echo get_option( 'wpbx_pager_hover' );
}
else {
echo WP_PLUGIN_URL . "/wp-bxslider/images/slide_active_nav.png";
}
?>) no-repeat left top;
}
.bxslider_wrap .bxcontent {
	position:absolute;
	font-family:Verdana, Geneva, sans-serif;
	width:350px;
	top:120px;
	margin-left:580px;
}
</style>
<script type="text/javascript">
jQuery(function(){
	jQuery( '.bxslider' ).bxSlider2({
		prev_image: '<?php if ( get_option( 'wpbx_prev' ) != '' ) { echo get_option( 'wpbx_prev' );}else{ echo WP_PLUGIN_URL . "/wp-bxslider/images/slider_prev.png";}?>',
		next_image: '<?php if ( get_option( 'wpbx_next' ) != '' ) { echo get_option( 'wpbx_next' );}else{ echo WP_PLUGIN_URL . "/wp-bxslider/images/slider_next.png";}?>',
		wrapper_class: 'bxslider_wrap',
		auto: <?php echo get_option( 'wpbx_auto' )?>,
		speed : <?php echo get_option( 'wpbx_speed' )?>,
		pause:<?php echo get_option( 'wpbx_pause' )?>,
		pager:<?php echo get_option( 'wpbx_pager' )?>
	});
});

</script>
<div class="slider">
  <ul class="bxslider">
    <?php 
        $category	= 	get_option( 'wpbx_category' );
        $n_slices	=	get_option( 'wpbx_slices' );
    ?>
    <?php 
		query_posts( 'cat=' . $category . '&posts_per_page=$n_slices' ); 
		if( have_posts() ) : 
			while( have_posts() ) : the_post(); ?>
    <?php if( has_post_thumbnail()) : ?>
    <li>
      <div class="slide_img">
        <?php the_post_thumbnail( 'full', array( 'alt' => $post->post_title ) ); ?>
      </div>
      <div class="bxcontent">
        <div class="content_right">
          <?php the_content();?>
        </div>
      </div>
    </li>
    <?php endif ?>
    <?php endwhile; endif;?>
    <?php wp_reset_query();?>
  </ul>
</div>
<?php } 

function wpbx_menu_function() {
?>
<div class="wrap">
  <h2>WP bxSlider Settings</h2>
  <form method="post" action="options.php">
    <?php settings_fields( 'wpbx-settings-group' ); ?>
    <table class="form-table">
      <tr valign="top">
        <th scope="row">Slider Category</th>
        <td><select name="wpbx_category" id="wpbx_category">
            <option value="">Select a Category</option>
            <?php 
 				$category = get_option( 'wpbx_category' );
  				$categories=  get_categories(); 
  				foreach ($categories as $cat) {
  					$option = '<option value="'.$cat->term_id.'"';
  					if ($category == $cat->term_id) $option .= ' selected="selected">';
  					else { $option .= '>'; }
					$option .= $cat->cat_name;
					$option .= ' ('.$cat->category_count.')';
					$option .= '</option>';
					echo $option;
  				}
 			?>
          </select>
      </tr>
      <tr valign="top">
        <th scope="row">Prev Images Path</th>
        <td><label>
            <input type="text" name="wpbx_prev" id="wpbx_prev" size="80" value="<?php echo get_option('wpbx_prev'); ?>" />
          </label>
      </tr>
      <tr valign="top">
        <th scope="row">Next Images Path</th>
        <td><label>
            <input type="text" name="wpbx_next" id="wpbx_next" size="80" value="<?php echo get_option('wpbx_next'); ?>" />
          </label>
      </tr>
      <tr valign="top">
        <th scope="row">Pager Images Path</th>
        <td><label>
            <input type="text" name="wpbx_pager_path" id="wpbx_pager_path" size="80" value="<?php echo get_option('wpbx_pager_path'); ?>" />
          </label>
      </tr>
      <tr valign="top">
        <th scope="row">Pager Active Images Path</th>
        <td><label>
            <input type="text" name="wpbx_pager_hover" id="wpbx_pager_hover" size="80" value="<?php echo get_option('wpbx_pager_hover'); ?>" />
          </label>
      </tr>
      <tr valign="top">
        <th scope="row">Pause Time</th>
        <td><label>
            <input type="text" name="wpbx_pause" id="wpbx_pause" size="7" value="<?php echo get_option('wpbx_pause'); ?>" />
          </label>
      </tr>
      <tr valign="top">
        <th scope="row">Slider Speed</th>
        <td><label>
            <input type="text" name="wpbx_speed" id="wpbx_speed" size="7" value="<?php echo get_option('wpbx_speed'); ?>" />
          </label>
      </tr>
      <tr valign="top">
        <th scope="row">Automatic</th>
        <td><label>
            <?php $wpbx_auto = get_option('wpbx_auto'); ?>
            <select name="wpbx_auto" id="wpbx_auto">
              <option value="true" <?php if($wpbx_auto == 'true') echo 'selected="selected"'; ?>>True</option>
              <option value="false" <?php if($wpbx_auto == 'false') echo 'selected="selected"'; ?> >False</option>
            </select>
          </label>
      </tr>
      <tr valign="top">
        <th scope="row">Pager</th>
        <td><label>
            <?php $wpbx_pager = get_option('wpbx_pager'); ?>
            <select name="wpbx_pager" id="wpbx_pager">
              <option value="true" <?php if($wpbx_pager == 'true') echo 'selected="selected"'; ?>>True</option>
              <option value="false" <?php if($wpbx_pager == 'false') echo 'selected="selected"'; ?> >False</option>
            </select>
          </label>
      </tr>
      <tr valign="top">
        <th scope="row">Width</th>
        <td><label>
            <input type="text" name="wpbx_width" id="wpbx_width" size="7" value="<?php echo get_option('wpbx_width'); ?>" />
            px </label>
      </tr>
      <tr valign="top">
        <th scope="row">Height</th>
        <td><label>
            <input type="text" name="wpbx_height" id="wpbx_height" size="7" value="<?php echo get_option('wpbx_height'); ?>" />
            px </label>
      </tr>
    </table>
    <p class="submit">
      <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>
  </form>
</div>
<?php } ?>
