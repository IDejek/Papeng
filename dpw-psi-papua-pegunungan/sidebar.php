<?php
/**
 * Sidebar Template
 * @package DPW_PSI_Papua_Pegunungan
 */
if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! is_active_sidebar( 'sidebar-blog' ) ) return;
?>
<aside class="dpw-sidebar">
    <?php dynamic_sidebar( 'sidebar-blog' ); ?>
</aside>
