<div class="dev-box">
    <div class="box-title">
        <h3><?php esc_html_e( "Logs", "defender-security" ) ?></h3>
        <a href="<?php echo admin_url('admin-ajax.php?action=lockoutExportAsCsv') ?>" class="button button-small button-secondary"><?php _e( "Export CSV", "defender-security" ) ?></a>
        <div class="sort">
            <span><?php _e( "Sort by", "defender-security" ) ?></span>
            <select name="sort" id="lockout-logs-sort">
                <option value="latest"><?php _e( "Latest", "defender-security" ) ?></option>
                <option value="oldest"><?php _e( "Oldest", "defender-security" ) ?></option>
                <option value="ip"><?php _e( "IP Address", "defender-security" ) ?></option>
            </select>
        </div>
        <!--        <button type="button" data-target=".lockout-logs-filter" rel="show-filter"-->
        <!--                class="button button-secondary button-small">-->
		<?php //_e( "Filter", "defender-security" ) ?><!--</button>-->
    </div>
    <div class="box-content">
		<?php
		$table = new \WP_Defender\Module\IP_Lockout\Component\Logs_Table();
		$table->prepare_items();
		$table->display();
		?>
    </div>
</div>
<!--<dialog id="bulk" class="no-close">-->
<!--    <form id="lockout-bulk" method="post" class="tc">-->
<!--        <h4>--><?php //_e( "Bulk Actions", "defender-security" ) ?><!--</h4>-->
<!--        <button type="submit" class="button button-primary button-small">--><?php //_e( "Ban", "defender-security" ) ?><!--</button>-->
<!--        <button type="submit" class="button button-secondary button-small">--><?php //_e( "Whitelist", "defender-security" ) ?><!--</button>-->
<!--    </form>-->
<!--</dialog>-->
