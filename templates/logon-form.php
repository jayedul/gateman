<?php

	use DevJK\SLR\Models\Logon;

	if ( ! defined( 'ABSPATH' ) ) exit;

	// $current_form, $error_message

	$field_data = Logon::getFields( $current_form );
	$fields     = $field_data['fields'] ?? array();
	$submit     = $field_data['submit'] ?? array();
?>

<?php if( is_user_logged_in() ): ?>
	<form class="slr-logon-form <?php esc_attr_e( $current_form ); ?> slr-logged-in">
		<div class="slr-logged-in">
			<?php echo esc_html( apply_filters( 'slr_already_logged_in_message', __( 'You are already logged in.', 'slr' ) ) ); ?>
		</div>
	</form>
<?php return; endif; ?>

<form class="slr-logon-form <?php esc_attr_e( $current_form ); ?>" method="POST" enctype="multipart/form-data">

	<?php do_action( 'slr_fields_before', $current_form ); ?>

	<?php foreach ( $fields as $name => $field ): ?>
		<?php do_action( 'slr_field_before_' . $name, $current_form ); ?>
		<div>
			<label><?php echo esc_html( apply_filters( 'slr_field_label_' . $name, $field['label'], $current_form ) ); ?></label>
			<input 
				name="<?php esc_attr_e( $name ); ?>"
				value="<?php $field['type'] !== 'password' ? esc_attr_e( sanitize_text_field( $_POST[ $name ] ?? '' ) ) : ''; ?>"
				type="<?php esc_attr_e( $field['type'] ); ?>" 
				placeholder="<?php esc_attr_e( apply_filters( 'slr_field_placeholder_' . $name, $field['placeholder'], $current_form ) ); ?>"
			/>
		</div>
		<?php do_action( 'slr_field_after' . $name, $current_form ); ?>
	<?php endforeach; ?>

	<?php do_action( 'slr_fields_after', $current_form ); ?>

	<?php if ( empty( $fields ) ): ?>
		<span><?php echo esc_html( 'Invalid form', 'slr' ); ?></span>
	<?php else: ?>

		<?php if ( ! empty( $error_message ) ): ?>
			<div class="slr-error-message">
				<?php echo esc_html( strip_tags( $error_message ) ); ?>
			</div>
		<?php endif; ?>
		
		<div>
			<input type="hidden" name="slr_form_submit" value="yes"/>
			<button type="submit">
				<?php echo esc_html( apply_filters( 'slr_submit_button_label', $submit['label'], $current_form ) ); ?>
			</button>
		</div>
	<?php endif; ?>

	<?php do_action( 'slr_submit_button_after', $current_form ); ?>
</form>