<?php

class TaxReport {

  private $plugin_name;
  private $version;

  public function __construct( $plugin_name, $version ) {

    $this->plugin_name = $plugin_name;
    $this->version = $version;
  }

  public function taxreport_load_textdomain() {
    load_plugin_textdomain(
      $this->plugin_name,
      false,
      dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
    );
  }

  public function enqueue_assets_public() {
    wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'static/public.css', array(), $this->version, 'all' );
    wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'static/public.js', array( 'jquery' ), null, false );
  }

  public function enqueue_assets_admin() {

  }

  /////////////

  public function customer_menu_item($items) {
    return
    array_merge(
      array_slice( $items, 0, count( $items ) ),
      array( 'taxreport' => 'Tax Report' ),
      array_slice( $items, count( $items ) )
    );
  }

  public function customer_menu_query_vars($endpoints) {
    $endpoints['taxreport'] = 'taxreport';
    return $endpoints;
  }

  public function csv_download() {
    if(isset($_POST['taxreport_form'])) {

      try {
        $year = $_POST['taxreport_year'];
        $fileName = date("d-m-y") . '-tax-report-'.$year.'.csv';

        header('Content-Type: application/csv'); 
        header('Content-Disposition: attachment; filename="' . $fileName . '"');

        $columns = [
          array('label' => 'Order Number', 'key' => 'order_number'),
          array('label' => 'Order Status', 'key' => 'status'),
          array('label' => 'Order Date', 'key' => 'date_paid', 'transform' => function($value) {
            return !empty($value) ? $value->date_i18n() : $value;
          }),
          array('label' => 'Email (Billing)', 'key' => 'billing_email'),
          array('label' => 'Order Total', 'key' => 'total'),
          array('label' => 'Order Total Tax Amount', 'key' => 'total_tax'),
          array('label' => 'BTC Address', 'key' => 'BTCPay_BTCaddress', 'meta' => true),
          array('label' => 'BTC Amount', 'key' => 'BTCPay_btcPaid', 'meta' => true),
          array('label' => 'BTC Rate', 'key' => 'BTCPay_rate', 'meta' => true)
        ];

        $content = "";

        foreach ($columns as $index => $column) {
          $content .= $column['label'] . (($index === count($columns)-1) ? "" : ",");
        }

        $orders = wc_get_orders( array(
          'customer_id' => get_current_user_id(),
          'date_paid' => "$year-01-01...$year-12-31",
          'limit' => -1,
        ) );

        foreach ($orders as $order) {

          $content .= " \n";
          foreach ($columns as $index => $column) {
            if( empty($column['meta']) ) {
              $value = call_user_func( array($order, 'get_'.$column['key']) );
              $value = !empty($column['transform']) ? $column['transform']($value) : $value;
              $content .= $value;
            }

            else {
              $value = $order->get_meta($column['key']);
              $content .= !empty($value) ? $value : '';
            }

            $content .= (($index === count($columns)-1) ? "" : ",");
          }
        }

        header("Content-Length: " . strlen($content));

        echo trim($content);
        exit;
      } 

      catch (Exception $e) {
      }
    }
  }

  public function customer_menu_content() {
    if(isset($_POST['taxreport_form'])) {
      add_action('woocommerce_init', array($this, 'csv_download'), 0);
    }

    $first_order = wc_get_orders(array(
      'customer_id' => get_current_user_id(),
      'limit' => 1,
      'paged' => 1,
      'orderby' => 'date_paid',
      'order' => 'ASC'
    ))[0];

    $start_year = $first_order->get_date_created()->format("Y");

    echo '
    <form action="" method="post">
    <select class="taxreport-select" name="taxreport_year" id="taxreport_year">';

    for($i = (int) $start_year; $i <= (int) date("Y"); $i++) {
      echo "<option value=\"$i\">$i</option>";
    }

    echo '</select>
    <button type="submit" name="taxreport_form" class="alt">Download Tax Report</button>
    </form>';
  }

}

?>
