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

namespace BXGX\App\Controllers\Admin;

defined( 'ABSPATH' ) || exit;

class ProductPage
{
    /**
     *
     * Tab function
     *
     * @param $buy_tabs
     *
     * @return mixed
     *
     */
    public function menuTab($buy_tabs ) {
        $buy_tabs['buy_get_tab'] =
          array(
            'label'   =>  __( ' Buy 1 Get 1 Offer ', 'buy-get' ),
            'target'  =>  'buy_product_data',
            'class'   => array(),
            'priority' => 70
          );
        return $buy_tabs;
    }


    /**
     *
     * Panel Data field
     *
     * @return void
     *
     */
    public function dataTab() {
        ?>
        <div id="buy_product_data" class="panel woocommerce_options_panel">
            <?php
            woocommerce_wp_checkbox(
                array(
                    'id'            => 'is_enable_buy_get',
                    'label'         => __( 'Buy 1 Get 1', 'buy-get' ),
                    'description'   => __( 'This option is used to buy 1 get 1 offer', 'buy-get' ),
                    'desc_tip'      => true,
                )
            );
            ?>
            <input type="hidden" name="Buy_X_Get_X_enable">
        </div>
        <?php
    }


    /**
     * Save meta data
     *
     * @param $post_id
     *
     * @return void
     *
     */
    public function checkPost( $post_id ) {
        if(isset($_POST['Buy_X_Get_X_enable'])) {
            update_post_meta( $post_id, 'is_enable_buy_get', sanitize_text_field($_POST['is_enable_buy_get']));
        }
    }
}

?>