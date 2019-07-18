<script type="text/template" id="_nelioab-alternative-partial">

<div class="nelio-alternative original-alternative postbox nelio-card">
	<div [* if ( winner ) { *] style="<?php
		require_once( NELIOAB_UTILS_DIR . '/wp-helper.php' );
		$colorscheme = NelioABWpHelper::get_current_colorscheme();
		echo esc_attr( 'background:' . $colorscheme['primary'] . ';color:' . $colorscheme['foreground'] );
	?>"[* } *] class="alt-info-header[* if ( ! alternativeNumber ) { *] masterTooltip" title="<?php
		esc_attr_e( 'This is the original version', 'nelio-ab-testing' );
?>" [* } else { *]"[* } *]>
		[*= iconTag *]
		<span class="alt-title">[*~ name *]</span>
	</div>
	<div class="alt-info-body">
		<div class="alt-screen[* if ( ! preview ) { *] no-hover[* } *]" style="color:[* if ( winner ) { *]<?php echo $colorscheme['winner']; ?>[* } else { *]<?php echo $colorscheme['primary']; ?>[* } *];">
			[* if ( preview ) { *]
				<a href="[*= preview *]" target="_blank"><span class="more-details"><?php echo esc_html( _x( 'View', 'command', 'nelio-ab-testing' ) ); ?></span></a>
			[* } *]
			[* if ( image ) { *]
				<img class="alt-theme-img" src="[*= image *]" width="320" height="240">
			[* } else if ( ! alternativeNumber ) { *]
				<div class="alt-name"><?php
					esc_html_e( 'Original', 'nelio-ab-testing' );
				?></div>
			[* } else { *]
				<div class="alt-name"><?php
					esc_html_e( 'Alternative', 'nelio-ab-testing' );
				?></div>
				<div class="alt-number">[*~ alternativeNumber *]</div>
			[* } *]
		</div>
		<div class="alt-stats">
			<canvas class="alt-stats-graphic" id="[*~ graphicId *]"></canvas>
			<div class="alt-status">
				<div class="alt-cv">
					<span class="alt-cv-title"><?php esc_html_e( 'Conversions / Views', 'nelio-ab-testing' ); ?></span>
					<span class="alt-cv">[*~ conversionViews *]</span>
				</div>
				<div class="alt-cr">
					<span class="alt-cr-title"><?php esc_html_e( 'Conversion Rate', 'nelio-ab-testing' ); ?></span>
					<span class="alt-cr">[*~ conversionRate *]</span>
				</div>
				[* if ( ! alternativeNumber ) { *]
					<div class="alt-stats">
						<div class="alt-name"><?php
							esc_html_e( 'Original Version', 'nelio-ab-testing' );
						?></div>
					</div>
				[* } else if ( showImprovement && 'up' === arrowDirection ) { *]
					<div class="alt-stats" style="color:green;">
						<span class="alt-if"><i class="fa fa-arrow-up" style="vertical-align: top;"></i>[*~ improvementFactor *]</span>
						<span class="alt-ii"><i class="fa fa-arrow-up" style="vertical-align: top;"></i>[*~ moneyGain *]</span>
					</div>
				[* } else if ( showImprovement && 'down' === arrowDirection ) { *]
					<div class="alt-stats" style="color:red;">
						<span class="alt-if"><i class="fa fa-arrow-down" style="vertical-align: top;"></i>[*~ improvementFactor *]</span>
						<span class="alt-ii"><i class="fa fa-arrow-down" style="vertical-align: top;"></i>[*~ moneyGain *]</span>
					</div>
				[* } *]
			</div>
		</div>
	</div>
	<div class="alt-info-footer">
		<div class="alt-info-footer-content">
			[* if ( buttons ) { *]
				[*= buttons *]
			[* } *]
		</div>
	</div>
</div>

</script>
