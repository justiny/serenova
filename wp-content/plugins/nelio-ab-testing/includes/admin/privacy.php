<?php

class NelioAB_Privacy {
	
	public static function init() {
		add_action( 'admin_init', array( __CLASS__, 'add_privacy_policy_content' ) );
	}

	public static function add_privacy_policy_content() {
		if ( ! function_exists( 'wp_add_privacy_policy_content' ) ) {
			return;
		}

		$content = '<div class="wp-suggested-text">';
		$content .= '<p>' . __( 'We use Nelio A/B Testing to split test our website. Split Testing (or A/B Testing) is a marketing technique used to test different variations of a website with the aim of identifying which variation is better at converting visitors.', 'nelio-ab-testing' ) . '</p>';
		$content .= sprintf(
			'<p>' . __( 'Nelio A/B Testing uses cookies to run split tests and track the actions you take while visiting our website. The cookies anonymously identify you and your session, specify whether you participate in our running tests and, if you do, in which ones, and keep track of the variations you are shown. Whenever you perform an action that is relevant to a running test, such as visiting a certain page, clicking on an element, or submitting a form, the fact that you performed said action is stored in Nelio\'s cloud in compliance to <a href="%s">Nelio A/B Testing\'s Terms and Conditions</a>. Please notice Nelio does not store any personal data that can be related to you&mdash;all the collected data is completely anonymized.', 'nelio-ab-testing' ) . '</p>',
			esc_url( __( 'https://neliosoftware.com/nelio-ab-testing-terms-conditions/', 'nelio-ab-testing' ) )
		);
		$content .= '<p class="privacy-policy-tutorial">' . __( '<strong>Notice:</strong> Nelio A/B Testing adds the anonymous ID that identifies a visitor, as well as the tests they participate in, in your forms as hidden fields. These, and only these fields, are used to synchronize a form submission with Nelio\'s cloud. No personal data is ever synchronized with Nelio\'s cloud. It is possible that your form stores that hidden field in your database and, if that is the case, you should warn your visitors about it.', 'nelio-ab-testing' ) . '</p>';
		$content .= '</div>';

		$content = wp_kses_post( apply_filters( 'nelioab_privacy_policy_content', wpautop( $content ) ) );

		wp_add_privacy_policy_content( 'Nelio A/B Testing', $content );
	}

}
