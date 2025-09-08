<?php
/**
 * The sidebar containing the main widget area
 *
 * @package Trinity
 */

if (!is_active_sidebar('sidebar-1')) {
    return;
}
?>

<aside id="secondary" class="widget-area sidebar col-lg-4" role="complementary">
    <div class="sidebar-content">
        <?php dynamic_sidebar('sidebar-1'); ?>
    </div>
</aside>
