<?php

use DevJK\SLR\Enums\Pages;
use DevJK\SLR\Models\Logon;
use DevJK\SLR\Models\Settings;

if (! defined('ABSPATH') ) { exit;
}

    // $current_form, $error_message

    $field_data   = Logon::getFields($current_form);
    $current_page = 'slr_' . $current_form;
    $fields       = $field_data['fields'] ?? array();
    $submit       = $field_data['submit'] ?? array();
    $reg_enabled  = ! empty(get_option('users_can_register'));
?>

<?php if(is_user_logged_in() && (int) ( sanitize_text_field($_GET['reauth'] ?? '') ) !== 1 ) : ?>
    <form class="slr-logon-form <?php esc_attr_e($current_form); ?> slr-logged-in">
        <div class="slr-logged-in">
    <?php echo esc_html(apply_filters('slr_already_logged_in_message', __('You are already logged in.', 'slr'))); ?>
        </div>
    </form>
    <?php return; 
endif; ?>

<?php if(! $reg_enabled && $current_page === Pages::REGISTRATION->value ) : ?>
    <form class="slr-logon-form <?php esc_attr_e($current_form); ?> slr-logged-in">
        <div class="slr-logged-in">
    <?php echo esc_html(apply_filters('slr_reg_disabled_message', __('Registration is disabled.', 'slr'))); ?>
        </div>
    </form>
    <?php return; 
endif; ?>

<form class="slr-logon-form <?php esc_attr_e($current_form); ?>" method="POST" enctype="multipart/form-data">

    <?php do_action('slr_fields_before', $current_form); ?>

    <?php foreach ( $fields as $name => $field ): ?>
        <?php do_action('slr_field_before_' . $name, $current_form); ?>
        <?php $label_id = 'slr_field_id_' . $name; ?>
        <div>
            <label for="<?php esc_attr_e($label_id) ?>">
        <?php echo esc_html(apply_filters('slr_field_label_' . $name, $field['label'], $current_form)); ?>
            </label>
            <input 
                id="<?php esc_attr_e($label_id) ?>"
                name="<?php esc_attr_e($name); ?>"
                value="<?php $field['type'] !== 'password' ? esc_attr_e(sanitize_text_field($_POST[ $name ] ?? $_GET[ $name ] ?? '')) : ''; ?>"
                type="<?php esc_attr_e($field['type']); ?>" 
                placeholder="<?php esc_attr_e(apply_filters('slr_field_placeholder_' . $name, $field['placeholder'], $current_form)); ?>"
        <?php echo ( $field['disabled'] ?? false ) === true ? 'disabled="disabled"' : ''; ?>
            />
        </div>
        <?php do_action('slr_field_after' . $name, $current_form); ?>
    <?php endforeach; ?>

    <?php do_action('slr_fields_after', $current_form); ?>

    <?php if (empty($fields) ) : ?>
        <span><?php echo esc_html('Invalid form', 'slr'); ?></span>
    <?php else: ?>

        <?php if (! empty($error_message) ) : ?>
            <div class="slr-error-message">
            <?php echo esc_html(strip_tags($error_message)); ?>
            </div>
        <?php endif; ?>
        
        <div>
            <input type="hidden" name="slr_form_submit" value="yes"/>
            <button type="submit">
        <?php echo esc_html(apply_filters('slr_submit_button_label', $submit['label'], $current_form)); ?>
            </button>
        </div>
    <?php endif; ?>

    <?php do_action('slr_submit_button_after', $current_form); ?>

    <div class="slr-form-nav-links">
        <?php if ($current_page === Pages::LOGIN->value ) : ?>
            <div>
                <a href="<?php echo Settings::getPagePermalink(Pages::RECOVER_PASSWORD); ?>">
            <?php esc_html_e('Forgot password?', 'slr'); ?>
                </a>
            </div>
            <div>
            <?php if ($reg_enabled ) : ?>
                    <a href="<?php echo Settings::getPagePermalink(Pages::REGISTRATION); ?>">
                <?php esc_html_e('Create account', 'slr'); ?>
                    </a>
            <?php endif; ?>
            </div>
        <?php elseif($current_page === Pages::REGISTRATION->value ) : ?>
            <div>
            <?php esc_html_e('Have an account account?', 'slr'); ?> <a href="<?php echo Settings::getPagePermalink(Pages::LOGIN); ?>">
            <?php esc_html_e('Login Now', 'slr'); ?>
                </a>
            </div>
        <?php elseif($current_page === Pages::RECOVER_PASSWORD->value || $current_page === Pages::RESET_PASSWORD->value ) : ?>
            <div>
				<?php esc_html_e('Remembered password?', 'slr'); ?> <a href="<?php echo Settings::getPagePermalink(Pages::LOGIN); ?>">
				<?php esc_html_e('Login Now', 'slr'); ?>
					</a>
            </div>
        <?php endif; ?>
    </div>
    

    <?php do_action('slr_nav_links_after', $current_form); ?>
</form>