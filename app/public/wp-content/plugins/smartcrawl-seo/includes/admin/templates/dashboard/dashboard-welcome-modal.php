<?php $id = 'wds-welcome-modal'; ?>

<div class="sui-modal sui-modal-md">
	<div
		role="dialog"
		id="<?php echo esc_attr( $id ); ?>"
		class="sui-modal-content <?php echo esc_attr( $id ); ?>-dialog"
		aria-modal="true"
		aria-labelledby="<?php echo esc_attr( $id ); ?>-dialog-title"
		aria-describedby="<?php echo esc_attr( $id ); ?>-dialog-description">

		<div class="sui-box" role="document">
			<div class="sui-box-header sui-flatten sui-content-center sui-spacing-top--40">
				<div class="sui-box-banner" role="banner" aria-hidden="true">
					<img src="<?php echo esc_attr( SMARTCRAWL_PLUGIN_URL ); ?>assets/images/upgrade-welcome-header.svg" alt="<?php esc_html_e( 'Analyze Multiple Focus Keywords', 'smartcrawl-seo' ); ?>"/>
				</div>
				<button
					class="sui-button-icon sui-button-float--right" data-modal-close
					id="<?php echo esc_attr( $id ); ?>-close-button"
					type="button"
				>
					<span class="sui-icon-close sui-md" aria-hidden="true"></span>
					<span class="sui-screen-reader-text"><?php esc_html_e( 'Close this dialog window', 'smartcrawl-seo' ); ?></span>
				</button>
				<h3 class="sui-box-title sui-lg" id="<?php echo esc_attr( $id ); ?>-dialog-title">
					<?php esc_html_e( 'Analyze Multiple Focus Keywords', 'smartcrawl-seo' ); ?>
				</h3>

				<div class="sui-box-body">
					<p class="sui-description" id="<?php echo esc_attr( $id ); ?>-dialog-description">
						<span><?php esc_html_e( 'Now you can add multiple focus keywords, and SmartCrawl will give you instant recommendations on improving the SEO of your page based on your chosen primary and secondary keywords. In addition, our enhanced SEO analysis tool detects if the same primary focus keyword is used on multiple pages.', 'smartcrawl-seo' ); ?></span>
					</p>
					<button
						id="<?php echo esc_attr( $id ); ?>-get-started"
						type="button"
						class="sui-button">
						<span class="sui-loading-text">
							<?php esc_html_e( 'Got it', 'smartcrawl-seo' ); ?>
						</span>
						<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
					</button>
				</div>
			</div>
		</div>
	</div>
</div>
