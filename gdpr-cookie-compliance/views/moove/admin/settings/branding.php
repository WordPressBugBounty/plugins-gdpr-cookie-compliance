<?php
/**
 * Branding File Doc Comment
 *
 * @category  Views
 * @package   gdpr-cookie-compliance
 * @author    Moove Agency
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

$gdpr_default_content = new Moove_GDPR_Content();
$option_name          = $gdpr_default_content->moove_gdpr_get_option_name();
$gdpr_options         = get_option( $option_name );
$wpml_lang            = $gdpr_default_content->moove_gdpr_get_wpml_lang();
$gdpr_options         = is_array( $gdpr_options ) ? $gdpr_options : array();

if ( isset( $_POST ) && isset( $_POST['moove_gdpr_nonce'] ) ) :
	$nonce = sanitize_key( $_POST['moove_gdpr_nonce'] );
	if ( ! wp_verify_nonce( $nonce, 'moove_gdpr_nonce_field' ) ) :
		die( 'Security check' );
	else :
		if ( is_array( $_POST ) ) :
			$gdpr_options = get_option( $option_name );
			$gdpr_options = is_array( $gdpr_options ) ? $gdpr_options : array();

			foreach ( $_POST as $form_key => $form_value ) :
				if ( 'moove_gdpr_info_bar_content' === $form_key ) :
					$value                                  = wp_kses_post( wpautop( wp_unslash( $form_value ) ) );
					$gdpr_options[ $form_key . $wpml_lang ] = $value;
				elseif ( 'moove_gdpr_floating_button_enable' !== $form_key && 'moove_gdpr_modal_powered_by_disable' !== $form_key && 'moove_gdpr_company_logo_id' !== $form_key ) :
					$value                     = sanitize_text_field( wp_unslash( $form_value ) );
					$gdpr_options[ $form_key ] = $value;
				endif;
			endforeach;
			$logo_attachment_id = false;

			if ( isset( $gdpr_options['moove_gdpr_company_logo'] ) && $gdpr_options['moove_gdpr_company_logo'] ) :
				$logo_attachment_id = attachment_url_to_postid( $gdpr_options['moove_gdpr_company_logo'] );
				if ( $logo_attachment_id && intval( $logo_attachment_id ) ) :
					$logo_attachment_id = intval( $logo_attachment_id );
				endif;
			endif;

			if ( $logo_attachment_id && intval( $logo_attachment_id ) ) :
				$gdpr_options['moove_gdpr_company_logo_id'] = $logo_attachment_id;
			else :
				$gdpr_options['moove_gdpr_company_logo_id'] = '';
			endif;

			update_option( $option_name, $gdpr_options );
			$gdpr_options = get_option( $option_name );
		endif;

		do_action( 'gdpr_cookie_filter_settings' );
		?>
		<script>
			jQuery('#moove-gdpr-setting-error-settings_updated').show();
		</script>
		<?php
	endif;
endif;
?>
<form action="<?php echo esc_url( admin_url( 'admin.php?page=moove-gdpr&tab=branding&gcat=settings' ) ); ?>" method="post" id="moove_gdpr_tab_branding_settings">
	<?php wp_nonce_field( 'moove_gdpr_nonce_field', 'moove_gdpr_nonce' ); ?>
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">
					<label for="moove_gdpr_brand_colour"><?php esc_html_e( 'Brand Colour', 'gdpr-cookie-compliance' ); ?></label>
				</th>
				<td>
					<div class="iris-colorpicker-group-cnt">
						<?php $color = isset( $gdpr_options['moove_gdpr_brand_colour'] ) && $gdpr_options['moove_gdpr_brand_colour'] ? $gdpr_options['moove_gdpr_brand_colour'] : '0C4DA2'; ?>
						<input class="iris-colorpicker regular-text" name="moove_gdpr_brand_colour" value="<?php echo esc_attr( $color ); ?>" style="background-color: <?php echo esc_attr( $color ); ?>" type="text">
						<span class="iris-selectbtn"><?php esc_html_e( 'Select', 'gdpr-cookie-compliance' ); ?></span>
					</div>
				</td>
			</tr>

			<tr>
				<th scope="row">
					<label for="moove_gdpr_company_logo"><?php esc_html_e( 'Cookie Settings Logo', 'gdpr-cookie-compliance' ); ?></label>
					<p class="description"><?php esc_html_e( 'Recommended size:', 'gdpr-cookie-compliance' ); ?><br>130 x 50 <?php esc_html_e( 'pixels', 'gdpr-cookie-compliance' ); ?></p>
					<!--  .description -->
				</th>
				<td>
					<?php
					if ( function_exists( 'wp_enqueue_media' ) ) :
						wp_enqueue_media();
					else :
						wp_enqueue_style( 'thickbox' );
						wp_enqueue_script( 'media-upload' );
						wp_enqueue_script( 'thickbox' );
					endif;
					?>
					<?php
					$plugin_dir         = moove_gdpr_get_plugin_directory_url();
					$image_url          = isset( $gdpr_options['moove_gdpr_company_logo'] ) && $gdpr_options['moove_gdpr_company_logo'] ? $gdpr_options['moove_gdpr_company_logo'] : $plugin_dir . 'dist/images/gdpr-logo.png';
					$logo_attachment_id = isset( $gdpr_options['moove_gdpr_company_logo_id'] ) && $gdpr_options['moove_gdpr_company_logo_id'] ? $gdpr_options['moove_gdpr_company_logo_id'] : '';
					?>
					<span class="moove_gdpr_company_logo_holder" style="background-image: url(<?php echo esc_url( $image_url ); ?>);"></span><br /><br />
					<input class="regular-text code" type="text" name="moove_gdpr_company_logo" value="<?php echo esc_url( $image_url ); ?>" required> <br /><br />
					<input type="hidden" name="moove_gdpr_company_logo_id" value='<?php echo esc_attr( $logo_attachment_id ); ?>'>
					<a href="#" class="button moove_gdpr_company_logo_upload">Upload Logo</a>
					<script>
						jQuery(document).ready(function($) {
							$('.moove_gdpr_company_logo_upload, .moove_gdpr_company_logo_holder').click(function(e) {
								e.preventDefault();

								var custom_uploader = wp.media({
									title: 'GDPR Modal - Company Logo',
									button: {
										text: 'Upload Logo'
									},
									multiple: false  // Set this to true to allow multiple files to be selected
								})
								.on('select', function() {
									var attachment = custom_uploader.state().get('selection').first().toJSON();
									$('.moove_gdpr_company_logo_holder').css('background-image', 'url('+attachment.url+')');
									$('input[name=moove_gdpr_company_logo]').val(attachment.url);
								})
								.open();
							});
						});
					</script>
				</td>
			</tr>

			<tr>
				<th scope="row">
					<label for="moove_gdpr_button_style"><?php esc_html_e( 'Button Style', 'gdpr-cookie-compliance' ); ?></label>
				</th>
				<td>
					<input name="moove_gdpr_button_style" type="radio" value="rounded" id="moove_gdpr_button_style_rounded" <?php echo isset( $gdpr_options['moove_gdpr_button_style'] ) ? ( 'rounded' === $gdpr_options['moove_gdpr_button_style'] ? 'checked' : '' ) : 'checked'; ?> class="on-off">
					<label for="moove_gdpr_button_style_rounded">
						<?php esc_html_e( 'Rounded corners', 'gdpr-cookie-compliance' ); ?>
					</label> 
					<span class="separator"></span>

					<input name="moove_gdpr_button_style" type="radio" value="squared" id="moove_gdpr_button_style_squared" <?php echo isset( $gdpr_options['moove_gdpr_button_style'] ) ? ( 'squared' === $gdpr_options['moove_gdpr_button_style'] ? 'checked' : '' ) : ''; ?> class="on-off">
					<label for="moove_gdpr_button_style_squared">
						<?php esc_html_e( 'Squared corners', 'gdpr-cookie-compliance' ); ?>
					</label> 
					<span class="separator"></span>

					<?php do_action( 'gdpr_cc_moove_gdpr_button_style_settings' ); ?>
				</td>
			</tr>

			<tr>
				<th scope="row">
					<label for="moove_gdpr_plugin_font_family"><?php esc_html_e( 'Choose font', 'gdpr-cookie-compliance' ); ?></label>
				</th>
				<td>
					<input name="moove_gdpr_plugin_font_type" type="radio" value="1" data-val="'Nunito', sans-serif" id="moove_gdpr_plugin_font_type_1" <?php echo isset( $gdpr_options['moove_gdpr_plugin_font_type'] ) ? ( '1' === $gdpr_options['moove_gdpr_plugin_font_type'] ? 'checked' : '' ) : 'checked'; ?> class="on-off"> <label for="moove_gdpr_plugin_font_type_1"><?php esc_html_e( 'Default', 'gdpr-cookie-compliance' ); ?></label> 
					<span class="separator"></span>
					<br /><br />

					<input name="moove_gdpr_plugin_font_type" type="radio" value="2" data-val="inherit" id="moove_gdpr_plugin_font_type_2" <?php echo isset( $gdpr_options['moove_gdpr_plugin_font_type'] ) ? ( '2' === $gdpr_options['moove_gdpr_plugin_font_type'] ? 'checked' : '' ) : ''; ?> class="on-off"> <label for="moove_gdpr_plugin_font_type_2"><?php esc_html_e( 'Inherit font-family from your WordPress theme', 'gdpr-cookie-compliance' ); ?></label>
					<span class="separator"></span>
					<br /><br />

					<input name="moove_gdpr_plugin_font_type" type="radio" value="3" data-val="" id="moove_gdpr_plugin_font_type_3" <?php echo isset( $gdpr_options['moove_gdpr_plugin_font_type'] ) ? ( '3' === $gdpr_options['moove_gdpr_plugin_font_type'] ? 'checked' : '' ) : ''; ?> class="on-off"> <label for="moove_gdpr_plugin_font_type_3"><?php esc_html_e( 'Specify custom font', 'gdpr-cookie-compliance' ); ?></label>
					<span class="separator"></span>
					<br /><br />
					<?php
					$field_class = '';
					if ( isset( $gdpr_options['moove_gdpr_plugin_font_type'] ) ) :
						if ( '1' === $gdpr_options['moove_gdpr_plugin_font_type'] || '2' === $gdpr_options['moove_gdpr_plugin_font_type'] ) {
							$field_class = 'moove-not-visible';
						}
					endif;

					?>
					<input name="moove_gdpr_plugin_font_family" type="text" id="moove_gdpr_plugin_font_family" value="<?php echo isset( $gdpr_options['moove_gdpr_plugin_font_family'] ) && $gdpr_options['moove_gdpr_plugin_font_family'] ? esc_attr( $gdpr_options['moove_gdpr_plugin_font_family'] ) : "'Nunito', sans-serif"; ?>" class="regular-text <?php echo esc_attr( $field_class ); ?>">
				</td>
			</tr>
			<?php do_action( 'gdpr_cc_general_modal_settings' ); ?>
		</tbody>
	</table>

	<br />
	<hr />
	<br />
	<button type="submit" class="button button-primary"><?php esc_html_e( 'Save changes', 'gdpr-cookie-compliance' ); ?></button>
	<?php do_action( 'gdpr_cc_general_buttons_settings' ); ?>
</form>
