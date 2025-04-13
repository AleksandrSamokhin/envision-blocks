<?php
/**
 * Add to wishlist button template
 *
 * @author YITH
 * @package YITH\Wishlist\Templates\AddToWishlist
 * @version 3.0.12
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Template variables:
 *
 * @var $base_url string Current page url
 * @var $wishlist_url              string Url to wishlist page
 * @var $exists                    bool Whether current product is already in wishlist
 * @var $show_exists               bool Whether to show already in wishlist link on multi wishlist
 * @var $show_count                bool Whether to show count of times item was added to wishlist
 * @var $product_id                int Current product id
 * @var $parent_product_id         int Parent for current product
 * @var $product_type              string Current product type
 * @var $label                     string Button label
 * @var $browse_wishlist_text      string Browse wishlist text
 * @var $already_in_wishslist_text string Already in wishlist text
 * @var $product_added_text        string Product added text
 * @var $icon                      string Icon for Add to Wishlist button
 * @var $link_classes              string Classed for Add to Wishlist button
 * @var $available_multi_wishlist  bool Whether add to wishlist is available or not
 * @var $disable_wishlist          bool Whether wishlist is disabled or not
 * @var $template_part             string Template part
 * @var $container_classes         string Container classes
 */

if ( ! defined( 'YITH_WCWL' ) ) {
	exit;
} // Exit if accessed directly

global $product;
?>

<div class="yith-wcwl-add-button">
	<?php
	/**
	 * APPLY_FILTERS: yith_wcwl_add_to_wishlist_title
	 *
	 * Filter the 'Add to wishlist' label.
	 *
	 * @param string $label Label
	 *
	 * @return string
	 */
	?>
	<a
		href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'add_to_wishlist', $product_id, $base_url ), 'add_to_wishlist' ) ); ?>"
		class="yith-wcwl-icon <?php echo esc_attr( $link_classes ); ?>"
		data-product-id="<?php echo esc_attr( $product_id ); ?>"
		data-product-type="<?php echo esc_attr( $product_type ); ?>"
		data-original-product-id="<?php echo esc_attr( $parent_product_id ); ?>"
		data-title="<?php echo esc_attr( apply_filters( 'yith_wcwl_add_to_wishlist_title', $label ) ); ?>"
		rel="nofollow"
	>
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
			<path fill="none" d="M0 0H24V24H0z"></path>
			<path d="M12.001 4.529c2.349-2.109 5.979-2.039 8.242.228 2.262 2.268 2.34 5.88.236 8.236l-8.48 8.492-8.478-8.492c-2.104-2.356-2.025-5.974.236-8.236 2.265-2.264 5.888-2.34 8.244-.228zm6.826 1.641c-1.5-1.502-3.92-1.563-5.49-.153l-1.335 1.198-1.336-1.197c-1.575-1.412-3.99-1.35-5.494.154-1.49 1.49-1.565 3.875-.192 5.451L12 18.654l7.02-7.03c1.374-1.577 1.299-3.959-.193-5.454z"></path>
		</svg>
		<span><?php echo wp_kses_post( $label ); ?></span>
	</a>
</div>
