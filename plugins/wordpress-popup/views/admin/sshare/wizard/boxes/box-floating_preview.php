<label><?php esc_attr_e( "Module preview", Opt_In::TEXT_DOMAIN ); ?></label>

<div id="wph-sshare-preview-floating">

    <?php $this->render( "general/modals/shares-floating", array( 'is_preview' => true ) ); ?>

</div>
