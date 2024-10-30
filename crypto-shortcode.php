<?php
/**
* Plugin Name: Cryptocurrency Rates Shortcode
* Plugin URI: http://www.chainpacket.com
* Description: Display cryptocurrency rates using a shortcode
* Version: 1.0
* Author: Richard Hogan
* License: See website for terms and conditions.
*/


add_action('admin_menu', function() {
    add_options_page( 'Crypto Shortcode Settings', 'Crypto Shortcode', 'manage_options', 'crypto-shortcode', 'crypto_shortcode_plugin_page' );
});
add_action( 'admin_init', function() {
	register_setting('crypto-shortcode-settings', 'map_option_1');
});
function crypto_shortcode_plugin_page() {
	?>
	   <div class="wrap" style="padding:25px;">
		 <h1 style="padding:20px 0px;">Crypto Shortcode</h1>
	     <form action="options.php" method="post">
	       <?php
	       settings_fields( 'crypto-shortcode-settings' );
	       do_settings_sections( 'crypto-shortcode-settings' );
		   ?>
           <input type="checkbox" name="map_option_1" <?php echo esc_attr( get_option('map_option_1') ) == 'on' ? 'checked="checked"' : ''; ?> /> Show currency icons instead of text
		   <br>		   
		   <?php submit_button(); ?>
	     </form>
	   </div>
	 <?php
}

function crypto_shortcode_rates() {
	
	$icons = get_option('map_option_1');

	$coins = ['bitcoin', 'bitcoin-cash','ethereum','monero','litecoin','dash'];
	$coinPrices = [];

	foreach ($coins as $x){
		$callUrl = "https://api.coinmarketcap.com/v1/ticker/".$x;
		$callResponse = file_get_contents($callUrl);
		$final = json_decode($callResponse,true);
		$final = $final[0]['price_usd'];
		$coinPrices[] = number_format($final,2);
	}

	$btc = $coinPrices[0];
	$bch = $coinPrices[1];
	$eth = $coinPrices[2];
	$xmr = $coinPrices[3];
	$ltc = $coinPrices[4];
	$dsh = $coinPrices[5];

	if ($icons == 'on'){ ?>
			<style>
				#cryptoRatesShortcode{
					color:black;
					margin:0 !important;
				}
				#cryptoRatesShortcode img{
					padding:5px 5px 0px 5px;
					height:35px !important;
				}
			</style>
		<?php
		return '<p id="cryptoRatesShortcode"><img src="' . plugins_url( 'images/btc.png', __FILE__ ) . '" > '.$btc.'<img src="' . plugins_url( 'images/bch.png', __FILE__ ) . '" > '.$bch.'<img src="' . plugins_url( 'images/eth.png', __FILE__ ) . '" > '.$eth.'<img src="' . plugins_url( 'images/xmr.png', __FILE__ ) . '" > '.$xmr.'<img src="' . plugins_url( 'images/ltc.png', __FILE__ ) . '" > '.$ltc.'<img src="' . plugins_url( 'images/dash.png', __FILE__ ) . '" > '.$dsh."</p>";
	} else {
		return '<p id="cryptoRatesShortcode">BTC = '.$btc.' BCH = '.$bch.' ETH = '.$eth.' XMR = '.$xmr.' LTC = '.$ltc.' DSH = '.$dsh.'</p>';		
	}
}
add_shortcode('CryptoRates', 'crypto_shortcode_rates');