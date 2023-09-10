<?php
/*
 * Plugin Name:       Rez Tiny Slider   
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Handle the basics with this plugin.
 * Version:           1.10.3
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Rezwanul Haque
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       rez-tiny-slider
 * Domain Path:       /languages
 */
if (!defined('ABSPATH')) {
   exit;
}

//? admin notice 
function my_plugin_activation_notice()
{
?>
   <div class="notice notice-success is-dismissible">
      <p><?php echo esc_html__('Congratulations!Rez Tiny Slider  plugin is now activated.', 'rez-tiny-slider'); ?></p>
   </div>
<?php
}

function my_plugin_activation()
{
   my_plugin_activation_notice();
}
register_activation_hook(__FILE__, 'my_plugin_activation');

add_action('plugin_loaded', 'rez_tiny_load_textdomain');
function rez_tiny_load_textdomain()
{
   load_plugin_textdomain('rez-tiny-slider', false, dirname(__FILE__) . "/languages");
}
//? enqueue scripts
function rtiny_assets()
{
   wp_enqueue_style('rez-tiny-slider-css', '//cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.4/tiny-slider.css', null, '1.0');
   wp_enqueue_script('rez-tiny-slider-js', '//cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.2/min/tiny-slider.js', null, '1.0', true);

   wp_enqueue_script('rez-tiny-main-js', plugin_dir_url(__FILE__) . '/includes/main.js', array('jquery'), '1.0', true);
}

add_action('wp_enqueue_scripts', 'rtiny_assets');

//? image size
function custom_image_sizes()
{
add_image_size('rtiny-slider', 800, 600, true);
   // add_image_size('my_custom_size', 200, 500, true);
}
add_action('plugins_loaded', 'custom_image_sizes');

//? short code
function rez_tiny_slider($args, $content)
{
   $defaults = array(
      'width' => 800,
      'height' => 600,
      'id' => ''
   );
   $atts = shortcode_atts($defaults, $args);
   $content = do_shortcode($content);



   $shortcode_output = <<<EOD
<div id="{$atts['id']}" style="width:{$atts['width']};height:{$atts['height']};">
      <div id="rez_slider">
      {$content}
      </div>
</div>
EOD;
   return $shortcode_output;
}
add_shortcode('rtslider', 'rez_tiny_slider');

function rez_tiny_slide($args)
{
   $defaults = array(
      'caption' => '',
      'id' => '',
      'size' => 'small'
   );
   $atts = shortcode_atts($defaults, $args);
   $imag_src = wp_get_attachment_image_src($atts['id'], 'rtiny-slider');

   $shortcode_output = <<<EOD
<div class='slide'>
<p><img src="{$imag_src[0]}" alt="{$atts['caption']}"></p>
<p>{$atts['caption']} </p>
</div>
EOD;
   return $shortcode_output;
}
add_shortcode('rtslide', 'rez_tiny_slide');
