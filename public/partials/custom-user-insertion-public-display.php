<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://www.multidots.com/
 * @since      1.0.0
 *
 * @package    Custom_User_Insertion
 * @subpackage Custom_User_Insertion/public/partials
 */
?>

<div class="custom-user-registration-form-container" id="custom-user-registration-form-container">
    <form id="contact" action="#" method="post">
        <div>
            <h3>Account</h3>
            <section>
                <label for="userName">User name *</label>
                <input id="userName" name="userName" type="text" class="required user_input">
                <span id="userName-error__message"></span>
                <label for="name">First name *</label>
                <input id="name" name="name" type="text" class="required user_input">
                <label for="surname">Last name *</label>
                <input id="surname" name="surname" type="text" class="required user_input">
                <label for="email">Email *</label>
                <input id="email" name="email" type="text" class="required">
                <span id="email-error__message"></span>
                <div class="custom-user-form__password--wrapper">
                    <label for="password">Password *</label>
                    <input id="password" name="password" type="password" class="required">
                    <input type="checkbox" id="password_show"> Show Password
                </div>
                <label for="confirm_password">Confirm Password *</label>
                <input id="confirm_password" name="confirm_password" type="password" class="required">
                <p>(*) Mandatory</p>
            </section>
            <h3>Profile Photo</h3>
            <section>
                <label for="profile_photo">Please Upload Your Profile Photo</label>
                <input id="profile_photo" name="profile_photo" type="file"  accept="image/*">
                <img src="#" id="profile_photo_preview"  alt="User Avatar">
                <p>(*) Mandatory</p>
            </section>
            <h3>More Details</h3> 
            <section>
                <label for="address">Primary Address *</label>
                <input id="address" name="address" type="text" class="required">
                <label for="secondary_address">Secondary Address</label>
                <input id="secondary_address" name="secondary_address" type="text">
                <label for="user_postal">Please Enter Your Postal Code *</label>
                <input id="user_postal" name="user_postal" min="1" type="number" class="required user_input">
                <label for="date_of_birth">Date Of Birth *</label>
                <input id="date_of_birth" name="date_of_birth" type="date" class="required">
                <label for="user_hobby">What are your hoibbies ?* ( write your hobbies seperated by "," )</label>
                <input id="user_hobby" name="user_hobby" type="text" class="required user_input">
                <label for="custom_user_skill">What are your skills ? *</label>
                <?php 
                    $skills = get_option( 'custom-user-admin-page__skill--list' );
                    $skills_new_array = explode(",",$skills);
                ?>
                <select name="custom_user_skill" id="custom_user_skill" class="custom_user_skill required" name="skills[]" multiple="multiple">
                    <?php 
                        foreach( $skills_new_array as $ops ){ ?>
                            <option value="<?php echo esc_attr($ops)?>"><?php echo esc_html($ops)?></option>
                    <?php	}
                    ?>
                </select>
                <?php 
                $args = array(
                        'show_option_all'	=> "Select the category",
                        'option_none_value'	=> "Select the category",
                        'orderby'           => 'id',
                        'order'             => 'ASC',
                        'show_count'        => 0,
                        'hide_empty'        => 0,
                        'exclude'			=> 0,
                        'child_of'          => 0,
                        'echo'              => 1,
                        'selected'          => 0,
                        'hierarchical'      => 0,
                        'name'              => 'custom_user_cat',
                        'id'                => 'custom_user_cat',
                        'class'             => 'custom_user_cat',
                        'depth'             => 0,
                        'tab_index'         => 0,
                        'taxonomy'          => array('user_category'),
                        'hide_if_empty'     => true,
                        'option_none_value' => -1,
                        'value_field'       => 'term_id',
                        'required'          => false,
                        'multiple'          => true
                    );
                    
                    wp_dropdown_categories( $args );					
                ?>
                <div class="error-message" id="error-message"></div>
                <p>(*) Mandatory</p>
            </section>
        </div>
    </form>
</div>

