<?php
/**
 * Copyright 2013 Nelio Software S.L.
 * This script is distributed under the terms of the GNU General Public
 * License.
 *
 * This script is free software: you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free
 * Software Foundation, either version 3 of the License.
 *
 * This script is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for
 * more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program. If not, see <http://www.gnu.org/licenses/>.
 */


if ( !class_exists( 'NelioABInvalidConfigPage' ) ) {

	require_once( NELIOAB_UTILS_DIR . '/admin-ajax-page.php' );

	class NelioABInvalidConfigPage extends NelioABAdminAjaxPage {


		public function __construct() {
			parent::__construct( '' );
			$this->set_icon( 'icon-nelioab' );
		}


		protected function do_render() {

			echo "<div class='nelio-message'>";

			printf( '<img class="animated flipInY" src="%s" alt="%s" />',
				esc_url( nelioab_admin_asset_link( '/images/settings-icon.png' ) ),
				esc_attr__( 'Information Notice', 'nelio-ab-testing' )
			);

			$tac_html = '';

			if ( NelioABAccountSettings::can_free_trial_be_started() ) {

				echo '<h2>' . esc_html__( 'Welcome!', 'nelio-ab-testing' ) . '</h2>';
				printf( '<p class="nelio-admin-explanation">%s</p>',
					__( 'Thank you very much for installing <strong>Nelio A/B Testing</strong> by <em>Nelio Software</em>. You\'re just one step away from optimizing your WordPress site.', 'nelio-ab-testing' )
				);
				printf( '<p class="nelio-admin-explanation"><strong>%s</strong></p>',
					esc_html__( 'Let\'s get started!', 'nelio-ab-testing' )
				);

				$account_url = admin_url( 'admin.php?page=nelioab-account&nabmode=my-account' );
				$my_account_button = sprintf(
					'<a href="%s" class="button nab-account button-disabled">%s</a>',
					esc_url( $account_url ), esc_html__( 'Use Nelio Account', 'nelio-ab-testing' )
				);
				$free_trial_button = sprintf(
					'<a href="%s" class="button button-no-longer-primary nab-trial button-disabled">%s</a>',
					'#', esc_html__( 'Start Free Trial', 'nelio-ab-testing' )
				);

				$tac_html = sprintf( // @codingStandardsIgnoreLine
					_x( 'Please, accept our <a target="_blank" href="%s">Terms and Conditions</a> and our <a target="_blank" href="%s">Privacy Policy</a> to use our plugin.', 'user', 'nelio-ab-testing' ),
					esc_url( _x( 'https://neliosoftware.com/nelio-ab-testing-terms-conditions/?plugin=start-ft', 'text', 'nelio-ab-testing' ) ),
					esc_url( _x( 'https://neliosoftware.com/privacy-policy-cookies/', 'text', 'nelio-ab-testing' ) )
				);

			} else if ( ! NelioABAccountSettings::is_email_valid() ||
				! NelioABAccountSettings::is_reg_num_valid() ||
				! NelioABAccountSettings::are_terms_and_conditions_accepted() ) {

				echo '<h2>' . esc_html__( 'Welcome!', 'nelio-ab-testing' ) . '</h2>';
				printf( '<p class="nelio-admin-explanation">%s</p>',
					__( 'Thank you very much for installing <strong>Nelio A/B Testing</strong> by <em>Nelio Software</em>. You\'re just one step away from optimizing your WordPress site.', 'nelio-ab-testing' )
				);
				printf( '<p class="nelio-admin-explanation"><strong>%s</strong></p>',
					esc_html__( 'Let\'s get started!', 'nelio-ab-testing' )
				);

				$account_url = admin_url( 'admin.php?page=nelioab-account&nabmode=my-account' );
				$my_account_button = sprintf(
					'<a href="%s" class="button button-no-longer-primary nab-account button-disabled">%s</a>',
					esc_url( $account_url ), esc_html__( 'Use Nelio Account', 'nelio-ab-testing' )
				);
				$free_trial_button = '';

			} else {

				echo '<h2>' . esc_html__( 'Setup', 'nelio-ab-testing' ) . '</h2>';
				printf( '<p class="nelio-admin-explanation">%s</p>',
					__( 'You\'re just one step away from optimizing WordPress with <strong style="white-space:nowrap;">Nelio A/B Testing</strong> by <em>Nelio Software</em>. Are you ready?', 'nelio-ab-testing' )
				);
				printf( '<p class="nelio-admin-explanation"><strong>%s</strong></p>',
					esc_html__( 'Activate this site in your account.', 'nelio-ab-testing' )
				);

				$account_url = admin_url( 'admin.php?page=nelioab-account&nabmode=my-account' );
				$my_account_button = sprintf(
					'<a href="%s" class="button button-no-longer-primary nab-account button-disabled">%s</a>',
					esc_url( $account_url ), esc_html__( 'Open My Account', 'nelio-ab-testing' )
				);
				$free_trial_button = '';

			}

			if ( current_user_can( 'manage_options' ) ) {
				printf( '<p id="nelio-cta-buttons" class="nelio-admin-explanation">%s %s</p>',
					$my_account_button, $free_trial_button );
			} else {
				esc_html_e( 'Please, ask the site administrator to configure the plugin.' );
			}

			if ( strlen( $tac_html ) > 0 ) {

				echo '<p style="padding-top:3em;font-size:95%;color:gray;"><input class="nab-accept" type="checkbox" /> ' . $tac_html . '</p>'; ?>
				<script type="text/javascript">
				(function( $ ) {
					var $accountButton = $( '#nelio-cta-buttons .button.nab-account' );
					var $trialButton = $( '#nelio-cta-buttons .button.nab-trial' );
					var $legal = $( '.nab-accept' );

					function enable( $button ) {
						$button.removeClass( 'button-disabled' );
						if ( $button.hasClass( 'button-no-longer-primary' ) ) {
							$button.removeClass( 'button-no-longer-primary' ).addClass( 'button-primary' );
						}//end if
					}

					function disable( $button ) {
						$button.addClass( 'button-disabled' );
						if ( $button.hasClass( 'button-primary' ) ) {
							$button.removeClass( 'button-primary' ).addClass( 'button-no-longer-primary' );
						}//end if
					}

					$legal.on( 'click', function() {

						if ( $legal.is( ':checked' ) ) {
							enable( $accountButton );
							enable( $trialButton );
						} else {
							disable( $accountButton );
							disable( $trialButton );
						}//end if

					});

					$( '#nelio-cta-buttons .button' ).on( 'click', function( ev ) {
						if ( $( this ).hasClass( 'button-disabled' ) ) {
							ev.preventDefault();
						}//end if
					});

				})( jQuery );
				</script><?php

			}

			if ( NelioABAccountSettings::can_free_trial_be_started() ) { ?>
				<script type="text/javascript">
				(function($) {
					$('#nelio-cta-buttons .nab-trial').click(function() {
						if ( $( this ).hasClass( 'button-disabled' ) ) {
							return;
						}//end if
						smoothTransitions();
						$.ajax({
							url: ajaxurl,
							data: {
								action: 'nelioab_start_free_trial'
							},
							type: 'post',
							success: function(res) {
								if ( "OK" === res ) {
									window.location = <?php echo json_encode( admin_url( 'admin.php?page=nelioab-account&nabmode=free-trial' ) ); ?>;
								} else {
									window.location.reload();
								}
							},
						});
					});
				})(jQuery);
				</script>
				<?php
			}

			echo '</div>';
		}

	}//NelioABInvalidConfigPage

}

