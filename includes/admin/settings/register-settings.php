<?php 

class Bulk_Generate_Coupons_Admin {

	public function __construct() {

		add_action( 'admin_menu', array( $this, 'create_options_page'), 15 );
		add_action( 'admin_init', array( $this, 'create_settings' ) );

		add_action( 'admin_init', array( $this, 'process_form' ) );

	}

	public function create_options_page() {

		add_submenu_page( 'issuem-leaky-paywall', __( 'Bulk Generate Coupons', 'issuem' ), __( 'Bulk Generate Coupons', 'issuem' ), apply_filters( 'manage_leaky_paywall_bulk_coupons_settings', 'manage_options' ), 'leaky-paywall-bulk-generate-coupons', array( $this, 'settings_page' ) );
	}

	public function settings_page() {

		?>
		<div class="wrap">
		<h2>Bulk Generate Coupons</h2>	
		<form method="post" action="options.php">
			<?php settings_fields('bgc_options'); ?>
			<?php do_settings_sections('bgc'); ?>
			<?php wp_nonce_field('bulk_coupon_submit', 'bulk_coupon_nonce' ); ?>
			<?php submit_button('Generate Coupons'); ?>
		</form>
		</div>

		<?php 
	}

	public function create_settings() {

		register_setting( 'bgc_options', 'bgc_options', array( $this, 'validate_settings' ) );

		add_settings_section( 'bgc_main', '', array( $this, 'section_text' ), 'bgc' );

		add_settings_field( 'num_coupons', 'Number of Coupons to Generate', array( $this, 'num_coupons_input' ), 'bgc', 'bgc_main');

		add_settings_field( 'prepend_value', 'Prepend Value', array( $this, 'prepend_value_input' ), 'bgc', 'bgc_main');

		add_settings_field( 'discount_type', 'Discount Type', array( $this, 'discount_type_input' ), 'bgc', 'bgc_main');

		add_settings_field( 'discount_amount', 'Discount Amount', array( $this, 'discount_amount_input' ), 'bgc', 'bgc_main');

		add_settings_field( 'max_use_limit', 'Max Use Limit', array( $this, 'max_use_limit_input' ), 'bgc', 'bgc_main');

		add_settings_field( 'products', 'Levels', array( $this, 'products_input' ), 'bgc', 'bgc_main');

		add_settings_field( 'start_date', 'Start Date', array( $this, 'start_date_input' ), 'bgc', 'bgc_main');
		add_settings_field( 'end_date', 'End Date', array( $this, 'end_date_input' ), 'bgc', 'bgc_main');

	}

	public function validate_settings( $input ) {

		return $input;

	}

	public function section_text() {
		?>
		<!-- <p>Fill in your information below.</p> -->
		<?php 
	}

	public function num_coupons_input() {

		$options = wp_parse_args( get_option( 'bgc_options' ), array('num_coupons' => ''));
		$num_coupons = $options['num_coupons'];

		?>
		<select name="bgc_options[num_coupons]" class="regular-text">
			<?php for($i = 1; $i <= 500; $i++) {
				echo '<option value="' . $i . '">' . $i . '</option>';
			} ?>
		</select>
		<?php 

	}

	public function prepend_value_input() {

		$options = wp_parse_args( get_option( 'bgc_options' ), array('prepend_value' => ''));
		$prepend_value = $options['prepend_value'];
		echo "<input type='text' id='prepend_value' name='bgc_options[prepend_value]' class='regular-text' value='$prepend_value'>";
		echo '<p class="description">This value will be prepended to each coupon code generated.</p>';
	}


	public function discount_type_input() {

		$options = wp_parse_args( get_option( 'bgc_options' ), array('discount_type' => ''));
		$discount_type = $options['discount_type'];

		?>
		<select name="bgc_options[discount_type]">
			<option value="percent">% Percent</option> 
			<option value="flat">$ USD</option>
		</select>
		<?php 
		
	}

	public function discount_amount_input() {

		$options = wp_parse_args( get_option( 'bgc_options' ), array('discount_amount' => ''));
		$discount_amount = $options['discount_amount'];
		echo "<input type='text' id='discount_amount' name='bgc_options[discount_amount]' class='regular-text' value='$discount_amount'>";
		
	}

	public function max_use_limit_input() {

		$options = wp_parse_args( get_option( 'bgc_options' ), array('max_use_limit' => ''));
		$max_use_limit = $options['max_use_limit'];
		echo "<input type='number' id='max_use_limit' name='bgc_options[max_use_limit]' class='regular-text' value='$max_use_limit'>";
		
	}

	public function products_input() {

		$leaky_paywall_settings = get_leaky_paywall_settings();

		$options = wp_parse_args( get_option( 'bgc_options' ), array('products' => ''));
		$products = $options['products'];
		?>

			<p>
			<?php
			foreach( $leaky_paywall_settings['levels'] as $level_id => $level ) {
				if ( is_multisite() && !empty( $level['site'] ) && ( 'all' !== $level['site'] && $blog_id !== $level['site'] ) )
					continue;
				

				echo '<input name="bgc_options[products][]" type="checkbox" value="' . $level_id . '"> ' . stripslashes( $level['label'] ) . '<br>';
				
			}					
			?>
			</p>
		<?php 
		echo '<p class="description">Select levels this discount applies to.</p>';
		
	}

	public function start_date_input() {

		$date_format = get_option( 'date_format' );
		$jquery_date_format = leaky_paywall_jquery_datepicker_format( $date_format );

		$options = wp_parse_args( get_option( 'bgc_options' ), array('start_date' => ''));
		$start_date = $options['start_date'];
		echo "<input type='text' id='bulk_coupon_start' name='bgc_options[start_date]' class='regular-text' value='$start_date'>";
		
	}

	public function end_date_input() {

		$date_format = get_option( 'date_format' );
		$jquery_date_format = leaky_paywall_jquery_datepicker_format( $date_format );

		$options = wp_parse_args( get_option( 'bgc_options' ), array('end_date' => ''));
		$end_date = $options['end_date'];
		echo "<input type='text' id='bulk_coupon_end' name='bgc_options[end_date]' class='regular-text' value='$end_date'>";
		?>
		<input type="hidden" name="date_format" value="<?php echo $jquery_date_format; ?>" />
		<?php 
	}

	public function process_form() {

		if ( ! isset( $_POST['bulk_coupon_nonce'] )  || ! wp_verify_nonce( $_POST['bulk_coupon_nonce'], 'bulk_coupon_submit' ) ) {
			 return;
		} 

		$data = $_POST['bgc_options'];
		$coupon_ids = array();

		for($i = 1; $i <= $data['num_coupons']; $i++ ) {

			$coupon_code = $data['prepend_value'] . $i . wp_rand(0,$i);

			$coupon_args = array(
			  'post_title'    => $coupon_code,
			  'post_content'  => '',
			  'post_status'   => 'publish',
			  'post_type'	 => 'lp-coupons',
			);

			$new_coupon = wp_insert_post( $coupon_args );

			update_post_meta( $new_coupon, '_lp_coupon_code', $coupon_code );
			update_post_meta( $new_coupon, '_lp_coupon_amount', $data['discount_amount'] );
			update_post_meta( $new_coupon, '_lp_coupon_amount_type', $data['discount_type'] );
			update_post_meta( $new_coupon, '_lp_coupon_limit', $data['max_use_limit'] );
			update_post_meta( $new_coupon, '_lp_coupon_start_date', $data['start_date'] );
			update_post_meta( $new_coupon, '_lp_coupon_end_date', $data['end_date'] );

			if ( !empty( $data['products'] ) ) {
				update_post_meta( $new_coupon, '_lp_coupon_products', $data['products'] );
			} else {
				update_post_meta( $new_coupon, '_lp_coupon_products', array( 'all' ) );
			}

			$coupon_ids[] = $new_coupon; // store up an array of the new coupon ids for generating the csv file later

		}
		
		$this->generate_csv( $coupon_ids );

	}


	public function generate_csv( $coupon_ids ) {

		if ( !is_admin() && !current_user_can( 'manage_options' ) ) {
			return;
		}

		$file_name = date('Ymd_His') . '-leaky-paywall-coupon-export.csv';
		$headers = array( 'Coupon Code', 'Discount', 'Start Date', 'End Date', 'Max Use Limit', 'Levels' );

		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header('Content-Description: File Transfer');
		header("Content-type: text/csv");
		header("Content-Disposition: attachment; filename={$file_name}");
		header("Expires: 0");
		header("Pragma: public");

		$out = fopen( 'php://output', 'w' );
		

		fputcsv( $out, $headers );

		foreach( $coupon_ids as $coupon_id ) {

			$output_array = array();

			$code        = get_post_meta( $coupon_id, '_lp_coupon_code', true );
			$amount      = get_post_meta( $coupon_id, '_lp_coupon_amount', true );
			$amount_type = get_post_meta( $coupon_id, '_lp_coupon_amount_type', true );
			$limit 		 = get_post_meta( $coupon_id, '_lp_coupon_limit', true );
			$products    = get_post_meta( $coupon_id, '_lp_coupon_products', true );
			$start       = get_post_meta( $coupon_id, '_lp_coupon_start_date', true );
			$end         = get_post_meta( $coupon_id, '_lp_coupon_end_date', true );

			if ( $amount_type == 'percent' ) {
				$amount_output = $amount . '%';
			} else {
				$amount_output = '$' . $amount;
			}

			if ( is_array( $products ) ) {

				$level_names = array();

				foreach( $products as $level_id ) {
					$level = get_leaky_paywall_subscription_level( $level_id );
					$level_names[] = $level['label']; 
				}

				$products_output = implode( ' | ', $level_names );

			} else {
				$products_output = $products;
			}

			$output_array[] = $code;
			$output_array[] = $amount_output;
			$output_array[] = $start;
			$output_array[] = $end;	
			$output_array[] = $limit;
			$output_array[] = $products_output;

			
			fputcsv( $out, $output_array );

		}

		fclose( $out );

		exit;

	}

	
}