<?php
/**
 * Buy X Get X Offer
 *
 * @package   buy-get
 * @author    Yegappan
 * @copyright 2022 Flycart
 * @license   GPL v2 or later
 * @link      https://flycart.org
 */

namespace BXGX\App;

defined( 'ABSPATH' ) || exit;


class Route
{
    /**
     * Plugin hooks
     */
    public static function hooks()
    {

        $product_page = new Controllers\Admin\ProductPage();

        //Save meta post function
        add_action('woocommerce_process_product_meta', [ $product_page, 'checkPost' ]);

        //Tab function
        add_filter('woocommerce_product_data_tabs', [ $product_page, 'menuTab' ]);

        //Data panel function
        add_action('woocommerce_product_data_panels', [ $product_page, 'dataTab' ]);

        $cart = new Controllers\Frontend\CartPage();

        //Change free product price and quantity
        add_action('woocommerce_before_calculate_totals', [ $cart, 'beforeCalculateTotals' ], 10);

        //Add free product and remove free product if parent product not exist
        add_action('woocommerce_after_calculate_totals', [ $cart, 'afterCalculateTotals' ], 100);

        //Remove free products Quantity option
        add_filter('woocommerce_cart_item_quantity', [ $cart, 'removeQuantityOption' ], 100, 3);

        //Remove free products Remove button(link)
        add_filter('woocommerce_cart_item_remove_link', [ $cart, 'removeDeleteOption' ], 100, 2);
        
    }
}
?>