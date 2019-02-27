<label><?php esc_attr_e( "Module preview", Opt_In::TEXT_DOMAIN ); ?></label>

<div id="wph-sshare-preview-widget">

    <?php $this->render( "general/modals/shares-widget", array( 'is_preview' => true ) ); ?>

</div>
