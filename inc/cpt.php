<?php 
namespace OcdEase\CPT;
defined('ABSPATH') or die();

/**
 * class to register our custom cpt name "ocd_ease" and our custom taxonomies "Trigger" and "Catergory"
 */
class CPT {
    private static $instance = null;
    public static function instance(){
        if( is_null(self::$instance) ){
            self::$instance = new self();
        }	
        return self::$instance;
    }

    function __construct(){
        add_action( 'init', array($this, 'register_cpt') );
        add_action( 'init', array($this, 'register_tax'), 15 );
    }

    /**
     * register our cpt, since "OCD Ease" is very common we avoid it, and we use "sorth_OCD Ease" instead.
     * but we use "OCD Ease" as slug for front 
     */
    function register_cpt(){
        $labels = array(
            'name'                  => __( 'OCD Ease', 'ocd-ease'),
            'singular_name'         => __( 'OCD Ease', 'ocd-ease'),
            'name_admin_bar'        => __( 'OCD Ease', 'ocd-ease' ),
            'add_new'               => __( 'Add New', 'ocd-ease' ),
            'add_new_item'          => __( 'Add New OCD Ease', 'ocd-ease' ),
            'new_item'              => __( 'New OCD Ease', 'ocd-ease' ),
            'edit_item'             => __( 'Edit OCD Ease', 'ocd-ease' ),
            'view_item'             => __( 'View OCD Ease', 'ocd-ease' ),
            'all_items'             => __( 'All OCD Ease', 'ocd-ease' ),
            'search_items'          => __( 'Search OCD Ease', 'ocd-ease' ),
        );
        register_post_type('ocd_ease',
            array(
                'labels'                => $labels,
                'public'                => true,
                'has_archive'           => true,
                'show_in_rest'          => true,
                'capability_type'       => 'post',
                'menu_icon'             => 'dashicons-book-alt',
                'supports'              => array('title', 'editor', 'thumbnail'),
                'taxonomies'            => array('ocd_cats', 'ocd_tags'),
                'rewrite'               => array('slug' => 'ocd-easy'),
                // 'register_meta_box_cb'  => array('OcdEase\MetaBox\MetaBox', 'metabox_cb'),
                'register_meta_box_cb'  => array(\OcdEase\MetaBox\MetaBox::instance(), 'metabox_cb'),
                )
            );
    }

    /**
     * register our custom taxonomies
     */
    function register_tax(){ 

        // TODO :: use $labels as array

        // author is a builtin in wordpress, so we should avoid it.
        register_taxonomy( 'ocd_cats', 'ocd_ease', array(
            'labels'      => array(
                'name'              => __( 'OcdEase Month', 'ocd-ease'),
                'singular_name'     => __( 'OCD Category', 'ocd-ease'),
                'menu_name'         => __( 'Categories', 'ocd-ease'),
                'all_items'         => __( 'All Categories', 'ocd-ease' ),
                'edit_item'         => __( 'Edit Category', 'ocd-ease' ),
                'update_item'       => __( 'Update Category', 'ocd-ease' ),
                'add_new_item'      => __( 'Add New Category', 'ocd-ease' ),
                'new_item_name'     => __( 'New Category Name', 'ocd-ease' ),
            ),
            'public'     => true,
            'show_in_rest' => true,   
            'rewrite'      => array( 'slug' => 'ocd-month' )
        ) );

        register_taxonomy( 'ocd_tags', 'ocd_ease', array(
            'labels'      => array(
                'name'              => __('Triggers', 'ocd-ease'),
                'singular_name'     => __('Trigger', 'ocd-ease'),
                'menu_name'         => __('Triggers', 'ocd-ease'),
                'all_items'         => __( 'All Triggers', 'ocd-ease' ),
                'edit_item'         => __( 'Edit Trigger', 'ocd-ease' ),
                'update_item'       => __( 'Update Trigger', 'ocd-ease' ),
                'add_new_item'      => __( 'Add New Trigger', 'ocd-ease' ),
                'new_item_name'     => __( 'New Trigger Name', 'ocd-ease' ),
            ),
            'public'     => true,
            'show_in_rest' => true,   
            'rewrite'      => array( 'slug' => 'ocd-tags' )
        ) );

    }

}
cpt::instance();
