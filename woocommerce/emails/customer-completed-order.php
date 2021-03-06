<?php
/**
 * Customer completed order email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-completed-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     2.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<p><?php echo __( "Hi there. Your vector portrait package from TJN.io is complete! Thanks for letting me draw your face", 'woocommerce'); ?></p>


<?php
//  Get vector portrait

error_log('order: '.print_r($order->get_id(), true));

$vector_portrait_package = get_field('vector_portrait_package', $order->get_id()); 

error_log('portrait_package: '.print_r($vector_portrait_package, true));
?>


<table style="width: 100%; text-align: center;">
	<tbody>
		<tr>
			<td>
				<p style="margin-bottom: 1em;"><a href="<?php echo $vector_portrait_package; ?>" download style="padding: 1em 2em; border-radius: 100px; box-shadow: 1px 1px 4px rgba(0, 0, 0, 0.2); background-color: #68BB63; color: white; text-decoration: none; margin-bottom: 1em;">Download Package</a></p>
			</td>
		</tr>
	</tbody>
</table>

<p><?php echo __( "Your order details are shown below for your reference:", 'woocommerce'); ?></p>

<?php

/**
 * @hooked WC_Emails::order_details() Shows the order details table.
 * @hooked WC_Structured_Data::generate_order_data() Generates structured data.
 * @hooked WC_Structured_Data::output_structured_data() Outputs structured data.
 * @since 2.5.0
 */
do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

/**
 * @hooked WC_Emails::order_meta() Shows order meta data.
 */
do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

/**
 * @hooked WC_Emails::customer_details() Shows customer details
 * @hooked WC_Emails::email_address() Shows email address
 */
// do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

/**
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action( 'woocommerce_email_footer', $email );
