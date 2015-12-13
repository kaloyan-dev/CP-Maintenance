<?php

# Add the menu page
add_action('template_redirect', array('CP_Maintenance', 'front_handle'), 11);