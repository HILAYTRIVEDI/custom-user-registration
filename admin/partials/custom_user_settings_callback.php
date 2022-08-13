<div class="custom-user-admin-page">

    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#form_field_settings">Form Field Settings</a></li>
        <li><a data-toggle="tab" href="#general_settings">General Settings</a></li>
    </ul>
   
    <div class="tab-content">
        <div id="form_field_settings" class="tab-pane fade in active">
            <div class="custom-user-admin-page__firstname custom-user-admin-page-wrapper">
                <label for="" class="custom-user-admin-page__firstname--label">Remove the first name field</label> 
                <?php if (get_option( 'custom-user-admin-page__switch--checkbox') == "yes") {
                    ?>
                    <label class="custom-user-admin-page__switch">
                        <input name="custom-user-admin-page__switch--checkbox" type="checkbox" value="yes" checked>
                        <span class="slider round"></span>
                    </label>
                    <?php
                } else {
                    ?>
                    <label class="custom-user-admin-page__switch">
                        <input name="custom-user-admin-page__switch--checkbox" type="checkbox" value="yes">
                        <span class="slider round"></span>
                    </label>
                    <?php
                }
                ?>
            </div>
            <div class="custom-user-admin-page__lastname custom-user-admin-page-wrapper">
                <label for="" class="custom-user-admin-page__lastname--label">Remove the last name field</label> 
                <?php if (get_option( 'custom-user-admin-page__lastname-switch--checkbox') == "yes") {
                    ?>
                    <label class="custom-user-admin-page__switch">
                        <input name="custom-user-admin-page__lastname-switch--checkbox" type="checkbox" value="yes" checked>
                        <span class="slider round"></span>
                    </label>
                    <?php
                } else {
                    ?>
                    <label class="custom-user-admin-page__switch">
                        <input name="custom-user-admin-page__lastname-switch--checkbox" type="checkbox" value="yes">
                        <span class="slider round"></span>
                    </label>
                    <?php
                }
                ?>
            </div>
            <div class="custom-user-admin-page__primary-address custom-user-admin-page-wrapper">
                <label for="" class="custom-user-admin-page__primary-address--label">Remove the primary address field</label> 
                <?php if (get_option( 'custom-user-admin-page__primary-address-switch--checkbox') == "yes") {
                    ?>
                    <label class="custom-user-admin-page__switch">
                        <input name="custom-user-admin-page__primary-address-switch--checkbox" type="checkbox" value="yes" checked>
                        <span class="slider round"></span>
                    </label>
                    <?php
                } else {
                    ?>
                    <label class="custom-user-admin-page__switch">
                        <input name="custom-user-admin-page__primary-address-switch--checkbox" type="checkbox" value="yes">
                        <span class="slider round"></span>
                    </label>
                    <?php
                }
                ?>
            </div>
            <div class="custom-user-admin-page__secondary-address custom-user-admin-page-wrapper">
                <label for="" class="custom-user-admin-page__secondary-address--label">Remove the secondary address field</label> 
                <?php if (get_option( 'custom-user-admin-page__secondary-address-switch--checkbox') == "yes") {
                    ?>
                    <label class="custom-user-admin-page__switch">
                        <input name="custom-user-admin-page__secondary-address-switch--checkbox" type="checkbox" value="yes" checked>
                        <span class="slider round"></span>
                    </label>
                    <?php
                } else {
                    ?>
                    <label class="custom-user-admin-page__switch">
                        <input name="custom-user-admin-page__secondary-address-switch--checkbox" type="checkbox" value="yes">
                        <span class="slider round"></span>
                    </label>
                    <?php
                }
                ?>
            </div>
            <div class="custom-user-admin-page__postal-code custom-user-admin-page-wrapper">
                <label for="" class="custom-user-admin-page__postal-code--label">Remove the postal code field</label> 
                <?php if (get_option( 'custom-user-admin-page__postal-code-switch--checkbox') == "yes") {
                    ?>
                    <label class="custom-user-admin-page__switch">
                        <input name="custom-user-admin-page__postal-code-switch--checkbox" type="checkbox" value="yes" checked>
                        <span class="slider round"></span>
                    </label>
                    <?php
                } else {
                    ?>
                    <label class="custom-user-admin-page__switch">
                        <input name="custom-user-admin-page__postal-code-switch--checkbox" type="checkbox" value="yes">
                        <span class="slider round"></span>
                    </label>
                    <?php
                }
                ?>
            </div>
            <div class="custom-user-admin-page__dob custom-user-admin-page-wrapper">
                <label for="" class="custom-user-admin-page__dob--label">Remove the date of birth field</label> 
                <?php if (get_option( 'custom-user-admin-page__dob-switch--checkbox') == "yes") {
                    ?>
                    <label class="custom-user-admin-page__switch">
                        <input name="custom-user-admin-page__dob-switch--checkbox" type="checkbox" value="yes" checked>
                        <span class="slider round"></span>
                    </label>
                    <?php
                } else {
                    ?>
                    <label class="custom-user-admin-page__switch">
                        <input name="custom-user-admin-page__dob-switch--checkbox" type="checkbox" value="yes">
                        <span class="slider round"></span>
                    </label>
                    <?php
                }
                ?>
            </div>
            <div class="custom-user-admin-page__hobbies custom-user-admin-page-wrapper">
                <label for="" class="custom-user-admin-page__hobbies--label">Remove the hobbies field</label> 
                <?php if (get_option( 'custom-user-admin-page__hobbies-switch--checkbox') == "yes") {
                    ?>
                    <label class="custom-user-admin-page__switch">
                        <input name="custom-user-admin-page__hobbies-switch--checkbox" type="checkbox" value="yes" checked>
                        <span class="slider round"></span>
                    </label>
                    <?php
                } else {
                    ?>
                    <label class="custom-user-admin-page__switch">
                        <input name="custom-user-admin-page__hobbies-switch--checkbox" type="checkbox" value="yes">
                        <span class="slider round"></span>
                    </label>
                    <?php
                }
                ?>
            </div>
            <div class="custom-user-admin-page__skills-setting custom-user-admin-page-wrapper">
                <label for="" class="custom-user-admin-page__skills-setting--label">Remove the skills field</label> 
                <?php if (get_option( 'custom-user-admin-page__skills-setting-switch--checkbox') == "yes") {
                    ?>
                    <label class="custom-user-admin-page__switch">
                        <input name="custom-user-admin-page__skills-setting-switch--checkbox" type="checkbox" value="yes" checked>
                        <span class="slider round"></span>
                    </label>
                    <?php
                } else {
                    ?>
                    <label class="custom-user-admin-page__switch">
                        <input name="custom-user-admin-page__skills-setting-switch--checkbox" type="checkbox" value="yes">
                        <span class="slider round"></span>
                    </label>
                    <?php
                }
                ?>
            </div>
        </div>   
        <div id="general_settings" class="tab-pane fade">
            <div class="custom-user-admin-page-wrapper">
                <label for="" class="custom-user-admin-page__skill--label">Please add new skill after ","</label>
                <input name="custom-user-admin-page__skill--list" id="custom-user-admin-page__skill--text" class="custom-user-admin-page__skill--text" value="<?php echo esc_attr(get_option( 'custom-user-admin-page__skill--list'))?>"> 
            </div>
            <div class="custom-user-admin-page-wrapper">
                <label for="" class="custom-user-admin-page__email--label">Please add email on which you want to receive confrmation mail</label>
                <input name="custom-user-admin-page__email" id="custom-user-admin-page__email--text" class="custom-user-admin-page__email--text" value="<?php echo esc_attr(get_option( 'custom-user-admin-page__email'))?>">
            </div>
        </div>
    </div>
</div>
