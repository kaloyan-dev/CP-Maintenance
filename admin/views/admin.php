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
                <p class="cpm-hidden">
                    <label>
                        <input type="radio" name="cpm-status" value="off" <?php checked( $status === 'off' ); ?> />
                        <span><?php _e( 'OFF', 'cpm' ); ?></span>
                    </label>
                    <label>
                        <input type="radio" name="cpm-status" value="on" <?php checked( $status === 'on' ); ?> />
                        <span><?php _e( 'ON', 'cpm' ); ?></span>
                    </label>
                </p>
                <?php
                    printf(
                        '<div class="%1$s"></div>',
                        'on' === $status ? 'cpm-controls cpm-on' : 'cpm-controls'
                    );
                ?>
            </div>
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