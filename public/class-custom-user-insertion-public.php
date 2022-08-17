<?php //PHPCS:ignore
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.multidots.com/
 * @since      1.0.0
 *
 * @package    Custom_User_Insertion
 * @subpackage Custom_User_Insertion/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Custom_User_Insertion
 * @subpackage Custom_User_Insertion/public
 * @author     Hilay Trivedi <hilay.trivedi@multidos.com>
 */

// If this file is called directly, abort.
if (!defined('ABSPATH')) die("No Hacking!");

if( !class_exists('Custom_User_Insertion_Public') ){

	class Custom_User_Insertion_Public {

		/**
		 * A constructure to add the shortcodes to the site
		 */
		public function __construct(){
			add_shortcode( 'custom_user_search_tool_form', array($this, 'custom_user_stepper_form_handler') );
			add_shortcode( 'custom_user_search_tool_list', array($this, 'custom_user_search_tool_list_handler') );
		}
		
		/**
		 * Register the stylesheets for the public-facing side of the site.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_styles() {
	
			wp_enqueue_style( "Custom_User_Insertion_public_css", plugin_dir_url( __FILE__ ) . 'css/main.css', array(), "1.0.0", 'all' );
			wp_enqueue_style( "Custom_User_Insertion_multiselect_dropdown_css", 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css', array(), "1.0.0", 'all' );
		}
	
		/**
		 * Register the JavaScript for the public-facing side of the site.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_scripts() {
	
			wp_enqueue_script( "multistepper_validator_js", plugin_dir_url( __FILE__ ) . 'js/jquery_validator.js', array( 'jquery' ), "1.0.0", false );
			wp_enqueue_script( "Custom_User_Insertion_multiselect_dropdown_js", 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', array( 'jquery' ), "1.0.0", false );
			wp_enqueue_script( "multistepper_js", plugin_dir_url( __FILE__ ) . 'js/jquery.steps.min.js', array( 'jquery' ), "1.0.0", false );
			wp_enqueue_script( "Custom_User_Insertion_public_js", plugin_dir_url( __FILE__ ) . 'js/custom-user-insertion-public.js', array( 'jquery' ), "1.0.0", false );
			wp_localize_script('Custom_User_Insertion_public_js', 'Custom_User_params', array('ajaxurl' => admin_url( 'admin-ajax.php' ),'nonce' => wp_create_nonce('ajax-nonce')));
		}

		/**
		 * Converts the simple dropdowns to multiselect dorpdown.
		 * 
		 * @since    1.0.0
		 */
		public function wp_dropdown_cats_multiple( $output, $r ) {

			if( isset( $r['multiple'] ) && $r['multiple'] ) {
		
				 $output = preg_replace( '/^<select/i', '<select multiple', $output );
		
				$output = str_replace( "name='{$r['name']}'", "name='{$r['name']}[]'", $output );
		
				foreach ( array_map( 'trim', explode( ",", $r['selected'] ) ) as $value )
					$output = str_replace( "value=\"{$value}\"", "value=\"{$value}\" selected", $output );
		
			}
		
			return $output;
		}

		/**
		 * Call back function for Multistepper Registration form
		 * 
		 * @since    1.0.0
		 */
		
		public function custom_user_stepper_form_handler(){
			
			ob_start();
			if ( ! ( is_user_logged_in() )  ) {
					include_once plugin_dir_path( __FILE__ ) . 'partials/custom-user-insertion-public-display.php';
				} else {
					echo "<script>
						alert('Looks like you are already login')	
						window.location.href = window.location.origin				
					</script>";
				}
				$html = ob_get_clean();
				return $html;
		}

		/**
		 * Ajax callback function for the user registration
		 * 
		 * @since    1.0.0
		 */
		public function custom_user_insertion_form_callback(){

			if(isset( $_POST['nonce'] ) && !empty( $_POST['nonce'] )){ //PHPCS:ignore
				if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce') ) { //PHPCS:ignore
					die();
				}
			}
			$user_name=( isset( $_POST['userName'] ) && !empty( $_POST['userName'] ) ) ? sanitize_text_field($_POST['userName']) :"";
			$name=( isset( $_POST['name'] ) && !empty( $_POST['name'] ) ) ? sanitize_text_field($_POST['name']) :""; 
			$surname=( isset( $_POST['surname'] ) && !empty( $_POST['surname'] ) ) ? sanitize_text_field($_POST['surname']) :"";
			$email=( isset( $_POST['email'] ) && !empty( $_POST['email'] ) ) ? sanitize_text_field($_POST['email']) :"";
			$user_avatar=( isset( $_FILES['userAvatar'] ) && !empty( $_FILES['userAvatar'] ) ) ? $_FILES['userAvatar'] :""; //PHPCS:ignore
			$address=( isset( $_POST['address'] ) && !empty( $_POST['address'] ) ) ? sanitize_text_field($_POST['address']) :"";
			$secondary_address=( isset( $_POST['secondary_address'] ) && !empty( $_POST['secondary_address'] ) ) ? sanitize_text_field($_POST['secondary_address']) :"";
			$date_of_birth=( isset( $_POST['date_of_birth'] ) && !empty( $_POST['date_of_birth'] ) ) ? sanitize_text_field($_POST['date_of_birth']) :"";
			$user_postal = ( isset( $_POST['user_postal'] ) && !empty( $_POST['user_postal'] ) ) ? sanitize_text_field($_POST['user_postal']) :"";
			$user_hobbies = ( isset( $_POST['user_hobby'] ) && !empty( $_POST['user_hobby'] ) ) ? sanitize_text_field($_POST['user_hobby']):"" ;
			$user_skills = ( isset( $_POST['custom_user_skill'] ) && !empty( $_POST['custom_user_skill'] ) ) ? sanitize_text_field($_POST['custom_user_skill']) :"" ;
			$custom_user_password=( isset( $_POST['password'] ) && !empty( $_POST['password'] ) ) ? sanitize_text_field($_POST['password']) :"" ;

			if(isset( $_POST['custom_user_cat'] ) && !empty( $_POST['custom_user_cat'] )){ //PHPCS:ignore
				$custom_user_cat = $_POST['custom_user_cat']; //PHPCS:ignore
				$custom_user_cat = array_pop($custom_user_cat);
			}

			if(!email_exists( $email ) && !username_exists( $user_name )){

				$wordpress_upload_dir = wp_upload_dir();
	
				$profilepicture = $user_avatar;
				$new_file_path = $wordpress_upload_dir['path'] . '/' . $profilepicture['name'];
				$new_file_mime = mime_content_type( $profilepicture['tmp_name'] );
	
				// If everything is OK
				if( move_uploaded_file( $profilepicture['tmp_name'], $new_file_path ) ) {
					

					$upload_id = wp_insert_attachment( array(
						'guid'           => $new_file_path, 
						'post_mime_type' => $new_file_mime,
						'post_title'     => preg_replace( '/\.[^.]+$/', '', $profilepicture['name'] ),
						'post_content'   => '',
						'post_status'    => 'inherit'
					), $new_file_path );

					// wp_generate_attachment_metadata() won't work if you do not include this file
					require_once( ABSPATH . 'wp-admin/includes/image.php' );

					// Generate and save the attachment metas into the database
					wp_update_attachment_metadata( $upload_id, wp_generate_attachment_metadata( $upload_id, $new_file_path ) );

				}

				$cst_user_id  = wp_insert_user( 
					array(
						'user_pass' 	=> $custom_user_password,
						'user_email'	=> $email,
						'user_login' 	=> $user_name,
						'display_name'	=> $user_name,
						'first_name' 	=> $name,
						'last_name' 	=> $surname,
						'role' 			=> 'subscriber'
					)
				);
				add_user_meta( $cst_user_id, "_user_validate_by_admin", false, true );
				
				// Generate a random string.
				$token = openssl_random_pseudo_bytes(16);
				
				//Convert the binary data into hexadecimal representation.
				$token = bin2hex($token);
				add_user_meta( $cst_user_id, "_user_unique_token_for_admin", $token, true );

				$url_approve = admin_url("/users.php")."?action=urp_verification&key=$token&verified=1";

				$msg = "New user has been registered under title '$user_name'.\nName : $name $surname\nEmail : $email\nDOB: $date_of_birth\nAdress:$address\nApprove:$url_approve";

				$custom_admin_mail = get_option( "custom-user-admin-page__email" );
				
				echo json_encode(array("Success"=> 1));

				wp_mail( $custom_admin_mail, 'New User Inquiry', $msg );
				
				$my_cptpost_args = array(
					'post_title'    => $user_name,
					'post_author'	=>	'1',
					'post_status'   => 'draft',
					'post_type'     => 'custom_user',
					'meta_input'    => array(
						'custom_user_first_name'        => $name,
						'custom_user_last_name'     	=> $surname,
						'custom_user_email'             => $email,
						'custom_user_dob'               => $date_of_birth,
						'custom_user_address'           => $address,
						'custom_user_address_two'		=> $secondary_address,
						'custom_user_postal'            => $user_postal,
						'custom_user_skills'            => $user_skills,
						'custom_user_hobby'             => $user_hobbies,
						'custom_user_password'			=> $custom_user_password,
						'custom_user_ratings'			=> 1
					)
				);
			
				$cpt_id = wp_insert_post( $my_cptpost_args );
				
				wp_set_post_terms($cpt_id  , $custom_user_cat, 'user_category' );

				set_post_thumbnail($cpt_id,$upload_id);

			} else {
				echo wp_json_encode(array("Success"=> 0));
			}
			wp_die();
		}

		/**
		 * AJAX funciton for the User Name verification 
		 * 
		 * @since    1.0.0
		 */
		public function custom_username_data_verification_callback(){
			if(isset( $_POST['nonce'] ) && !empty( $_POST['nonce'] )){
				if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce') ) {
					die();
				}
			}
			if(isset( $_POST['userName'] ) && !empty( $_POST['userName'] )){
				if(username_exists( $_POST['userName'] )){
					echo "User Name Already Exist";
				}
			}
			wp_die( );
		}

		/**
		 * AJAX funciton for the verification
		 * 
		 * @since    1.0.0
		 */
		public function custom_email_data_verification_callback(){
			if(isset( $_POST['nonce'] ) && !empty( $_POST['nonce'] )){
				if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce') ) {
					die();
				}
			}
			if(isset( $_POST['email'] ) && !empty( $_POST['email'] )){
				if(email_exists( $_POST['email'] )){
					echo "Email Already Exist";
				}
			}
			wp_die( );
		}


		/**
		 * Call Back function for filter list
		 * 
		 * @since    1.0.0
		 */
		public function custom_user_search_tool_list_handler( $attr ){
			$shortcode_args = shortcode_atts( array(
				'category' => ""
			), $attr );

			if(isset( $_GET['nonce'] ) && !empty( $_GET['nonce'] )){
				if (!wp_verify_nonce($_GET['nonce'], 'ajax-nonce') ) {
					die();
				}
			}
			$skills = get_option('custom-user-admin-page__skill--list');
			
			$skills_new_array = explode(",",$skills);
		
			ob_start(); ?>
			<div class="custom-user-tool__container">
				<div id="custom-user-tool__search--form" class="custom-user-tool__search--wrapper">
					<div class="custom-user-tool__search--field">
						<label for="custom-user-tool__search--keyword"> Search User by Keyword</label>
						<input type="text" id="custom-user-tool__search--keyword" class="custom-user-tool__search--keyword user_input">
					</div>
					<div class="custom-user-tool__search--field">
						<label for="custom-user-tool__search--dob"> Search User by Date Of Birth</label>
						<div class="custom-user-tool__search--dob-wrapper">
							<div class="custom-user-tool__search--date--wrapper">
								<span>From: </span>
								<input type="date" id="custom-user-tool__search--dobfrom" class="custom-user-tool__search--dobfrom">
							</div>
							<div class="custom-user-tool__search--date--wrapper">
								<span>To: </span>
								<input type="date" id="custom-user-tool__search--dobto" class="custom-user-tool__search--dobto">
							</div>
						</div>
					</div>
					<div class="custom-user-tool__search--field">
						<select name="custom-user-tool__search--skill" id="custom-user-tool__search--skill">
							<option value="">Search User by Skill</option>
							<?php 
								foreach( $skills_new_array as $ops ){ ?>
									<option value="<?php echo esc_attr($ops)?>"><?php echo esc_html($ops)?></option>
							<?php	}
							?>	
						</select>
					</div>
					<div class="custom-user-tool__search--field">
						<?php 
						
						$args = array(
							'show_option_all'	=> "Search User by Category",
							'orderby'           => 'id',
							'order'             => 'ASC',
							'show_count'        => 0,
							'hide_empty'        => 0,
							'child_of'          => 0,
							'echo'              => 1,
							'selected'          => $shortcode_args['category'],
							'hierarchical'      => 0,
							'name'              => 'custom_user_cat_public',
							'id'                => 'custom_user_cat_public',
							'class'             => 'custom_user_cat_public',
							'depth'             => 0,
							'tab_index'         => 0,
							'taxonomy'          => array('user_category'),
							'hide_if_empty'     => true,
							'option_none_value' => -1,
							'value_field'       => 'term_id',
						);
						
						wp_dropdown_categories( $args );				
						?>
					</div>
					<div class="custom-user-tool__search--field">
						<label for="custom-user-tool__search--ratings"> Search User by Ratings:</label>
						<span id="custom-user-tool__search--ratingsvalue">1</span>/5
						<input type="range" id="custom-user-tool__search--ratings" min="1" max="5" class="custom-user-tool__search--ratings" value="1">
					</div>
					<button id="custom-user-tool__search--submit" class="custom-user-tool__search--submit">
						Search User
					</button>
				</div>
				<div class="custom-user-tool__list">
					<div class="custom-user-tool__list--wrapper">
						<?php 
							$args = array(
								'post_type' 		=> "custom_user",
								'post_status'		=> array('draft','publish'),
								'orderby'           => 'title',
								'order'             => 'ASC',
								'paged'       		=> 1,
							);

							if(isset($shortcode_args['category']) && !empty($shortcode_args['category'])){
								$args['tax_query']=array(
										array (
											'taxonomy' => 'user_category',
											'field' => 'ID',
											'terms' => $shortcode_args['category'],
										)
									);
							}
	
							$query = new WP_Query($args);

							if( $query->have_posts(  ) ):
								while( $query->have_posts(  ) ):
									$query->the_post(); 
									$current_post_id = esc_html(get_the_ID(  ));
									$name = get_the_title( $current_post_id );
									$dob = get_post_meta( $current_post_id,  'custom_user_dob', true );
									$email = get_post_meta( $current_post_id,  'custom_user_email', true );
									$skills_item = get_post_meta( $current_post_id,  'custom_user_skills', true );
									$ratings = get_post_meta( $current_post_id, 'custom_user_ratings', true );
									if(!is_array($skills_item)){
										$skills_item = explode(",",$skills_item);
									}
									$current_post_Status = esc_html(get_post_status($current_post_id));
									?>
									
									<a href="<?php echo esc_url(get_the_permalink($current_post_id)) ?>" class="custom-user-tool__list--link" data-dob="<?php echo esc_attr($dob)?>">
										<div class="custom-user-tool__list--item">
											<?php
												if (has_post_thumbnail( $current_post_id ) ){
												$image = wp_get_attachment_image_src( get_post_thumbnail_id( $current_post_id ), 'single-post-thumbnail' );
													?>
													<img src="<?php echo esc_url($image[0]); ?>" class="custom-user__avatar" alt="User Avatar">
													<?php
											}?>
											<h6 class="custom-user__name"><span>Name : </span><?php echo esc_html($name)?></h6>
											<p> <span>User Status</span> : <?php 
												if($current_post_Status=="draft"){
													echo "Not Verified";
												} else {
													echo "Verified";
												}
											?>
											</p>
											<p class="custom-user__dob"><span>DOB : </span><?php echo esc_html($dob)?></p>
											<p class="custom-user__email"><span>Email : </span><?php echo esc_html($email)?></p>
											<div class="custom-user__ratings">
											<p>Ratings: </p>
												<?php 
													for($i = 0; $i< $ratings ;$i++){ ?>
														<span>★</span>
													<?php }
												?>
											</div>
											<div class="custom-user__skills">
												<span>Skills : </span>
												<ul>
													<?php foreach($skills_item as $skill_name ){ ?>
														<li><?php echo esc_html( $skill_name ) ?></li>
													<?php } ?>
												</ul>
											</div>
										</div>
									</a>

							<?php	endwhile;
							wp_reset_query(  );
							endif;
						?>
					</div>
					<?php
						$total_pages = $query->max_num_pages;
						if ( $total_pages > 1 ) {
						?>
							<div class="custom-user-pagination-section">
								<div class="custom-user-pagination-leftarrow">
									<svg xmlns="http://www.w3.org/2000/svg" width="16.084" height="26.635" class="home-testimonial__left-arrow" viewBox="0 0 16.084 26.635">
										<path id="Path_156" data-name="Path 156" d="M707.492,845.393l12-12,12,12" transform="matrix(0.035, -0.999, 0.999, 0.035, -855.42, 703.096)" fill="none" stroke-width="3"/>
									</svg>
								</div>
								<div class="custom-pagination" id="custom-pagination" >
									<?php for ( $i = 1; $i <= $total_pages; $i++ ) { ?>
										<span class='page-numbers page-number<?php echo $i; ?>' page-no=<?php echo esc_attr( $i ); ?> ><?php echo esc_html( $i ); ?></span>
									<?php } ?>
								</div>
								<div class="custom-user-pagination-rightarrow">
									<svg xmlns="http://www.w3.org/2000/svg" width="15.182" height="26.121" class="home-testimonial__right-arrow" viewBox="0 0 15.182 26.121">
										<path id="Path_156" data-name="Path 156" d="M707.492,845.393l12-12,12,12" transform="translate(846.454 -706.432) rotate(90)" fill="none" stroke-width="3"/>
									</svg>
								</div>
							</div>
						<?php } ?>
				</div>
			</div>
		<?php
			$html = ob_get_clean();
			return $html;
		}

		/**
		 * AJAX function for filter list
		 * 
		 * @since    1.0.0
		 */
		public function custom_search_listing_data_callback(){

			if(isset( $_POST['nonce'] ) && !empty( $_POST['nonce'] )){
				if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce') ) {
					die();
				}
			}
			
			$page_no = filter_input( INPUT_POST, 'page_no', FILTER_SANITIZE_STRING );

			$meta_query = array('relation' => 'AND');
			$args = array(
				'post_type' 		=> "custom_user",
				'post_status'		=> array('draft','publish'),
				'orderby'           => 'title',
				'order'             => 'ASC',
				'paged'       		=> $page_no,
			);

			if (isset( $_POST['keyWord'] ) && !empty( $_POST['keyWord'] )) {
				$custom_user_keyword =  sanitize_text_field($_POST['keyWord']);
				$args['s'] = $custom_user_keyword;
			}

			if (isset( $_POST['category'] ) && !empty( $_POST['category'] )) {
				$custom_category =  $_POST['category'];
				$args['tax_query'] = array(
					array (
						'taxonomy' => 'user_category',
						'field' => 'ID',
						'terms' => $custom_category,
					)
				);
			}

			if (isset( $_POST['skills'] ) && !empty( $_POST['skills'] )) {
				$custom_skills =  sanitize_text_field($_POST['skills']);
				$args['meta_query'][] = array(
					'key' => 'custom_user_skills',
					'compare' => 'LIKE',
					'value' => $custom_skills,
				);
			}

			if (isset( $_POST['ratings'] ) && !empty( $_POST['ratings'] )) {
				$custom_ratings =  sanitize_text_field($_POST['ratings']);
				$args['meta_query'][] = array(
					'key' => 'custom_user_ratings',
					'compare' => '=',
					'value' => $custom_ratings,
				);
			}

			if (isset( $_POST['dobfrom'] ) && !empty( $_POST['dobfrom'] ) && isset( $_POST['dobto'] ) && !empty( $_POST['dobto'] )) {
				$dob_from =  sanitize_text_field($_POST['dobfrom']);
				$dob_to =  sanitize_text_field($_POST['dobto']);
				$args['meta_query'][] = array(
					'key' 	=> 'custom_user_dob', 
					'value' => array($dob_from, $dob_to),
					'compare' => 'BETWEEN', 
					'type' => 'DATE',
				);
			}

			$query = new WP_Query($args);

			if( $query->have_posts(  ) ):
				?>
				<div class="custom-user-tool__list" current_page='<?php echo esc_attr( $page_no ); ?>'>
					<div class="custom-user-tool__list--wrapper">
						<?php
							while( $query->have_posts(  ) ):
								$query->the_post(); 
									$current_post_id = esc_html(get_the_ID(  ));
									$name = get_the_title( $current_post_id );
									$dob = get_post_meta( $current_post_id,  'custom_user_dob', true );
									$email = get_post_meta( $current_post_id,  'custom_user_email', true );
									$skills_item = get_post_meta( $current_post_id,  'custom_user_skills', true );
									$ratings = get_post_meta( $current_post_id, 'custom_user_ratings', true );
									if(!is_array($skills_item)){
										$skills_item = explode(",",$skills_item);
									}
									?>
									
									<a href="<?php echo esc_url(get_the_permalink($current_post_id)) ?>" class="custom-user-tool__list--link" data-dob="<?php echo esc_attr($dob)?>">
										<div class="custom-user-tool__list--item">
											<?php
												if (has_post_thumbnail( $current_post_id ) ){
												$image = wp_get_attachment_image_src( get_post_thumbnail_id( $current_post_id ), 'single-post-thumbnail' );
													?>
													<img src="<?php echo esc_url($image[0]); ?>" class="custom-user__avatar" alt="User Avatar">
													<?php
											}?>
											<h6 class="custom-user__name"><span>Name : </span><?php echo esc_html($name)?></h6>
											<p> <span>User Status</span> : <?php 
												if($current_post_Status=="draft"){
													echo "Not Verified";
												} else {
													echo "Verified";
												}
											?>
											<p class="custom-user__dob"><span>DOB : </span><?php echo esc_html($dob)?></p>
											<p class="custom-user__email"><span>Email : </span><?php echo esc_html($email)?></p>
											<div class="custom-user__ratings">
											<p>Ratings: </p>
												<?php 
													for($i = 0; $i< $ratings ;$i++){ ?>
														<span>★</span>
													<?php }
												?>
											</div>
											<div class="custom-user__skills">
												<span>Skills : </span>
												<ul>
													<?php foreach($skills_item as $skill_name ){ ?>
														<li><?php echo esc_html( $skill_name ) ?></li>
													<?php } ?>
												</ul>
											</div>
										</div>
									</a>
							<?php	endwhile;
						?>
					</div>
					<?php
						$total_pages = $query->max_num_pages;
						if ( $total_pages > 1 ) {
						?>
							<div class="custom-user-pagination-section">
								<div class="custom-user-pagination-leftarrow">
									<svg xmlns="http://www.w3.org/2000/svg" width="16.084" height="26.635" class="home-testimonial__left-arrow" viewBox="0 0 16.084 26.635">
										<path id="Path_156" data-name="Path 156" d="M707.492,845.393l12-12,12,12" transform="matrix(0.035, -0.999, 0.999, 0.035, -855.42, 703.096)" fill="none" stroke-width="3"/>
									</svg>
								</div>
								<div class="custom-pagination" id="custom-pagination" >
									<?php for ( $i = 1; $i <= $total_pages; $i++ ) { ?>
										<span class='page-numbers page-number<?php echo $i; ?>' page-no=<?php echo esc_attr( $i ); ?> ><?php echo esc_html( $i ); ?></span>
									<?php } ?>
								</div>
								<div class="custom-user-pagination-rightarrow">
									<svg xmlns="http://www.w3.org/2000/svg" width="15.182" height="26.121" class="home-testimonial__right-arrow" viewBox="0 0 15.182 26.121">
										<path id="Path_156" data-name="Path 156" d="M707.492,845.393l12-12,12,12" transform="translate(846.454 -706.432) rotate(90)" fill="none" stroke-width="3"/>
									</svg>
								</div>
							</div>
						<?php } ?>
				</div>
			<?php
			else: ?>
				<div class="custom-user-tool__list">
					<div class="custom-user-tool__list--nodata">Sorry No Data Avaliable !!</div>
				</div>
				<?php
			endif;
			wp_die();
		}

	}

	$Custom_User_Insertion_Public = new Custom_User_Insertion_Public;

}