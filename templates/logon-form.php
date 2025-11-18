<?php

	use DevJK\Gateman\Enums\Pages;
	use DevJK\Gateman\Models\Logon;
	use DevJK\Gateman\Models\Settings;
	use DevJK\Gateman\Setup\Shortcode;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

	// Comes from render function: $current_page, $error_message, $field_data, $fields, $submit, $reg_enabled
?>

<?php if ( is_user_logged_in() && (int) ( sanitize_text_field( Shortcode::$input['reauth'] ?? '' ) ) !== 1 ) : ?>
	<form class="gateman-logon-form gateman-logged-in">
		<div class="gateman-logged-in">
			<?php
				echo esc_html( apply_filters( 'gateman_already_logged_in_message', __( 'You are already logged in.', 'gateman' ) ) );
			?>
			<br/>
			<?php
				// translators: 1: Acnhor tag start, 2: Anchor tag end
				echo sprintf( esc_html__( 'Want to %1$sLogout%2$s?', 'gateman' ), '<a href="' . esc_attr( wp_logout_url() ) . '">', '</a>' );
			?>
		</div>
	</form>
	<?php
	return;
endif;
?>

<?php if ( ! $reg_enabled && $current_page === Pages::REGISTRATION->value ) : ?>
	<form class="gateman-logon-form gateman-registration-disabled">
		<div class="gateman-logged-in">
			<?php echo esc_html( apply_filters( 'gateman_reg_disabled_message', __( 'Registration is disabled.', 'gateman' ) ) ); ?>
		</div>
	</form>
	<?php
	return;
endif;
?>

<form class="gateman-logon-form 
<?php
echo esc_attr( $current_page ) . ' ';
echo Settings::getOption( 'use_gateman_css', true ) ? 'gateman-use-css' : '';
?>
" method="POST" enctype="multipart/form-data">

	<?php do_action( 'gateman_fields_before', $current_page ); ?>

	<?php foreach ( $fields as $gateman_name => $gateman_field ) : ?>
		<?php do_action( 'gateman_field_before_' . $gateman_name, $current_page ); ?>
		<div>
			<label for="<?php echo esc_attr( 'gateman_field_id_' . $gateman_name ); ?>">
		<?php echo esc_html( apply_filters( 'gateman_field_label_' . $gateman_name, $gateman_field['label'], $current_page ) ); ?>
			</label>
			<input 
				id="<?php echo esc_attr( 'gateman_field_id_' . $gateman_name ); ?>"
				class="gateman-input"
				name="<?php echo esc_attr( $gateman_name ); ?>"
				value="<?php $gateman_field['type'] !== 'password' ? esc_attr( sanitize_text_field( Shortcode::$input[ $gateman_name ] ?? '' ) ) : ''; ?>"
				type="<?php echo esc_attr( $gateman_field['type'] ); ?>" 
				placeholder="<?php echo esc_attr( apply_filters( 'gateman_field_placeholder_' . $gateman_name, $gateman_field['placeholder'], $current_page ) ); ?>"
		<?php echo ( $gateman_field['disabled'] ?? false ) === true ? 'disabled="disabled"' : ''; ?>
			/>
		</div>
		<?php do_action( 'gateman_field_after' . $gateman_name, $current_page ); ?>
	<?php endforeach; ?>

	<?php do_action( 'gateman_fields_after', $current_page ); ?>

	<?php if ( empty( $fields ) ) : ?>
		<span>
			<?php echo esc_html( 'Invalid form', 'gateman' ); ?>
		</span>
	<?php else : ?>

		<?php if ( ! empty( $error_message ) ) : ?>
			<div class="gateman-error-message">
				<?php echo esc_html( wp_strip_all_tags( $error_message ) ); ?>
			</div>
		<?php endif; ?>
	
		<input type="hidden" name="gateman_form_submit" value="<?php echo esc_attr( $current_page ); ?>"/>
		<?php wp_nonce_field(); ?>

		<div>
			<button type="submit" class="gateman-button">
				<?php echo esc_html( apply_filters( 'gateman_submit_button_label', $submit['label'], $current_page ) ); ?>
			</button>
		</div>
	<?php endif; ?>

	<?php do_action( 'gateman_submit_button_after', $current_page ); ?>

	<div class="gateman-form-nav-links">
		<?php if ( $current_page === Pages::LOGIN->value ) : ?>
			<div>
				<a href="<?php echo esc_attr( Settings::getPagePermalink( Pages::RECOVER_PASSWORD ) ); ?>">
			<?php esc_html_e( 'Forgot password?', 'gateman' ); ?>
				</a>
			</div>
			<div>
			<?php if ( $reg_enabled ) : ?>
					<a href="<?php echo esc_attr( Settings::getPagePermalink( Pages::REGISTRATION ) ); ?>">
				<?php esc_html_e( 'Create account', 'gateman' ); ?>
					</a>
			<?php endif; ?>
			</div>
		<?php elseif ( $current_page === Pages::REGISTRATION->value ) : ?>
			<div>
			<?php esc_html_e( 'Have an account account?', 'gateman' ); ?> <a href="<?php echo esc_attr( Settings::getPagePermalink( Pages::LOGIN ) ); ?>">
			<?php esc_html_e( 'Login Now', 'gateman' ); ?>
				</a>
			</div>
		<?php elseif ( $current_page === Pages::RECOVER_PASSWORD->value || $current_page === Pages::RESET_PASSWORD->value ) : ?>
			<div>
				<?php esc_html_e( 'Remembered password?', 'gateman' ); ?> <a href="<?php echo esc_attr( Settings::getPagePermalink( Pages::LOGIN ) ); ?>">
				<?php esc_html_e( 'Login Now', 'gateman' ); ?>
					</a>
			</div>
		<?php endif; ?>
	</div>
	

	<?php do_action( 'gateman_nav_links_after', $current_page ); ?>
</form>
