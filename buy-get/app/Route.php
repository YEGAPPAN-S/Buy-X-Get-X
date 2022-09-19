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
    public static function BXGX_hooks()
    {

        $frontend = new Controllers\Frontend\Frontend();

        //Tab function
        add_filter('woocommerce_product_data_tabs', [ $frontend , 'menuTab' ]);

        //Data panel function
        add_action('woocommerce_product_data_panels', [ $frontend , 'dataTab' ]);

        $control = new Controllers\Admin\Controller();


        //Save meta post function
        add_action('woocommerce_process_product_meta', [ $control , 'checkPost' ]);

        //Change free product price
        add_action('woocommerce_before_calculate_totals', [ $control , 'updatePrice' ], 10);

        //Change free product quantity
        add_action('woocommerce_before_calculate_totals', [ $control , 'updateQuantity' ], 10);

        //Add free product
        add_action('woocommerce_after_calculate_totals', [ $control , 'addProduct' ], 100);

        //Remove free product if actual product not in cart
        add_action('woocommerce_after_calculate_totals', [ $control , 'removeProduct' ], 100);

        $store = new Controllers\Frontend\Store\Store();

        //Remove free products Quantity option
        add_filter('woocommerce_cart_item_quantity', [ $store , 'removeQuantityOption' ], 100, 3);

        //Remove free products Remove button(link)
        add_filter('woocommerce_cart_item_remove_link', [ $store , 'removeDeleteOption' ], 100, 2);
        
    }
}
?>