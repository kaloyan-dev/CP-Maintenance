<?php

# Add the menu page
add_action( 'admin_menu', array( 'CP_Maintenance', 'add_menu_page' ) );

# Enqueue the necessary scripts and styles
add_action( 'admin_enqueue_scripts', array( 'CP_Maintenance', 'scripts_and_styles' ) );
