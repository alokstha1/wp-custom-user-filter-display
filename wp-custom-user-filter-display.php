<?php
/**
* Plugin Name: Wp Custom User Filter Display
* Description: Plugin to display users in frontend, filter them by roles and order them in alphabetical order by display_name and username.
* Version: 1.0.0
* Author: Alok Shrestha
* Author URI: http://alokshrestha.com.np
* License: GPL2
* Text Domain: wcufd
*/

if (!defined('ABSPATH')) {
    exit;
}

/**
* Check whether the class `WpCustomUserFilterDisplay` is already defined or not.
*/
if (! class_exists('WpCustomUserFilterDisplay')) {
    /**
    * Define a class `WpCustomUserFilterDisplay`
    */
    class WpCustomUserFilterDisplay
    {
        /**
        * The constructor method
        */
        public function __construct() {

            // Hook to use for enqueuing scripts and styles
            add_action( 'wp_enqueue_scripts', array( $this, 'wcufd_load_styles_scripts' ) );

            // Hook to add shortcode
            add_shortcode( 'wcufd-lists', array( $this, 'wcufd_list_users' ) );

            // send ajax request only for privleged users
            add_action( 'wp_ajax_filter_user', array( $this, 'wcufd_ajax_filter_user' ) );
        }

        /**
        * Callback function of hook `wp_enqueue_scripts`.
        */
        public function wcufd_load_styles_scripts() {
            //main script file of the plugin
            wp_register_script( 'wcufd-front-end-js', plugins_url( 'js/wcufd-script.js',__FILE__ ), array('jquery'), '', true );
            wp_localize_script( 'wcufd-front-end-js', 'wcufd_vars', array(
                'ajaxurl'           => admin_url('admin-ajax.php'),
                'current_user_can'  => current_user_can('administrator')
                )
            );
            wp_register_style( 'wcufd-front-end-style', plugins_url( 'css/wcufd-style.css',__FILE__ ) );
        }

        /**
        * Shortcode function that is used to render html.
        */
        public function wcufd_list_users( $atts ) {

            ob_start();
            if ( current_user_can('administrator') ) {
                $number         = 10; //max display per page
                $paged          = (get_query_var('paged')) ? get_query_var('paged') : 1; //current number of page
                $offset         = ($paged - 1) * $number; //page offset
                $count_users    = count_users(); // get the count of users
                $total_users    = $count_users['total_users'];//count total users
                $total_pages    = ($total_users / $number); // get the total pages by dividing the total users to the maximum numbers of user to be displayed
                $total_pages    = is_float($total_pages) ? intval($total_users / $number) + 1 : intval($total_users / $number); //Check if the total pages has a decimal we will add + 1 page
                $args           = array(
                                    'offset'    => $offset,
                                    'number'    => $number,
                                );
                $user_query     = get_users($args);
                $total_query    = count($user_query);//count the maximum displayed users

                wp_enqueue_script('wcufd-front-end-js'); //enqueue script here with the shortcode
                wp_enqueue_style('wcufd-front-end-style'); //enqueue tyle here with the shortcode
                echo '<div class="wcufd-container">';
                echo '<div class="wcufd-loading"></div>'; //loader class element
                echo '<div class="wcufd-filter-container">';
                    include_once( plugin_dir_path( __FILE__ ). 'templates/filter-form.php' );
                echo '</div>';

                echo '<div class="wcufd-table-list">';
                    include_once( plugin_dir_path( __FILE__ ). 'templates/html-template-user.php' );
                echo '</div>';
                echo '</div>';
            }

            return ob_get_clean();
        }

        public function wcufd_ajax_filter_user() {
            if ( current_user_can('administrator') ) {

                if ( !isset( $_POST['validate_submit'] ) || !wp_verify_nonce( $_POST['validate_submit'], 'wcufd_nonce_users' ) ) {
                    // validate nonce & return false if not validated
                    $return = array(
                        'status'    => false,
                        'response_html'  => __( 'Something went wrong! Please try again.', 'wcufd' )
                    );
                } else {

                    ob_start();
                    $paged          = ( isset( $_POST['paged'] ) && !empty( $_POST['paged'] ) ) ? intval( $_POST['paged'] ) : 1;
                    $number         = 10; //max display per page
                    $offset         = ($paged - 1) * $number; //page offset
                    $count_users    = count_users(); // get the count of users

                    // if role filter is used
                    if ( isset( $_POST['users_role'] ) && !empty( $_POST['users_role'] ) ) {

                        $role           = sanitize_text_field( $_POST['users_role'] );
                        $args['role']   = $role;
                        $total_users    = ( isset( $count_users['avail_roles'][$role] ) && !empty( $count_users['avail_roles'][$role] ) ) ? $count_users['avail_roles'][$role] : 0;//count total users based on roles for using in pagination

                    } else {

                        $total_users    = $count_users['total_users'];//count total users
                    }

                    $total_pages    = ($total_users / $number); // get the total pages by dividing the total users to the maximum numbers of user to be displayed
                    $total_pages    = is_float($total_pages) ? intval($total_users / $number) + 1 : intval($total_users / $number); //Check if the total pages has a decimal we will add + 1 page
                    $args['offset'] = $offset;
                    $args['number'] = $number;

                    // order by Ascending or Descending
                    if ( isset( $_POST['users_order'] ) && !empty( $_POST['users_order'] ) ) {

                        $order          = sanitize_text_field( $_POST['users_order'] );
                        $args['order']  = $order;

                    }

                    // if orderby parameter is passed
                    if ( isset( $_POST['users_orderby'] ) && !empty( $_POST['users_orderby'] ) ) {
                        $order_by           = sanitize_text_field( $_POST['users_orderby'] );
                        $args['orderby']    = $order_by;
                    }

                    $user_query     = get_users($args); //main user query to list based on the passed arguments
                    $total_query    = count($user_query);//count the maximum displayed users

                    // Template included with html loop for above query
                    include_once( plugin_dir_path( __FILE__ ). 'templates/html-template-user.php' );

                    $html_result = ob_get_clean();
                    $return = array(
                        'status'        => true,
                        'response_html' => $html_result
                    );
                }
            } else {
                $return = array(
                    'status'        => true,
                    'response_html' => __( 'You must be logged in as Admin to perform filter!', 'wcufd' )
                );
            }
            wp_send_json( $return ); // Send a JSON response back and die().
        }
    }

    $wcufd = new WpCustomUserFilterDisplay(); //creating instance of the class WpCustomUserFilterDisplay
}
