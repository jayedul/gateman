<?php

	use DevJK\SLR\Enums\Pages;
	use DevJK\SLR\Models\Logon;
	use DevJK\SLR\Models\Settings;
	use DevJK\SLR\Setup\Shortcode;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

	// Comes from render function: $current_page, $error_message, $field_data, $fields, $submit, $reg_enabled
?>

<?php if ( is_user_logged_in() && (int) ( sanitize_text_field( Shortcode::$input['reauth'] ?? '' ) ) !== 1 ) : ?>
	<form class="slr-logon-form slr-logged-in">
		<div class="slr-logged-in">
			<?php
				echo esc_html( apply_filters( 'slr_already_logged_in_message', __( 'You are already logged in.', 'simple-login-registration' ) ) );
			?>
			 <br/>
			<?php
				// translators: 1: Acnhor tag start, 2: Anchor tag end
				echo sprintf( esc_html__( 'Want to %1$sLogout%2$s?', 'simple-login-registration' ), '<a href="' . esc_attr( wp_logout_url() ) . '">', '</a>' );
			?>
		</div>
	</form>
	<?php
	return;
endif;
?>

<?php if ( ! $reg_enabled && $current_page === Pages::REGISTRATION->value ) : ?>
	<form class="slr-logon-form slr-registration-disabled">
		<div class="slr-logged-in">
			<?php echo esc_html( apply_filters( 'slr_reg_disabled_message', __( 'Registration is disabled.', 'simple-login-registration' ) ) ); ?>
		</div>
	</form>
	<?php
	return;
endif;
?>

<form class="slr-logon-form <?php echo esc_attr( $current_page ); ?>" method="POST" enctype="multipart/form-data">

	<?php do_action( 'slr_fields_before', $current_page ); ?>

	<?php foreach ( $fields as $slr_name => $slr_field ) : ?>
		<?php do_action( 'slr_field_before_' . $slr_name, $current_page ); ?>
		<div>
			<label for="<?php echo esc_attr( 'slr_field_id_' . $slr_name ); ?>">
		<?php echo esc_html( apply_filters( 'slr_field_label_' . $slr_name, $slr_field['label'], $current_page ) ); ?>
			</label>
			<input 
				id="<?php echo esc_attr( 'slr_field_id_' . $slr_name ); ?>"
				name="<?php echo esc_attr( $slr_name ); ?>"
				value="<?php $slr_field['type'] !== 'password' ? esc_attr( sanitize_text_field( Shortcode::$input[ $slr_name ] ?? '' ) ) : ''; ?>"
				type="<?php echo esc_attr( $slr_field['type'] ); ?>" 
				placeholder="<?php echo esc_attr( apply_filters( 'slr_field_placeholder_' . $slr_name, $slr_field['placeholder'], $current_page ) ); ?>"
		<?php echo ( $slr_field['disabled'] ?? false ) === true ? 'disabled="disabled"' : ''; ?>
			/>
		</div>
		<?php do_action( 'slr_field_after' . $slr_name, $current_page ); ?>
	<?php endforeach; ?>

	<?php do_action( 'slr_fields_after', $current_page ); ?>

	<?php if ( empty( $fields ) ) : ?>
		<span>
			<?php echo esc_html( 'Invalid form', 'simple-login-registration' ); ?>
		</span>
	<?php else : ?>

		<?php if ( ! empty( $error_message ) ) : ?>
			<div class="slr-error-message">
				<?php echo esc_html( wp_strip_all_tags( $error_message ) ); ?>
			</div>
		<?php endif; ?>
	
		<input type="hidden" name="slr_form_submit" value="<?php echo esc_attr( $current_page ); ?>"/>
		<?php wp_nonce_field(); ?>

		<div>
			<button type="submit">
				<?php echo esc_html( apply_filters( 'slr_submit_button_label', $submit['label'], $current_page ) ); ?>
			</button>
		</div>
	<?php endif; ?>

	<?php do_action( 'slr_submit_button_after', $current_page ); ?>

	<div class="slr-form-nav-links">
		<?php if ( $current_page === Pages::LOGIN->value ) : ?>
			<div>
				<a href="<?php echo esc_attr( Settings::getPagePermalink( Pages::RECOVER_PASSWORD ) ); ?>">
			<?php esc_html_e( 'Forgot password?', 'simple-login-registration' ); ?>
				</a>
			</div>
			<div>
			<?php if ( $reg_enabled ) : ?>
					<a href="<?php echo esc_attr( Settings::getPagePermalink( Pages::REGISTRATION ) ); ?>">
				<?php esc_html_e( 'Create account', 'simple-login-registration' ); ?>
					</a>
			<?php endif; ?>
			</div>
		<?php elseif ( $current_page === Pages::REGISTRATION->value ) : ?>
			<div>
			<?php esc_html_e( 'Have an account account?', 'simple-login-registration' ); ?> <a href="<?php echo esc_attr( Settings::getPagePermalink( Pages::LOGIN ) ); ?>">
			<?php esc_html_e( 'Login Now', 'simple-login-registration' ); ?>
				</a>
			</div>
		<?php elseif ( $current_page === Pages::RECOVER_PASSWORD->value || $current_page === Pages::RESET_PASSWORD->value ) : ?>
			<div>
				<?php esc_html_e( 'Remembered password?', 'simple-login-registration' ); ?> <a href="<?php echo esc_attr( Settings::getPagePermalink( Pages::LOGIN ) ); ?>">
				<?php esc_html_e( 'Login Now', 'simple-login-registration' ); ?>
					</a>
			</div>
		<?php endif; ?>
	</div>
	

	<?php do_action( 'slr_nav_links_after', $current_page ); ?>
</form>
