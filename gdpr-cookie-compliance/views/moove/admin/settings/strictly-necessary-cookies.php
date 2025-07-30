<?php
/**
 * Necessary Doc Comment
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
			foreach ( $_POST as $form_key => $form_value ) :
				if ( 'moove_gdpr_strict_necessary_cookies_tab_content' === $form_key ) :
					$value                                  = wp_unslash( $form_value );
					$gdpr_options[ $form_key . $wpml_lang ] = $value;
					update_option( $option_name, $gdpr_options );
					$gdpr_options = get_option( $option_name );
				elseif ( 'moove_gdpr_strictly_header_scripts' === $form_key || 'moove_gdpr_strictly_body_scripts' === $form_key || 'moove_gdpr_strictly_footer_scripts' === $form_key || 'moove_gdpr_strictly_necessary_cookies_tab_title' === $form_key ) :
					$value                     = wp_unslash( $form_value );
					$gdpr_options[ $form_key ] = maybe_serialize( $value );
					update_option( $option_name, $gdpr_options );
					$gdpr_options = get_option( $option_name );
				endif;
			endforeach;
		endif;
		do_action( 'gdpr_cookie_filter_settings' );
		?>
		<script>
			jQuery('#moove-gdpr-setting-error-settings_updated').show();
		</script>
		<?php
	endif;
endif;
$nav_label = isset( $gdpr_options[ 'moove_gdpr_strictly_necessary_cookies_tab_title' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_strictly_necessary_cookies_tab_title' . $wpml_lang ] ? $gdpr_options[ 'moove_gdpr_strictly_necessary_cookies_tab_title' . $wpml_lang ] : __( 'Necessary', 'gdpr-cookie-compliance' );
?>
<form action="<?php echo esc_url( admin_url( 'admin.php?page=moove-gdpr&tab=strictly-necessary-cookies&gcat=cookie_categories' ) ); ?>" method="post" id="moove_gdpr_tab_strictly_necessary_cookies">
	<?php wp_nonce_field( 'moove_gdpr_nonce_field', 'moove_gdpr_nonce' ); ?>
	<table class="form-table">
		<tbody>
			
			<tr>
				<th scope="row">
					<label for="moove_gdpr_strictly_necessary_cookies_tab_title">
						<?php esc_html_e( 'Tab Title', 'gdpr-cookie-compliance' ); ?>
					</label>
				</th>
				<td>
					<input name="moove_gdpr_strictly_necessary_cookies_tab_title<?php echo esc_attr( $wpml_lang ); ?>" type="text" id="moove_gdpr_strictly_necessary_cookies_tab_title" value="<?php echo esc_attr( $nav_label ); ?>" class="regular-text">
				</td>
			</tr>

			<tr>
				<th scope="row" colspan="2" style="padding-bottom: 0;">
					<label for="moove_gdpr_strict_necessary_cookies_tab_content">
						<?php esc_html_e( 'Tab Content', 'gdpr-cookie-compliance' ); ?>
					</label>
				</th>
			</tr>
			<tr class="moove_gdpr_table_form_holder">
				<td colspan="2" scope="row" style="padding-left: 0;">
					<?php
						$content = isset( $gdpr_options[ 'moove_gdpr_strict_necessary_cookies_tab_content' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_strict_necessary_cookies_tab_content' . $wpml_lang ] ? wp_unslash( $gdpr_options[ 'moove_gdpr_strict_necessary_cookies_tab_content' . $wpml_lang ] ) : false;
					if ( ! $content ) :
						$content = $gdpr_default_content->moove_gdpr_get_strict_necessary_content();
						endif;

						$settings = array(
							'media_buttons' => false,
							'editor_height' => 150,
						);
						wp_editor( $content, 'moove_gdpr_strict_necessary_cookies_tab_content', $settings );
						?>
				</td>
			</tr>
		</tbody>
	</table>

	<div class="gdpr-script-tab-content">
		<hr />
		<h3><?php esc_html_e( 'The scripts inserted below are considered necessary for the correct functionality of the website and therefore will be loaded on every page load.', 'gdpr-cookie-compliance' ); ?></h3>
		<h4><?php esc_html_e( 'Users can’t reject them. Leave the script section below empty if you don’t want to load any default scripts on your site.', 'gdpr-cookie-compliance' ); ?></h4>

		<div class="gdpr-tab-code-section-nav">
			<ul>
				<li>
					<a href="#strictly_head" class="gdpr-active">
						<?php esc_html_e( 'Head Section', 'gdpr-cookie-compliance' ); ?>
					</a>
				</li>
				<li>
					<a href="#strictly_body">
						<?php esc_html_e( 'Body Section', 'gdpr-cookie-compliance' ); ?>
					</a>
				</li>
				<li>
					<a href="#strictly_footer">
						<?php esc_html_e( 'Footer Section', 'gdpr-cookie-compliance' ); ?>
					</a>
				</li>
			</ul>
		</div>
		<!--  .gdpr-tab-code-section-nav -->
		<div class="gdpr-script-tabs-main-cnt">

			<div class="gdpr-tab-code-section gdpr-active" id="strictly_head">
				<h4 for="moove_gdpr_strictly_header_scripts"><?php esc_html_e( 'Add scripts that you would like to be inserted to the HEAD section of your pages without user consent.', 'gdpr-cookie-compliance' ); ?></h4>
				<table>
					<tbody>
						<tr class="moove_gdpr_strictly_header_scripts">
							<td scope="row" colspan="2" style="padding: 20px 0;">
								<?php
								$content = isset( $gdpr_options['moove_gdpr_strictly_header_scripts'] ) && $gdpr_options['moove_gdpr_strictly_header_scripts'] ? maybe_unserialize( $gdpr_options['moove_gdpr_strictly_header_scripts'] ) : '';
								?>
								<textarea name="moove_gdpr_strictly_header_scripts" id="moove_gdpr_strictly_header_scripts" class="large-text code" rows="13"><?php apply_filters( 'gdpr_cc_keephtml', $content, true ); ?></textarea>
								<div class="gdpr-code"></div>
								<!--  .gdpr-code -->
								<p class="description" id="moove_gdpr_strictly_header_scripts-description">
									<?php esc_html_e( 'For example, you can use it for Google Tag Manager script or any other 3rd party code snippets.', 'gdpr-cookie-compliance' ); ?>
								</p>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<!--  .gdpr-tab-code-section -->

			<div class="gdpr-tab-code-section" id="strictly_body">
				<h4 for="moove_gdpr_strictly_header_scripts"><?php esc_html_e( 'Add scripts that you would like to be inserted to the BODY section of your pages without user consent.', 'gdpr-cookie-compliance' ); ?></h4>
				<table>
					<tbody>                   
						<tr class="moove_gdpr_strictly_body_scripts">
							<td scope="row" colspan="2" style="padding: 20px 0;">
								<?php
									$content = isset( $gdpr_options['moove_gdpr_strictly_body_scripts'] ) && $gdpr_options['moove_gdpr_strictly_body_scripts'] ? maybe_unserialize( $gdpr_options['moove_gdpr_strictly_body_scripts'] ) : '';
								?>
								<textarea name="moove_gdpr_strictly_body_scripts" id="moove_gdpr_strictly_body_scripts" class="large-text code" rows="13"><?php apply_filters( 'gdpr_cc_keephtml', $content, true ); ?></textarea>
								<div class="gdpr-code"></div>
								<!--  .gdpr-code -->
								<p class="description" id="moove_gdpr_strictly_body_scripts-description">
									<?php esc_html_e( 'For example, you can use it for Google Tag Manager script or any other 3rd party code snippets.', 'gdpr-cookie-compliance' ); ?>
								</p>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<!--  .gdpr-tab-code-section -->

			<div class="gdpr-tab-code-section" id="strictly_footer">
				<h4 for="moove_gdpr_strictly_header_scripts"><?php esc_html_e( 'Add scripts that you would like to be inserted to the FOOTER section of your pages without user consent.', 'gdpr-cookie-compliance' ); ?></h4>
				<table>
					<tbody>
						<tr class="moove_gdpr_strictly_footer_scripts">
							<td scope="row" colspan="2" style="padding: 20px 0;">
								<?php
									$content = isset( $gdpr_options['moove_gdpr_strictly_footer_scripts'] ) && $gdpr_options['moove_gdpr_strictly_footer_scripts'] ? maybe_unserialize( $gdpr_options['moove_gdpr_strictly_footer_scripts'] ) : '';
								?>
								<textarea name="moove_gdpr_strictly_footer_scripts" id="moove_gdpr_strictly_footer_scripts" class="large-text code" rows="13"><?php apply_filters( 'gdpr_cc_keephtml', $content, true ); ?></textarea>
								<div class="gdpr-code"></div>
								<!--  .gdpr-code -->
								<p class="description" id="moove_gdpr_strictly_footer_scripts-description">
									<?php esc_html_e( 'For example, you can use it for Google Analytics script or any other 3rd party code snippets.', 'gdpr-cookie-compliance' ); ?>
								</p>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<!--  .gdpr-tab-code-section -->

			<?php do_action( 'gdpr_tab_code_section_content_extension', 'strictly' ); ?>
		</div>
		<!--  .gdpr-script-tabs-main-cnt -->
	</div>
	<!--  .gdpr-script-tab-content -->

	<?php do_action( 'gdpr_settings_screen_extension', 'strictly' ); ?>
	<br />
	<hr />
	<br />
	<button type="submit" class="button button-primary"><?php esc_html_e( 'Save changes', 'gdpr-cookie-compliance' ); ?></button>

	<script type="text/javascript">
		window.onload = function() {
			jQuery('.gdpr-tab-section-cnt textarea.code').each(function(){
				if (typeof CodeMirror === "function") {
					var element = jQuery(this).closest('tr').find('.gdpr-code')[0];
					var id = jQuery(this).attr('id');

					jQuery(this).css({
						'opacity'   : '0',
						'height'    : '0',
					});
					var  editor = CodeMirror( element, {
						mode: "text/html",
						lineWrapping: true,
						lineNumbers: true,
						value: document.getElementById(id).value
					});
					editor.on('change',function(cMirror){
					// get value right from instance
					document.getElementById(id).value = cMirror.getValue();
				});
			} else {
				jQuery('.gdpr-code').hide();
			}
		});
	};
	</script>
	
</form>
<div class="gdpr-admin-popup gdpr-admin-popup-fsm-settings" style="display: none;">
	<span class="gdpr-popup-overlay"></span>
	<div class="gdpr-popup-content">
		<div class="gdpr-popup-content-header">
			<a href="#" class="gdpr-popup-close"><span class="dashicons dashicons-no-alt"></span></a>
		</div>
		<!--  .gdpr-popup-content-header -->
		<div class="gdpr-popup-content-content">
			<h4><strong><?php esc_html_e( 'This option is not available in Full Screen Mode', 'gdpr-cookie-compliance' ); ?> </strong></h4>

			<p class="description"><strong><?php esc_html_e( 'Please note that Full Screen Mode feature requires the Necessary to be always enabled (otherwise the Cookie Banner would be displayed at every visit).', 'gdpr-cookie-compliance' ); ?></strong></p> <br /><br />
					<!--  .description -->

			<a href="<?php echo esc_attr( admin_url( 'admin.php?page=moove-gdpr&tab=full-screen-mode&gcat=cookie_categories' ) ); ?>" class="button button-primary button-deactivate-confirm">
				<?php esc_html_e( 'Disable the Full Screen Mode', 'gdpr-cookie-compliance' ); ?>
			</a>
		</div>
		<!--  .gdpr-popup-content-content -->    
	</div>
	<!--  .gdpr-popup-content -->
</div>
<!--  .gdpr-admin-popup -->

