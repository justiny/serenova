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

if ( !class_exists( 'NelioABAltExpProgressPage' ) ) {

	require_once( NELIOAB_MODELS_DIR . '/experiment.php' );
	require_once( NELIOAB_UTILS_DIR . '/admin-ajax-page.php' );

	abstract class NelioABAltExpProgressPage extends NelioABAdminAjaxPage {

		const NO_WINNER = -999999;

		protected $exp;
		protected $results;
		protected $winner_label;
		protected $goals;
		protected $goal;
		protected $colorscheme;

		public function __construct( $title ) {
			parent::__construct( $title );
			$this->set_icon( 'icon-nelioab' );
			$this->exp          = null;
			$this->goal         = null;
			$this->results      = null;

			require_once( NELIOAB_UTILS_DIR . '/wp-helper.php' );
			$this->colorscheme = NelioABWpHelper::get_current_colorscheme();
		}

		public function set_experiment( $exp ) {
			$this->exp = $exp;
		}

		public function set_goals( $goals ) {
			$sorted = array();
			$aux    = array();
			foreach ( $goals as $goal ) {
				if ( $goal->is_main_goal() )
					array_push( $sorted, $goal );
				else
					array_push( $aux, $goal );
			}

			// Sort aux alphabetically...
			usort( $aux, array( 'NelioABAltExpProgressPage', 'sort_by_name' ) );
			// usort( $aux, array( 'NelioABAltExpProgressPage', 'sort_by_id' ) );

			// And add them in sorted
			foreach ( $aux as $goal )
				array_push( $sorted, $goal );
			$this->goals = $sorted;

			// Autoset names are only used by pre-3.0 experiments. For those,
			// the only possible actions where PageAccessedActions, and that's
			// why I assume $action[0] is a $page.
			$are_all_undefined = true;
			foreach ( $this->goals as $goal )
				if ( $goal->get_name() != __( 'Undefined', 'nelio-ab-testing' ) )
					$are_all_undefined = false;
			if ( $are_all_undefined )
				foreach ( $this->goals as $goal )
					$this->autoset_goal_name( $goal );

			// Finally, we select one by default...
			$this->results = null;
		}

		private function autoset_goal_name( $goal ) {
			if ( $goal->is_main_goal() ) {
				$goal->set_name( __( 'Aggregated Info', 'nelio-ab-testing' ) );
				return;
			}
			$action = $goal->get_actions();
			$page = $action[0];
			if ( $page->is_external() ) {
				$goal->set_name( $page->get_title() );
			}
			else {
				$name = __( 'Unnamed', 'nelio-ab-testing' );
				$post = get_post( $page->get_reference() );
				if ( $post ) {
					$name = strip_tags( $post->post_title );
					if ( mb_strlen( $name ) > 30 )
						$name = mb_strimwidth( $name, 0, 30 ) . '...';
				}
				$goal->set_name( $name );
			}
		}

		public static function sort_by_id( $a, $b ) {
			return $a->get_id() - $b->get_id();
		}

		public static function sort_by_name( $a, $b ) {
			return strcmp( $a->get_name(), $b->get_name() );
		}

		public function set_current_selected_goal( $id ) {
			$this->goal = false;

			foreach ( $this->goals as $goal )
				if ( $goal->get_id() == $id )
					$this->goal = $goal;

			if ( !$this->goal ) {
				foreach ( $this->goals as $goal )
					if ( $goal->is_main_goal() )
						$this->goal = $goal;
			}

			// If there's only one goal, but it's not set as the main goal (weird),
			// I use it by default. It should not happen, but sometimes it does. This
			// fragment resolves the issue.
			if ( ! $this->goal && count( $this->goals ) == 1 ) {
				$this->goal = $this->goals[0];
			}

			if ( ! $this->goal ) {
				return;
			}

			try {
				$this->results = $this->goal->get_results();
			}
			catch ( Exception $e ) {
				require_once( NELIOAB_UTILS_DIR . '/backend.php' );
				if ( $e->getCode() == NelioABErrCodes::RESULTS_NOT_AVAILABLE_YET ) {
					$this->results = null;
				}
				else {
					require_once( NELIOAB_ADMIN_DIR . '/error-controller.php' );
					NelioABErrorController::build( $e );
				}
			}
		}

		protected abstract function get_original_name();
		protected abstract function get_original_value();
		protected abstract function print_js_function_for_post_data_overwriting();

		protected function print_actions_info() {
			$actions = $this->goal->get_actions();
			if ( count( $actions ) <= 0 ) {
				$message = __( 'There are no actions in this goal.', 'nelio-ab-testing' );
				echo "<h3>" . esc_html( $message ) . "</h3>";
				return;
			}

			// PAGE_ACCESSED
			$page_accessed_actions = array_filter( $actions, array( $this, 'select_page_accessed_actions' ) );

			// POST_ACCESSED
			$post_accessed_actions = array_filter( $actions, array( $this, 'select_post_accessed_actions' ) );

			// EXTERNAL_PAGE_ACCESSED
			$external_page_accessed_actions = array_filter( $actions, array( $this, 'select_external_page_accessed_actions' ) );

			// SUBMIT_CF7_FORM & SUBMIT_GRAVITY_FORM
			$submit_form_actions = array_filter( $actions, array( $this, 'select_submit_form_actions' ) );

			// CLICK_ELEMENT
			$click_element_actions = array_filter( $actions, array( $this, 'select_click_element_actions' ) );

			// CLICK_ELEMENT
			$order_completed_actions = array_filter( $actions, array( $this, 'select_order_completed_actions' ) );

			// PHONE CALLS
			$phone_call_actions = array_filter( $actions, array( $this, 'select_phone_call_actions' ) );

			if ( count( $page_accessed_actions )>0 )
				$this->print_page_accessed_actions_box( $page_accessed_actions );

			if ( count( $post_accessed_actions )>0 )
				$this->print_post_accessed_actions_box( $post_accessed_actions );

			if ( count( $external_page_accessed_actions )>0 )
				$this->print_external_page_accessed_actions_box( $external_page_accessed_actions );

			if ( count( $submit_form_actions )>0 )
				$this->print_submit_form_actions_box( $submit_form_actions );

			if ( count( $click_element_actions )>0 )
				$this->print_click_element_actions_box( $click_element_actions );

			if ( count( $order_completed_actions )>0 )
				$this->print_order_completed_actions_box( $order_completed_actions );

			if ( count( $phone_call_actions )>0 )
				$this->print_phone_call_actions_box();
		}

		/* ACTION SELECTION FUNCTIONS */

		public function select_page_accessed_actions( $action ) {
			return $this->select_action( $action, NelioABAction::PAGE_ACCESSED );
		}

		public function select_post_accessed_actions( $action ) {
			return $this->select_action( $action, NelioABAction::POST_ACCESSED );
		}

		public function select_external_page_accessed_actions( $action ) {
			return $this->select_action( $action, NelioABAction::EXTERNAL_PAGE_ACCESSED );
		}

		public function select_submit_form_actions( $action ) {
			return $this->select_action( $action, NelioABAction::SUBMIT_CF7_FORM ) ||
			       $this->select_action( $action, NelioABAction::SUBMIT_GRAVITY_FORM );
		}

		public function select_click_element_actions( $action ) {
			return $this->select_action( $action, NelioABAction::CLICK_ELEMENT );
		}

		public function select_order_completed_actions( $action ) {
			return $this->select_action( $action, NelioABAction::WC_ORDER_COMPLETED );
		}

		public function select_phone_call_actions( $action ) {
			return $this->select_action( $action, NelioABAction::PHONE_CALL );
		}

		private function select_action( $action, $action_type ) {
			/* @var NelioABAction $action */
			if ( $action->get_type() == $action_type )
				return true;
			else return false;
		}

		/* ACTION INFO PRINTING FUNCTIONS */

		protected function print_page_accessed_actions_box( $page_accessed_actions ) {
			$this->print_beautiful_box(
				'nelio-page-accessed-actions',
				$this->get_action_heading( NelioABAction::PAGE_ACCESSED ),
				array( &$this, 'print_page_accessed_actions_content', array( $page_accessed_actions ) ) );
		}

		protected function print_post_accessed_actions_box( $post_accessed_actions ) {
			$this->print_beautiful_box(
				'nelio-post-accessed-actions',
				$this->get_action_heading( NelioABAction::POST_ACCESSED ),
				array( &$this, 'print_post_accessed_actions_content', array( $post_accessed_actions ) ) );
		}

		protected function print_page_accessed_actions_content( $actions ) {

			foreach ( $actions as $action ) {
				$indirect = $action->accepts_indirect_navigations();

				if ( $this->exp->get_type() == NelioABExperiment::PAGE_ALT_EXP ) {
					if ( !$indirect ) {
						$escaped_label = esc_html__( 'The following page is accessed from the tested page:', 'nelio-ab-testing' );
					} else {
						$escaped_label = esc_html__( 'The following page is accessed:', 'nelio-ab-testing' );
					}
				} elseif ( $this->exp->get_type() == NelioABExperiment::POST_ALT_EXP ) {
					if ( !$indirect ) {
						$escaped_label = esc_html__( 'The following page is accessed from the tested post:', 'nelio-ab-testing' );
					} else {
						$escaped_label = esc_html__( 'The following page is accessed:', 'nelio-ab-testing' );
					}
				} else {
					if ( !$indirect ) {
						$escaped_label = esc_html__( 'The following page is accessed from the tested page:', 'nelio-ab-testing' );
					} else {
						$escaped_label = esc_html__( 'The following page is accessed:', 'nelio-ab-testing' );
					}
				}

				$post = get_post( $action->get_reference() );
				if ( $post ) {
					$name = trim( strip_tags( $post->post_title ) );
					if ( strlen( $name ) == 0 ) {
						$name = __( 'no title', 'nelio-ab-testing' );
					}//end if
					$link = sprintf( '<a class="button" href="%s" target="_blank">%s <i class="fa fa-eye"></i></a>',
						esc_url( get_permalink( $post ) ),
						esc_html( $name )
					);
				} else {
					$escaped_label = esc_html__( 'A visitor accessed a page that, unfortunately, does no longer exist', 'nelio-ab-testing' );
				}//end if

				$html = <<<HTML
				<div class="nelio-page-accessed-action nelio-action-item">
					<i class="fa fa-file"></i>
					<span class="page-info">$escaped_label</span>
					<span class="page-value">$link</span>
				</div>
HTML;
				echo $html;
			}
		}


		protected function print_post_accessed_actions_content( $actions ) {

			foreach ( $actions as $action ) {
				$indirect = $action->accepts_indirect_navigations();

				if ( $this->exp->get_type() == NelioABExperiment::PAGE_ALT_EXP ) {
					if ( !$indirect ) {
						$escaped_label = esc_html__( 'The following post is accessed from the tested page:', 'nelio-ab-testing' );
					} else {
						$escaped_label = esc_html__( 'The following post is accessed:', 'nelio-ab-testing' );
					}
				} elseif ( $this->exp->get_type() == NelioABExperiment::POST_ALT_EXP ) {
					if ( !$indirect ) {
						$escaped_label = esc_html__( 'The following post is accessed from the tested post:', 'nelio-ab-testing' );
					} else {
						$escaped_label = esc_html__( 'The following post is accessed:', 'nelio-ab-testing' );
					}
				} else {
					if ( !$indirect ) {
						$escaped_label = esc_html__( 'The following post is accessed from the tested page:', 'nelio-ab-testing' );
					} else {
						$escaped_label = esc_html__( 'The following post is accessed:', 'nelio-ab-testing' );
					}
				}

				$post = get_post( $action->get_reference() );
				if ( $post ) {
					$name = trim( strip_tags( $post->post_title ) );
					if ( strlen( $name ) == 0 ) {
						$name = __( 'no title', 'nelio-ab-testing' );
					}//end if
					$link = sprintf( '<a class="button" href="%s" target="_blank">%s <i class="fa fa-eye"></i></a>',
						esc_url( get_permalink( $post ) ),
						esc_html( $name )
					);
				} else {
					$escaped_label = esc_html__( 'A visitor accessed a post that, unfortunately, does no longer exist', 'nelio-ab-testing' );
				}//end if

				$html = <<<HTML
				<div class="nelio-page-accessed-action nelio-action-item">
					<i class="fa fa-thumb-tack"></i>
					<span class="page-info">$escaped_label</span>
					<span class="page-value">$link</span>
				</div>
HTML;
				echo $html;
			}
		}

		protected function print_external_page_accessed_actions_box( $external_page_accessed_actions ) {
			$this->print_beautiful_box(
				"nelio-external-page-accessed-actions",
				$this->get_action_heading( NelioABAction::EXTERNAL_PAGE_ACCESSED ),
				array( &$this, 'print_external_page_accessed_actions_content', array( $external_page_accessed_actions ) ) );
		}

		protected function print_external_page_accessed_actions_content( $external_page_accessed_actions ) {

			foreach ( $external_page_accessed_actions as $action ) {

				$indirect = $action->accepts_indirect_navigations();

				if ( $this->exp->get_type() == NelioABExperiment::PAGE_ALT_EXP ) {
					if ( !$indirect ) {
						$escaped_label = esc_html__( 'A visitor is about to leave your site from the tested page and go to:', 'nelio-ab-testing' );
					} else {
						$escaped_label = esc_html__( 'A visitor is about to leave your site and go to:', 'nelio-ab-testing' );
					}
				} elseif ( $this->exp->get_type() == NelioABExperiment::POST_ALT_EXP ) {
					if ( !$indirect ) {
						$escaped_label = esc_html__( 'A visitor is about to leave your site from the tested post and go to:', 'nelio-ab-testing' );
					} else {
						$escaped_label = esc_html__( 'A visitor is about to leave your site and go to:', 'nelio-ab-testing' );
					}
				} else {
					if ( !$indirect ) {
						$escaped_label = esc_html__( 'A visitor is about to leave your site from the tested page and go to:', 'nelio-ab-testing' );
					} else {
						$escaped_label = esc_html__( 'A visitor is about to leave your site and go to:', 'nelio-ab-testing' );
					}
				}

				switch ( $action->get_regex_mode() ) {

					case 'starts-with':
						$html_link = sprintf( '%1$s (<span style="text-decoration:underline;" title="%3$s">%2$s</span>)',
							esc_html( $action->get_title() ),
							esc_html( $action->get_clean_reference() ),
							esc_attr( sprintf( __( 'URL starts with "%s"', 'nelio-ab-testing' ), $action->get_clean_reference() ) )
						);
						break;

					case 'ends-with':
						$html_link = sprintf( '%1$s (<span style="text-decoration:underline;" title="%3$s">%2$s</span>)',
							esc_html( $action->get_title() ),
							esc_html( $action->get_clean_reference() ),
							esc_attr( sprintf( __( 'URL ends with "%s"', 'nelio-ab-testing' ), $action->get_clean_reference() ) )
						);
						break;

					case 'contains':
						$html_link = sprintf( '%1$s (<span style="text-decoration:underline;" title="%3$s">%2$s</span>)',
							esc_html( $action->get_title() ),
							esc_html( $action->get_clean_reference() ),
							esc_attr( sprintf( __( 'URL contains "%s"', 'nelio-ab-testing' ), $action->get_clean_reference() ) )
						);
						break;

					default:
						$html_link = sprintf( '<a class="button" href="%2$s" target="_blank">%1$s <i class="fa fa-eye"></i></a>',
							esc_html( $action->get_title() ),
							esc_url( $action->get_reference() )
);

				}//end switch

				$html = <<<HTML
				<div class="nelio-external-page-accessed-action nelio-action-item">
					<i class="fa fa-paper-plane"></i>
					<span class="external-page-info">$escaped_label</span>
					<span class="external-page-value">$html_link</span>
				</div>
HTML;
				echo $html;
			}
		}

		protected function print_submit_form_actions_box( $submit_form_actions ) {
			$this->print_beautiful_box(
				"nelio-submit-form-actions",
				$this->get_action_heading( NelioABAction::FORM_SUBMIT ),
				array( &$this, 'print_submit_form_actions_content', array( $submit_form_actions ) ) );
		}

		protected function print_submit_form_actions_content( $submit_form_actions ) {

			$cf7  = is_plugin_active( 'contact-form-7/wp-contact-form-7.php' );
			$gf   = is_plugin_active( 'gravityforms/gravityforms.php' );

			foreach ( $submit_form_actions as $action ) {

				$form_id = $action->get_form_id();
				$type    = '';
				$name    = '';
				$link    = '';
				$mode    = '';
				$form    = '';
				$icon    = '';

				switch ( $action->get_type() ) {

					case NelioABAction::SUBMIT_CF7_FORM:
						$icon = 'fa-check-square';
						$type = 'Contact Form 7';
						if ( $cf7 ) {
							$aux = WPCF7_ContactForm::find( array( 'p' => $form_id ) );
							if ( count( $aux ) > 0 ) {
								$form = $aux[0];
								$name = $form->title();
								$link = admin_url( 'admin.php?page=wpcf7&action=edit&post=' . $form_id );
							}
						}
						$mode = __( 'from the tested page', 'nelio-ab-testing' );
						if ( $action->accepts_submissions_from_any_page() )
							$mode = __( 'from any page', 'nelio-ab-testing' );
						break;

					case NelioABAction::SUBMIT_GRAVITY_FORM:
						$icon = 'fa-check-square-o';
						$type = 'Gravity Forms';
						$mode = __( 'from the tested page', 'nelio-ab-testing' );
						if ( $action->accepts_submissions_from_any_page() )
							$mode = __( 'from any page', 'nelio-ab-testing' );
						if ( $gf ) {
							$form = GFAPI::get_form( $form_id );
							if ( $form ) {
								$name = $form['title'];
								$link = admin_url( 'admin.php?page=gf_edit_forms&id=' . $form_id );
							}
						}
						break;
				}

				if ( ! $name ) {
					$name = __( 'Unknown Form', 'nelioab' );
				}//end if

				$submission = esc_html( sprintf( __( '%1$s submission', 'nelio-ab-testing' ),	$type ) );
				$mode = esc_html( $mode );
				$icon = esc_attr( $icon );
				$link = esc_url( $link );
				$name = esc_html( $name );

				$html = <<<HTML
				<div class="nelio-form-submission-action nelio-action-item">
					<i class="fa $icon"></i>
					<span class="form-info">$submission ($mode):</span>
					<span class="form-value">
						<a class="button" href="$link" target="_blank">
							$name
							<i class="fa fa-pencil"></i>
						</a>
					</span>
				</div>
HTML;
				echo $html;
			}
		}

		protected function print_click_element_actions_box( $click_element_actions ) {
			$this->print_beautiful_box(
				'nelio-click-element-actions',
				$this->get_action_heading( NelioABAction::CLICK_ELEMENT ),
				array( &$this, 'print_click_element_actions_content', array( $click_element_actions ) ) );
		}

		protected function print_click_element_actions_content( $click_element_actions ) {
			foreach ( $click_element_actions as $action ) {
				switch ( $action->get_mode() ) {
					case NelioABClickElementAction::ID_MODE:
						$icon      = esc_attr( 'fa-code' );
						$condition = esc_html__( 'Click on an element whose HTML identifier is:', 'nelio-ab-testing' );
						$value     = esc_html( $action->get_value() );
						break;
					case NelioABClickElementAction::CSS_MODE:
						$icon      = esc_attr( 'fa-pencil' );
						$condition = esc_html__( 'Click on an element whose CSS path is:', 'nelio-ab-testing' );
						$value     = esc_html( $action->get_value() );
						break;
					case NelioABClickElementAction::TEXT_MODE:
						$icon      = esc_attr( 'fa-font' );
						$condition = esc_html__( 'Click on an element whose text is:', 'nelio-ab-testing' );
						$value     = esc_html( $action->get_value() );
						break;
					default:
						continue;
				}
				$html = <<<HTML
				<div class="nelio-click-element-action nelio-action-item">
					<i class="fa $icon"></i>
					<span class="condition">$condition</span>
					<span class="value">$value</span>
				</div>
HTML;
				echo $html;
			}
		}

		protected function print_order_completed_actions_box( $order_completed_actions ) {
			$this->print_beautiful_box(
				'nelio-order-completed-actions',
				$this->get_action_heading( NelioABAction::WC_ORDER_COMPLETED ),
				array( &$this, 'print_order_completed_actions_content', array( $order_completed_actions ) ) );
		}

		protected function print_order_completed_actions_content( $order_completed_actions ) {

			foreach ( $order_completed_actions as $action ) {

				$post = get_post( $action->get_product_id() );
				if ( $post ) {
					$name = trim( strip_tags( $post->post_title ) );
					if ( strlen( $name ) == 0 ) {
						$name = __( 'no title', 'nelio-ab-testing' );
					}//end if
					$link = sprintf( '<a class="button" href="%s" target="_blank">%s <i class="fa fa-eye"></i></a>',
						esc_url( get_permalink( $post ) ),
						esc_html( $name )
					);
				} else {
					$link = esc_html__( 'The product does not exist', 'nelio-ab-testing' );
				}

				$message = esc_html__( 'An order that contains the following product is completed:', 'nelio-ab-testing' );

				$html = <<<HTML
				<div class="nelio-page-accessed-action nelio-action-item">
					<i class="fa fa-shopping-cart"></i>
					<span class="page-info">$message</span>
					<span class="page-value">$link</span>
				</div>
HTML;
				echo $html;
			}
		}

		protected function print_phone_call_actions_box() {
			$this->print_beautiful_box(
				'nelio-phone-call-actions',
				$this->get_action_heading( NelioABAction::PHONE_CALL ),
				array( &$this, 'print_phone_call_actions_content' ) );
		}

		protected function print_phone_call_actions_content() {

			$message = esc_html__( 'A visitor calls to your ResponseTap account.', 'nelio-ab-testing' );

			$html = <<<HTML
			<div class="nelio-page-accessed-action nelio-action-item">
				<i class="fa fa-phone"></i>
				<span class="page-info">$message</span>
			</div>
HTML;
			echo $html;

		}

		protected function get_action_heading( $type ) {
			switch ( $type ) {
				case NelioABAction::PAGE_ACCESSED:
					$icon = nelioab_admin_asset_link( '/images/tab-type-page.png' );
					$alt = __( 'A visitor accesses a page.', 'nelio-ab-testing' );
					$title = __( 'Visit a Page', 'nelio-ab-testing' );
					break;
				case NelioABAction::POST_ACCESSED:
					$icon = nelioab_admin_asset_link( '/images/tab-type-post.png' );
					$alt = __( 'A visitor accesses a post.', 'nelio-ab-testing' );
					$title = __( 'Visit a Post', 'nelio-ab-testing' );
					break;
				case NelioABAction::EXTERNAL_PAGE_ACCESSED:
					$icon = nelioab_admin_asset_link( '/images/tab-type-external.png' );
					$alt = __( 'A visitor leaves your site and accesses an external page.', 'nelio-ab-testing' );
					$title = __( 'Visit an External Page', 'nelio-ab-testing' );
					break;
				case NelioABAction::FORM_SUBMIT:
					$icon = nelioab_admin_asset_link( '/images/tab-type-form.png' );
					$alt = __( 'A visitor submits a form.', 'nelio-ab-testing' );
					$title = __( 'Form Submissions', 'nelio-ab-testing' );
					break;
				case NelioABAction::CLICK_ELEMENT:
					$icon = nelioab_admin_asset_link( '/images/tab-type-click.png' );
					$alt = __( 'A visitor clicks an element.', 'nelio-ab-testing' );
					$title = __( 'Click Actions', 'nelio-ab-testing' );
					break;
				case NelioABAction::WC_ORDER_COMPLETED:
					$icon = nelioab_admin_asset_link( '/images/tab-type-wc-product-summary.png' );
					$alt = __( 'An order with a certain product is completed.', 'nelio-ab-testing' );
					$title = __( 'Order Completed', 'nelio-ab-testing' );
					break;
				case NelioABAction::PHONE_CALL:
					$icon = nelioab_admin_asset_link( '/images/phone-call-action.png' );
					$alt = __( 'A visitor calls to your ResponseTap account.', 'nelio-ab-testing' );
					$title = __( 'Phone Call', 'nelio-ab-testing' );
					break;
			}

			$html = <<< HTML
			<div class="nelio-action-heading">
				<img src="$icon" alt="$alt"/>
				<span>$title</span>
			</div>
HTML;
			return $html;
		}

		protected function get_digested_results() {
			return array();
		}//end get_digested_results()

		protected function do_render() {
			require_once( NELIOAB_UTILS_DIR . '/formatter.php' );

			// SOME VARIABLES
			$exp  = $this->exp;
			$res  = $this->results;

			// Description of the experiment
			$descr = trim( $exp->get_description() );
			if ( empty( $descr ) )
				$descr = '-';

			// Statistics
			$total_visitors    = 0;
			$total_conversions = 0;
			$conversion_rate   = '&mdash;';
			$originals_conversion_rate = '&mdash;';
			if ( $res ) {
				$total_visitors    = number_format_i18n( $res->get_total_visitors() );
				$total_conversions = number_format_i18n( $res->get_total_conversions() );
				$conversion_rate   = number_format_i18n( $res->get_total_conversion_rate(), 2 );
				$aux = $res->get_alternative_results();
				$originals_conversion_rate = number_format_i18n( $aux[0]->get_conversion_rate(), 2 );
				if ( $aux[0]->get_num_of_visitors() == 0 ) {
					$originals_conversion_rate = '&mdash;';
				}
			}

			// Winner (if any) details
			$the_winner            = $this->who_wins();
			$the_winner_confidence = $this->get_winning_confidence();

			$best_alt = $this->get_best_alt();

			$best_alt_improvement_factor = $this->get_best_alt_improvement_factor( $best_alt );
			if ( !is_double( $best_alt_improvement_factor ) )
				$best_alt_improvement_factor = '';
			else
				$best_alt_improvement_factor = number_format_i18n( $best_alt_improvement_factor, 2 );

			$best_alt_conversion_rate = $this->get_best_alt_conversion_rate();
			if ( $best_alt_conversion_rate < 0 )
				$best_alt_conversion_rate = '&mdash;';
			else
				$best_alt_conversion_rate = number_format_i18n( $best_alt_conversion_rate, 2 );

			$this->winner_label = sprintf( '" style="color:%s;background:%s" title="%s"',
				esc_attr( $this->colorscheme['foreground'] ),
				esc_attr( $this->colorscheme['focus'] ),
				esc_attr( sprintf( __( 'Wins with a %s%% confidence', 'nelio-ab-testing' ), $the_winner_confidence ) )
			);

			// PRINTING RESULTS
			// ----------------------------------------------------------------
			?>

			<script type="text/javascript">
				var timelineGraphic;
				var visitsGraphic;
				var improvFactorGraphic;
				var convRateGraphic;
			</script>

			<div id="nelio-upper-progress-bar">
				<div id="nelio-progress-status"><?php
					if ( $exp->get_status() == NelioABExperiment::STATUS_RUNNING ) { ?>
						<div id="nelio-progress-status-running">
							<i class="fa fa-play faa-pulse animated faa-slow"></i>
							<span><?php esc_html_e( 'Running', 'nelio-ab-testing' ) ?></span>
						</div>
						<?php
						if ( NelioABExperimentsManager::current_user_can( $exp, 'stop' ) ) { ?>
							<div id="nelio-progress-status-stop" class="faa-parent animated-hover">
							<i class="fa fa-stop faa-pulse"></i>
							<span><?php esc_html_e( 'Stop', 'nelio-ab-testing' ) ?></span>
							</div><?php
						} else { ?>
							<div id="nelio-progress-status-stop" class="faa-parent" style="background:grey; border-color:grey; cursor:default!important;">
							<i class="fa fa-stop"></i>
							<span><?php esc_html_e( 'Stop', 'nelio-ab-testing' ) ?></span>
							</div><?php
						}
					} else { ?>
						<div id="nelio-progress-status-finish">
						<i class="fa fa-stop"></i>
						<span><?php esc_html_e( 'Finished', 'nelio-ab-testing' ) ?></span>
						</div><?php
					} ?>
				</div>
				<div id="nelio-export-progress">
					<i title="<?php esc_attr_e( 'Export experiment results', 'nelio-ab-testing' ) ?>" class="fa fa-download fa-2x"></i>
				</div>
				<?php
				if ( count( $this->goals ) > 1 ) { ?>
					<div id="nelio-goal-selector">
					<i title="<?php esc_attr_e( 'Select a goal', 'nelio-ab-testing' ) ?>" class="fa fa-dot-circle-o fa-2x"></i>
					<i class="fa fa-caret-down"></i>
					<ul class="nelio-goals"><?php
						$this_goal_id = $this->goal->get_id();
						foreach ( $this->goals as $goal ) { ?>
							<li><?php
							$name   = $goal->get_name();
							$link   = add_query_arg( 'goal', $goal->get_id(), $_SERVER['HTTP_REFERER'] );
							if ( $goal->get_id() == $this_goal_id ) {
								printf( '<span class="nelio-goal-active">%s</span>',
									esc_html( $name )
								);
							} else {
								printf( '<a href="%s">%s</a>',
									esc_url( $link ),
									esc_html( $name )
								);
							}//end if
							?>
							</li><?php
						} ?>
					</ul>
					</div><?php
				} ?>
			</div>

			<h3 id="exp-tabs" class="nav-tab-wrapper" style="padding: 0em 0em 0em 1em;margin: 0em 0em 2em;">
				<span id="tab-info" class="nav-tab nelio-tab-progress nav-tab-active"><?php esc_html_e( 'General', 'nelio-ab-testing' ); ?></span>
				<span id="tab-alts" class="nav-tab nelio-tab-progress"><?php esc_html_e( 'Alternatives', 'nelio-ab-testing' ); ?></span>
				<span id="tab-actions" class="nav-tab nelio-tab-progress"><?php esc_html_e( 'Conversion Actions', 'nelio-ab-testing' ); ?></span>
			</h3>

			<!-- FRONT INFO BAR -->
			<div id="nelio-container-tab-info" class="nelio-tab-container" style="display:block;">
				<div class="row">
					<div class="fixed-width">
						<div id="nelio-exp-status" class="postbox nelio-card">
							<?php $this->print_experiment_status( $exp, $res, $the_winner, $the_winner_confidence,
								$originals_conversion_rate,	$best_alt, $best_alt_conversion_rate, $best_alt_improvement_factor ); ?>
						</div>
					</div>

					<div class="fluid">
						<div id="nelio-exp-timeline" class="postbox nelio-card">
							<canvas id="nelioab-timeline" class="nelioab-timeline-graphic"></canvas>
							<div id="nelioab-timeline-info">
								<div id="nelioab-timeline-info-text">
									<span class="nelio-pageviews-text"><?php esc_html_e( 'Pageviews', 'nelio-ab-testing' ); ?></span>
									<span class="nelio-pageviews-value"><?php echo esc_html( $total_visitors ); ?></span>
									<span class="nelio-conversions-text"><?php esc_html_e( 'Conversions', 'nelio-ab-testing' ); ?></span>
									<span class="nelio-conversions-value"><?php echo esc_html( $total_conversions ); ?></span>
									<span class="nelio-conversion-rate-value">(<?php echo esc_html( $conversion_rate . '%' ); ?>)</span>
								</div>
							</div>
							<?php $this->print_timeline_for_alternatives_js(); ?>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="fixed-width">
						<div id="nelio-exp-info" class="postbox nelio-card">
							<?php $this->print_experiment_information( $exp, $descr, $res ); ?>
						</div>
					</div>

					<?php
					// If results are available, print them.
					if ( $res != null ) { ?>

						<div class="fluid">
							<div id="nelio-exp-stats" class="postbox nelio-card">
								<canvas id="nelioab-conversion-rate" class="nelioab-summary-graphic"></canvas>
								<?php $this->print_conversion_rate_js(); ?>
							</div>
						</div>

						<div class="fluid">
							<div id="nelio-exp-config" class="postbox nelio-card">
								<canvas id="nelioab-improvement-factor" class="nelioab-summary-graphic"></canvas>
								<?php $this->print_improvement_factor_js(); ?>
							</div>
						</div>

						<?php

						// Otherwise, show a message stating that no data is available yet
					} else {
						if ( $exp->get_status() == NelioABExperiment::STATUS_RUNNING ) {
							$src = nelioab_admin_asset_link( '/images/collecting-results.png' );
							$message = __( 'Please be patient while we process the first results', 'nelio-ab-testing' );
							$main_message = __( 'Collecting Data...', 'nelio-ab-testing' );
							$status_message = __( 'There are no results available yet. Please, be patient until we collect more data. It might take up to half an hour to get your first results.', 'nelio-ab-testing' );
							?>
							<div class="fluid">
								<div id="no-results" class="postbox nelio-card">
									<div class="content">
									<span class="main-message"><?php echo esc_html( $main_message ); ?></span>
									<img src="<?php echo esc_url( $src ); ?>" title="<?php echo esc_attr( $message ); ?>" alt="<?php echo esc_attr( $message ); ?>" class="masterTooltip animated flipInY"/>
									<span class="additional-message"><?php echo esc_html( $status_message ); ?></span>
									</div>
								</div>
							</div>
						<?php
						}
						else {
							$src = nelioab_admin_asset_link( '/images/cloud-data.png' );
							$message = __( 'No data was collected before stopping the experiment', 'nelio-ab-testing' );
							$main_message = __( 'No Data Available', 'nelio-ab-testing' );
							$status_message = __( 'The experiment has no results, probably because it was stopped before Nelio A/B Testing could collect any data.', 'nelio-ab-testing' );
							?>
							<div class="fluid">
								<div id="no-results" class="postbox nelio-card">
									<div class="content">
									<span class="main-message"><?php echo esc_html( $main_message ); ?></span>
									<img src="<?php echo esc_url( $src ); ?>" title="<?php echo esc_attr( $message ); ?>" alt="<?php echo esc_attr( $message ); ?>" class="masterTooltip animated flipInY"/>
									<span class="additional-message"><?php echo esc_html( $status_message ); ?></span>
										</div>
								</div>
							</div>
						<?php
						}
					}?>

				</div>
			</div>

			<div id="nelio-container-tab-alts" class="nelio-tab-container">

				<?php include_once( NELIOAB_ADMIN_DIR . '/views/progress/alternative-partial.php' ); ?>
				<div id="nelio-progress-alternatives"></div>
				<script>
				(function( $ ) {
					var results = <?php echo json_encode( $this->get_digested_results() ); ?>;
					var tab = document.getElementById( 'nelio-progress-alternatives' );
					var template = _.template( document.getElementById( '_nelioab-alternative-partial' ).innerHTML, { 'evaluate': /\[\*([\s\S]+?)\*\]/g, 'interpolate': /\[\*=([\s\S]+?)\*\]/g, 'escape': /\[\*~([\s\S]+?)\*\]/g } );
					for ( var i = 0; i < results.length; ++i ) {
						var aux = results[ i ];
						var child = document.createElement( 'div' );
						child.innerHTML = template( aux );
						tab.appendChild( child );
						drawAlternativeGraphic(
							aux.graphicId,
							aux.conversions,
							aux.labels.conversions,
							aux.color,
							aux.pageviews,
							aux.labels.pageviews
						);
					}//end for
				})( jQuery );
				</script>

				<?php
				if ( $exp->get_status() == NelioABExperiment::STATUS_FINISHED ) { ?>
					<script>
						<?php
						$this->print_js_function_for_post_data_overwriting();
						?>

						(function($) {
							$('#dialog-modal').dialog({
								title: <?php echo json_encode( esc_html__( 'Overwrite Original', 'nelio-ab-testing' ) ); ?>,
								dialogClass   : 'wp-dialog',
								modal         : true,
								autoOpen      : false,
								closeOnEscape : true,
								buttons: [
									{
										text: <?php echo json_encode( esc_html__( 'Cancel', 'nelio-ab-testing' ) ); ?>,
										click: function() {
											$(this).dialog('close');
										}
									},
									{
										text: <?php echo json_encode( esc_html__( 'Overwrite', 'nelio-ab-testing' ) ); ?>,
										'class': 'button-primary',
										click: function() {
											$(this).dialog('close');
											var id = $(this).data('overwrite-with');
											nelioab_do_overwrite(id);
										}
									}
								]
							});
						})(jQuery);

						function nelioab_show_the_dialog_for_overwriting(id) {
							var aux = jQuery("#dialog-modal");
							aux.data('overwrite-with', id);
							aux.dialog('open');
						}

						function nelioab_do_overwrite(id) {
							jQuery(".apply-link").each(function() {
								var aux = jQuery(this);
								aux.addClass('disabled');
								aux.attr('href','javascript:;');
							});
							jQuery("#loading-" + id).parent().removeClass('disabled');
							jQuery("#loading-" + id).delay(120).fadeIn();

							jQuery.ajax({
								url: jQuery("#apply_alternative").attr("action"),
								type: 'post',
								data: jQuery('#apply_alternative').serialize(),
								success: function(data) {
									jQuery("#loading-" + id).delay(250).fadeOut(250);
									jQuery("#loading-" + id).parent().addClass('disabled');
									jQuery("#loading-" + id).parent().text(<?php echo json_encode( __( 'Done!', 'nelio-ab-testing' ) ); ?>);
									jQuery("#success-" + id).delay(1000).fadeIn(200).delay(10000).fadeOut(200);
								}
							});
						}
					</script>
				<?php
				}
				?>
			</div>

			<div id="nelio-container-tab-actions" class="nelio-tab-container">
				<div id="exp-info-goal-actions">
					<?php
					$this->print_actions_info();
					?>
				</div>
			</div>

			<p style="color:gray;">
				<span><?php
					try { $this->add_download_csv_button(); } catch ( Exception $ex ) {}
				?></span>
				<span style="float:right;"><?php
					if ( $res != null ) {
						if ( $exp->get_status() == NelioABExperiment::STATUS_RUNNING ) { ?>
							<i class="fa fa-clock-o"
							   style="font-size: 1.5em;vertical-align: middle;"></i>&nbsp;<?php
							printf( esc_html__( 'Last Update: %s', 'nelio-ab-testing' ),
								esc_html( NelioABFormatter::format_date( $res->get_last_update() ) )
							);
						} else {
							printf( esc_html__( 'Last Update: %s', 'nelio-ab-testing' ),
								esc_html( NelioABFormatter::format_date( $res->get_last_update() ) )
							);
						}
					}
				?></span>
			</p>

			<?php
			if ( $exp->get_status() == NelioABExperiment::STATUS_RUNNING ) { ?>
				<script type="text/javascript">
					(function ($) {
						$('#dialog-modal').dialog({
							dialogClass: 'wp-dialog',
							modal: true,
							autoOpen: false,
							closeOnEscape: true,
							buttons: [
								{
									text: <?php echo json_encode( esc_html__( 'Cancel', 'nelio-ab-testing' ) ); ?>,
									click: function () {
										$(this).dialog('close');
									}
								},
								{
									text: <?php echo json_encode( esc_html__( 'OK', 'nelio-ab-testing' ) ); ?>,
									'class': 'button-primary',
									click: function () {
										$(this).dialog('close');
										nelioabAcceptDialog($(this));
									}
								}
							]
						});
					})(jQuery);

					function nelioabAcceptDialog(dialog) {
						var action = dialog.data('action');
						if ('stop' == action)
							nelioabForceStop();
						else if ('edit' == action)
							nelioabConfirmEditing(dialog.data('href'));
					}

					function nelioabConfirmEditing(href, dialog) {
						if ('dialog' == dialog) {<?php
						$title = __( 'Edit Alternative', 'nelio-ab-testing' );
						$title = str_replace( '"', '\\"', $title );
						$msg = __( 'Editing an alternative while the experiment is running may invalidate the results of the experiment. Do you really want to continue?', 'nelio-ab-testing' );
						$msg = str_replace( '"', '\\"', $msg ); ?>
							var $dialog = jQuery('#dialog-modal');
							jQuery('#dialog-content').html(<?php echo json_encode( $msg ); ?>);
							$dialog.dialog('option', 'title', <?php echo json_encode( $title ); ?>);
							$dialog.parent().find('.button-primary .ui-button-text').text(<?php echo json_encode( __( 'Edit' ) ); ?>);
							$dialog.data('action', 'edit');
							$dialog.data('href', href);
							$dialog.dialog('open');
							return;
						}
						window.location.href = href;
					}

					function nelioabForceStop(dialog) {
						if ('dialog' == dialog) {<?php
						$title = __( 'Stop Experiment', 'nelio-ab-testing' );
						$title = str_replace( '"', '\\"', $title );
						$msg = __( 'You are about to stop an experiment. Once the experiment is stopped, you cannot resume it. Are you sure you want to stop the experiment?', 'nelio-ab-testing' );
						$msg = str_replace( '"', '\\"', $msg ); ?>
							var $dialog = jQuery('#dialog-modal');
							jQuery('#dialog-content').html(<?php echo json_encode( $msg ); ?>);
							$dialog.dialog('option', 'title', <?php echo json_encode( $title ); ?>);
							$dialog.parent().find('.button-primary .ui-button-text').text(<?php echo json_encode( __( 'Stop', 'nelio-ab-testing' ) ); ?>);
							$dialog.data('action', 'stop');
							$dialog.dialog('open');
							return;
						}
						smoothTransitions();
						jQuery.get(
							<?php
								$url = add_query_arg( array(
									'page'      => 'nelioab-experiments',
									'action'    => 'progress',
									'id'        => $this->exp->get_id(),
									'exp_type'  => $this->exp->get_type(),
									'forcestop' => 'true',
								), admin_url( 'admin.php' ) );
								echo json_encode( $url );
							?>,
							function (data) {
								data = jQuery.trim(data);
								if ( data.indexOf( '[NELIOAB_LINK]' ) !== -1 &&
										data.indexOf( '[/NELIOAB_LINK]' ) !== -1 ) {
									data = data.substring( data.indexOf( '[NELIOAB_LINK]' ) );
									data = data.substring( 0, data.indexOf( '[/NELIOAB_LINK]' ) );
									data = data.replace( '[NELIOAB_LINK]', '' );
									location.href = data;
								}
								else {
									document.open();
									document.write(data);
									document.close();
								}
							});
					}
				</script><?php
			} ?>

			<script type="text/javascript">
				(function($) {
					$( document ).ready(function() {

						// Navigation Tabs
						$(".nav-tab").click(function () {
							if ( $(this).hasClass("nav-tab-active") )
								return;

							$(".nav-tab").removeClass("nav-tab-active");
							$(".nelio-tab-container").hide();

							$(this).addClass("nav-tab-active");
							var id = $(this).attr('id');
							$("#nelio-container-" + id).fadeIn(600);

							if (id == 'tab-actions') {
								// Masonry for Conversion Actions
								var container = $('#exp-info-goal-actions');
								container.masonry();
							}
						});

						// Upper Bar
						$("#nelio-upper-progress-bar").fadeIn(600);

						<?php
						if ( NelioABExperimentsManager::current_user_can( $exp, 'stop' ) ) { ?>
							$("#nelio-progress-status-stop").click(function() {
								javascript:nelioabForceStop('dialog');
							});
						<?php
						} ?>

						$("#nelio-export-progress").click(function() {
							alert("This functionality is under development.");
						});
						$("#nelio-goal-selector").click(function() {
							$("ul.nelio-goals").fadeIn(100);
						});
						$('ul.nelio-goals').on('mouseleave',function(){ // When losing focus
							$(this).fadeOut(100);
						});

						// Tooltip Image Status
						$('.masterTooltip').hover(function() {
							// Hover over code
							var title = $(this).attr('title');
							$(this).data('tipText', title).removeAttr('title');
							$('<p class="tooltip"></p>')
								.text(title)
								.appendTo('body')
								.fadeIn('slow');
						}, function() {
							// Hover out code
							$(this).attr('title', $(this).data('tipText'));
							$('.tooltip').remove();
						}).mousemove(function(e) {
							var mousex = e.pageX + 20; //Get X coordinates
							var mousey = e.pageY + 10; //Get Y coordinates
							$('.tooltip')
								.css({ top: mousey, left: mousex })
						});
					});
				})(jQuery);
			</script>


			<script type="text/javascript">
			(function( $ ) {
				var $loader = $( 'span.nelioab-results-loader' );
				$loader.addClass( 'is-active' );
				$.ajax({
					url: ajaxurl,
					async: true,
					data: {
						action: "nelioab_update_results",
						exp: <?php echo json_encode( $this->exp->get_id() ); ?>,
						goal: <?php echo json_encode( $this->goal->get_id() ); ?>
					},
					success: function( result ) {
						$loader.removeClass( 'is-active' );
						if ( result.indexOf( 'nelioab-new-results-available' ) >= 0 ) {
							console.log( result );
							var $div = $( '#message-div' );
							$div.html( <?php
								$message = sprintf(
									__( 'Hey! It looks like there are <strong>new results available. <a href="%s">Refresh!</a></strong>', 'nelio-ab-testing' ),
									esc_attr( 'javascript:window.location.reload()' )
								);
								echo json_encode( '<p style="font-size:1.3em;">' . $message . '</p>' );
							?> );
							$div.show();
						} else if ( result.indexOf( 'nelioab-results-are-ok' ) >= 0 ) {
							// Nothing to be done
							<?php
							if ( NELIOAB_SHOW_LOCAL_EXPS ) { ?>
								console.log( 'Results are valid and there\'s no need to look for new data.' );
							<?php
							} ?>
						} else {
							<?php
							if ( NELIOAB_SHOW_LOCAL_EXPS ) { ?>
								console.log( 'Nelio A/B Testing Update Results Message\n', result );
							<?php
							} ?>
						}//end if
					},//end success()
					error: function() {
						$loader.removeClass( 'is-active' );
					}//end error()
				});
			})( jQuery );
			</script>
			<?php

		}

		protected function print_experiment_status( $exp, $res, $the_winner, $the_winner_confidence,
			$originals_conversion_rate, $best_alt, $best_alt_conversion_rate, $best_alt_improvement_factor ) {
			if ( $res )
				$message = NelioABGTest::generate_status_message( $res->get_summary_status() );
			else
				$message = NelioABGTest::generate_status_message( false );

			$src = nelioab_admin_asset_link( '/images/progress-no.png' );

			if ( $best_alt > 0 )
				$best_alt = '(' . __( 'Alternative', 'nelio-ab-testing' ) . ' ' . $best_alt . ')';
			else
				$best_alt = '';

			$arrow = '';
			$arrow_gain = '';
			$stats_color = 'auto';
			$gain = '';

			if ( self::NO_WINNER == $the_winner ) {
				$main_message = __( 'Testing...', 'nelio-ab-testing' );

				if ( NelioABExperiment::STATUS_RUNNING == $exp->get_status() )
					$html_status_message = __( 'No alternative is better than the rest', 'nelio-ab-testing' );
				else
					$html_status_message = __( 'No alternative was better than the rest', 'nelio-ab-testing' );
			}
			else {
				if ( $the_winner == 0 ) {
					if ( $the_winner_confidence >= NelioABSettings::get_min_confidence_for_significance() ) {
						$html_status_message = sprintf( __( 'Original wins with a <strong>%1$s%%</strong> confidence', 'nelio-ab-testing' ),
							$the_winner_confidence );
						$main_message = __( 'Winner!', 'nelio-ab-testing' );
					} else {
						$html_status_message = sprintf( __( 'Original wins with just a <strong>%1$s%%</strong> confidence', 'nelio-ab-testing' ),
							$the_winner_confidence );
						$main_message = __( 'Possible Winner', 'nelio-ab-testing' );
					}
				} else {
					if ( $the_winner_confidence >= NelioABSettings::get_min_confidence_for_significance() ) {
						$html_status_message = sprintf( __( 'Alternative %1$s wins with a <strong>%2$s%%</strong> confidence', 'nelio-ab-testing' ),
							$the_winner, $the_winner_confidence );
						$main_message = __( 'Winner!', 'nelio-ab-testing' );
					} else {
						$html_status_message = sprintf( __( 'Alternative %1$s wins with just a <strong>%2$s%%</strong> confidence', 'nelio-ab-testing' ),
							$the_winner, $the_winner_confidence );
						$main_message = __( 'Possible Winner', 'nelio-ab-testing' );
					}
				}

				if ( $the_winner_confidence >= NelioABSettings::get_min_confidence_for_significance() ) {
					$src = nelioab_admin_asset_link( '/images/progress-yes.png' );
				} else {
					$src = nelioab_admin_asset_link( '/images/progress-yes-no.png' );
				}
			}

			$print_improvement = false;
			$print_gain        = false;

			if ( is_numeric( $best_alt_improvement_factor ) ) {

				// gain
				$alt_results = $this->results->get_alternative_results();
				$ori_conversions = $alt_results[0]->get_num_of_conversions();
				$aux = ( $ori_conversions * $this->goal->get_benefit() * $best_alt_improvement_factor )/100;

				$print_improvement = true;
				$print_gain = true;
				// format improvement factor
				if ( $best_alt_improvement_factor < 0 ) {
					$arrow                       = 'fa-arrow-down';
					$stats_color                 = 'red';
					$best_alt_improvement_factor = $best_alt_improvement_factor * - 1;
				} else if ( $best_alt_improvement_factor > 0 ) {
					$arrow = 'fa-arrow-up';
					$stats_color = 'green';
				} else {
					$print_improvement = false;
					$arrow = 'fa-arrow-none';
					$stats_color = 'black';
				}

				$arrow_gain = $arrow;

				if ( $aux > 0 ) {
					$gain = sprintf( _x( '%1$s%2$s', 'money', 'nelio-ab-testing' ),
						NelioABSettings::get_conv_unit(),
						number_format_i18n( $aux, 2 )
					);
				} else {
					if ( $exp->get_type() == NelioABExperiment::HEADLINE_ALT_EXP ||
						  $exp->get_type() == NelioABExperiment::WC_PRODUCT_SUMMARY_ALT_EXP ) {
						$gain       = '';
						$print_gain = false;
						$arrow_gain = 'fa-arrow-none';
					}
					else {
						$gain = sprintf( _x( '%1$s%2$s', 'money', 'nelio-ab-testing' ),
							NelioABSettings::get_conv_unit(),
							number_format_i18n( $aux * -1, 2 )
						);
					}
				}
			}

			?>

			<div id="info-status">
				<span class="main-message"><?php echo esc_html( $main_message ); ?></span>
				<img src="<?php echo esc_url( $src ); ?>" title="<?php echo esc_attr( $message ); ?>" alt="<?php echo esc_attr( $message ); ?>" class="masterTooltip animated flipInY"/>
				<span class="additional-message"><?php echo $html_status_message; ?></span>
			</div>
			<div id="ori-status">
				<span class="ori-name"><?php esc_html_e( 'Original', 'nelio-ab-testing' ); ?></span>
				<div id="ori-cr">
					<span class="ori-cr-title"><?php esc_html_e( 'Conversion Rate', 'nelio-ab-testing' ); ?></span>
					<span class="ori-cr-value"><?php printf( '%s %%', esc_html( $originals_conversion_rate ) ); ?></span>
				</div>
			</div>
			<div id="alt-status">
				<span class="alt-name"><?php esc_html_e( 'Best Alternative', 'nelio-ab-testing' ); ?> <?php echo esc_html( $best_alt ); ?></span>
				<div id="alt-cr">
					<span class="alt-cr-title"><?php esc_html_e( 'Conversion Rate', 'nelio-ab-testing' ); ?></span>
					<span class="alt-cr"><?php printf( '%s %%', esc_html( $best_alt_conversion_rate ) ); ?></span>
				</div>
				<div id="alt-stats" style="color:<?php echo esc_attr( $stats_color ); ?>;">
					<span class="alt-if"><i class="fa <?php echo esc_attr( $arrow ); ?>" style="vertical-align: top;"></i><?php if ( $print_improvement ) printf( '%s%%', esc_html( $best_alt_improvement_factor ) ); ?></span>
					<span class="alt-ii"><i class="fa <?php echo esc_attr( $arrow_gain ); ?>" style="vertical-align: top;"></i><?php if ( $print_gain ) echo esc_html( $gain ); ?></span>
				</div>
			</div>
		<?php
		}

		protected function get_experiment_icon( $exp ){
			$img = '<div class="tab-type tab-type-%1$s" alt="%2$s" title="%2$s"></div>';

			switch( $exp->get_type() ) {
				case NelioABExperiment::PAGE_ALT_EXP:
					$page_on_front = get_option( 'page_on_front' );
					$aux = $exp->get_original();
					if ( $page_on_front == $aux->get_value() )
						return sprintf( $img, 'landing-page', esc_attr__( 'Landing Page', 'nelio-ab-testing' ) );
					else
						return sprintf( $img, 'page', esc_attr__( 'Page', 'nelio-ab-testing' ) );

				case NelioABExperiment::POST_ALT_EXP:
					return sprintf( $img, 'post', esc_attr__( 'Post', 'nelio-ab-testing' ) );

				case NelioABExperiment::CPT_ALT_EXP:
					return sprintf( $img, 'cpt', esc_attr__( 'Custom Post Type', 'nelio-ab-testing' ) );

				case NelioABExperiment::HEADLINE_ALT_EXP:
					return sprintf( $img, 'title', esc_attr__( 'Headline', 'nelio-ab-testing' ) );

				case NelioABExperiment::THEME_ALT_EXP:
					return sprintf( $img, 'theme', esc_attr__( 'Theme', 'nelio-ab-testing' ) );

				case NelioABExperiment::CSS_ALT_EXP:
					return sprintf( $img, 'css', esc_attr__( 'CSS', 'nelio-ab-testing' ) );

				case NelioABExperiment::HEATMAP_EXP:
					return sprintf( $img, 'heatmap', esc_attr__( 'Heatmap', 'nelio-ab-testing' ) );

				case NelioABExperiment::WIDGET_ALT_EXP:
					return sprintf( $img, 'widget', esc_attr__( 'Widget', 'nelio-ab-testing' ) );

				case NelioABExperiment::MENU_ALT_EXP:
					return sprintf( $img, 'menu', esc_attr__( 'Menu', 'nelio-ab-testing' ) );

				case NelioABExperiment::WC_PRODUCT_SUMMARY_ALT_EXP:
					return sprintf( $img, 'wc-product-summary', esc_attr__( 'WooCommerce Product Summary', 'nelio-ab-testing' ) );

				default:
					return '';
			}
		}

		protected function get_winner_icon( $exp ){
			$img = '<div class="tab-type tab-type-winner" alt="%1$s" title="%1$s"></div>';

			switch( $exp->get_type() ) {
				case NelioABExperiment::PAGE_ALT_EXP:
					$page_on_front = get_option( 'page_on_front' );
					$aux = $exp->get_original();
					if ( $page_on_front == $aux->get_value() )
						return sprintf( $img, esc_attr__( 'Landing Page', 'nelio-ab-testing' ) );
					else
						return sprintf( $img, esc_attr__( 'Page', 'nelio-ab-testing' ) );

				case NelioABExperiment::POST_ALT_EXP:
					return sprintf( $img, esc_attr__( 'Post', 'nelio-ab-testing' ) );

				case NelioABExperiment::CPT_ALT_EXP:
					return sprintf( $img, esc_attr__( 'Custom Post Type', 'nelio-ab-testing' ) );

				case NelioABExperiment::HEADLINE_ALT_EXP:
					return sprintf( $img, esc_attr__( 'Headline', 'nelio-ab-testing' ) );

				case NelioABExperiment::THEME_ALT_EXP:
					return sprintf( $img, esc_attr__( 'Theme', 'nelio-ab-testing' ) );

				case NelioABExperiment::CSS_ALT_EXP:
					return sprintf( $img, esc_attr__( 'CSS', 'nelio-ab-testing' ) );

				case NelioABExperiment::HEATMAP_EXP:
					return sprintf( $img, esc_attr__( 'Heatmap', 'nelio-ab-testing' ) );

				case NelioABExperiment::WIDGET_ALT_EXP:
					return sprintf( $img, esc_attr__( 'Widget', 'nelio-ab-testing' ) );

				case NelioABExperiment::MENU_ALT_EXP:
					return sprintf( $img, esc_attr__( 'Menu', 'nelio-ab-testing' ) );

				default:
					return '';
			}
		}

		protected function print_experiment_information( $exp, $descr, $res ) { ?>
			<div id="exp-info-header">
				<?php echo $this->get_experiment_icon( $exp ); ?>
				<span class="exp-title"><?php echo esc_html( $exp->get_name() ); ?></span>
				<div style="position:absolute;right:1em;top:1em;background:white;color:grey;font-family:monospace;font-size:10px;">ID:<?php echo esc_html( $exp->get_key_id() ); ?></div>
			</div>

			<?php
			$startDate = NelioABFormatter::format_date( $exp->get_start_date() );
			$end   = $exp->get_end_date();
			if ( empty( $end ) ) {
				$running = __( 'Started on', 'nelio-ab-testing' ) . ' ' . $startDate;
			} else {
				$endDate = NelioABFormatter::format_date( $end );
				$running = $startDate . '&mdash;' . $endDate;
			}

			$duration_title = __( 'Elapsed Time', 'nelio-ab-testing' );
			if ( $res == null && $exp->get_status() == NelioABExperiment::STATUS_FINISHED ) {
				$duration_title = __( 'Duration', 'nelio-ab-testing' );
				$duration = NelioABFormatter::get_timelapse( $exp->get_start_date(), $exp->get_end_date() );
			} else if ( $res == null ) {
				$duration = __( 'Not available', 'nelio-ab-testing' );
			} else {
				$duration = NelioABFormatter::get_timelapse( $exp->get_start_date(), $res->get_last_update() );
			}
			?>

			<div id="exp-info-running-time">
				<span class="exp-info-header"><?php echo esc_html( $duration_title ); ?></span>
				<span class="exp-info-duration"><?php echo esc_html( $duration ); ?></span>
				<span class="exp-info-running-values"><?php echo esc_html( $running ); ?></span>
			</div>

			<?php
			$end_mode = __( 'The experiment can only be stopped manually', 'nelio-ab-testing' );

			if ( $exp->get_status() == NelioABExperiment::STATUS_RUNNING &&
			     NelioABAccountSettings::get_subscription_plan() >= NelioABAccountSettings::ENTERPRISE_SUBSCRIPTION_PLAN ) {

				switch ( $exp->get_finalization_mode() ) {
					case NelioABExperiment::FINALIZATION_MANUAL:
						$end_mode = __( 'The experiment can only be stopped manually', 'nelio-ab-testing' );
						break;

					case NelioABExperiment::FINALIZATION_AFTER_DATE:
						$raw_fin_value = $exp->get_finalization_value();
						$fin_value     = __( '24 hours', 'nelio-ab-testing' );
						if ( $raw_fin_value >= 2 ) {
							$fin_value = __( '48 hours', 'nelio-ab-testing' );
						}
						if ( $raw_fin_value >= 5 ) {
							$fin_value = __( '5 days', 'nelio-ab-testing' );
						}
						if ( $raw_fin_value >= 7 ) {
							$fin_value = __( '1 week', 'nelio-ab-testing' );
						}
						if ( $raw_fin_value >= 14 ) {
							$fin_value = __( '2 weeks', 'nelio-ab-testing' );
						}
						if ( $raw_fin_value >= 30 ) {
							$fin_value = __( '1 month', 'nelio-ab-testing' );
						}
						if ( $raw_fin_value >= 60 ) {
							$fin_value = __( '2 months', 'nelio-ab-testing' );
						}
						$end_mode = sprintf(
							__( 'The experiment will be automatically stopped %s after it was started.', 'nelio-ab-testing' ),
							$fin_value
						);
						break;

					case NelioABExperiment::FINALIZATION_AFTER_VIEWS:
						$end_mode = sprintf(
							__( 'The experiment will be automatically stopped when the tested page (along with its alternatives) has been seen over %s times.', 'nelio-ab-testing' ),
							$exp->get_finalization_value()
						);
						break;

					case NelioABExperiment::FINALIZATION_AFTER_CONFIDENCE:
						$end_mode = sprintf(
							__( 'The experiment will be automatically stopped when confidence reaches %s%%.', 'nelio-ab-testing' ),
							$exp->get_finalization_value()
						);
						break;
				}
			} ?>

			<div id="exp-info-end-mode">
				<span><?php esc_html_e( 'Finalization Mode', 'nelio-ab-testing' ); ?></span>
				<span class="exp-end-mode"><?php echo esc_html( $end_mode ); ?></span>
			</div>

			<?php
			if ( empty( $descr ) )
				$descr = __( 'No description provided', 'nelio-ab-testing' );
			?>

			<div id="exp-info-descr">
				<span><?php esc_html_e( 'Description', 'nelio-ab-testing' ) ?></span>
				<span><?php echo esc_html( $descr ); ?></span>
			</div>
		<?php
		}

		protected function trunk( $in ) {
			return mb_strlen( $in ) > 50 ? mb_strimwidth( $in, 0, 50 ) . '...' : $in;
		}

		protected function get_best_alt() {
			$res = $this->results;
			if ( $res == null )
				return -1;
			$best = 0;
			$best_name = '';
			$alts = $res->get_alternative_results();
			for ( $i = 1; $i < count( $alts ); ++$i ) {
				$alt_result = $alts[$i];
				$conv = $alt_result->get_conversion_rate();
				if ( $best < $conv ) {
					$best = $conv;
					$best_name = $i;
				}
			}
			return $best_name;
		}

		protected function get_best_alt_conversion_rate() {
			$res = $this->results;
			if ( $res == null )
				return self::NO_WINNER;
			$best = -1;
			$alts = $res->get_alternative_results();
			$page_views = 0;
			for ( $i = 1; $i < count( $alts ); ++$i ) {
				$alt_result = $alts[$i];
				$conv = $alt_result->get_conversion_rate();
				if ( $best < $conv ) {
					$best = $conv;
					$page_views = $alt_result->get_num_of_visitors();
				}
			}
			if ( 0 == $page_views ) {
				return self::NO_WINNER;
			}
			return $best;
		}

		protected function get_best_alt_improvement_factor( $i ) {
			$res = $this->results;
			if ( $res == null )
				return '';
			if ( $i <= 0 )
				return '';

			$alts = $res->get_alternative_results();
			$alt_result = $alts[$i];
			$best = $alt_result->get_improvement_factor();

			return $best;
		}

		protected function get_winning_confidence() {
			$bestg = $this->get_winning_gtest();
			if ( !$bestg )
				return self::NO_WINNER;
			return number_format_i18n( $bestg->get_certainty(), 2 );
		}

		protected function get_winning_gtest() {
			$res = $this->results;
			if ( $res == null )
				return false;

			$gtests = $res->get_gtests();

			if ( count( $gtests ) == 0 )
				return false;

			/** @var NelioABGTest $bestg */
			$bestg = $gtests[count( $gtests ) - 1];

			if ( $bestg->is_original_the_best() ) {
				if ( $bestg->get_type() == NelioABGTest::WINNER )
					return $bestg;
			}
			else {
				$aux = null;
				foreach ( $gtests as $gtest )
					if ( $gtest->get_min() == $this->get_original_value() )
						$aux = $gtest;
				if ( $aux )
					if ( $aux->get_type() == NelioABGTest::WINNER ||
					     $aux->get_type() == NelioABGTest::DROP_VERSION )
						return $aux;
			}

			return false;
		}

		protected function is_winner( $id ) {
			$winner = $this->who_wins_real_id();

			if ( self::NO_WINNER == $winner )
				return false;
			else
				return $id == $winner;
		}

		protected function who_wins() {
			$exp = $this->exp;
			$winner_id = $this->who_wins_real_id();
			if ( $winner_id == $exp->get_originals_id() )
				return 0;
			$i = 1;
			foreach ( $exp->get_alternatives() as $alt ) {
				if ( $winner_id == $alt->get_value() )
					return $i;
				$i++;
			}
			return self::NO_WINNER;
		}

		protected function who_wins_real_id() {
			$res = $this->results;
			if ( $res == null )
				return self::NO_WINNER;

			$gtests = $res->get_gtests();
			if ( count( $gtests ) == 0 )
				return self::NO_WINNER;

			$aux = false;
			foreach ( $gtests as $gtest ) {
				if ( $gtest->get_type() == NelioABGTest::WINNER ||
				     $gtest->get_type() == NelioABGTest::DROP_VERSION )
					$aux = $gtest->get_max();
			}

			if ( $aux )
				return $aux;
			else
				return self::NO_WINNER;
		}

		private function array_division( $arr_numerator, $arr_divisor ) {
			$len = count( $arr_numerator );
			$aux = count( $arr_divisor );
			if ( $aux < $len )
				$len = $aux;

			$result = array();
			for ( $i = 0; $i < $len; ++$i ) {
				$num = $arr_numerator[$i];
				$div = $arr_divisor[$i];
				if ( $div < 1 )
					$aux = 0;
				elseif ( $num < $div )
					$aux = round( ($num / $div) * 100, 1 );
				else
					$aux = 100;
				array_push( $result, $aux );
			}

			return $result;
		}

		/**
		 *
		 *
		 */
		protected function print_timeline_for_alternatives_js() {

			$res = $this->results;

			// Start date
			// -------------------------------------------
			$first_update = time();
			if ( is_object( $res ) )
				$first_update = strtotime( $res->get_first_update() ); // This has to be a unixtimestamp...
			$timestamp    = mktime( 0, 0, 0,
				date( 'n', $first_update ),
				date( 'j', $first_update ),
				date( 'Y', $first_update )
			); // M, D, Y

			// Build data
			// -------------------------------------------
			$average      = array();
			$alternatives = array();
			if ( is_object( $res ) ) {
				$average = $this->array_division(
					$res->get_conversions_history(), $res->get_visitors_history() );

				$alternatives = array();
				foreach( $res->get_alternative_results() as $alt_res ) {
					array_push( $alternatives, $this->array_division(
						$alt_res->get_conversions_history(), $alt_res->get_visitors_history() ) );
				}
			}

			// Computing max value
			$max = 5;
			foreach ( $alternatives as $values )
				foreach ( $values as $val )
					if ( $val > $max )
						$max = $val;
			if ( $max > 100 )
				$max = 100;

			$the_count = count( $average );
			for( $i = 0; $i < ( 7 - $the_count ); ++$i ) {
				array_unshift( $average, 0 );
				$aux = array();
				foreach( $alternatives as $alt ) {
					array_unshift( $alt, 0 );
					array_push( $aux, $alt );
				}
				$alternatives = $aux;
				$timestamp = $timestamp - 86400; // substract one day
			}
			$year  = date( 'Y', $timestamp );
			$month = intval( date( 'n', $timestamp ) ) - 1;
			$day   = date( 'j', $timestamp );
			$date  = sprintf( 'Date.UTC(%s, %s, %s)', json_encode( $year ), json_encode( $month ), json_encode( $day ) );

			// Building labels (i18n)
			// -------------------------------------------
			$labels = array();
			$labels['title']       = __( 'Evolution of the Experiment', 'nelio-ab-testing' );
			$labels['subtitle1']   = __( 'Click and drag in the plot area to zoom in', 'nelio-ab-testing' );
			$labels['subtitle2']   = __( 'Pinch the chart to zoom in', 'nelio-ab-testing' );
			$labels['yaxis']       = __( 'Conversion Rate (%)', 'nelio-ab-testing' );
			$labels['original']    = __( 'Original', 'nelio-ab-testing' );
			$labels['alternative'] = __( 'Alternative %s', 'nelio-ab-testing' );
			?>
			<script type="text/javascript">
				(function($) {
					var alternatives = <?php echo json_encode( $alternatives ); ?>;
					var labels       = <?php echo json_encode( $labels ); ?>;
					var startDate    = <?php echo $date; ?>;
					timelineGraphic = makeTimelinePerAlternativeGraphic("nelioab-timeline", labels, alternatives, startDate, <?php echo json_encode( $max ); ?>);
				})(jQuery);
			</script>
		<?php
		}

		abstract protected function get_labels_for_conversion_rate_js();
		protected function print_conversion_rate_js() {
			$alt_results = $this->results->get_alternative_results();

			// Build categories
			// -------------------------------------------
			$categories = array();
			$the_size = count( $alt_results );
			if ( $the_size > 0 ) {
				array_push( $categories, $alt_results[0]->get_name() );
				if ( $the_size > 3 ) {
					for ( $i = 1; $i < count( $alt_results ); $i++ )
						array_push( $categories, sprintf( __( 'Alt %s', 'nelio-ab-testing' ), $i ) );
				}
				else {
					for ( $i = 1; $i < count( $alt_results ); $i++ )
						array_push( $categories, sprintf( __( 'Alternative %s', 'nelio-ab-testing' ), $i ) );
				}
			}

			// Build data
			// -------------------------------------------
			$max_value = 0;
			$unique    = true;

			// Find the max conversion rate (if any)
			foreach( $alt_results as $aux ) {
				$rate = $aux->get_conversion_rate();
				if ( $rate > $max_value ) {
					$max_value = $rate;
					$unique    = true;
				}
				else if ( $rate == $max_value ) {
					$unique = false;
				}
			}

			// (if one or more alternatives have the same max value, none
			// has to be highlighted)
			if ( !$unique )
				$max_value = 105;

			// Retrieve the results of each alternative, highlighting the
			// one whose conversion rate equals $max_value
			$data = array();
			foreach( $alt_results as $aux ) {
				$rate = $aux->get_conversion_rate();
				if ( $rate == $max_value ) {
					$color = '#b0d66f';
				} else {
					$color = $this->colorscheme['primary'];
				}//end if
				$rate = number_format( $rate, 2, '.', '' );
				array_push( $data, array(
					'y'     => sprintf( '%s', $rate ),
					'color' => $color,
				) );
			}

			// Building labels (i18n)
			// -------------------------------------------
			$labels = $this->get_labels_for_conversion_rate_js();
			?>
			<script type="text/javascript">
				(function($) {
					var categories  = <?php echo json_encode( $categories ); ?>;
					var data        = <?php echo json_encode( $data ); ?>;
					var labels      = <?php echo json_encode( $labels ); ?>;
					convRateGraphic = makeConversionRateGraphic("nelioab-conversion-rate", labels, categories, data);
				})(jQuery);
			</script>
		<?php
		}

		abstract protected function get_labels_for_improvement_factor_js();
		protected function print_improvement_factor_js() {
			$alt_results = $this->results->get_alternative_results();

			// For the improvement factor, the original alternative is NOT used
			$alt_results = array_slice( $alt_results, 1 );

			// Build categories
			// -------------------------------------------
			$categories = array();
			$the_size = count( $alt_results );
			if ( $the_size > 0 ) {
				if ( $the_size > 2 ) {
					for ( $i = 0; $i < count( $alt_results ); $i++ )
						array_push( $categories, sprintf( __( 'Alt %s', 'nelio-ab-testing' ), $i+1 ) );
				}
				else {
					for ( $i = 0; $i < count( $alt_results ); $i++ )
						array_push( $categories, sprintf( __( 'Alternative %s', 'nelio-ab-testing' ), $i+1 ) );
				}
			}

			// Build data
			// -------------------------------------------
			$max_value = 0;
			$unique    = true;

			// Find the max improvement factor (if any)
			foreach( $alt_results as $aux ) {
				$factor = $aux->get_improvement_factor();
				if ( $factor > $max_value ) {
					$max_value = $factor;
					$unique    = true;
				}
				else if ( $factor == $max_value ) {
					$unique = false;
				}
			}

			// (if one or more alternatives have the same max value, none
			// has to be highlighted)
			if ( !$unique )
				$max_value = 105;

			// Retrieve the results of each alternative, highlighting the
			// one whose improvement factor equals $max_value
			$data = array();
			foreach( $alt_results as $aux ) {
				$factor = $aux->get_improvement_factor();
				if ( $factor == $max_value ) {
					$color = '#b0d66f';
				} else if ( $factor < 0 ) {
					$color = '#cf4944';
				} else {
					$color = $this->colorscheme['primary'];
				}//end if
				$factor = number_format( $factor, 2, '.', '' );
				array_push( $data, array(
					'y'     => sprintf( '%s', $factor ),
					'color' => $color,
				) );
			}

			// Building labels (i18n)
			// -------------------------------------------
			$labels = $this->get_labels_for_improvement_factor_js();
			?>
			<script type="text/javascript">
				(function($) {
					var categories      = <?php echo json_encode( $categories ); ?>;
					var data            = <?php echo json_encode( $data ); ?>;
					var labels          = <?php echo json_encode( $labels ); ?>;
					improvFactorGraphic = makeImprovementFactorGraphic("nelioab-improvement-factor", labels, categories, data);
				})(jQuery);
			</script>
		<?php
		}

		private function add_download_csv_button() {

			$exp  = $this->exp;
			$res  = $this->results;
			if ( ! $res ) {
				return;
			}//end if

			$total_visitors    = number_format( $res->get_total_visitors() );
			$total_conversions = number_format( $res->get_total_conversions() );
			$aux = $res->get_alternative_results();
			$ori_conv_rate = number_format_i18n( $aux[0]->get_conversion_rate(), 2 );
			if ( $aux[0]->get_num_of_visitors() == 0 ) {
				$ori_conv_rate = '-';
			}

			$best_alt_conv_rate = $this->get_best_alt_conversion_rate();
			if ( $best_alt_conv_rate < 0 )
				$best_alt_conv_rate = '-';
			else
				$best_alt_conv_rate = number_format( $best_alt_conv_rate, 2 );

			$descr = trim( $exp->get_description() );
			if ( empty( $descr ) ) {
				$descr = __( 'No description', 'nelioab' );
			}

			$start_date = $exp->get_start_date();
			$start_date = NelioABFormatter::format_date( $start_date, false, 'Y-m-d H:i' );

			$end_date = $exp->get_end_date();
			if ( empty( $end_date ) ) {
				$end_date = '-';
			} else {
				$end_date = NelioABFormatter::format_date( $end_date, false, 'Y-m-d H:i' );
			}

			$winner = $this->who_wins();
			if ( self::NO_WINNER === $winner ) {
				$winner = '-';
			}

			$bestg = $this->get_winning_gtest();
			if ( $bestg ) {
				$confidence = number_format( $bestg->get_certainty(), 2 );
			} else  {
				$confidence = '-';
			}

			$json = array(
				'name'           => trim( $exp->get_name() ),
				'description'    => $descr,
				'startDate'      => $start_date,
				'endDate'        => $end_date,
				'conversions'    => $total_conversions,
				'pageViews'      => $total_visitors,
				'oriConvRate'    => $ori_conv_rate,
				'bestConvRate'   => $best_alt_conv_rate,
				'winner'         => $winner,
				'confidence'     => $confidence,
				'days'           => array(),
				'alternatives'   => array(),
			);

			$alternatives = $exp->get_alternatives();
			$alt_results = $res->get_alternative_results();
			for ( $i = 0; $i < count( $alt_results ); ++$i ) {

				if ( 0 === $i ) {
					$name = sprintf( __( '%s (Original)', 'nelioab' ), $this->get_original_name() );
				} else {
					$alt = $alternatives[ $i - 1 ];
					$name = $alt->get_name();
				}//end if

				$conv_rate_history = $this->array_division(
					$alt_results[ $i ]->get_conversions_history(), $alt_results[ $i ]->get_visitors_history()
				);
				array_push( $json['alternatives'], array(
						'name'              => $name,
						'convRateHistory'   => $conv_rate_history,
						'totalPageViews'    => $alt_results[ $i ]->get_num_of_visitors(),
						'totalConversions'  => $alt_results[ $i ]->get_num_of_conversions(),
				) );

			}//end for

			$date = new DateTime( $exp->get_start_date() );
			$date_interval = new DateInterval( 'P1D' );
			$num_of_days = count( $alt_results[ 0 ]->get_conversions_history() );
			for ( $i = 0; $i < $num_of_days; ++$i ) {
				array_push( $json['days'], NelioABFormatter::format_unix_timestamp( date_timestamp_get( $date ), false, 'Y-m-d' ) );
				$date->add( $date_interval );
			}

			echo '<a href="#" id="nab-download-csv">' . esc_html__( 'Download CSV', 'nelioab' ) . '</a>';
			echo '<script>var nabResults = ' . json_encode( $json ) . ';</script>';
			?>
<script>
(function( $ ) {

	function wrap( str ) {
		if ( 'string' !== typeof str ) {
			str = str + '';
		}//end if
		return '"' + str.replace( /"/g, '""' ) + '"';
	}

	function completeLine( numOfCommas ) {
		numOfCommas = Math.max( numOfCommas, 0 );
		try {
			return ','.repeat( numOfCommas ) + "\n";
		} catch( e ) {}
		var res = '';
		for ( var i = 0; i < numOfCommas; ++i ) { res += ','; }
		return res + "\n";
	}

	function generateCSV() {
		var numOfCommas = Math.max( 7, nabResults.alternatives.length - 1 );
		var csv = '';
		csv += wrap( nabResults.name ) + completeLine( numOfCommas );
		csv += wrap( nabResults.description ) + completeLine( numOfCommas );
		csv += completeLine( numOfCommas );
		csv += '"Winner","Confidence","Conversions","Page Views","Original\'s Conv. Rate","Best Alternative\'s Conv. Rate","Start Date","End Date"' + completeLine( numOfCommas - 7 );
		csv +=
			wrap( nabResults.winner ) + ',' +
			wrap( nabResults.confidence ) + ',' +
			wrap( nabResults.conversions ) + ',' +
			wrap( nabResults.pageViews ) + ',' +
			wrap( nabResults.oriConvRate ) + ',' +
			wrap( nabResults.bestConvRate ) + ',' +
			wrap( nabResults.startDate ) + ',' +
			wrap( nabResults.endDate ) + completeLine( numOfCommas - 7 );

		csv += completeLine( numOfCommas );

		csv += '""';
		for ( var i = 0; i < nabResults.alternatives.length; ++i ) {
			csv += ',' + wrap( nabResults.alternatives[ i ].name );
		}
		csv += completeLine( numOfCommas - nabResults.alternatives.length );

		csv += '"Conversions"';
		for ( i = 0; i < nabResults.alternatives.length; ++i ) {
			csv += ',' + nabResults.alternatives[ i ].totalConversions;
		}
		csv += completeLine( numOfCommas - nabResults.alternatives.length );

		csv += '"Page Views"';
		for ( i = 0; i < nabResults.alternatives.length; ++i ) {
			csv += ',' + nabResults.alternatives[ i ].totalPageViews;
		}
		csv += completeLine( numOfCommas - nabResults.alternatives.length );

		csv += completeLine( numOfCommas );
		csv += '"CR HISTORY (%)"' + completeLine( numOfCommas );

		for ( var day = 0; day < nabResults.days.length; ++day ) {
			csv += wrap( nabResults.days[ day ] );
			for ( i = 0; i < nabResults.alternatives.length; ++i ) {
				csv += ',' + nabResults.alternatives[ i ].convRateHistory[ day ];
			}
			csv += completeLine( numOfCommas - nabResults.alternatives.length );
		}

		return csv;
	}

	function downloadCSV() {
		var csv = generateCSV();
		var url = window.URL || window.webkitURL;
		var blob = new Blob( [ csv ], { type:'text/csv;charset=UTF-8', encoding:'UTF-8' } );
		var element = document.createElement( 'a' );
		element.setAttribute( 'href', url.createObjectURL( blob ) );
		element.setAttribute( 'download', 'results.csv' );
		document.body.appendChild( element );
		element.click();
		document.body.removeChild( element );
		view.unlock();
	}

	$( '#nab-download-csv' ).on( 'click', downloadCSV );

})( jQuery );
</script>
			<?php

		}

	}//NelioABAltExpProgressPage
}
