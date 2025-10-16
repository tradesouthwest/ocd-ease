<?php 
namespace OcdEase\MetaBox;
defined('ABSPATH') or die();
/**
 * class for render and showing custom meta box with custom meta data
 */
class MetaBox {
    private static $instance = null;
    public static function instance(){
        if( is_null(self::$instance) ){
            self::$instance = new self();
    }	
        return self::$instance;
        }
    
    function __construct(){
        add_action( 'save_post', array( $this, 'save_ocdease') );
    }

    /**
	 * add Meta Box to screen (edit page of ocd_ease cpt in admin).
	 *
     * this function is callback in "register_post_type" in "OcdEase\CPT\CPT" class
     * so we have $post as param
	 * @param WP_Post $post The post object.
	 */
    function metabox_cb($post){
        if($post->post_type != 'ocd_ease') return;
        add_meta_box(
            'your information',
            __( 'Your Information', 'ocd-ease' ),
            array( $this, 'meta_box_content' ),
            'ocd_ease',
            'advanced',
            'high'
        );
    }

    /**
	 * Render Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
    function meta_box_content($post){
        // get saved meta data
        $ocdease_emotscale = get_post_meta($post->ID, 'ocdease_emotscale', true);
        $ocdease_whatis = get_post_meta($post->ID, 'ocdease_whatis', true);
        $ocdease_label = get_post_meta($post->ID, 'ocdease_label', true);

        // print a nonce field to check in save hook
        wp_nonce_field( 'ocd_ease_meta', 'ocd_ease_meta_nonce' );

        ?>
        <table>
            <tr>
                <th><label for="ocdease_emotscale"><?php _e( 'ESY score', 'ocd-ease' ); ?></label></th>
                <td><input type="number" id="ocdease_emotscale" name="ocdease_emotscale" value="<?php echo esc_attr( $ocdease_emotscale ); ?>" size="5" />
            <small>1= Should have never got out of bed. 2= Dealing with it some. 3= Using all my tools to ease.</small></td>
            </tr>
            <tr>
                <th><label for="ocdease_whatis"><?php _e( 'Though urge?', 'ocd-ease' ); ?></label></th>
                <td><input type="text" id="ocdease_whatis" name="ocdease_whatis" value="<?php echo esc_attr( $ocdease_whatis ); ?>" size="28" />
            <small>What you were perceiving</small></td>
            </tr>
            <tr>
                <th><label for="ocdease_label"><?php _e( 'Label of thought', 'ocd-ease' ); ?></label></th>
                <td><input type="text" id="ocdease_label" name="ocdease_label" value="<?php echo esc_attr( $ocdease_label ); ?>" size="28" min="0" />
            <small>Mood | Stride | Yield</small></td>
            </tr>
        </table>
		<?php
    }

    /**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
     * ocdease_emotscale
     * ocd_whatis
     * ocdease_label
	 */
    function save_ocdease($post_id){
        // Check if our nonce is set.
		if ( ! isset( $_POST['ocd_ease_meta_nonce'] ) ) {
			return $post_id;
		}

		$nonce = $_POST['ocd_ease_meta_nonce'];

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'ocd_ease_meta' ) ) {
			return $post_id;
		}

        /*
		 * If this is an autosave, our form has not been submitted,
		 * so we don't want to do anything.
		 */
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

        // check user permissions        
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return $post_id;
        }

        // get posted meta data
        $ocdease_emotscale = intval( $_POST['ocdease_emotscale'] );
        $ocdease_whatis    = sanitize_text_field( $_POST['ocdease_whatis'] );
        $ocdease_label     = sanitize_text_field( $_POST['ocdease_label']);

        // save and update meta data
        update_post_meta( $post_id, 'ocdease_emotscale', 
                            sanitize_text_field( $ocdease_emotscale ) );
        update_post_meta( $post_id, 'ocdease_whatis', 
                            sanitize_text_field( $ocdease_whatis ) );
        update_post_meta( $post_id, 'ocdease_label', 
                            sanitize_text_field( $ocdease_label ) );

    }
}
MetaBox::instance();