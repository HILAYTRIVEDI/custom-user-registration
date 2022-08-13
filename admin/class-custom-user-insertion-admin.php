<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.multidots.com/
 * @since      1.0.0
 *
 * @package    Custom_User_Insertion
 * @subpackage Custom_User_Insertion/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    Custom_User_Insertion
 * @subpackage Custom_User_Insertion/admin
 * @author     Hilay Trivedi <hilay.trivedi@multidos.com>
 */

// If this file is called directly, abort.
if (!defined('ABSPATH')) die("No Hacking!");

if( !class_exists('Custom_User_Insertion_Admin') ){
	


	class Custom_User_Insertion_Admin {

		/**
		 * Register the stylesheets for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_styles() {
			wp_enqueue_style( "Custom_User_Insertion_css", plugin_dir_url( __FILE__ ) . 'css/custom-user-insertion-admin.css', array(), "1.0.0", 'all' );
			wp_enqueue_style( "Custom_User_Insertion_multiselect_dropdown_css", 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css', array(), "1.0.0", 'all' );
		}
	
		/**
		 * Register the JavaScript for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_scripts() {
	
			wp_enqueue_script( "Custom_User_Insertion_js", plugin_dir_url( __FILE__ ) . 'js/custom-user-insertion-admin.js', array( 'jquery' ), "1.0.0", false );
			wp_enqueue_script( "Custom_User_Insertion_multiselect_dropdown_js", 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', array( 'jquery' ), "1.0.0", false );

		}

		/**
		 * Function to check admin approval status
		 * 
		 * @since 1.0.0
		 * 
		 * @return void
		 */
		public function custom_user_approval_status(){

			$action = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING );
			$verified = filter_input( INPUT_GET, 'verified', FILTER_VALIDATE_BOOLEAN );
			$token = filter_input( INPUT_GET, 'key', FILTER_SANITIZE_STRING );

			if(isset($action) && "urp_verification" === $action){
				if($verified){
					$cst_user = get_users(
						array(
						 'meta_key' => '_user_unique_token_for_admin', //PHPCS:ignore
						 'meta_value' => $token, //PHPCS:ignore
						)
						);
					$cst_user = $cst_user[0];
					$userID= $cst_user->ID;
					$userName = $cst_user->user_login;
					update_user_meta( $userID , '_user_validate_by_admin', true );
					
					// Send the mail on the approved user.
					wp_mail($cst_user->user_email,"Approved!!","Congrultaion your registratioon is approved. Now you can login");//PHPCS:ignore

					$page = wpcom_vip_get_page_by_title($userName, OBJECT, 'custom_user');
					$custom_post_ID = $page->ID;
					if($custom_post_ID){
						wp_update_post(array(
							'ID'    =>  $custom_post_ID,
							'post_status'   =>  'publish'
						));
					}
				}
			}
		}
		/**
		 * Restrict non confirmed user for login
		 * 
		 * @since 1.0.0
		 * 
		 * @param array $user array of the user which is trying to login.
		 * 
		 * @return array $user array of the validate user
		 */
		public function check_validation_status($user) {

			if(isset($user) && !empty($user->ID)){
				$userID = $user->ID;
				$userRole = $user->roles;
				$is_verified = get_user_meta( $userID, '_user_validate_by_admin', true );
				if($userID && !in_array("administrator",$userRole,true)){
					if(!$is_verified){
						$errors = new WP_Error();
						$errors->add('title_error', __('<strong>ERROR</strong>: This account has not been verified.'));
						return $errors;
					}
					return $user;
				}
			}
			return $user;
		}

		/**
		 * Overrite the single post page of the theme.
		 * 
		 * @since 1.0.0
		 * 
		 * @param array $single array for the template.
		 * 
		 * @return array $single array for the template.
		 */
		public function my_custom_single_template($single) {

			global $post;
		
			/* Checks for single template by post type */
			if ( $post->post_type === 'custom_user' ) {
				if ( file_exists( plugin_dir_path( __FILE__ ) . 'templates/single-custom-user.php' ) ) {
					return plugin_dir_path( __FILE__ ) . 'templates/single-custom-user.php';
				}
			}
		
			return $single;
		
		}

		/**
		 * Register the Custom Post Type for the admin area.
		 *
		 * @since    1.0.0
		 * 
		 * @return object
		 */
		public function custom_posts(){

			// UI labels for Custom Post Type
			$labels = array(
				'name'                => _x( 'Custom Users', 'Post Type General Name'),
				'singular_name'       => _x( 'User', 'Post Type Singular Name'),
				'menu_name'           => __( 'Custom Users'),
				'parent_item_colon'   => __( 'Parent User'),
				'all_items'           => __( 'All Custom Users'),
				'view_item'           => __( 'View User'),
				'add_new_item'        => __( 'Add New User'),
				'add_new'             => __( 'Add New'),
				'edit_item'           => __( 'Edit User'),
				'update_item'         => __( 'Update User'),
				'search_items'        => __( 'Search User'),
				'not_found'           => __( 'Not Found'),
				'not_found_in_trash'  => __( 'Not found in Trash'),
			);
			 
			// Options for Custom Post Type 
			$args = array(
				'label'               => __( 'users' ),
				'description'         => __( 'Custom user' ),
				'labels'              => $labels,
				'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
				'taxonomies'          => array( 'user_category','user_tag'),
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_nav_menus'   => true,
				'show_in_admin_bar'   => true,
				'menu_position'       => 5,
				'menu_icon'           => 'dashicons-groups',
				'can_export'          => true,
				'has_archive'         => true,
				'exclude_from_search' => false,
				'publicly_queryable'  => true,
				'capability_type'     => 'post',
				'show_in_rest' 		  => true,
		 
			);
			register_post_type( 'custom_user', $args );	
			
		}

		/**
		 * Register the Custom Taxonomy for the admin area.
		 *
		 * @since    1.0.0
		 * 
		 * @return object
		 */
		public function custom_taxonomy(){
			$labels = array(
				'name'              => _x( 'Custom User Categorys', 'taxonomy general name' ),
				'singular_name'     => _x( 'Custom User Category', 'taxonomy singular name' ),
				'search_items'      => __( 'Search Custom User Categorys' ),
				'all_items'         => __( 'All Custom User Categorys' ),
				'parent_item'       => __( 'Parent Custom User Category' ),
				'parent_item_colon' => __( 'Parent Custom User Category:' ),
				'edit_item'         => __( 'Edit Custom User Category' ),
				'update_item'       => __( 'Update Custom User Category' ),
				'add_new_item'      => __( 'Add New Custom User Category' ),
				'new_item_name'     => __( 'New Custom User Category Name' ),
				'menu_name'         => __( 'Custom User Category' ),
			);
		 
			$args = array(
				'hierarchical'      => true,
				'labels'            => $labels,
				'show_ui'           => true,
				'show_admin_column' => true,
				'show_in_rest' 		=> true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'user_category' ),
			);
		 
			register_taxonomy( 'user_category', array( 'custom_user' ), $args );

		}
		 

		/**
		 * Register the Custom admin page for the admin area.
		 *
		 * @since    1.0.0
		 * 
		 * @return object
		 */
		public function custom_user_admin_menu() {
			add_menu_page(
				__( 'Custom Users skills', 'Custom_User_Insertion' ),
				__( 'Custom Users skills menu', 'Custom_User_Insertion' ),
				'manage_options',
				'custom-user-skills',
				array($this,'custom_user_admin_menu_content_callback'),
				'dashicons-schedule',
				7
			);
		}

		/**
		 * A call back funciton of custom admin page content
		 *
		 * @since    1.0.0
		 * 
		 * @return html
		 */
		public function custom_user_admin_menu_content_callback() {
			require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/custom-user-insertion-admin-display.php';
		}

		/**
		 * Register the settings section to the cutom admin page
		 *
		 * @since    1.0.0
		 */
		public function custom_user_skills()
		{
			//Adding general setting section
			add_settings_section(
				'custom_user_skills',
				'Form Settings',
				array($this,'custom_user_skills_callback'),
				'custom_user_skills'
			);

			register_setting('custom_user_skills', 'custom-user-admin-page__skill--list');
			register_setting('custom_user_skills', 'custom-user-admin-page__email');
			register_setting('custom_user_skills', 'custom-user-admin-page__switch--checkbox');
            register_setting('custom_user_skills', 'custom-user-admin-page__lastname-switch--checkbox');
            register_setting('custom_user_skills', 'custom-user-admin-page__primary-address-switch--checkbox');
            register_setting('custom_user_skills', 'custom-user-admin-page__secondary-address-switch--checkbox');
            register_setting('custom_user_skills', 'custom-user-admin-page__postal-code-switch--checkbox');
            register_setting('custom_user_skills', 'custom-user-admin-page__dob-switch--checkbox');
            register_setting('custom_user_skills', 'custom-user-admin-page__hobbies-switch--checkbox');
 			register_setting('custom_user_skills', 'custom-user-admin-page__skills-setting-switch--checkbox');

			if(empty(get_option( "custom-user-admin-page__email" ))){
				$current_user = wp_get_current_user();
				$custom_admin_mail = $current_user->user_email;
				update_option( "custom-user-admin-page__email" , $custom_admin_mail );
			}
		}

		/**
		 * Call back function for the custom setting section
		 *
		 * @since    1.0.0
		 * 
		 * @return html
		 */
		public function custom_user_skills_callback(){

			require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/custom_user_settings_callback.php';

		}
		/**
		 * Register the Custom Metabox for the admin area.
		 *
		 * @since    1.0.0
		 * 
		 * @return html
		 */
		public function custom_metabox(){
			add_meta_box('custom_user_details', 'User Details', array( $this, 'custom_user_details_html' ), 'custom_user','normal');
		}
		
		/**
		 * Call back function for the HTML of Meta boxes
		 * 
		 * @since    1.0.0
		 * 
		 * @param object $post object of the post data.
		 */
		public function custom_user_details_html($post){
		
			$user_password = get_post_meta( $post->ID,  'custom_user_password', true );
$first_name = get_post_meta( $post->ID,  'custom_user_first_name', true );
$last_name = get_post_meta( $post->ID,  'custom_user_last_name', true );
$email = get_post_meta( $post->ID,  'custom_user_email', true );
$dob = get_post_meta( $post->ID,  'custom_user_dob', true );
$add = get_post_meta( $post->ID,  'custom_user_address', true );
$add2 = get_post_meta( $post->ID,  'custom_user_address_two', true );
$postal = get_post_meta( $post->ID,  'custom_user_postal', true );
$skills = get_option( '	custom-user-admin-page__skill--list' );
$selected_skills = get_post_meta( $post->ID,  'custom_user_skills', true );
$hobby = get_post_meta( $post->ID,  'custom_user_hobby', true );
$ratings = get_post_meta( $post->ID,  'custom_user_ratings', true );

?>

    <div class="custom_user_field--wrapper">
        <label for="custom_user_passwordfield" class="custom_meta_notes">User Password</label>
        <input type="text" value="<?php echo esc_attr($user_password)?>" placeholder="Password" name="custom_user_passwordfield" id="custom_user_passwordfield" class="custom_user_passwordfield--text" require>
    </div>
    <div class="custom_user_field--wrapper">
        <label for="custom_user_first_namefield" class="custom_meta_notes">First Name</label>
        <input type="text" placeholder="First Name" value="<?php echo esc_attr($first_name)?>" name="custom_user_first_namefield" id="custom_user_first_namefield" class="custom_user_first_namefield--text" require>
    </div>
    <div class="custom_user_field--wrapper">
        <label for="custom_user_lastname_namefield" class="custom_meta_notes">Last Name</label>
        <input type="text" placeholder="Last Name" value="<?php echo esc_attr($last_name)?>" name="custom_user_last_namefield" id="custom_user_last_namefield" class="custom_user_lastname_namefield--text" require>
    </div>
    <div class="custom_user_field--wrapper">
        <label for="custom_user_emailfield" class="custom_meta_notes">User Email</label>
        <input type="email" placeholder="Email Adress" value="<?php echo esc_attr($email)?>" name="custom_user_emailfield" id="custom_user_emailfield" class="custom_user_emailfield--text" require>
    </div>
    <div class="custom_user_field--wrapper">
        <label for="custom_user_dobfield" class="custom_meta_notes">User DOB</label>
        <input type="date" id="custom_user_dobfield" value="<?php echo esc_attr($dob)?>" name="custom_user_dobfield" class="user_input custom_user_dobfield--text" require>
    </div>
    <div class="custom_user_field--wrapper">
        <label for="custom_user_addressfield" class="custom_meta_notes">Adress 1</label>
        <input type="text" id="custom_user_addressfield" value="<?php echo esc_attr($add)?>" name="custom_user_addressfield" class="user_input custom_user_addressfield--text" placeholder="Adress 1">
        <label for="custom_user_addressfieldtwo" class="custom_meta_notes">Adress 2</label>
        <input type="text" id="custom_user_addressfieldtwo" value="<?php echo esc_attr($add2)?>" name="custom_user_addressfieldtwo" class="user_input custom_user_addressfield--text" placeholder="Adress 2">
    </div>
    <div class="custom_user_field--wrapper">
        <label for="custom_user_postalfield" class="custom_meta_notes">User Postal Code</label>
        <input type="number" value="<?php echo esc_attr($postal)?>" placeholder="Postal Code" name="custom_user_postalfield" id="custom_user_postalfield" name="custom_user_postalfield" class="user_input custom_user_postalfield--text" >
    </div>
    <?php 
        $skills_new_array = explode(",",$skills);
        if(!is_array($selected_skills)){
            $selected_skills = explode(",",$selected_skills);
        }

    ?>

    <div class="custom_user_field--wrapper">
        <label for="custom_user_skillsfield" class="custom_meta_notes">Please select your skills</label>
        <select id="custom_user_skill" class="custom_user_skill required" name="custom_user_skills[]" multiple>
        <?php 
            foreach( $skills_new_array as $ops ){
                if(in_array($ops,$selected_skills,true)){
                    $selected = "selected";
                } else {
                    $selected = "";
                }

                ?>
                <option <?php echo esc_html( $selected ) ?> value="<?php echo esc_attr($ops)?>"><?php echo esc_html($ops)?></option>
        <?php	
        }
        ?>	
        </select>
                
    </div>
    <div class="custom_user_field--wrapper">
        <label for="custom_user_hobbyfield" class="custom_meta_notes">Please enter your new hobbies seperated by ","</label>
        <input id="custom_user_hobbyfield" value="<?php echo esc_attr($hobby)?>" class="user_input custom_user_hobbyfield--text" name="custom_user_hobbyfield"/>
    </div>
    <div class="custom_user_field--wrapper">
        <label for="custom_user_ratingsfield" class="custom_meta_notes">User Ratings out of 5</label>
        <input type="number" placeholder="Ratings for users" value="<?php echo esc_attr($ratings)?>" name="custom_user_ratingsfield" id="custom_user_ratingsfield" class="custom_user_ratingsfield--text" min="1" max="5">
    </div>

	<?php

			wp_nonce_field( 'create_user_details_action', 'create_user_details', );
		}


		/**
		 * Call back function to save values of Meta boxes
		 * 
		 * @since    1.0.0
		 * 
		 * @param integer $post_id is the id of the updated post.
		 * 
		 * @return object
		 */
		public function custom_meta_box_saver($post_id){

			if(isset($_POST) && !empty($_POST)){
				if ( isset( $_POST['create_user_details'] ) && wp_verify_nonce( $_POST['create_user_details'], 'create_user_details_action' )) { //PHPCS:ignore

					if(isset($_POST["custom_user_passwordfield"])):
						update_post_meta($post_id, 'custom_user_password', sanitize_text_field ( $_POST["custom_user_passwordfield"]) );
					endif;
					if(isset($_POST["custom_user_first_namefield"])):
						update_post_meta($post_id, 'custom_user_first_name', sanitize_text_field ( $_POST["custom_user_first_namefield"]) );
					endif;
					if(isset($_POST["custom_user_last_namefield"])):
						update_post_meta($post_id, 'custom_user_last_name', sanitize_text_field ( $_POST["custom_user_last_namefield"]) );
					endif;
					if(isset($_POST["custom_user_emailfield"])):
						update_post_meta($post_id, 'custom_user_email', sanitize_email ( $_POST["custom_user_emailfield"]) );
					endif;
					if(isset($_POST["custom_user_dobfield"])):
						update_post_meta($post_id, 'custom_user_dob', sanitize_text_field( $_POST["custom_user_dobfield"]) );
					endif;
					if(isset($_POST["custom_user_addressfield"])):
						update_post_meta($post_id, 'custom_user_address', sanitize_text_field( $_POST["custom_user_addressfield"]) );
					endif;
					if(isset($_POST["custom_user_addressfieldtwo"])):
						update_post_meta($post_id, 'custom_user_address_two', sanitize_text_field( $_POST["custom_user_addressfieldtwo"]) );
					endif;
					if(isset($_POST["custom_user_postalfield"])):
						update_post_meta($post_id, 'custom_user_postal', sanitize_text_field( $_POST["custom_user_postalfield"]) );
					endif;
					if(isset($_POST["custom_user_skills"])):
						update_post_meta($post_id, 'custom_user_skills', $_POST["custom_user_skills"] ); //PHPCS:ignore
					endif;
					if(isset($_POST["custom_user_hobbyfield"])):
						update_post_meta($post_id, 'custom_user_hobby', sanitize_text_field( $_POST["custom_user_hobbyfield"]) );
					endif;
					if(isset($_POST["custom_user_ratingsfield"])):
						if(empty($_POST["custom_user_ratingsfield"])){
							$_POST["custom_user_ratingsfield"] = 1;
						}
						if($_POST["custom_user_ratingsfield"] > 5){
							$_POST["custom_user_ratingsfield"] = 5;
						}
						if ($_POST["custom_user_ratingsfield"] < 0){
							$_POST["custom_user_ratingsfield"] = 1;
						}
						update_post_meta($post_id, 'custom_user_ratings', sanitize_text_field( $_POST["custom_user_ratingsfield"]) );
					endif;
					
				}
			}
		}
		
		/**
		 * Call back function to add the column of meta boxes in the table
		 * 
		 * @since    1.0.0
		 * 
		 * @param array $colums add the custom user fields in the table of custom users.
		 * 
		 * @return array
		 */
		public function manage_custom_user_posts_columns($columns) {
			unset($columns['date']);
			unset($columns['title']);
			unset($columns['taxonomy-user_category']);
			unset($columns['comments']);
			return array_merge($columns, array(
				'title' => __('Name'),
				'email' => __('Email'),
				'custom_user_ratings' => __('Ratings'),
				'custom_user_dob' => __('Date of birth'),
				'taxonomy-user_category' => __('Category'),
				'date' => __('Inquiry Date'),
			));
		}

		/**
		 * Call back function to set the data in the column of the table
		 * 
		 * @since    1.0.0
		 * 
		 * @param array $colums add the custom user fields in the table of custom users.
		 */
		public function adding_custom_user_posts_columns_data($columns) {
			global $post;
			switch ($columns) {
				case 'email':
					echo esc_html__(get_post_meta($post->ID, 'custom_user_email', true));
					break;
				case 'custom_user_ratings':
					echo esc_html__(get_post_meta($post->ID, 'custom_user_ratings', true));
					break;
				case 'custom_user_dob':
					echo esc_html__(get_post_meta($post->ID, 'custom_user_dob', true));
					break;
			}
		}

	}

	/**
	 * Check if the user is admin or not.
	 * 
	 * @since 1.0.0
	 */
	if( is_admin() ) {
		$Custom_User_Insertion_Admin = new Custom_User_Insertion_Admin; //PHPCS:ignore
	}

}
