<?php
/**
 * Moove_GDPR_Content File Doc Comment
 *
 * @category Moove_GDPR_Content
 * @package   gdpr-cookie-compliance
 * @author    Moove Agency
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


/**
 * Moove_GDPR_Content Class Doc Comment
 *
 * @category Class
 * @package  Moove_Controller
 * @author   Moove Agency
 */
class Moove_GDPR_Content {

	/**
	 * Construct
	 */
	public function __construct() {

	}

	/**
	 * Integration Extensions
	 *
	 * @param array $cache_array Cache array.
	 * @param array $gdpr_options Plugin options.
	 */
	public static function gdpr_extend_integration_snippets( $cache_array, $gdpr_options ) {
		$gdin_values = isset( $gdpr_options['gdin_values'] ) ? json_decode( $gdpr_options['gdin_values'], true ) : array();
		if ( $gdin_values && ! empty( $gdin_values ) && is_array( $gdin_values ) ) :
			$gdin_modules = gdpr_get_integration_modules( $gdpr_options, $gdin_values );
			foreach ( $gdin_modules as $_gdin_module_slug => $_gdin_module ) :
				if ( isset( $_gdin_module['tacking_id'] ) && $_gdin_module['tacking_id'] && $_gdin_module['status'] ) :
					$cache_array = apply_filters( 'gdpr_insert_integration_' . $_gdin_module_slug . '_snippet', $cache_array, $_gdin_module );
				endif;
			endforeach;
		endif;
		return $cache_array;
	}

	/**
	 * Loads the simplified admin top menu items
	 *
	 * @param array $active_tab Active Tab.
	 * @param array $current_gcat Active Tab Category.
	 * @param array $modal_options Plugin settings.
	 * @return array $wpml_lang Translation slug.
	 */
	public static function gdpr_admin_top_nav_links_gcat( $active_tab, $current_gcat ) {
		$current_gcat = $current_gcat ? $current_gcat : ( ! isset( $_GET['tab'] ) && isset( $_GET['page'] ) && esc_attr( $_GET['page'] ) === 'moove-gdpr'  ? 'settings' : '' );

		$view_cnt = new GDPR_View();
		$tab_data = $view_cnt->load( 'moove.admin.nav-tabs.' . $current_gcat, array(
			'active_tab'		=> $active_tab,
			'current_gcat'	=> $current_gcat
		));
		apply_filters( 'gdpr_cc_keephtml', $tab_data, true );

	}

	/**
	 * Integration Extensions
	 *
	 * @param array $cache_array Cache array.
	 * @param array $_gdin_module Integration Module.
	 */
	public static function gdpr_insert_integration_ga_snippet( $cache_array, $_gdin_module ) {
		if ( isset( $_gdin_module['tacking_id'] ) && $_gdin_module['tacking_id'] && intval( $_gdin_module['cookie_cat'] ) ) :
			$cookie_cat_n = '';
			switch ( intval( $_gdin_module['cookie_cat'] ) ) {
				case 2:
					$cookie_cat_n = 'thirdparty';
					break;
				case 3:
					$cookie_cat_n = 'advanced';
					break;
				case 4:
					$cookie_cat_n = 'performance';
					break;
				case 5:
					$cookie_cat_n = 'preference';
					break;
				default:
					// code...
					break;
			}

			if ( $cookie_cat_n ) :
				ob_start();
				?>
				<?php /* phpcs:disable WordPress.WP.EnqueuedResources.NonEnqueuedScript */ ?>
				<!-- Google tag (gtag.js) -->
				<script src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr( $_gdin_module['tacking_id'] ); ?>" data-type="gdpr-integration"></script>
				<script data-type="gdpr-integration">
					window.dataLayer = window.dataLayer || [];
					function gtag(){dataLayer.push(arguments);}
					gtag('js', new Date());

					gtag('config', '<?php echo esc_attr( $_gdin_module['tacking_id'] ); ?>');
				</script>
				<?php /* phpcs:enable WordPress.WP.EnqueuedResources.NonEnqueuedScript */ ?>
				<?php

				if ( ! defined( 'gdpr_i_ga_h' ) ) :
					$cache_array[ $cookie_cat_n ]['header'] .= ob_get_clean();
					define( 'gdpr_i_ga_h', true );
				else :
					ob_end_clean();
				endif;				
			endif;
		endif;
		return $cache_array;
	}

	/**
	 * Integration Extensions
	 *
	 * @param array $cache_array Cache array.
	 * @param array $_gdin_module Integration Module.
	 */
	public static function gdpr_insert_integration_ga4_snippet( $cache_array, $_gdin_module ) {
		if ( isset( $_gdin_module['tacking_id'] ) && $_gdin_module['tacking_id'] && intval( $_gdin_module['cookie_cat'] ) ) :
			$cookie_cat_n = '';
			switch ( intval( $_gdin_module['cookie_cat'] ) ) {
				case 2:
					$cookie_cat_n = 'thirdparty';
					break;
				case 3:
					$cookie_cat_n = 'advanced';
					break;
				case 4:
					$cookie_cat_n = 'performance';
					break;
				case 5:
					$cookie_cat_n = 'preference';
					break;
				default:
					// code...
					break;
			}

			if ( $cookie_cat_n ) :
				ob_start();
				?>
				<?php /* phpcs:disable WordPress.WP.EnqueuedResources.NonEnqueuedScript */ ?>
				<!-- Google tag (gtag.js) - Google Analytics 4 -->
				<script src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr( $_gdin_module['tacking_id'] ); ?>" data-type="gdpr-integration"></script>
				<script data-type="gdpr-integration">
					window.dataLayer = window.dataLayer || [];
					function gtag(){dataLayer.push(arguments);}
					gtag('js', new Date());

					gtag('config', '<?php echo esc_attr( $_gdin_module['tacking_id'] ); ?>');
				</script>
				<?php /* phpcs:enable WordPress.WP.EnqueuedResources.NonEnqueuedScript */ ?>
				<?php
				if ( ! defined( 'gdpr_i_ga4_h' ) ) :
					$cache_array[ $cookie_cat_n ]['header'] .= ob_get_clean();
					define( 'gdpr_i_ga4_h', true );
				else :
					ob_end_clean();
				endif;					
			endif;
		endif;
		return $cache_array;
	}

	/**
	 * Integration Extensions
	 *
	 * @param array $cache_array Cache array.
	 * @param array $_gdin_module Integration Module.
	 */
	public static function gdpr_insert_integration_gtm_snippet( $cache_array, $_gdin_module ) {
		if ( isset( $_gdin_module['tacking_id'] ) && $_gdin_module['tacking_id'] && intval( $_gdin_module['cookie_cat'] ) ) :
			$cookie_cat_n = '';
			switch ( intval( $_gdin_module['cookie_cat'] ) ) {
				case 2:
					$cookie_cat_n = 'thirdparty';
					break;
				case 3:
					$cookie_cat_n = 'advanced';
					break;
				case 4:
					$cookie_cat_n = 'performance';
					break;
				case 5:
					$cookie_cat_n = 'preference';
					break;
				default:
					// code...
					break;
			}
			if ( $cookie_cat_n ) :
				ob_start();
				self::gdpr_render_google_tag_loader( $_gdin_module['tacking_id'], 'data-type="gdpr-integration"' );
				if ( ! defined( 'gdpr_i_gtm_h' ) ) :
					$cache_array[ $cookie_cat_n ]['header'] .= ob_get_clean();
					define( 'gdpr_i_gtm_h', true );
				else :
					ob_end_clean();
				endif;

				// ns.html only exists for GTM containers, not Google tag IDs.
				if ( self::gdpr_is_gtm_container_id( $_gdin_module['tacking_id'] ) ) :
					ob_start();
					?>
					<!-- Google Tag Manager (noscript) -->
					<noscript data-type="gdpr-integration"><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo esc_attr( trim( $_gdin_module['tacking_id'] ) ); ?>"
					height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
					<!-- End Google Tag Manager (noscript) -->
					<?php
					if ( ! defined( 'gdpr_i_gtm_b' ) ) :
						$cache_array[ $cookie_cat_n ]['body'] .= ob_get_clean();
						define( 'gdpr_i_gtm_b', true );
					else :
						ob_end_clean();
					endif;
				endif;
			endif;
		endif;
		return $cache_array;
	}

	/**
	 * Get strict secondary notice [DEPRECATED]
	 */
	public function moove_gdpr_get_secondary_notice() {
		$_content          = '';
		return $_content;
	}

	/**
	 * Google Consent Mode v2 - 'consent default' + GTM container or Google tag (default language).
	 *
	 * Bails when something has already emitted the head half for this request, so a
	 * WPML site running the premium language-specific variant does not end up with
	 * two 'consent default' calls and two loaders on the same page. The
	 * add-on's counterpart runs at wp_head priority 0, ahead of this one, and claims
	 * the guard whenever it has a language-specific GTM ID to use.
	 *
	 * @see gdpr_render_gtm_consent_default() for the markup and the category mapping.
	 */
	public static function gdpr_google_consent_mode2_snippet() {
		if ( defined( 'GDPR_CC_GTM2_HEAD_DONE' ) ) :
			return;
		endif;

		$gdpr_default_content = new Moove_GDPR_Content();
		$option_name          = $gdpr_default_content->moove_gdpr_get_option_name();
		$gdpr_options         = get_option( $option_name );
		$gdin_values          = isset( $gdpr_options['gdin_values'] ) ? json_decode( $gdpr_options['gdin_values'], true ) : array();
		$gdin_modules         = gdpr_get_integration_modules( $gdpr_options, $gdin_values );

		if ( isset( $gdin_modules['gtmc2'] ) && isset( $gdin_modules['gtmc2']['tacking_id'] ) && $gdin_modules['gtmc2']['status'] ) :
			self::gdpr_render_gtm_consent_default( $gdin_modules['gtmc2']['tacking_id'] );
		endif;
	}

	/**
	 * Renders the Consent Mode v2 head half: the 'consent default' call followed by
	 * the GTM container or Google tag loader (see gdpr_render_google_tag_loader()).
	 *
	 * Shared by the free (default language) and premium (WPML language-specific)
	 * entry points. It used to be duplicated, and the two copies drifted - the
	 * premium one kept emitting a hardcoded all-denied default long after this one
	 * learned to read the returning visitor's stored decision, so translated pages
	 * silently lost the ecommerce behaviour described below.
	 *
	 * Read the user's stored consent before GTM loads so that returning visitors
	 * who have already accepted cookies receive 'granted' defaults from the very
	 * first gtag() call.  Without this, the Consent Update fires after GTM has
	 * already initialised, which is too late for ecommerce events
	 * (view_item_list, view_item, add_to_cart) triggered on page load - those
	 * events are blocked and never retro-fired by GTM.
	 *
	 * Consent type mapping (must stay in step with the update half - see
	 * gdpr_insert_integration_gtmc2_snippet):
	 *   strict      -> functionality_storage, security_storage
	 *   thirdparty  -> analytics_storage  (GA4 / GTM analytics tags)
	 *   advanced    -> ad_storage, ad_user_data, ad_personalization
	 *   preference  -> personalization_storage
	 *
	 * The state is resolved in the browser rather than rendered by PHP: this
	 * markup is served from the page cache, so baking one visitor's decision
	 * into it would hand that decision to every subsequent visitor. See
	 * gdpr_consent_cookie_js_preamble(). The read is still synchronous and
	 * still happens before GTM loads, so the ecommerce behaviour above is
	 * unaffected.
	 *
	 * @param string $tag_id GTM container ID (GTM-XXXXXX) or Google tag ID (G-XXXXXXX).
	 * @return void
	 */
	public static function gdpr_render_gtm_consent_default( $tag_id ) {
		// The ID lands inside a JS string in a <script> block, where esc_attr() cannot
		// escape quotes for JS and esc_js() cannot stop a literal '</script>'. A
		// container or tag ID is only ever [A-Za-z0-9_-], so constrain it to that
		// instead of relying on the output escaper alone.
		$tag_id = preg_replace( '/[^A-Za-z0-9_-]/', '', trim( $tag_id ) );

		if ( ! $tag_id || defined( 'GDPR_CC_GTM2_HEAD_DONE' ) ) :
			return;
		endif;

		define( 'GDPR_CC_GTM2_HEAD_DONE', true );

		// wait_for_update is off unless a site opts in through the filter. Google's
		// tag does not treat it as a maximum wait: GA4 holds its hits until a consent
		// update carrying the ad signals arrives, and the update half sends nothing
		// until the banner is answered - so any value here suppresses the Advanced
		// Consent Mode cookieless pings for first-time visitors. Stored consent is
		// read synchronously below, before GTM loads, so there is nothing to wait for.
		// When enabled it only applies on first visit (no stored consent); for
		// returning users the values are final.
		$wait_for_update_ms = absint( apply_filters( 'gdpr_cc_gtm2_wait_for_update', 0 ) );

		?>
			<?php /* phpcs:disable WordPress.WP.EnqueuedResources.NonEnqueuedScript */ ?>
			<script>
				// Define dataLayer and the gtag function.
				window.dataLayer = window.dataLayer || [];
				function gtag(){dataLayer.push(arguments);}
				(function(){
					<?php echo self::gdpr_consent_cookie_js_preamble(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

					var _gdpr_ads = _gdpr_state('advanced'), _gdpr_strict = _gdpr_state('strict');
					var _gdpr_consent = {
						'ad_storage': _gdpr_ads,
						'ad_user_data': _gdpr_ads,
						'ad_personalization': _gdpr_ads,
						'analytics_storage': _gdpr_state('thirdparty'),
						'personalization_storage': _gdpr_state('preference'),
						'security_storage': _gdpr_strict,
						'functionality_storage': _gdpr_strict
					};
					<?php if ( $wait_for_update_ms > 0 ) : ?>
					if ( ! _gdpr_has ) {
						_gdpr_consent['wait_for_update'] = <?php echo $wait_for_update_ms; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>;
					}
					<?php endif; ?>
					gtag('consent', 'default', _gdpr_consent);
				})();
			</script>
			<?php /* phpcs:enable WordPress.WP.EnqueuedResources.NonEnqueuedScript */ ?>
		<?php
		self::gdpr_render_google_tag_loader( $tag_id );
	}

	/**
	 * Prints the loader for the ID entered in a Google Tag Manager integration.
	 *
	 * The GTM fields ask for a container ID but have always accepted any ID, and
	 * sites routinely enter a Google tag ID there instead (G-, GT-, AW-, DC-).
	 * Google does not support loading those through gtm.js: from 2 October 2026
	 * gtm.js initialises on load and ignores gtag('config'). So only a GTM-
	 * container gets the GTM snippet; any other ID gets the standard gtag.js one.
	 *
	 * Both loaders read the consent state already queued in the dataLayer, so this
	 * must be printed after any gtag('consent', 'default') call.
	 *
	 * @param string $tag_id       GTM container ID or Google tag ID.
	 * @param string $script_attrs Extra attributes for the script tags. Literal markup, never user input.
	 * @return void
	 */
	private static function gdpr_render_google_tag_loader( $tag_id, $script_attrs = '' ) {
		// The ID lands inside a JS string - see gdpr_render_gtm_consent_default() for
		// why it is constrained rather than only escaped.
		$tag_id = preg_replace( '/[^A-Za-z0-9_-]/', '', trim( $tag_id ) );

		if ( ! $tag_id ) :
			return;
		endif;

		$script_attrs = $script_attrs ? ' ' . $script_attrs : '';
		?>
			<?php /* phpcs:disable WordPress.WP.EnqueuedResources.NonEnqueuedScript, WordPress.Security.EscapeOutput.OutputNotEscaped */ ?>
			<?php if ( self::gdpr_is_gtm_container_id( $tag_id ) ) : ?>
			<!-- Google Tag Manager -->
			<script<?php echo $script_attrs; ?>>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
			new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
			j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
			'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
			})(window,document,'script','dataLayer','<?php echo esc_js( $tag_id ); ?>');</script>
			<!-- End Google Tag Manager -->
			<?php else : ?>
			<!-- Google tag (gtag.js) -->
			<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr( $tag_id ); ?>"<?php echo $script_attrs; ?>></script>
			<script<?php echo $script_attrs; ?>>
				window.dataLayer = window.dataLayer || [];
				function gtag(){dataLayer.push(arguments);}
				gtag('js', new Date());
				gtag('config', '<?php echo esc_js( $tag_id ); ?>');
			</script>
			<!-- End Google tag (gtag.js) -->
			<?php endif; ?>
			<?php /* phpcs:enable WordPress.WP.EnqueuedResources.NonEnqueuedScript, WordPress.Security.EscapeOutput.OutputNotEscaped */ ?>
		<?php
	}

	/**
	 * Whether an ID is a GTM container (GTM-XXXXXX) rather than a Google tag ID.
	 *
	 * @param string $tag_id Tag ID as entered in the integration settings.
	 * @return bool
	 */
	private static function gdpr_is_gtm_container_id( $tag_id ) {
		return 0 === stripos( trim( $tag_id ), 'GTM-' );
	}

	/**
	 * JS preamble that reads the visitor's stored consent client-side.
	 *
	 * Consent state must NEVER be rendered into the page HTML by PHP. The plugin's
	 * consent cookie is not in the default bypass list of any of the common page
	 * caches (WP Rocket, LiteSpeed, Varnish, Cloudflare APO), so a page generated
	 * for a visitor who accepted would be stored and then served to visitors who
	 * never did - silently granting consent on their behalf.
	 *
	 * Reading the cookie in the browser keeps the markup identical for every
	 * visitor, so it stays safely cacheable. This still runs synchronously in
	 * wp_head, before any tag has loaded, so nothing is lost by deferring it.
	 *
	 * Defines in the enclosing scope:
	 *   _gdpr_has          - bool, whether a stored decision exists at all.
	 *   _gdpr_state( key ) - 'granted' | 'denied' for a cookie category key.
	 *
	 * Categories are only ever 'granted' when a decision has actually been stored;
	 * the admin's "enable on first visit" defaults deliberately do not apply here.
	 *
	 * @return string JavaScript, for embedding inside a <script> block.
	 */
	private static function gdpr_consent_cookie_js_preamble() {
		// document.cookie throws in a sandboxed iframe without allow-same-origin;
		// both reads fail closed to 'denied' rather than leaving consent unset.
		return "var _gdpr_c = {}, _gdpr_m = null, _gdpr_has = false;\n"
			. "\t\t\t\ttry { _gdpr_m = document.cookie.match(/(?:^|;\\s*)moove_gdpr_popup=([^;]*)/); _gdpr_has = !! _gdpr_m; } catch ( e ) {}\n"
			. "\t\t\t\tif ( _gdpr_m ) { try { _gdpr_c = JSON.parse( decodeURIComponent( _gdpr_m[1] ) ) || {}; } catch ( e ) { _gdpr_c = {}; } }\n"
			. "\t\t\t\tvar _gdpr_state = function( k ) { return _gdpr_has && parseInt( _gdpr_c[ k ], 10 ) === 1 ? 'granted' : 'denied'; };";
	}

	/**
	 * Microsoft Clarity - Consent API v2.
	 *
	 * Unlike the other snippet modules, Clarity is NOT held back until consent is
	 * given. The Consent API is signal-based: the tag loads on every page view and
	 * is told what it may store. With analytics_Storage denied Clarity runs in
	 * "no-consent mode" - no first or third party cookies, a throwaway ID per page
	 * view - which is what lets it keep working lawfully before the visitor decides.
	 *
	 * Emitting this on wp_head (rather than only after the banner is accepted) means
	 * returning visitors get their stored decision applied on the very first call,
	 * before Clarity has a chance to write anything. The state itself is resolved in
	 * the browser - see gdpr_consent_cookie_js_preamble() for why.
	 *
	 * Consent type mapping:
	 *   analytics_Storage -> the category the module is assigned to on the
	 *                        Integrations screen (Clarity is analytics tooling).
	 *   ad_Storage        -> 'advanced', the plugin's advertising/targeting
	 *                        category, matching the Consent Mode v2 mapping above.
	 *
	 * @see https://learn.microsoft.com/en-us/clarity/setup-and-installation/clarity-consent-api-v2
	 */
	public static function gdpr_clarity_consent_snippet() {
		$gdpr_default_content = new Moove_GDPR_Content();
		$option_name          = $gdpr_default_content->moove_gdpr_get_option_name();
		$gdpr_options         = get_option( $option_name );
		$gdin_values          = isset( $gdpr_options['gdin_values'] ) ? json_decode( $gdpr_options['gdin_values'], true ) : array();
		$gdin_modules         = gdpr_get_integration_modules( $gdpr_options, $gdin_values );

		if ( ! isset( $gdin_modules['clarity'] ) || empty( $gdin_modules['clarity']['status'] ) ) :
			return;
		endif;

		$project_id = isset( $gdin_modules['clarity']['tacking_id'] ) ? trim( $gdin_modules['clarity']['tacking_id'] ) : '';
		$cookie_cat = gdpr_get_cookie_cat_slug( $gdin_modules['clarity']['cookie_cat'] );

		if ( ! $project_id || ! $cookie_cat ) :
			return;
		endif;

		?>
		<?php /* phpcs:disable WordPress.WP.EnqueuedResources.NonEnqueuedScript */ ?>
		<!-- Microsoft Clarity -->
		<script data-type="gdpr-integration">
			(function(c,l,a,r,i,t,y){
				c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
				t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
				y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
			})(window, document, "clarity", "script", "<?php echo esc_js( $project_id ); ?>");

			// Queued against the shim defined above, so it is applied as soon as the
			// tag finishes loading - no race with the banner.
			(function(){
				<?php echo self::gdpr_consent_cookie_js_preamble(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

				window.clarity('consentv2', {
					ad_Storage: _gdpr_state('advanced'),
					analytics_Storage: _gdpr_state('<?php echo esc_js( $cookie_cat ); ?>')
				});
			})();
		</script>
		<!-- End Microsoft Clarity -->
		<?php /* phpcs:enable WordPress.WP.EnqueuedResources.NonEnqueuedScript */ ?>
		<?php
	}

	/**
	 * Google Consent Mode v2 - consent update on acceptance.
	 *
	 * Counterpart to gdpr_google_consent_mode2_snippet(), which emits the
	 * 'consent default' call in wp_head. Both halves MUST use the same category ->
	 * signal mapping, or the plugin grants permissions the visitor was never asked
	 * for:
	 *
	 *   strict      -> functionality_storage, security_storage
	 *   thirdparty  -> analytics_storage  (GA4 / GTM analytics tags)
	 *   advanced    -> ad_storage, ad_user_data, ad_personalization
	 *   preference  -> personalization_storage
	 *
	 * The mapping is fixed by Consent Mode semantics, so - as in the wp_head half -
	 * the module's own 'cookie_cat' assignment is deliberately not consulted here.
	 * It only ever decided which cache bucket the snippet landed in, which is what
	 * previously made a single accepted category grant all seven signals.
	 *
	 * State is resolved in the browser rather than rendered by PHP: this markup is
	 * cached per category and each bucket is served to whoever accepted that one
	 * category, while the payload has to describe every category. See
	 * gdpr_consent_cookie_js_preamble().
	 *
	 * Injected into every gated bucket, because the visitor may have accepted any
	 * subset of them and only the accepted buckets are delivered. Running more than
	 * once would be harmless (each copy reports the same resolved state) but would
	 * duplicate the 'cookie_consent_update' event and double-fire any GTM tag bound
	 * to it, so the first copy to execute claims a window flag and the rest return.
	 *
	 * 'strict' is excluded on purpose: the AJAX delivery path never returns that
	 * bucket, and the static path can inject it before the visitor has decided
	 * anything (the "enabled on first visit" default). A visitor who accepts
	 * nothing but strict therefore gets no update at all - correct, since the
	 * wp_head defaults already describe that state.
	 *
	 * Withdrawal needs no counterpart - the plugin reloads the page on revoke and
	 * the wp_head snippet then emits the denied state.
	 *
	 * @param array $cache_array Cache array.
	 * @param array $_gdin_module Integration Module.
	 */
	public static function gdpr_insert_integration_gtmc2_snippet( $cache_array, $_gdin_module ) {
		if ( ! isset( $_gdin_module['tacking_id'] ) || ! $_gdin_module['tacking_id'] ) :
			return $cache_array;
		endif;

		if ( defined( 'gdpr_i_gtmc2_h' ) ) :
			return $cache_array;
		endif;

		ob_start();
		?>
		<?php /* phpcs:disable WordPress.WP.EnqueuedResources.NonEnqueuedScript */ ?>
		<script data-type="gdpr-integration">
			(function(){
				// gtag() is defined by the wp_head half; if that did not run there is
				// no consent state to update. Bail before claiming the flag so a later
				// copy can still succeed.
				if ( window._gdpr_gtmc2_updated || typeof gtag !== 'function' ) { return; }

				<?php echo self::gdpr_consent_cookie_js_preamble(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

				// No stored decision means the banner has not been answered yet. Leave
				// the wp_head defaults alone.
				if ( ! _gdpr_has ) { return; }

				window._gdpr_gtmc2_updated = true;

				var _gdpr_ads = _gdpr_state('advanced'), _gdpr_strict = _gdpr_state('strict');

				gtag('consent', 'update', {
					'ad_storage': _gdpr_ads,
					'ad_user_data': _gdpr_ads,
					'ad_personalization': _gdpr_ads,
					'analytics_storage': _gdpr_state('thirdparty'),
					'personalization_storage': _gdpr_state('preference'),
					'security_storage': _gdpr_strict,
					'functionality_storage': _gdpr_strict
				});

				window.dataLayer = window.dataLayer || [];
				window.dataLayer.push({
					'event': 'cookie_consent_update'
				});
			})();
		</script>
		<?php /* phpcs:enable WordPress.WP.EnqueuedResources.NonEnqueuedScript */ ?>
		<?php
		$gtmc2_snippet = ob_get_clean();

		/*
		 * Target the gated categories explicitly rather than iterating whatever
		 * happens to be in $cache_array. The premium 'performance' and 'preference'
		 * buckets are appended by the add-on AFTER the per-module snippet filters
		 * have already run (see gdpr_extend_integration_snippets_lsi), so a loop
		 * over existing keys can never reach them - the visitor would accept
		 * Preferences and get no personalization_storage grant.
		 *
		 * Buckets are created when missing; the add-on appends to them with .= and
		 * only initialises them when unset, so pre-creating here is safe.
		 */
		$gated_buckets = apply_filters(
			'gdpr_cc_gtmc2_update_buckets',
			array(
				'thirdparty' => 'moove_gdpr_third_party_cookies_enable',
				'advanced'   => 'moove_gdpr_advanced_cookies_enable',
				'performance' => 'moove_gdpr_performance_ccat_enable',
				'preference' => 'moove_gdpr_preference_ccat_enable',
			)
		);

		$gdpr_default_content = new Moove_GDPR_Content();
		$gdpr_options         = get_option( $gdpr_default_content->moove_gdpr_get_option_name() );

		$injected = false;
		foreach ( $gated_buckets as $_bucket_key => $_enable_key ) :
			// Skip categories the site has switched off, so free installs do not
			// carry empty premium buckets around in the script cache.
			$enabled = ! $_enable_key || ( isset( $gdpr_options[ $_enable_key ] ) && 1 === intval( $gdpr_options[ $_enable_key ] ) );
			if ( ! $enabled && ! isset( $cache_array[ $_bucket_key ] ) ) :
				continue;
			endif;

			if ( ! isset( $cache_array[ $_bucket_key ] ) || ! is_array( $cache_array[ $_bucket_key ] ) ) :
				$cache_array[ $_bucket_key ] = array(
					'header' => '',
					'body'   => '',
					'footer' => '',
				);
			endif;

			if ( ! isset( $cache_array[ $_bucket_key ]['header'] ) ) :
				$cache_array[ $_bucket_key ]['header'] = '';
			endif;

			$cache_array[ $_bucket_key ]['header'] .= $gtmc2_snippet;
			$injected                               = true;
		endforeach;

		if ( $injected ) :
			define( 'gdpr_i_gtmc2_h', true );
		endif;

		return $cache_array;
	}

	/**
	 * Microsoft Clarity - consent update on acceptance.
	 *
	 * The tag itself is already on the page (see gdpr_clarity_consent_snippet), so
	 * this only re-signals consent once the visitor accepts the mapped category.
	 * The state is read from the plugin's own cookie at run time rather than baked
	 * in at render time: this snippet is cached per category, and ad_Storage depends
	 * on a different category than the one that triggered the injection.
	 *
	 * Withdrawal needs no counterpart here - the plugin reloads the page on revoke,
	 * and the wp_head snippet then emits 'denied', which makes Clarity drop its
	 * cookies and restart in no-consent mode.
	 *
	 * @param array $cache_array Cache array.
	 * @param array $_gdin_module Integration Module.
	 */
	public static function gdpr_insert_integration_clarity_snippet( $cache_array, $_gdin_module ) {
		if ( ! isset( $_gdin_module['tacking_id'] ) || ! $_gdin_module['tacking_id'] ) :
			return $cache_array;
		endif;

		$cookie_cat_n = gdpr_get_cookie_cat_slug( $_gdin_module['cookie_cat'] );

		// 'strict' has no cache bucket - it is never gated behind acceptance.
		if ( ! $cookie_cat_n || 'strict' === $cookie_cat_n || ! isset( $cache_array[ $cookie_cat_n ] ) ) :
			return $cache_array;
		endif;

		ob_start();
		?>
		<?php /* phpcs:disable WordPress.WP.EnqueuedResources.NonEnqueuedScript */ ?>
		<script data-type="gdpr-integration">
			(function(){
				if ( typeof window.clarity !== 'function' ) { return; }
				<?php echo self::gdpr_consent_cookie_js_preamble(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

				window.clarity('consentv2', {
					ad_Storage: _gdpr_state('advanced'),
					analytics_Storage: _gdpr_state('<?php echo esc_js( $cookie_cat_n ); ?>')
				});
			})();
		</script>
		<?php /* phpcs:enable WordPress.WP.EnqueuedResources.NonEnqueuedScript */ ?>
		<?php
		if ( ! defined( 'gdpr_i_clarity_h' ) ) :
			$cache_array[ $cookie_cat_n ]['header'] .= ob_get_clean();
			define( 'gdpr_i_clarity_h', true );
		else :
			ob_end_clean();
		endif;

		return $cache_array;
	}

	/**
	 * Integration Extensions
	 *
	 * @param array $cache_array Cache array.
	 * @param array $_gdin_module Integration Module.
	 */
	public static function gdpr_insert_integration_gadc_snippet( $cache_array, $_gdin_module ) {
		if ( isset( $_gdin_module['tacking_id'] ) && $_gdin_module['tacking_id'] && intval( $_gdin_module['cookie_cat'] ) ) :
			$cookie_cat_n = '';
			switch ( intval( $_gdin_module['cookie_cat'] ) ) {
				case 2:
					$cookie_cat_n = 'thirdparty';
					break;
				case 3:
					$cookie_cat_n = 'advanced';
					break;
				case 4:
					$cookie_cat_n = 'performance';
					break;
				case 5:
					$cookie_cat_n = 'preference';
					break;
				default:
					// code...
					break;
			}
			if ( $cookie_cat_n ) :
				ob_start();
				?>
				<?php /* phpcs:disable WordPress.WP.EnqueuedResources.NonEnqueuedScript */ ?>
				<!-- Global site tag (gtag.js) - Google Ads -->
				<script type="text/javascript" data-type="gdpr-integration" src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr( $_gdin_module['tacking_id'] ); ?>"></script>
				<script data-type="gdpr-integration">
					window.dataLayer = window.dataLayer || [];
					function gtag(){dataLayer.push(arguments);}
					gtag('js', new Date());
					gtag('config', '<?php echo esc_attr( $_gdin_module['tacking_id'] ); ?>');
				</script>
				<!-- End Google Ads -->
				<?php /* phpcs:enable WordPress.WP.EnqueuedResources.NonEnqueuedScript */ ?>
				<?php
				if ( ! defined( 'gdpr_i_gadc_h' ) ) :
					$cache_array[ $cookie_cat_n ]['header'] .= ob_get_clean();
					define( 'gdpr_i_gadc_h', true );
				else :
					ob_end_clean();
				endif;
			endif;
		endif;
		return $cache_array;
	}

	/**
	 * Integration Extensions
	 *
	 * @param array $cache_array Cache array.
	 * @param array $_gdin_module Integration Module.
	 */
	public static function gdpr_insert_integration_fbp_snippet( $cache_array, $_gdin_module ) {
		if ( isset( $_gdin_module['tacking_id'] ) && $_gdin_module['tacking_id'] && intval( $_gdin_module['cookie_cat'] ) ) :
			$cookie_cat_n = '';
			switch ( intval( $_gdin_module['cookie_cat'] ) ) {
				case 2:
					$cookie_cat_n = 'thirdparty';
					break;
				case 3:
					$cookie_cat_n = 'advanced';
					break;
				case 4:
					$cookie_cat_n = 'performance';
					break;
				case 5:
					$cookie_cat_n = 'preference';
					break;
				default:
					// code...
					break;
			}
			if ( $cookie_cat_n ) :
				ob_start();
				?>
				<?php /* phpcs:disable WordPress.WP.EnqueuedResources.NonEnqueuedScript */ ?>
				<!-- Facebook Pixel Code -->
				<script data-type="gdpr-integration">
					!function(f,b,e,v,n,t,s)
					{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
					n.callMethod.apply(n,arguments):n.queue.push(arguments)};
					if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
					n.queue=[];t=b.createElement(e);t.async=!0;
					t.src=v;s=b.getElementsByTagName(e)[0];
					s.parentNode.insertBefore(t,s)}(window, document,'script',
					'https://connect.facebook.net/en_US/fbevents.js');
					fbq('init', '<?php echo esc_attr( $_gdin_module['tacking_id'] ); ?>');
					fbq('track', 'PageView');
				</script>
				<?php
				if ( ! defined( 'gdpr_i_fbp_h' ) ) :
					$cache_array[ $cookie_cat_n ]['header'] .= ob_get_clean();
					define( 'gdpr_i_fbp_h', true );
				else :
					ob_end_clean();
				endif;				
				ob_start();
				?>
				<noscript data-type="gdpr-integration">
					<img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=<?php echo esc_attr( $_gdin_module['tacking_id'] ); ?>&ev=PageView&noscript=1"/>
				</noscript>
				<!-- End Facebook Pixel Code -->
				<?php /* phpcs:enable WordPress.WP.EnqueuedResources.NonEnqueuedScript */ ?>
				<?php				
				if ( ! defined( 'gdpr_i_fbp_b' ) ) :
					$cache_array[ $cookie_cat_n ]['body'] .= ob_get_clean();
					define( 'gdpr_i_fbp_b', true );
				else :
					ob_end_clean();
				endif;
			endif;
		endif;
		return $cache_array;
	}

	/**
	 * Integration Extensions
	 *
	 * @param array $cache_array Cache array.
	 * @param array $_gdin_module Integration Module.
	 */
	public static function gdpr_insert_integration_muet_snippet( $cache_array, $_gdin_module ) {
		if ( isset( $_gdin_module['tacking_id'] ) && $_gdin_module['tacking_id'] && intval( $_gdin_module['cookie_cat'] ) ) :
			$cookie_cat_n = '';
			switch ( intval( $_gdin_module['cookie_cat'] ) ) {
				case 2:
					$cookie_cat_n = 'thirdparty';
					break;
				case 3:
					$cookie_cat_n = 'advanced';
					break;
				case 4:
					$cookie_cat_n = 'performance';
					break;
				case 5:
					$cookie_cat_n = 'preference';
					break;
				default:
					// code...
					break;
			}
			if ( $cookie_cat_n ) :
				ob_start();
				?>
				<?php /* phpcs:disable WordPress.WP.EnqueuedResources.NonEnqueuedScript */ ?>
				<!-- Microsoft Advertising UET Code -->
				<script data-type="gdpr-integration">
					// Version: 1.0.0  
					(function(w,d,t,r,u){  
					    var f,n,i;  
					    w[u]=w[u]||[],f=function(){  
					        var o={ti: <?php echo esc_attr( $_gdin_module['tacking_id'] ); ?>, enableAutoSpaTracking: <?php echo apply_filters( 'gdpr_microsoft_uet_autospatracking', 'false' ); ?>, tm:"wpp_1.0.7"};  
					        o.q=w[u],w[u]=new UET(o),w[u].push("pageLoad")  
					    },  
					    n=d.createElement(t),n.src=r,n.async=1,n.onload=n.onreadystatechange=function(){  
					        var s=this.readyState;  
					        s&&s!=="loaded"&&s!=="complete"||(f(),n.onload=n.onreadystatechange=null)  
					    },  
					    i=d.getElementsByTagName(t)[0],i.parentNode.insertBefore(n,i)  
					})(window,document,"script","//bat.bing.com/bat.js","uetq");  
				</script>
				<?php
				if ( ! defined( 'gdpr_i_muet_h' ) ) :
					$cache_array[ $cookie_cat_n ]['header'] .= ob_get_clean();
					define( 'gdpr_i_muet_h', true );
				else :
					ob_end_clean();
				endif;
			endif;
		endif;
		return $cache_array;
	}

	/**
	 * Integration Extensions
	 *
	 * @param array $cache_array Cache array.
	 * @param array $_gdin_module Integration Module.
	 */
	public static function gdpr_insert_integration_gtm4wp_snippet( $cache_array, $_gdin_module ) {
		if ( defined( 'GTM4WP_OPTIONS' ) && defined( 'GTM4WP_OPTION_GTM_PLACEMENT' ) && defined( 'GTM4WP_PLACEMENT_OFF' ) ) :
			$storedoptions                 = (array) get_option( GTM4WP_OPTIONS );
			$gtm4wp_container_code_written = false;
			if ( ( isset( $storedoptions[ GTM4WP_OPTION_GTM_PLACEMENT ] ) && GTM4WP_PLACEMENT_OFF === $storedoptions[ GTM4WP_OPTION_GTM_PLACEMENT ] ) && isset( $_gdin_module['tacking_id'] ) && $_gdin_module['tacking_id'] && intval( $_gdin_module['cookie_cat'] ) ) :
				$cookie_cat_n = '';
				switch ( intval( $_gdin_module['cookie_cat'] ) ) {
					case 2:
						$cookie_cat_n = 'thirdparty';
						break;
					case 3:
						$cookie_cat_n = 'advanced';
						break;
					case 4:
						$cookie_cat_n = 'performance';
						break;
					case 5:
						$cookie_cat_n = 'preference';
						break;
					default:
						// code...
						break;
				}
				if ( $cookie_cat_n && ! $gtm4wp_container_code_written ) :
					ob_start();
					?>
					<?php /* phpcs:disable WordPress.WP.EnqueuedResources.NonEnqueuedScript */ ?>
					<!-- Google Tag Manager -->
					<script data-type="gdpr-integration">(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
					new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
					j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
					'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
					})(window,document,'script','dataLayer','<?php echo esc_attr( $_gdin_module['tacking_id'] ); ?>');</script>
					<!-- End Google Tag Manager -->
					<?php
					if ( ! defined( 'gdpr_i_gtm4wp_h' ) ) :
						$cache_array[ $cookie_cat_n ]['header'] .= ob_get_clean();
						define( 'gdpr_i_gtm4wp_h', true );
					else :
						ob_end_clean();
					endif;			
					ob_start();
					?>
					<!-- Google Tag Manager (noscript) -->
					<noscript data-type="gdpr-integration"><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo esc_attr( $_gdin_module['tacking_id'] ); ?>"
					height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
					<!-- End Google Tag Manager (noscript) -->
					<?php /* phpcs:enable WordPress.WP.EnqueuedResources.NonEnqueuedScript */ ?>
					<?php
					if ( ! defined( 'gdpr_i_gtm4wp_b' ) ) :
						$cache_array[ $cookie_cat_n ]['body'] .= ob_get_clean();
						define( 'gdpr_i_gtm4wp_b', true );
					else :
						ob_end_clean();
					endif;	
					$gtm4wp_container_code_written         = true;
				endif;
			endif;
		endif;
		return $cache_array;
	}

	/**
	 * Privacy Overview Tab Content
	 *
	 * @return string Filtered Content
	 */
	public function moove_gdpr_get_privacy_overview_content() {
		$_content = '<p>' . __( 'This website uses cookies so that we can provide you with the best user experience possible. Cookie information is stored in your browser and performs functions such as recognising you when you return to our website and helping our team to understand which sections of the website you find most interesting and useful.', 'gdpr-cookie-compliance' ) . '</p>';
		return $_content;
	}

	/**
	 * Returns the GDPR activation key
	 *
	 * @param string $option_key Option key.
	 */
	public function gdpr_get_activation_key( $option_key ) {
		$value = get_option( $option_key );
		if ( is_multisite() && ! $value ) :
			$_value = function_exists( 'get_site_option' ) ? get_site_option( $option_key ) : false;
			if ( $_value ) :
				$main_blog_id = get_main_site_id();
				if ( $main_blog_id ) :
					switch_to_blog( $main_blog_id );
					update_option(
						$option_key,
						$_value
					);
					restore_current_blog();
					delete_site_option( $option_key );
					$value = $_value;
				endif;
			endif;
		endif;
		return $value;
	}

	/**
	 * JavaScript localize extension
	 */
	public static function moove_gdpr_get_localize_scripts() {
		$loc_data      = array();
		$gdpr_loc_data = apply_filters( 'gdpr_extend_loc_data', $loc_data );
		return $gdpr_loc_data;
	}

	/**
	 * Strict Necessary Tab Content
	 *
	 * @return string Filtered Content.
	 */
	public function moove_gdpr_get_strict_necessary_content() {
		$_content = '<p>' . __( 'Strictly Necessary Cookie should be enabled at all times so that we can save your preferences for cookie settings.', 'gdpr-cookie-compliance' ) . '</p>';
		return $_content;
	}

	/**
	 * Advanced Cookies Tab Content
	 *
	 * @return string Filtered Content.
	 */
	public function moove_gdpr_get_advanced_cookies_content() {
		$_content = '<p>' . __( 'This website uses the following additional cookies:</p><p>(List the cookies that you are using on the website here.)', 'gdpr-cookie-compliance' ) . '</p>';
		return $_content;
	}

	/**
	 * Third Party Cookies Tab Content
	 *
	 * @return string Filtered Content.
	 */
	public function moove_gdpr_get_third_party_content() {
		$_content  = '<p>' . __( 'This website uses Google Analytics to collect anonymous information such as the number of visitors to the site, and the most popular pages.', 'gdpr-cookie-compliance' );
		$_content .= '<p>' . __( 'Keeping this cookie enabled helps us to improve our website.', 'gdpr-cookie-compliance' ) . '</p>';
		return $_content;
	}

	/**
	 * Cookie Policy Tab Content
	 *
	 * @return string Filtered Content.
	 */
	public function moove_gdpr_get_cookie_policy_content() {
		$privacy_policy_page = get_option( 'wp_page_for_privacy_policy' );
		$privacy_policy_link = $privacy_policy_page ? esc_url( get_permalink( $privacy_policy_page ) ) : false;
		$privacy_policy_link = $privacy_policy_link ? $privacy_policy_link : '#';

		$_content = '<p>' . sprintf( __( 'More information about our [privacy_link]Cookie Policy[/privacy_link]', 'gdpr-cookie-compliance' ), $privacy_policy_link ) . '</p>';
		$_content = str_replace( '[privacy_link]', '<a href="' . $privacy_policy_link . '" target="_blank">', $_content );
		$_content = str_replace( '[/privacy_link]', '</a>', $_content );

		return $_content;
	}

	/**
	 * Cookie Policy Tab Content
	 *
	 * @return string Filtered Content.
	 */
	public function moove_gdpr_ifb_content() {
		$_content  = '<h2>' . __( 'This content is blocked', 'gdpr-cookie-compliance' );
		$_content .= '<p>' . __( 'Please enable the cookies to view this content', 'gdpr-cookie-compliance' );
		$_content .= '<br><br>';
		$_content .= '{accept}' . esc_html__( 'Accept', 'gdpr-cookie-compliance' ) . '{/accept} ';
		$_content .= '{setting}' . esc_html__( 'Adjust your settings', 'gdpr-cookie-compliance' ) . '{/setting}';
		return $_content;
	}

	/**
	 * Get option name
	 */
	public function moove_gdpr_get_option_name() {
		return 'moove_gdpr_plugin_settings';
	}

	/**
	 * Get option name
	 */
	public function moove_gdpr_get_key_name() {
		return 'moove_gdpr_plugin_key';
	}

	/**
	 * Get WMPL language code
	 *
	 * @param string $type Type.
	 */
	public function moove_gdpr_get_wpml_lang( $type = 'code' ) {
		if ( function_exists( 'trp_get_languages' ) && isset( $_GET['gdpr-lang'] ) && is_admin() ) : // phpcs:ignore
			$lang_code = sanitize_text_field( wp_unslash( $_GET['gdpr-lang'] ) ); // phpcs:ignore
			if ( 'code' === $type ) :
				return $lang_code;
			else :
				$trp_languages = trp_get_languages();
				return isset( $trp_languages[ $lang_code ] ) ? $trp_languages[ $lang_code ] : '';
			endif;
		elseif ( class_exists( 'Falang' ) && isset( $_GET['gdpr-lang'] ) && is_admin() ) : // phpcs:ignore
			$lang_code = sanitize_text_field( wp_unslash( $_GET['gdpr-lang'] ) ); // phpcs:ignore
			if ( 'code' === $type ) :
				return $lang_code;
			else :
				$falang_languages = Falang()->get_model()->get_languages_list();
				$lang_name        = $lang_code;
				foreach ( $falang_languages as $language ) :
					$_code     = isset( $language->locale ) ? $language->locale : ( isset( $language->slug ) ? $language->slug : '' );
					$lang_name = $_code === $lang_code && isset( $language->name ) ? $language->name : $lang_name;
				endforeach;
				return $lang_name;
			endif;
		else :
			if ( function_exists( 'trp_get_languages' ) ) :
				$trp_languages = trp_get_languages();
				global $TRP_LANGUAGE; // phpcs:ignore
				return 'code' === $type ? $TRP_LANGUAGE : $trp_languages[ $TRP_LANGUAGE ]; // phpcs:ignore
			elseif ( class_exists( 'Falang' ) ) :
				$current_language = Falang()->get_current_language();
				if ( 'code' === $type ) :
					$lang = isset( $current_language->locale ) ? $current_language->locale : ( isset( $current_language->slug ) ? $current_language->slug : '' );
				else :
					$lang = isset( $current_language->name ) ? $current_language->name : '';
				endif;
				return $lang;
			elseif ( defined( 'ICL_LANGUAGE_CODE' ) ) :
				$language_code = ICL_LANGUAGE_CODE;
				if ( ICL_LANGUAGE_CODE === 'all' ) :
					if ( function_exists( 'pll_default_language' ) ) :
						$language_code = pll_default_language();
					elseif ( class_exists( 'SitePress' ) ) :
						global $sitepress;
						$language_code = $sitepress->get_default_language();
					endif;
				endif;
				return '_' . $language_code;
			elseif ( isset( $GLOBALS['q_config']['language'] ) ) :
				return $GLOBALS['q_config']['language'];
			elseif ( function_exists( 'wpm_get_user_language' ) ) :
				return wpm_get_user_language();
			endif;
		endif;
		return '';
	}

	/**
	 * Licence token
	 */
	public function get_license_token() {
		$license_token = trailingslashit( site_url() );
		return $license_token;
	}

	/**
	 * Licence hash
	 */
	public function get_license_hash() {
		$license_token = is_multisite() ? trailingslashit( network_home_url() ) : trailingslashit( site_url() );
		return $license_token;
	}

	/**
	 * PHP Cookie Checker, available from version 1.3.0
	 */
	public function gdpr_get_php_cookies() {
		$cookies_accepted = array(
			'strict'      => false,
			'thirdparty'  => false,
			'advanced'    => false,
			'performance' => false,
			'preference'  => false,
		);
		if ( isset( $_COOKIE['moove_gdpr_popup'] ) ) :
			$cookies         = sanitize_text_field( wp_unslash( $_COOKIE['moove_gdpr_popup'] ) );
			$cookies_decoded = json_decode( wp_unslash( $cookies ), true );
			if ( $cookies_decoded && is_array( $cookies_decoded ) && ! empty( $cookies_decoded ) ) :
				$cookies_accepted = array(
					'strict'      => isset( $cookies_decoded['strict'] ) && intval( $cookies_decoded['strict'] ) === 1 ? true : false,
					'thirdparty'  => isset( $cookies_decoded['thirdparty'] ) && intval( $cookies_decoded['thirdparty'] ) === 1 ? true : false,
					'advanced'    => isset( $cookies_decoded['advanced'] ) && intval( $cookies_decoded['advanced'] ) === 1 ? true : false,
					'performance' => isset( $cookies_decoded['performance'] ) && intval( $cookies_decoded['performance'] ) === 1 ? true : false,
					'preference'  => isset( $cookies_decoded['preference'] ) && intval( $cookies_decoded['preference'] ) === 1 ? true : false,
				);
		endif;
	else :
		$options_name      = $this->moove_gdpr_get_option_name();
		$gdpr_options      = get_option( $options_name );
		$wpml_lang_options = $this->moove_gdpr_get_wpml_lang();

		$strictly_functionality = isset( $gdpr_options['moove_gdpr_strictly_necessary_cookies_functionality'] ) && intval( $gdpr_options['moove_gdpr_strictly_necessary_cookies_functionality'] ) ? intval( $gdpr_options['moove_gdpr_strictly_necessary_cookies_functionality'] ) : 1;

		$strictly_default     = isset( $gdpr_options['moove_gdpr_strictly_necessary_cookies_functionality'] ) && intval( $gdpr_options['moove_gdpr_strictly_necessary_cookies_functionality'] ) ? intval( $gdpr_options['moove_gdpr_strictly_necessary_cookies_functionality'] ) : 0;
		$strictly_default 		= 4 === $strictly_default || 2 === $strictly_default ? 1 : 0;

		$thirdparty_default     = isset( $gdpr_options['moove_gdpr_third_party_cookies_enable_first_visit'] ) && intval( $gdpr_options['moove_gdpr_third_party_cookies_enable_first_visit'] ) ? intval( $gdpr_options['moove_gdpr_third_party_cookies_enable_first_visit'] ) : 0;
		$advanced_default       = isset( $gdpr_options['moove_gdpr_advanced_cookies_enable_first_visit'] ) && intval( $gdpr_options['moove_gdpr_advanced_cookies_enable_first_visit'] ) ? intval( $gdpr_options['moove_gdpr_advanced_cookies_enable_first_visit'] ) : 0;

		$performance_default = isset( $gdpr_options['moove_gdpr_performance_ccat_enable_first_visit'] ) && intval( $gdpr_options['moove_gdpr_performance_ccat_enable_first_visit'] ) ? intval( $gdpr_options['moove_gdpr_performance_ccat_enable_first_visit'] ) : 0;

		$preference_default = isset( $gdpr_options['moove_gdpr_preference_ccat_enable_first_visit'] ) && intval( $gdpr_options['moove_gdpr_preference_ccat_enable_first_visit'] ) ? intval( $gdpr_options['moove_gdpr_preference_ccat_enable_first_visit'] ) : 0;

		if ( 1 === $strictly_functionality && 1 !== $strictly_default ) :
			if ( 1 === $thirdparty_default || 1 === $advanced_default || 1 === $performance_default || 1 === $preference_default ) :
				$strict_default = 1;
			else :
				$strict_default = 2 === $strictly_default ? 1 : 0;
			endif;
		else :
			$strict_default = 1;
		endif;

		$cookies_accepted = array(
			'strict'      => $strict_default,
			'thirdparty'  => $thirdparty_default,
			'advanced'    => $advanced_default,
			'performance' => $performance_default,
			'preference'  => $preference_default,
		);

	endif;
	return $cookies_accepted;
	}

	/**
	 * GDPR Licence action button
	 *
	 * @param array  $response Response.
	 * @param string $gdpr_key GDPR Key.
	 */
	public static function gdpr_licence_action_button( $response, $gdpr_key ) {
		$type = isset( $response['type'] ) ? $response['type'] : false;
		if ( 'expired' === $type || 'activated' === $type || 'max_activation_reached' === $type ) :
			if ( 'activated' !== $type ) :
				?>
				<br />
				<button type="submit" name="gdpr_activate_license" class="button button-primary button-inverse">
					<?php esc_html_e( 'Activate', 'gdpr-cookie-compliance' ); ?>
				</button>
				<?php
			endif;
		elseif ( 'invalid' === $type ) :
			?>
			<br />
			<button type="submit" name="gdpr_activate_license" class="button button-primary button-inverse">
				<?php esc_html_e( 'Activate', 'gdpr-cookie-compliance' ); ?>
			</button>
			<?php
		else :
			?>
			<br />
			<button type="submit" name="gdpr_activate_license" class="button button-primary button-inverse">
				<?php esc_html_e( 'Activate', 'gdpr-cookie-compliance' ); ?>
			</button>
			<br /><br />
			<hr />
			<h4 style="margin-bottom: 0;"><?php esc_html_e( 'Buy licence', 'gdpr-cookie-compliance' ); ?></h4>
			<p>
				<?php
				$store_link = __( 'You can buy licences from our [store_link]online store[/store_link].', 'gdpr-cookie-compliance' );
				$store_link = str_replace( '[store_link]', '<a href="https://www.mooveagency.com/wordpress-plugins/gdpr-cookie-compliance/" target="_blank" class="gdpr_admin_link">', $store_link );
				$store_link = str_replace( '[/store_link]', '</a>', $store_link );
				apply_filters( 'gdpr_cc_keephtml', $store_link, true );
				?>
			</p>
			<p>
				<a href="https://www.mooveagency.com/wordpress-plugins/gdpr-cookie-compliance/" target="_blank" class="button button-primary">Buy Now</a>
			</p>
			<br />
			<hr />
			<?php
		endif;
	}

	/**
	 * Licence input key
	 *
	 * @param array  $response Response.
	 * @param string $gdpr_key GDPR Key.
	 */
	public static function gdpr_licence_input_field( $response, $gdpr_key ) {
		$type = isset( $response['type'] ) ? $response['type'] : false;
		if ( 'expired' === $type ) :
			// LICENSE EXPIRED.
			?>
			<tr>
				<th scope="row" style="padding: 0 0 10px 0;">
					<hr />
					<h4 style="margin-bottom: 0;"><?php esc_html_e( 'Renew your licence', 'gdpr-cookie-compliance' ); ?></h4>
					<p><?php esc_html_e( 'Your licence has expired. You will not receive the latest updates and features unless you renew your licence.', 'gdpr-cookie-compliance' ); ?></p>
					<a href="<?php echo esc_attr( MOOVE_SHOP_URL ); ?>?renew=<?php echo esc_attr( $response['key'] ); ?>" target="_blank" class="button button-primary">Renew Licence</a>
					<br /><br />
					<hr />

					<h4 style="margin-bottom: 0;"><?php esc_html_e( 'Enter new licence key', 'gdpr-cookie-compliance' ); ?></h4>
				</th>
			</tr>
			<tr>
				<td style="padding: 0;">
					<input name="moove_gdpr_license_key" required min="35" type="text" id="moove_gdpr_license_key" value="" class="regular-text">
				</td>
			</tr>
			<?php
		elseif ( 'activated' === $type || 'max_activation_reached' === $type ) :
			// LICENSE ACTIVATED.
			?>
			<tr>
				<th scope="row" style="padding: 0 0 10px 0;">
					<hr />
					<h4 style="margin-bottom: 0;"><?php esc_html_e( 'Buy more licences', 'gdpr-cookie-compliance' ); ?></h4>
					<p>
						<?php
						$store_link = __( 'You can buy more licences from our [store_link]online store[/store_link].', 'gdpr-cookie-compliance' );
						$store_link = str_replace( '[store_link]', '<a href="https://www.mooveagency.com/wordpress-plugins/gdpr-cookie-compliance/" target="_blank" class="gdpr_admin_link">', $store_link );
						$store_link = str_replace( '[/store_link]', '</a>', $store_link );
						apply_filters( 'gdpr_cc_keephtml', $store_link, true );
						?>
					</p>
					<p>
						<a href="https://www.mooveagency.com/wordpress-plugins/gdpr-cookie-compliance/" target="_blank" class="button button-primary">
							Buy Now
						</a>
					</p>
					<br />
					<hr />
				</th>
			</tr>
			<?php
			if ( 'max_activation_reached' === $type ) :
				?>
					<tr>
						<th scope="row" style="padding: 0 0 10px 0;">
							<label><?php esc_html_e( 'Enter a new licence key:', 'gdpr-cookie-compliance' ); ?></label>
						</th>
					</tr>
					<tr>
						<td style="padding: 0;">
							<input name="moove_gdpr_license_key" required min="35" type="text" id="moove_gdpr_license_key" value="" class="regular-text">
						</td>
					</tr>
				<?php
			endif;
		elseif ( 'invalid' === $type ) :
			?>
			<tr>
				<th scope="row" style="padding: 0 0 10px 0;">
					<hr />
					<h4 style="margin-bottom: 0;"><?php esc_html_e( 'Buy licence', 'gdpr-cookie-compliance' ); ?></h4>
					<p>
						<?php
						$store_link = __( 'You can buy licences from our [store_link]online store[/store_link].', 'gdpr-cookie-compliance' );
						$store_link = str_replace( '[store_link]', '<a href="https://www.mooveagency.com/wordpress-plugins/gdpr-cookie-compliance/" target="_blank" class="gdpr_admin_link">', $store_link );
						$store_link = str_replace( '[/store_link]', '</a>', $store_link );
						apply_filters( 'gdpr_cc_keephtml', $store_link, true );
						?>
					</p>
					<p>
						<a href="https://www.mooveagency.com/wordpress-plugins/gdpr-cookie-compliance/" target="_blank" class="button button-primary">Buy Now</a>
					</p>
					<br />
					<hr />
				</th>
			</tr>
			<tr>
				<th scope="row" style="padding: 0 0 10px 0;">
					<label><?php esc_html_e( 'Enter your licence key:', 'gdpr-cookie-compliance' ); ?></label>
				</th>
			</tr>
			<tr>
				<td style="padding: 0;">
					<input name="moove_gdpr_license_key" required min="35" type="text" id="moove_gdpr_license_key" value="" class="regular-text">
				</td>
			</tr>
			<?php
		else :
			?>
			<tr>
				<th scope="row" style="padding: 0 0 10px 0;">
					<label><?php esc_html_e( 'Enter licence key:', 'gdpr-cookie-compliance' ); ?></label>
				</th>
			</tr>
			<tr>
				<td style="padding: 0;">
					<input name="moove_gdpr_license_key" required min="35" type="text" id="moove_gdpr_license_key" value="" class="regular-text">
				</td>
			</tr>
			<?php
		endif;
	}

	/**
	 * GDPR Alert Box
	 *
	 * @param string $type Type.
	 * @param array  $response Response.
	 * @param string $gdpr_key GDPR Key.
	 */
	public static function gdpr_get_alertbox( $type, $response, $gdpr_key ) {
		if ( 'error' === $type ) :
			$messages = isset( $response['message'] ) && is_array( $response['message'] ) ? implode( '</p><p>', $response['message'] ) : '';
			if ( isset( $response['type'] ) && ( $response['type'] === 'inactive' || $response['type'] === 'max_activation_reached' || $response['type'] === 'suspended' ) ) : // phpcs:ignore
				$gdpr_default_content = new Moove_GDPR_Content();
				$option_key           = $gdpr_default_content->moove_gdpr_get_key_name();
				$gdpr_key             = $gdpr_default_content->gdpr_get_activation_key( $option_key );

				update_option(
					$option_key,
					array(
						'key'          => $response['key'],
						'deactivation' => strtotime( 'now' ),
					)
				);
				$gdpr_key = $gdpr_default_content->gdpr_get_activation_key( $option_key );
			endif;
			?>
			<div class="gdpr-admin-alert gdpr-admin-alert-error">
				<div class="gdpr-alert-content">        
					<div class="gdpr-licence-key-wrap">
						<p><?php esc_html_e( 'License key:', 'gdpr-cookie-compliance' ); ?>: 
						<strong><?php echo esc_attr( apply_filters( 'gdpr_licence_key_visibility', isset( $response['key'] ) ? $response['key'] : ( isset( $gdpr_key['key'] ) ? $gdpr_key['key'] : $gdpr_key ) ) ); ?></strong>								
						</p>
					</div>
					<!-- .gdpr-licence-key-wrap -->
					<p><?php apply_filters( 'gdpr_cc_keephtml', $messages, true ); ?></p>
				</div>
				<span class="dashicons dashicons-dismiss"></span>
			</div>
			<!--  .gdpr-admin-alert gdpr-admin-alert-success -->
			<?php
		else :
			$messages = isset( $response['message'] ) && is_array( $response['message'] ) ? implode( '</p><p>', $response['message'] ) : '';
			?>
			<div class="gdpr-admin-alert gdpr-admin-alert-success">    
				<div class="gdpr-alert-content">
					<div class="gdpr-licence-key-wrap">
						<p><?php esc_html_e( 'License key:', 'gdpr-cookie-compliance' ); ?>: 
						<strong><?php echo esc_attr( apply_filters( 'gdpr_licence_key_visibility', isset( $response['key'] ) ? $response['key'] : ( isset( $gdpr_key['key'] ) ? $gdpr_key['key'] : $gdpr_key ) ) ); ?></strong>								
						</p>
					</div>
					<!-- .gdpr-licence-key-wrap -->					
					<p><?php apply_filters( 'gdpr_cc_keephtml', $messages, true ); ?></p>
				</div>
				<span class="dashicons dashicons-yes-alt"></span>
			</div>
			<!--  .gdpr-admin-alert gdpr-admin-alert-success -->
			<?php
		endif;
		do_action( 'gdpr_plugin_updater_notice' );
	}

	/**
	 * GDPR Update Alert
	 *
	 * @return void
	 */
	public static function gdpr_premium_update_alert() {

		$plugins     = get_site_transient( 'update_plugins' );
		$lm          = new Moove_GDPR_License_Manager();
		$plugin_slug = $lm->get_add_on_plugin_slug();

		if ( isset( $plugins->response[ $plugin_slug ] ) && is_plugin_active( $plugin_slug ) ) :
			$version = $plugins->response[ $plugin_slug ]->new_version;

			$current_user = wp_get_current_user();
			$user_id      = isset( $current_user->ID ) ? $current_user->ID : 0;
			$dismiss      = get_option( 'gdpr_hide_update_notice_' . $user_id );

			if ( isset( $plugins->response[ $plugin_slug ]->package ) && ! $plugins->response[ $plugin_slug ]->package ) :
				$gdpr_default_content = new Moove_GDPR_Content();
				$option_key           = $gdpr_default_content->moove_gdpr_get_key_name();
				$gdpr_key             = $gdpr_default_content->gdpr_get_activation_key( $option_key );
				$license_key          = isset( $gdpr_key['key'] ) ? sanitize_text_field( $gdpr_key['key'] ) : false;
				$renew_link           = MOOVE_SHOP_URL . '?renew=' . $license_key;
				$license_manager      = admin_url( 'admin.php' ) . '?page=moove-gdpr_licence';
				$purchase_link        = 'https://www.mooveagency.com/wordpress-plugins/gdpr-cookie-compliance/';
				$notice_text          = '';
				if ( $license_key && isset( $gdpr_key['activation'] ) ) :
					// Expired.
					$notice_text = 'Update is not available until you <a href="' . $renew_link . '" target="_blank">renew your licence</a>. You can also update your licence key in the <a href="' . $license_manager . '">Licence Manager</a>.';
				elseif ( $license_key && isset( $gdpr_key['deactivation'] ) ) :
					// Deactivated.
					$notice_text = 'Update is not available until you <a href="' . $purchase_link . '" target="_blank">purchase a licence</a>. You can also update your licence key in the <a href="' . $license_manager . '">Licence Manager</a>.';
				elseif ( ! $license_key ) :
					// No license key installed.
					$notice_text = 'Update is not available until you <a href="' . $purchase_link . '" target="_blank">purchase a licence</a>. You can also update your licence key in the <a href="' . $license_manager . '">Licence Manager</a>.';
				endif;
				?>
			<div class="gdpr-cookie-alert gdpr-cookie-update-alert" style="display: inline-block;">
				<h4>
					<?php esc_html_e( 'There is a new version of GDPR Cookie Compliance - Premium Add-On.', 'gdpr-cookie-compliance' ); ?></h4>
				<p><?php apply_filters( 'gdpr_cc_keephtml', $notice_text, true ); ?></p>
			</div>
			<!--  .gdpr-cookie-alert -->
				<?php
		endif;
	endif;
	}

	/**
	 * Licence Action Buttons
	 *
	 * @param boolean $show_title Show title.
	 */
	public static function gdpr_cc_licence_manager_action_button( $show_title = true ) {
		if ( function_exists( 'is_multisite' ) && is_multisite() ) :
			$is_bulk_view = isset( $_GET['view'] ); // phpcs:ignore
			$button_view  = $is_bulk_view ? '' : '&view=bulk';
			?>
			<div class="gdpr-multisite-bal">
				<?php if ( $show_title ) : ?>
					<h3><?php esc_html_e( 'Bulk Multisite Activation', 'gdpr-cookie-compliance' ); ?></h3>
					<p><?php esc_html_e( 'You can activate the Licence Key on all your subsites using the tool below.', 'gdpr-cookie-compliance' ); ?></p>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=moove-gdpr_licence' . $button_view ) ); ?>" class="button button-primary button-inverse">
						<?php
						if ( ! $is_bulk_view ) :
							esc_html_e( 'Bulk Licence Activation', 'gdpr-cookie-compliance' );
							else :
								esc_html_e( 'Single Activation', 'gdpr-cookie-compliance' );
							endif;
							?>
					</a>
				<?php elseif ( $is_bulk_view ) : ?>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=moove-gdpr_licence' . $button_view ) ); ?>" class="button button-primary button-inverse">
						<?php esc_html_e( 'Single Activation', 'gdpr-cookie-compliance' ); ?>
					</a>
				<?php endif; ?>

			</div>
			<!-- .gdpr-multisite-bal -->
			<?php
		endif;
	}
}
new Moove_GDPR_Content();
