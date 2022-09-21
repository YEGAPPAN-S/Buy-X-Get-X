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

        $productpage = new Controllers\Admin\ProductPage();

        //Save meta post function
        add_action('woocommerce_process_product_meta', [ $productpage , 'checkPost' ]);

        //Tab function
        add_filter('woocommerce_product_data_tabs', [ $productpage , 'menuTab' ]);

        //Data panel function
        add_action('woocommerce_product_data_panels', [ $productpage , 'dataTab' ]);


        $storepage = new Controllers\Frontend\ProductPage();

        //Change free product price and quantity
        add_action('woocommerce_before_calculate_totals', [ $storepage , 'beforeCalculateTotals' ], 10);

        //Add free product and remove free product if parent product not exist
        add_action('woocommerce_after_calculate_totals', [ $storepage , 'afterCalculateTotals' ], 100);

        
        $cartpage = new Controllers\Frontend\CartPage();

        //Remove free products Quantity option
        add_filter('woocommerce_cart_item_quantity', [ $cartpage , 'removeQuantityOption' ], 100, 3);

        //Remove free products Remove button(link)
        add_filter('woocommerce_cart_item_remove_link', [ $cartpage , 'removeDeleteOption' ], 100, 2);
        
    }
}
?>