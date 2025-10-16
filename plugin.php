<?php 
namespace OcdEase;
/**
 * @package ClassicPress
 * @subpackage OCD Ease
 * @since v1.0
 */
class main {
    private static $instance = null;
    public static function instance(){
        if( is_null(self::$instance) ){
            self::$instance = new self();
        }	
        return self::$instance;
    }

    function __construct(){
        add_action( 'init', array($this, 'load_text_domain') );
        add_filter('the_content', array($this, 'add_meta_info_at_the_end_of_content'));
    }

    /**
     * Load plugin textdomain.  
     */
    function load_text_domain() {
        load_plugin_textdomain( 'ocd-ease', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
    }

    /**
     * Custom meta data in front
     * we can use custom templates for our cpt if we want but for now we dont need.
     * @param String $content content that will print in front
     * @param String $ocdease_emotscale Level of 3 questions
     * @param String $ocdease_whatis    First questio on thoughts
     * @param String $ocdease_label     Second question
     * 
     * @return HTML
     */
    function add_meta_info_at_the_end_of_content($content){
        // check if we are in front
        if(is_admin()) return $content;

        // check if we are in single post (ocd_ease cpt)
        global $post;
        if($post->post_type != 'ocd_ease') return $content;

        // get saved meta data
        $ocdease_emotscale = get_post_meta($post->ID, 'ocdease_emotscale', true);
        $ocdease_whatis = get_post_meta($post->ID, 'ocdease_whatis', true);
        $ocdease_label = get_post_meta($post->ID, 'ocdease_label', true);
        ob_start();
        ?>
        <table>
            <tr>
                <th><?php _e( 'ESY score', 'ocd-ease' ); ?></th>
                <td><?php echo esc_html( $ocdease_emotscale ); ?></td>
            </tr>
            <tr>
                <th><?php _e( 'What is the obsessive thought or urge?', 'ocd-ease' ); ?></th>
                <td><?php echo esc_html( $ocdease_whatis ); ?></td>
            </tr>
            <tr>
                <th><?php _e( 'How would you label this? (Remember: It\'s not you, it\'s OCD)', 'ocd-ease' ); ?></th>
                <td><?php echo esc_html( $ocdease_label ); ?></td>
            </tr>
        </table>
		<?php
        return $content.ob_get_clean();

    }
}
main::instance();

/**
 * including other classes
 */
require_once OCDEASEPATH.'inc/cpt.php';
require_once OCDEASEPATH.'inc/metabox.php';