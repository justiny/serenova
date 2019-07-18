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

if ( !class_exists( 'NelioABAccountPage' ) ) {

	require_once( NELIOAB_UTILS_DIR . '/admin-ajax-page.php' );
	class NelioABAccountPage extends NelioABAdminAjaxPage {

		const ACTIVE_ACCOUNT = 1;
		const DEACTIVATED_ACCOUNT = 2;

		private $p_style;
		private $email;
		private $is_email_valid;
		private $reg_num;
		private $is_reg_num_valid;
		private $tac;
		private $sites;
		private $max_sites;
		private $user_info;

		private $current_site_status;
		private $error_retrieving_registered_sites;

		private $is_account_active;

		public function __construct( $title ) {
			parent::__construct( $title );
			$this->p_style          = '';
			$this->email            = '';
			$this->reg_num          = '';
			$this->is_reg_num_valid = false;
			$this->tac              = false;
			$this->sites            = array();
			$this->max_sites        = 1;

			$this->error_retrieving_registered_sites = false;
		}

		public function set_current_site_status( $site_status ) {
			$this->current_site_status = $site_status;
		}

		public function set_user_info( $user_info ) {
			$this->user_info = $user_info;
			$this->is_account_active = isset( $this->user_info['status'] ) &&
				$this->user_info['status'] == self::ACTIVE_ACCOUNT;
		}

		public function set_email( $email ) {
			$this->email = $email;
		}

		public function set_email_validity( $is_email_valid ) {
			$this->is_email_valid = $is_email_valid;
		}

		public function set_reg_num( $reg_num ) {
			$this->reg_num = $reg_num;
		}

		public function set_reg_num_validity( $is_reg_num_valid ) {
			$this->is_reg_num_valid = $is_reg_num_valid;
		}

		public function set_error_retrieving_registered_sites() {
			$this->error_retrieving_registered_sites = true;
		}

		public function set_tac_checked( $tac ) {
			$this->tac = $tac;
		}

		public function set_registered_sites( $sites ) {
			$this->sites = $sites;
		}

		public function set_max_sites( $max_sites ) {
			$this->max_sites = $max_sites;
		}

		protected function do_render() { ?>
			<form id="nelioab_account_form" method="post">

				<input type="hidden" name="nelioab_account_form" value="true" />

				<?php
				$fields = array(
					array (
						'label'     => esc_html__( 'E-Mail', 'nelio-ab-testing' ),
						'id'        => 'settings_email',
						'callback'  => array( &$this, 'print_email_field' ),
						'mandatory' => true ),
					array (
						'label'     => esc_html__( 'Registration Number', 'nelio-ab-testing' ),
						'id'        => 'settings_reg_num',
						'callback'  => array( &$this, 'print_reg_num_field' ),
						'mandatory' => true )
				);

				$this->make_section(
					__( 'Nelio AB Testing &ndash; Account Access Details', 'nelio-ab-testing' ),
					$fields
				);
				?>

			</form>

			<?php echo $this->make_submit_button(
					__( 'Access', 'nelio-ab-testing' ),
					'nelioab_account_form'
				); ?>


			<br /><br /><br />
			<?php
			$print_plans = true;
			if ( isset( $this->user_info['status'] ) &&
			   ( $this->user_info['status'] == self::ACTIVE_ACCOUNT ||
			      $this->user_info['status'] == self::DEACTIVATED_ACCOUNT ) )
				$print_plans = false;

			if ( $print_plans ) {
				$this->set_message(
					__( 'Haven\'t you subscribed to any of our plans? <b><a href="https://neliosoftware.com/testing/pricing/?plugin=account-message" target="_blank">Check them out and choose the one that best fits you</a></b>!', 'nelio-ab-testing' ) );
			}
			?>
			<h2 style="margin-bottom:0px;padding-bottom:0px;"><?php
				$is_status_defined = false;
				$status_fg_color = '#777777';
				$status_bg_color = '#EFEFEF';
				$status_text     = __( 'UNDEFINED', 'nelio-ab-testing' );

				if ( isset( $this->user_info['status'] ) ) {
					if ( $this->user_info['status'] == self::ACTIVE_ACCOUNT ) {
						$status_fg_color = '#008800';
						$status_bg_color = '#d9ffd9';
						$status_text     = __( 'ACTIVE', 'nelio-ab-testing' );
						$is_status_defined = true;
					}
					else if ( $this->user_info['status'] == self::DEACTIVATED_ACCOUNT ) {
						$status_fg_color = '#cc0000';
						$status_bg_color = '#e5cece';
						$status_text     = __( 'NOT ACTIVE', 'nelio-ab-testing' );
						$is_status_defined = true;
					}
				}

				if ( ! $this->tac ||
						! $this->is_email_valid || ! $this->is_reg_num_valid ) {
					$status_fg_color = '#777777';
					$status_bg_color = '#EFEFEF';
					$status_text     = __( 'UNKNOWN', 'nelio-ab-testing' );
				}

				$status_title = sprintf(
					'<span style="color:%s;background-color:%s;font-size:0.5em;" class="add-new-h2">%s</span>',
					esc_attr( $status_fg_color ), esc_attr( $status_bg_color ), esc_html( $status_text ) );

				echo esc_html__( 'Account Information', 'nelio-ab-testing' ) . '&nbsp;&nbsp;' . $status_title;

			?></h2>

			<?php
			if ( ! $this->tac || ! $is_status_defined ||
					! $this->is_email_valid || ! $this->is_reg_num_valid ) {
				echo '<p>';
				esc_html_e( 'Please, fill in all required fields in order to view your account details and use our service.', 'nelio-ab-testing' );
				echo '</p>';
			}
			else { ?>

				<?php if ( !$this->user_info['agency'] && !empty( $this->user_info['firstname'] ) ) { ?>
					<p style="margin-top:0em;margin-left:3em;"><?php
						echo esc_html( sprintf( __( 'Hi %s!', 'nelio-ab-testing' ),  $this->user_info['firstname'] ) );
					?></p>
				<?php } ?>

				<p style="margin-top:0em;margin-left:3em;"><?php
					if ( !isset( $this->user_info['subscription_url'] ) ) {
						esc_html_e( 'No subscription information available.', 'nelio-ab-testing' );
					}
					else if ( $this->user_info['subscription_url'] == 'BETA' ) {
						_e( 'You are using a <b>beta free-pass</b>.', 'nelio-ab-testing' );
					}
					else {
						if ( $this->user_info['subscription_plan'] == 0 ) {
							printf( '%s<br /><a href="%s">%s</a>',
								__( 'You are using the <b>Free Trial</b> version of Nelio A/B Testing.', 'nelio-ab-testing' ),
								esc_url( $this->user_info['subscription_url'] ),
								esc_html__( 'Subscribe now and continue using our service!', 'nelio-ab-testing' ) );
						}
						else {
							if ( ! $this->user_info['agency'] ) {
								$reactivate_account_url = false;
								$button = '<p><a style="margin-left:3em;margin-bottom:1.5em;" class="button" target="_blank" href="%2$s">%1$s</a></p>';
								switch ( $this->user_info['subscription_plan'] ) {
									case NelioABAccountSettings::BASIC_SUBSCRIPTION_PLAN:
										if ( $this->is_account_active ) {
											_e( 'You are subscribed to our <b>Basic Plan</b>.', 'nelio-ab-testing' );
										} else {
											_e( 'You were subscribed to our <b>Basic Plan</b>.', 'nelio-ab-testing' );
											$reactivate_account_url = 'https://sites.fastspring.com/nelio/instant/nelioabtestingbasicservicesubscription';
										}
										break;
									case NelioABAccountSettings::PROFESSIONAL_SUBSCRIPTION_PLAN:
										if ( $this->is_account_active ) {
											_e( 'You are subscribed to our <b>Professional Plan</b>.', 'nelio-ab-testing' );
										} else {
											_e( 'You were subscribed to our <b>Professional Plan</b>.', 'nelio-ab-testing' );
											$reactivate_account_url = 'https://sites.fastspring.com/nelio/instant/nelioabtestingprofessionalservicesubscription';
										}
										break;
									case NelioABAccountSettings::ENTERPRISE_SUBSCRIPTION_PLAN:
										if ( $this->is_account_active ) {
											_e( 'You are subscribed to our <b>Enterprise Plan</b>.', 'nelio-ab-testing' );
										} else {
											_e( 'You were subscribed to our <b>Enterprise Plan</b>.', 'nelio-ab-testing' );
											$reactivate_account_url = 'https://sites.fastspring.com/nelio/instant/nelioabtestingenterpriseservicesubscription';
										}
										break;
								}

								if ( $reactivate_account_url ) {
									$reactivate_account_url = add_query_arg( 'contact_fname', $this->user_info['firstname'], $reactivate_account_url );
									$reactivate_account_url = add_query_arg( 'contact_lname', $this->user_info['lastname'],  $reactivate_account_url );
									$reactivate_account_url = add_query_arg( 'contact_email', $this->user_info['email'],     $reactivate_account_url );
									printf( $button,
										esc_html__( 'Reactivate Account', 'nelio-ab-testing' ),
										esc_url( $reactivate_account_url )
									);
								}

								if ( $this->is_account_active ) {
									echo '<br><span class="subscription-actions">';
									printf( '<a class="edit" href="%s">%s</a> ',
										esc_url( $this->user_info['subscription_url'] ),
										esc_html__( 'Edit Subscription', 'nelio-ab-testing' )
									);
									printf( '<a class="delete" href="%s">%s</a> ',
										esc_url( $this->user_info['subscription_url'] ),
										esc_html__( 'Cancel Subscription', 'nelio-ab-testing' )
									);
									echo '</span>';

									$upgrade_message = 'I\'d like to upgrade to the %s Plan. I\'m subscribed to Nelio A/B Testing with the following e-mail address: %s.';

									if ( $this->user_info['subscription_plan'] == NelioABAccountSettings::BASIC_SUBSCRIPTION_PLAN ) {
										echo '<br />';
										printf(
											'<a href="%2$s">%1$s</a>',
											esc_html__( 'Upgrade to our Professional Plan.', 'nelio-ab-testing' ),
											esc_attr( 'mailto:support@neliosoftware.com?' .
												'subject=Nelio%20A%2FB%20Testing%20-%20Upgrade%20to%20Professional%20Plan&' .
												'body=' . urlencode( sprintf( $upgrade_message, 'Professional', NelioABAccountSettings::get_email() ) )
											)
										);
									}

									if ( $this->user_info['subscription_plan'] == NelioABAccountSettings::PROFESSIONAL_SUBSCRIPTION_PLAN ) {
										echo '<br />';
										printf(
											'<a href="%2$s">%1$s</a>',
											esc_html__( 'Upgrade to our Enterprise Plan.', 'nelio-ab-testing' ),
											esc_attr( 'mailto:support@neliosoftware.com?' .
												'subject=Nelio%20A%2FB%20Testing%20-%20Upgrade%20to%20Enterprise%20Plan&' .
												'body=' . urlencode( sprintf( $upgrade_message, 'Enterprise', NelioABAccountSettings::get_email() ) )
											)
										);
									}

								}
							}
							else {
								switch ( $this->user_info['subscription_plan'] ) {
									case NelioABAccountSettings::BASIC_SUBSCRIPTION_PLAN:
										printf( __( 'You are subscribed to Nelio A/B Testing <b>Basic Plan</b> thanks to %s.', 'nelio-ab-testing' ),
											esc_html( $this->user_info['agencyname'] ) );
										echo '<br />';
										break;
									case NelioABAccountSettings::PROFESSIONAL_SUBSCRIPTION_PLAN:
										printf( __( 'You are subscribed to Nelio A/B Testing <b>Professional Plan</b> thanks to %s.', 'nelio-ab-testing' ),
											esc_html( $this->user_info['agencyname'] ) );
										echo '<br />';
										break;
									case NelioABAccountSettings::ENTERPRISE_SUBSCRIPTION_PLAN:
										printf( __( 'You are subscribed to Nelio A/B Testing <b>Enterprise Plan</b> thanks to %s.', 'nelio-ab-testing' ),
											esc_html( $this->user_info['agencyname'] ) );
										echo '<br />';
										break;
								}
							}
						}
					}
				?></p>

				<?php
				$post_quota = '';
				if ( $this->is_account_active ) {
					if ( !$this->user_info['agency'] ) {
						if ( isset( $this->user_info['total_quota'] ) )
							$post_quota = sprintf( __( '<br />Your current plan permits up to %1$s page views under test per month. If you need more quota, please consider buying additional page views as you need them using the button below, or <a href="%2$s">contact us for an update of your monthly quota</a>.', 'nelio-ab-testing' ),
								esc_html( number_format_i18n( $this->user_info['total_quota'] ) ),
								esc_attr( 'mailto:support@neliosoftware.com?subject=Nelio%20A%2FB%20Testing%20-%20Monthly%20Quota%20Update' ) );
					}
					else {
						$post_quota = sprintf( __( '<br />Your current plan permits up to %1$s page views under test per month. If you need more quota, please contact <a href="%2$s">%3$s</a>.', 'nelio-ab-testing' ),
								esc_html( number_format_i18n( $this->user_info['total_quota'] ) ),
								esc_html( $this->user_info['agencymail'] ),
								esc_html( $this->user_info['agencyname'] ) );
					}

					if ( !NelioABAccountSettings::is_using_free_trial() && isset( $this->user_info['quota'] ) ) { ?>
						<p style="margin-top:0em;margin-left:3em;max-width:600px;">
						<b><?php esc_html_e( 'Available Quota:', 'nelio-ab-testing' ); ?></b>
						<?php
							$the_total_quota = $this->user_info['total_quota'];
							$the_quota       = $this->user_info['quota'];
							$quota_color     = '#54ae65';
							if ( $the_quota < ( $the_total_quota * 0.15 ) )
								$quota_color = '#e38000';
							if ( $the_quota < ( $the_total_quota * 0.05 ) )
								$quota_color = 'c81212';
						?>
						<b><?php
							printf( __( '<span style="font-size:120%%;color:%1$s;">%2$s</span> Page Views' ),
								esc_attr( $quota_color ), esc_html( number_format_i18n( $the_quota ) ) ); ?></b>
						<small>(<a href="http://support.nelioabtesting.com/support/solutions/articles/1000129162"><?php
							esc_html_e( 'Help', 'nelio-ab-testing' );
						?></a>)</small><?php echo $post_quota; ?></p><?php

						if ( isset( $this->user_info['status'] ) && $this->user_info['status'] == 1 &&
								!$this->user_info['agency'] ) {
							$url = 'https://sites.fastspring.com/nelio/instant/nelioextrapageviewsforthepersonalserviceplan';
							$url = add_query_arg( 'contact_fname',   $this->user_info['firstname'], $url );
							$url = add_query_arg( 'contact_lname',   $this->user_info['lastname'],  $url );
							$url = add_query_arg( 'contact_email',   $this->user_info['email'],     $url );
							?><a style="margin-left:3em;margin-bottom:1.5em;" class="button" target="_blank"
							href="<?php echo esc_url( $url ); ?>""><?php
								esc_html_e( 'Buy More', 'nelio-ab-testing' );
							?></a>

						<?php
						}
					}
				}

				if ( !NelioABAccountSettings::is_using_free_trial() && $this->is_account_active &&
						$this->is_email_valid && $this->is_reg_num_valid && $this->tac ) { ?>

					<?php
					if ( $this->error_retrieving_registered_sites ) { ?>
						<h3><?php esc_html_e( 'Active Sites', 'nelio-ab-testing' ); ?></h3><?php


						?><p style="margin-top:0em;margin-left:3em;"><?php
							esc_html_e( 'There was an error while retrieving the list of active sites related to this account.', 'nelio-ab-testing' );

						if ( NelioABAccountSettings::has_a_configured_site() ) {
							echo ' ';
							esc_html_e( 'Nonetheless, this site is active and ready to be tested.', 'nelio-ab-testing' );
						}

						?></p><?php

					}
					else {
						$print_table = true;
						$sites = array();

						switch( $this->current_site_status ) {
							case NelioABSite::NON_MATCHING_URLS: ?>
								<h3><?php esc_html_e( 'Active Sites', 'nelio-ab-testing' ); ?></h3><?php
								$this->print_site_non_matching();
								$print_table = false;
								break;

							case NelioABSite::ACTIVE:
								$this->print_table_of_registered_sites();
								break;

							default:
								?> <h3><?php esc_html_e( 'This Site is Not Active', 'nelio-ab-testing' ); ?></h3> <?php
								$can_register = count( $this->sites ) < $this->max_sites;
								if ( $can_register )
									$this->print_site_should_be_registered();
								else
									$this->print_site_cannot_be_registered();

								$this->print_table_of_registered_sites();
						} ?>

						<form id="nelioab_registration_form" method="post">
							<input type="hidden" name="nelioab_registration_form" value="true" />
							<input type="hidden" id="nelioab_registration_action" name="nelioab_registration_action" value="" />
							<input type="hidden" id="nelioab_registration_type" name="nelioab_registration_type" value="" />
							<input type="hidden" id="nelioab_registration_sector" name="nelioab_registration_sector" value="" />
						</form>

					<?php
					}
					?>

					<script type="text/javascript">
					(function($) {

						$dialog = $('#dialog-modal');
						$dialog.dialog({
							dialogClass   : 'wp-dialog',
							modal         : true,
							autoOpen      : false,
							closeOnEscape : true,
							width         : 400,
							title         : <?php echo json_encode( __('About Your Site', 'nelio-ab-testing' ) ); ?>,
							buttons: [
								{
									text: <?php echo json_encode( __( 'Skip and Activate', 'nelio-ab-testing' ) ); ?>,
									click: function() {
										$(this).dialog('close');
										$('#nelioab_registration_type').attr('value', 'unknown' );
										$('#nelioab_registration_sector').attr('value', 'unknown' );
										$('#nelioab_registration_action').attr('value', 'register');
										$('#nelioab_registration_form').submit();
									}
								},
								{
									text: <?php echo json_encode( __( 'Activate', 'nelio-ab-testing' ) ); ?>,
									'class': 'button button-primary',
									click: function() {
										if ( $okButton.hasClass('disabled') ) return;
										$('#nelioab_registration_action').attr('value', 'register');
										$('#nelioab_registration_form').submit();
									}
								}
							]
						});

						var $okButton = $dialog.closest('.ui-dialog').find('.button-primary');
						$okButton.addClass('disabled');

						var $typeSelector = $('#business-type-selector');
						$typeSelector.on('change', function() {
							var ts = $typeSelector.attr('value');
							var ss = $sectorSelector.attr('value');
							if ( 'unknown' == ts || 'unknown' == ss ) $okButton.addClass('disabled');
							else $okButton.removeClass('disabled');
							$('#nelioab_registration_type').attr('value', ts );
						});


						var $sectorSelector = $('#business-sector-selector');
						$sectorSelector.on('change', function() {
							var ts = $typeSelector.attr('value');
							var ss = $sectorSelector.attr('value');
							if ( 'unknown' == ts || 'unknown' == ss ) $okButton.addClass('disabled');
							else $okButton.removeClass('disabled');
							$('#nelioab_registration_sector').attr('value', ss );
						});

						function openDialog() {
							if ( $typeSelector.attr('value') == 'unknown' ) $okButton.addClass('disabled');
							if ( $sectorSelector.attr('value') == 'unknown' ) $okButton.addClass('disabled');
							$dialog.dialog('open');
						}

						$("#register-site-button").click(function(ev) { ev.preventDefault(); openDialog(); });

					})(jQuery);
					</script>

				<?php
				}
			}
		}

		public function print_dialog_content() { ?>
			<p><?php
				esc_html_e( 'In order to offer relevant and valuable suggestions, we need to know a little bit more about your site. Please, answer the following two questions:', 'nelio-ab-testing' );
			?></p>
			<p><strong><?php
				esc_html_e( 'How would you describe your site?', 'nelio-ab-testing' );
			?></strong></p>
			<select style="width:100%;max-width:280px;" id="business-type-selector">
				<option value="unknown" disabled="disabled" selected="selected"></option>
				<?php
				$types = array(
					'publisher' => __( 'Publishing Platform', 'nelio-ab-testing' ),
					'personal'  => __( 'Personal Blog', 'nelio-ab-testing' ),
					'company'   => __( 'Business/Corporate Website', 'nelio-ab-testing' ),
					'ecommerce' => __( 'E-Commerce', 'nelio-ab-testing' ),
				);
				asort( $types );
				foreach ( $types as $value => $name )
					printf( '<option value="%s">%s</option>', esc_attr( $value ), esc_html( $name ) );
				?>
			</select>
			<p><strong><?php
				esc_html_e( 'What is it focused on?', 'nelio-ab-testing' );
			?></strong></p>
			<select style="width:100%;max-width:280px;" id="business-sector-selector">
				<option value="unknown" disabled="disabled" selected="selected"></option>
				<?php
				$sectors = array(
					'software-services'  => __( 'Computer Software & Services', 'nelio-ab-testing' ),
					'education'          => __( 'Education', 'nelio-ab-testing' ),
					'financial-services' => __( 'Financial Services', 'nelio-ab-testing' ),
					'food-and-beverage'  => __( 'Food & Beverage', 'nelio-ab-testing' ),
					'health'             => __( 'Health Services', 'nelio-ab-testing' ),
					'leisure'            => __( 'Leisure', 'nelio-ab-testing' ),
					'media'              => __( 'Media (Adv & Mkt Ag & Publishing)', 'nelio-ab-testing' ),
					'real-estate'        => __( 'Real Estate', 'nelio-ab-testing' ),
					'retail'             => __( 'Retail', 'nelio-ab-testing' ),
				);
				asort( $sectors );
				$sectors['others'] = __( 'Others', 'nelio-ab-testing' );
				foreach ( $sectors as $value => $name )
					printf( '<option value="%s">%s</option>', esc_attr( $value ), esc_html( $name ) );
				?>
			</select>
			<?php
		}

		public function print_email_field() { ?>
			<input name="settings_email" type="text" id="settings_email" maxlength="400"
				class="regular-text" value="<?php echo esc_attr( $this->email ); ?>"/><?php
		}

		public function print_reg_num_field() { ?>
			<input name="settings_reg_num" type="text" id="settings_reg_num" maxlength="26"
				class="regular-text" value="<?php echo esc_attr( $this->reg_num ); ?>"/><?php
			if ( $this->is_email_valid) {
				if ( $this->is_reg_num_valid ) { ?>
					<span style="color:#00AA00; font-weight:bold;"><?php esc_html_e( 'OK', 'nelio-ab-testing' ); ?></span><?php
				}
				else { ?>
					<span style="color:red; font-weight:bold;"><?php esc_html_e( 'INVALID', 'nelio-ab-testing' ); ?></span><?php
				}
			}
		}

		private function print_site_non_matching() {
			echo '<p>Error #001 - Non matching URLs.<br />Please, report this error to Nelio using the Feedback form.</p>';
		}

		private function print_site_should_be_registered() {
			printf( '<p style="%s">%s <strong><a href="#" id="register-site-button">%s</a></strong></p>',
				esc_attr( $this->p_style . 'margin-left:3em;font-size:120%;line-height:1.3em;' ),
				esc_html__( 'This site is not yet active in your account.', 'nelio-ab-testing' ),
				esc_html__( 'Activate it now!', 'nelio-ab-testing' ) );
		}

		private function print_site_cannot_be_registered() {
			printf( '<p style="%1$s">%2$s</p><p style="%1$s">%3$s</p>',
				esc_attr( $this->p_style . 'margin-left:3em;font-size:120%;line-height:1.3em;' ),
				esc_html__( 'It looks like this site is not connected to you account. In order to use our service in this site, you have to activate it. Unfortunately, you already reached the maximum number of sites allowed by your current subscription plan (see the table below).', 'nelio-ab-testing' ),
				sprintf(
					__( 'Please, <a href="%s"><b>upgrade your <i>Nelio A/B Testing</i> subscription</b></a> so that you can activate and manage more sites, or <b>access one of the other sites to deactivate it</b> and try again. Keep in mind that deactivating a site may cause permanent loss of all experiments associated to that site.', 'nelio-ab-testing' ),
					esc_url( 'https://neliosoftware.com/testing/pricing/?plugin=maxsites' )
				)
			);
		}

		private function print_table_of_registered_sites() {
			$sites = array();

			$other_sites = array();
			$this_url    = get_option( 'siteurl' );
			$this_id     = NelioABAccountSettings::get_site_id();
			$reg_name    = false;
			foreach( $this->sites as $site ) {
				if ( $site->get_id() != $this_id || $this->current_site_status != NelioABSite::ACTIVE )
					array_push( $other_sites, $site );
				else
					$reg_name = $site->get_url();
			}

			if( $this->current_site_status == NelioABSite::ACTIVE ) {
				$live = ( false === $reg_name ) ? 'live' : 'staging';

				array_push( $sites, array(
						'this_site' => true,
						'name'      => $this_url,
						'reg_name'  => $reg_name,
						'live'      => $live,
					) );
			}

			foreach ( $other_sites as $site )
				array_push( $sites, array(
						'this_site' => false,
						'name'      => $site->get_url(),
						'goto_link' => $site->get_url()
					) );

			for ( $i = count( $sites ); $i < $this->max_sites; ++$i )
				array_push( $sites, array(
						'this_site' => false,
						'name'      => __( 'Empty Slot', 'nelio-ab-testing' )
					) );

			echo '<div id="registered-sites-table" style="margin-left:2em;">';
			$aux = new NelioABRegisteredSitesTable( $sites );
			$aux->prepare_items();
			$aux->display();
			echo '</div>';
		}

	}//NelioABAccountPage


	require_once( NELIOAB_UTILS_DIR . '/admin-table.php' );
	class NelioABRegisteredSitesTable extends NelioABAdminTable {

		function __construct( $items ){
			parent::__construct( array(
				'singular'  => __( 'active site', 'nelio-ab-testing' ),
				'plural'    => __( 'active sites', 'nelio-ab-testing' ),
				'ajax'      => false
			)	);
			$this->set_items( $items );
		}

		public function get_columns() {
			return array(
				'name' => __( 'Active Sites', 'nelio-ab-testing' ),
			);
		}

		public function column_name( $site ) {
			if ( $site['this_site'] )
				return $this->make_this_site( $site );
			else
				return $this->make_other_site( $site );
		}

		private function make_this_site( $site ) {
			$name = $this->beautify_url( $site['name'] );
			$name = sprintf( $name, '#000000', '#909090' );
			$name = '<span class="row-title"%s><strong style="font-weight:bold;">' . $name . '</strong>';

			$is_live = true;
			$reg_title = '';
			if ( $site['reg_name'] !== $site['name'] ) {
				$is_live = false;
				$name .= '*';
				$reg_title = $site['reg_name'];
				$reg_title = sprintf( __( 'Activation URL is «%s»', 'nelio-ab-testing' ), $reg_title );
				$reg_title = ' title="' . esc_attr( $reg_title ) . '"';
			}
			$name = sprintf( $name, $reg_title );

			$name .= ' <strong style="font-variant:small-caps;">(' . esc_html__( 'this site', 'nelio-ab-testing' ) . ')</strong> ';

			$name .= '</span>';

			if ( $is_live ) {
				$link = 'javascript:' .
					'jQuery(\'#nelioab_registration_action\').attr(\'value\', \'deregister\');' .
					'jQuery(\'#nelioab_registration_form\').submit();';
				$actions = $this->row_actions( array(
						'delete' => '<a href="' . esc_attr( $link  ). '">' . esc_html__( 'Deactivate', 'nelio-ab-testing' ) . '</a>'
					) );
			}
			else {
				$link = 'javascript:' .
					'jQuery(\'#nelioab_registration_action\').attr(\'value\', \'unlink\');' .
					'jQuery(\'#nelioab_registration_form\').submit();';
				$actions = $this->row_actions( array(
						'delete' => '<a href="' . esc_attr( $link ) . '">' . esc_html__( 'Unlink Site', 'nelio-ab-testing' ) . '</a>'
					) );
			}

			$careful = '';
			if ( !$is_live ) {
				$careful = sprintf(
						'<br><small style="font-weight:normal;"%s>%s</small> ',
						$reg_title,
						esc_html__( '* The site was activated using a different URL. If you want to activate this site using its own URL, unlink it first.', 'nelio-ab-testing' )
					);
			}


			return $name . $careful . $actions;
		}

		private function make_other_site( $site ) {
			if ( isset( $site['goto_link'] ) ) {
				$name = $this->beautify_url( $site['name'] );
				$name = sprintf( $name, '#404040', '#AAAAAA' );
				$name = '<span class="row-title" style="font-weight:normal;">' . $name . '</span>';
				$actions = $this->row_actions( array(
						'edit-content' => '<a href="' . esc_url( $site['goto_link'] ) . '">Go to this site</a>'
					) );
			}
			else {
				$style   = 'color:#AAAAAA;font-weight:normal;font-style:italic;';
				$name    = '<span class="row-title" style="' . $style . '">' . $site['name'] . '</span>';
				$actions = $this->row_actions( array( 'none' => '&nbsp;' ) );
			}

			return $name . $actions;
		}

		private function beautify_url( $url ) {
			$result = '<span style="color:%2$s">';
			if ( strpos( $url, 'http://' ) === 0 ) {
				$result .= 'http://</span>';
				$url = substr( $url, 7 );
			}
			else if ( strpos( $url, 'https://' ) === 0 ) {
				$result .= 'https://</span>';
				$url = substr( $url, 8 );
			}
			$result .= '<span style="color:%1$s">';

			$first_slash = strpos( $url, '/' );
			if ( $first_slash ) {
				$result .= substr( $url, 0, $first_slash );
				$result .= '</span><span style="color:%2$s">';
				$result .= substr( $url, $first_slash );
				$result .= '</span>';
			}
			else {
				$result .= substr( $url, 0, strlen( $url ) );
				$result .= '</span><span style="color:%2$s"></span>';
			}

			return $result;
		}

		public function print_column_headers( $with_id = true ) {
			if ( $with_id )
				parent::print_column_headers();
		}

	}//NelioABRegisteredSitesTable

}

