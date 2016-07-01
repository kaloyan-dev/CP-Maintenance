<?php

class CP_Maintenance {
	public static function install() {
		add_option( 'cpm-status', 'off' );
		add_option( 'cpm-redirect', 'redirect' );
		add_option( 'cpm-redirect-url', '' );
		add_option( 'cpm-redirect-page', 0 );
		add_option( 'cpm-redirect-html', '' );
	}

	public static function uninstall() {
		delete_option( 'cpm-status' );
		delete_option( 'cpm-redirect' );
		delete_option( 'cpm-redirect-url' );
		delete_option( 'cpm-redirect-page' );
		delete_option( 'cpm-redirect-html' );
	}

	public static function add_menu_page() {
		add_menu_page(
			__( 'Maintenance', 'cpm' ),
			__( 'Maintenance', 'cpm' ),
			'manage_options',
			'cp_maintenance',
			array( 'CP_Maintenance', 'render_menu_page' ),
			'div',
			110
		);
	}

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
			$redirect_html = stripslashes($_POST['cpm-redirect-html']);

			update_option( 'cpm-status'       , $status );
			update_option( 'cpm-redirect'     , $redirect );
			update_option( 'cpm-redirect-url' , $redirect_url );
			update_option( 'cpm-redirect-page', $redirect_page );
			update_option( 'cpm-redirect-html', $redirect_html );
		}
		?>
		<div class="wrap">
			<div id="CPM-wrap">
				<h2><?php _e( 'Maintenance Options', 'cpm' ); ?></h2>
				<?php echo $message; ?>
				<form method="post" action="?page=cp_maintenance">
					<?php
						$status   = get_option( 'cpm-status' );
						$redirect = get_option( 'cpm-redirect' );
					?>
					<div class="cpm-section cpm-activate">
						<h3><?php _e( 'Enable Maintenance Mode:', 'cpm' ); ?></h3>
						<p class="cpm-js-hide">
							<label>
								<input type="radio" name="cpm-status" value="off" <?php checked( $status === 'off' ); ?> />
								<span><?php _e( 'OFF', 'cpm' ); ?></span>
							</label>
						</p>
						<p class="cpm-js-hide">
							<label>
								<input type="radio" name="cpm-status" value="on" <?php checked( $status === 'on' ); ?> />
								<span><?php _e( 'ON', 'cpm' ); ?></span>
							</label>
						</p>
					</div>
					<div class="cl">&nbsp;</div>
					<div class="cpm-section">
						<label>
							<input type="radio" name="cpm-redirect" value="redirect" <?php checked( $redirect === 'redirect' ); ?> />
							<span><?php _e( 'Redirect to a specific URL:', 'cpm' ); ?></span>
						</label>
						<input type="text" class="regular-text" value="<?php echo $redirect_url; ?>" name="cpm-redirect-url" />
					</div>
					<?php
						$pages = get_pages();
						if ( $pages ):
							$pages_list = array();
							foreach ( $pages as $page ) {
								$pages_list[$page->ID] = $page->post_title;
							}
							?>
							<div class="cpm-section">
								<label>
									<input type="radio" name="cpm-redirect" value="page" <?php checked( $redirect === 'page' ); ?>/>
									<span><?php _e( 'Redirect to a specific page:', 'cpm' ); ?></span>
								</label>

								<select name="cpm-redirect-page">
									<?php foreach ( $pages_list as $id => $title ): ?>
										<option value="<?php echo $id; ?>" <?php selected( $redirect_page === $id ); ?>>
											<?php echo $title; ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
						<?php endif;
					?>
					<div class="cpm-section">
						<label>
							<input type="radio" name="cpm-redirect" value="html" <?php checked( $redirect === 'html' ); ?> />
							<span><?php _e( 'Use custom HTML / CSS:', 'cpm' ); ?></span>
						</label>
						<textarea id="cpm-html" name="cpm-redirect-html"><?php echo $redirect_html; ?></textarea>
					</div>
					<div class="cpm-section">
						<label>
							<input type="radio" name="cpm-redirect" value="login" <?php checked( $redirect === 'login' ); ?> />
							<span><?php _e( 'Redirect to the login page', 'cpm' ); ?></span>
						</label>
					</div>
					<div class="cpm-section">
						<input type="submit" class="button button-primary" value="Save Settings" />
						<input type="hidden" value="true" name="cpm-save-settings" />						
					</div>
				</form>
			</div>
		</div>
		<?php
	}

	public static function scripts_and_styles() {
		wp_enqueue_style( 'cpm-style', plugins_url( '../admin/css/style.css', __FILE__ ), array(), '1.0', 'screen' );

		wp_enqueue_script( 'cpm-functions', plugins_url( '../admin/js/functions.js', __FILE__ ), array( 'jquery' ), '1.0' );
	}

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
