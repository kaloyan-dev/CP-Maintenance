<?php

class CP_Maintenance {

	/**
	 * Sets up the plugin options
	 *
	 * @return void
	 */
	public static function install() {
		add_option( 'cpm-status', 'off' );
		add_option( 'cpm-redirect', 'redirect' );
		add_option( 'cpm-redirect-url', '' );
		add_option( 'cpm-redirect-page', 0 );
		add_option( 'cpm-redirect-html', '' );
	}

	/**
	 * Uninstall method - removes the plugin options
	 *
	 * @return void
	 */
	public static function uninstall() {
		delete_option( 'cpm-status' );
		delete_option( 'cpm-redirect' );
		delete_option( 'cpm-redirect-url' );
		delete_option( 'cpm-redirect-page' );
		delete_option( 'cpm-redirect-html' );
	}

	/**
	 * Adds the plugin page in the WordPress dashboard
	 *
	 * @return  void
	 */
	public static function add_menu_page() {
		add_menu_page(
			__( 'Maintenance', 'cpm' ),
			__( 'Maintenance', 'cpm' ),
			'manage_options',
			'cp_maintenance',
			array( 'CP_Maintenance', 'render_menu_page' ),
			'dashicons-hammer',
			110
		);
	}

	/**
	 * Renders the plugin menu page
	 *
	 * @return void
	 */
	public static function render_menu_page() {
		$message       = '';
		$status        = get_option( 'cpm-status' );
		$redirect      = get_option( 'cpm-redirect' );
		$redirect_url  = get_option( 'cpm-redirect-url' );
		$redirect_page = intval( get_option( 'cpm-redirect-page' ) );
		$redirect_html = get_option( 'cpm-redirect-html' );

		if ( isset( $_POST['cpm-save-settings'] ) ) {
			$message = '
				<div id="setting-error-settings_updated" class="updated settings-error">
					<p>
						<strong>' . __( 'Settings saved.', 'cpm' ) . '</strong>
					</p>
				</div>';
			$status        = $_POST['cpm-status'];
			$redirect      = $_POST['cpm-redirect'];
			$redirect_url  = $_POST['cpm-redirect-url'];
			$redirect_page = intval( $_POST['cpm-redirect-page'] );
			$redirect_html = stripslashes( $_POST['cpm-redirect-html'] );

			update_option( 'cpm-status'       , $status );
			update_option( 'cpm-redirect'     , $redirect );
			update_option( 'cpm-redirect-url' , $redirect_url );
			update_option( 'cpm-redirect-page', $redirect_page );
			update_option( 'cpm-redirect-html', $redirect_html );
		}

		include_once( CPM_DIR . 'admin/views/admin.php' );
	}

	/**
	 * Enqueues the plugin scripts and styles
	 *
	 * @return void
	 */
	public static function scripts_and_styles() {
		wp_enqueue_style( 'cpm-style', plugins_url( '../admin/css/style.css', __FILE__ ), array(), '1.0', 'screen' );

		wp_enqueue_script( 'cpm-functions', plugins_url( '../admin/js/functions.js', __FILE__ ), array(), '1.0', true );
	}

	/**
	 * Handles the front-end logic of the maintenance functionality
	 *
	 * @return void
	 */
	public static function front_handle() {
		if ( is_user_logged_in() ) {
			return;
		}

		$status = get_option( 'cpm-status' );

		if ( $status !== 'on' ) {
			return;
		}

		$id            = get_the_id();
		$redirect      = get_option( 'cpm-redirect' );
		$redirect_url  = get_option( 'cpm-redirect-url' );
		$redirect_page = intval( get_option( 'cpm-redirect-page' ) );
		$redirect_html = get_option( 'cpm-redirect-html' );

		if ( $redirect === 'redirect' && $redirect_url && get_permalink( $id ) !== $redirect_url ) {
			wp_redirect( $redirect_url );
			exit;
		}

		if ( $redirect === 'page' && $redirect_page && $id !== $redirect_page ) {
			$redirect_to = get_permalink( $redirect_page );
			wp_redirect( $redirect_to );
			exit;
		}

		if ( $redirect === 'html' && $redirect_html ) {
			echo $redirect_html;
			exit;
		}

		if ( $redirect === 'login' ) {
			wp_redirect( wp_login_url() );
			exit;
		}
	}
}
