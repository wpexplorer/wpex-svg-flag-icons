<?php
/**
 * Plugin Name: WPEX SVG Flag Icons
 * Plugin URI: https://github.com/wpexplorer/wpex-svg-flag-icons
 * Description: Display SVG flag icons via a shortcode.
 * Author: AJ Clarke
 * Author URI: http://www.wpexplorer.com
 * Version: 1.0.0
 *
 * Plugin based on the flag-icon-css project located here: http://lipis.github.io/flag-icon-css/
 * 
 * Country Codes : https://countrycode.org/
 * Usage         : [wpex_flag_icon country="us"]
 * Params        : country (country code)
 *                 height  (pixels or percent)
 *                 inline  (should flag display inline or full-width)
 *                 before  (text that displays before the flag - inline must be true)
 *                 after   (text that displays after the flag - inline must be true)
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Main plugin class
if ( ! class_exists( 'WPEX_SVG_Flag_Icons' ) ) :

	class WPEX_SVG_Flag_Icons {


		/**
		 * Start things up
		 */
		public function __construct() {

			// Register styles
			add_action( 'wp_enqueue_scripts', array( $this, 'register_styles' ) );

			// Add/Render shortcode
			add_shortcode( 'wpex_flag_icon' , array( $this, 'add_shortcode' ) );

		}

		/**
		 * Register styles
		 *
		 * @access public
		 * @since  1.0.0
		 *
		 * @link   https://codex.wordpress.org/Plugin_API/Action_Reference/wp_enqueue_scripts
		 */
		public static function register_styles( $atts ) {
			wp_register_style( 'wpex-svg-flag-icons', plugins_url( 'css/wpex-flag-icon.css', __FILE__ ) );
		}

		/**
		 * Add/Render shortcode
		 *
		 * @access public
		 * @since  1.0.0
		 *
		 * @link   https://codex.wordpress.org/Function_Reference/add_shortcode
		 */
		public function add_shortcode( $atts ) {

			// Extract shortcode attributes
			extract( shortcode_atts( array(
				'country'  => 'us',
				'height'   => '',
				'width'    => '',
				'inline'   => 'false',
				'before'   => '',
				'after'    => '',
			), $atts, 'wpex_flag_icon' ) );

			// Define output var
			$output = '';

			// Sanitize inline var
			$inline = ( 'true' == $inline ) ? true : false;

			// Check and sanitize country
			if ( $country = $this->get_country_code( $country ) ) {

				// Enqueue stylesheet only when shortcode is present.
				// Loading styles here is more efficient but will make it so the flag won't display until the site footer is rendered.
				// However you can enqueue the stylesheet on all pages if you want in the <head> via a child theme function ;)
				wp_enqueue_style( 'wpex-svg-flag-icons' );

				// Get element classes
				$classes = 'flag-icon flag-icon-background';
				$classes .= ' flag-icon-'. esc_attr( $country );
				if ( $height && $width && ( $height == $width ) ) {
					$classes .= ' flag-icon-squared';
				}

				// Inline style for height and width
				$inline_style = '';
				if ( $height ) {
					$inline_style .= 'height:'. esc_attr( $height ) .';';
				}
				if ( $width ) {
					$inline_style .= 'width:'. esc_attr( $width ) .';';
				}
				if ( $inline_style ) {
					$inline_style .= ' style="'. $inline_style .'"';
				}

				// Render shortcode
				if ( ! $inline ) {
					$output = '<div class="wpex-flag-wrapper"'. $inline_style .'>';
				}
					if ( $inline && $before ) {
						$output .= '<span class="wpex-flag-before">'. esc_html( $before ) .'</span>';
					}
					$output .= '<span class="'. esc_attr( $classes ) .'"></span>';
					if ( $inline && $after ) {
						$output .= '<span class="wpex-flag-after">'. esc_html( $after ) .'</span>';
					}
				if ( ! $inline ) {
						$output .= '<span class="wpex-flag-wrapper-after"></span>';
					$output .= '</div>';
				}

				// Return output
				return $output;

			}

		}

		/**
		 * Helper function used to return correct country code
		 *
		 * @access public
		 * @since  1.0.0
		 */
		public static function get_country_code( $country = '' ) {

			// Return if country isn't defined
			if ( ! $country ) {
				return;
			}

			// Array of country codes
			$country_codes = array(
				'AF' => 'Afghanistan',
				'AX' => 'Åland Islands',
				'AL' => 'Albania',
				'DZ' => 'Algeria',
				'AS' => 'American Samoa',
				'AD' => 'Andorra',
				'AO' => 'Angola',
				'AI' => 'Anguilla',
				'AQ' => 'Antarctica',
				'AG' => 'Antigua and Barbuda',
				'AR' => 'Argentina',
				'AU' => 'Australia',
				'AT' => 'Austria',
				'AZ' => 'Azerbaijan',
				'BS' => 'Bahamas',
				'BH' => 'Bahrain',
				'BD' => 'Bangladesh',
				'BB' => 'Barbados',
				'BY' => 'Belarus',
				'BE' => 'Belgium',
				'BZ' => 'Belize',
				'BJ' => 'Benin',
				'BM' => 'Bermuda',
				'BT' => 'Bhutan',
				'BO' => 'Bolivia',
				'BA' => 'Bosnia and Herzegovina',
				'BW' => 'Botswana',
				'BV' => 'Bouvet Island',
				'BR' => 'Brazil',
				'IO' => 'British Indian Ocean Territory',
				'BN' => 'Brunei Darussalam',
				'BG' => 'Bulgaria',
				'BF' => 'Burkina Faso',
				'BI' => 'Burundi',
				'KH' => 'Cambodia',
				'CM' => 'Cameroon',
				'CA' => 'Canada',
				'CV' => 'Cape Verde',
				'KY' => 'Cayman Islands',
				'CF' => 'Central African Republic',
				'TD' => 'Chad',
				'CL' => 'Chile',
				'CN' => 'China',
				'CX' => 'Christmas Island',
				'CC' => 'Cocos (Keeling) Islands',
				'CO' => 'Colombia',
				'KM' => 'Comoros',
				'CG' => 'Congo',
				'CD' => 'Zaire',
				'CK' => 'Cook Islands',
				'CR' => 'Costa Rica',
				'CI' => 'Côte D\'Ivoire',
				'HR' => 'Croatia',
				'CU' => 'Cuba',
				'CY' => 'Cyprus',
				'CZ' => 'Czech Republic',
				'DK' => 'Denmark',
				'DJ' => 'Djibouti',
				'DM' => 'Dominica',
				'DO' => 'Dominican Republic',
				'EC' => 'Ecuador',
				'EG' => 'Egypt',
				'SV' => 'El Salvador',
				'GQ' => 'Equatorial Guinea',
				'ER' => 'Eritrea',
				'EE' => 'Estonia',
				'ET' => 'Ethiopia',
				'FK' => 'Falkland Islands (Malvinas)',
				'FO' => 'Faroe Islands',
				'FJ' => 'Fiji',
				'FI' => 'Finland',
				'FR' => 'France',
				'GF' => 'French Guiana',
				'PF' => 'French Polynesia',
				'TF' => 'French Southern Territories',
				'GA' => 'Gabon',
				'GM' => 'Gambia',
				'GE' => 'Georgia',
				'DE' => 'Germany',
				'GH' => 'Ghana',
				'GI' => 'Gibraltar',
				'GR' => 'Greece',
				'GL' => 'Greenland',
				'GD' => 'Grenada',
				'GP' => 'Guadeloupe',
				'GU' => 'Guam',
				'GT' => 'Guatemala',
				'GG' => 'Guernsey',
				'GN' => 'Guinea',
				'GW' => 'Guinea-Bissau',
				'GY' => 'Guyana',
				'HT' => 'Haiti',
				'HM' => 'Heard Island and Mcdonald Islands',
				'VA' => 'Vatican City State',
				'HN' => 'Honduras',
				'HK' => 'Hong Kong',
				'HU' => 'Hungary',
				'IS' => 'Iceland',
				'IN' => 'India',
				'ID' => 'Indonesia',
				'IR' => 'Iran, Islamic Republic of',
				'IQ' => 'Iraq',
				'IE' => 'Ireland',
				'IM' => 'Isle of Man',
				'IL' => 'Israel',
				'IT' => 'Italy',
				'JM' => 'Jamaica',
				'JP' => 'Japan',
				'JE' => 'Jersey',
				'JO' => 'Jordan',
				'KZ' => 'Kazakhstan',
				'KE' => 'KENYA',
				'KI' => 'Kiribati',
				'KP' => 'Korea, Democratic People\'s Republic of',
				'KR' => 'Korea, Republic of',
				'KW' => 'Kuwait',
				'KG' => 'Kyrgyzstan',
				'LA' => 'Lao People\'s Democratic Republic',
				'LV' => 'Latvia',
				'LB' => 'Lebanon',
				'LS' => 'Lesotho',
				'LR' => 'Liberia',
				'LY' => 'Libyan Arab Jamahiriya',
				'LI' => 'Liechtenstein',
				'LT' => 'Lithuania',
				'LU' => 'Luxembourg',
				'MO' => 'Macao',
				'MK' => 'Macedonia, the Former Yugoslav Republic of',
				'MG' => 'Madagascar',
				'MW' => 'Malawi',
				'MY' => 'Malaysia',
				'MV' => 'Maldives',
				'ML' => 'Mali',
				'MT' => 'Malta',
				'MH' => 'Marshall Islands',
				'MQ' => 'Martinique',
				'MR' => 'Mauritania',
				'MU' => 'Mauritius',
				'YT' => 'Mayotte',
				'MX' => 'Mexico',
				'FM' => 'Micronesia, Federated States of',
				'MD' => 'Moldova, Republic of',
				'MC' => 'Monaco',
				'MN' => 'Mongolia',
				'ME' => 'Montenegro',
				'MS' => 'Montserrat',
				'MA' => 'Morocco',
				'MZ' => 'Mozambique',
				'MM' => 'Myanmar',
				'NA' => 'Namibia',
				'NR' => 'Nauru',
				'NP' => 'Nepal',
				'NL' => 'Netherlands',
				'AN' => 'Netherlands Antilles',
				'NC' => 'New Caledonia',
				'NZ' => 'New Zealand',
				'NI' => 'Nicaragua',
				'NE' => 'Niger',
				'NG' => 'Nigeria',
				'NU' => 'Niue',
				'NF' => 'Norfolk Island',
				'MP' => 'Northern Mariana Islands',
				'NO' => 'Norway',
				'OM' => 'Oman',
				'PK' => 'Pakistan',
				'PW' => 'Palau',
				'PS' => 'Palestinian Territory, Occupied',
				'PA' => 'Panama',
				'PG' => 'Papua New Guinea',
				'PY' => 'Paraguay',
				'PE' => 'Peru',
				'PH' => 'Philippines',
				'PN' => 'Pitcairn',
				'PL' => 'Poland',
				'PT' => 'Portugal',
				'PR' => 'Puerto Rico',
				'QA' => 'Qatar',
				'RE' => 'Réunion',
				'RO' => 'Romania',
				'RU' => 'Russian Federation',
				'RW' => 'Rwanda',
				'SH' => 'Saint Helena',
				'KN' => 'Saint Kitts and Nevis',
				'LC' => 'Saint Lucia',
				'PM' => 'Saint Pierre and Miquelon',
				'VC' => 'Saint Vincent and the Grenadines',
				'WS' => 'Samoa',
				'SM' => 'San Marino',
				'ST' => 'Sao Tome and Principe',
				'SA' => 'Saudi Arabia',
				'SN' => 'Senegal',
				'RS' => 'Serbia',
				'SC' => 'Seychelles',
				'SL' => 'Sierra Leone',
				'SG' => 'Singapore',
				'SK' => 'Slovakia',
				'SI' => 'Slovenia',
				'SB' => 'Solomon Islands',
				'SO' => 'Somalia',
				'ZA' => 'South Africa',
				'GS' => 'South Georgia and the South Sandwich Islands',
				'ES' => 'Spain',
				'LK' => 'Sri Lanka',
				'SD' => 'Sudan',
				'SR' => 'Suriname',
				'SJ' => 'Svalbard and Jan Mayen',
				'SZ' => 'Swaziland',
				'SE' => 'Sweden',
				'CH' => 'Switzerland',
				'SY' => 'Syrian Arab Republic',
				'TW' => 'Taiwan, Province of China',
				'TJ' => 'Tajikistan',
				'TZ' => 'Tanzania, United Republic of',
				'TH' => 'Thailand',
				'TL' => 'Timor-Leste',
				'TG' => 'Togo',
				'TK' => 'Tokelau',
				'TO' => 'Tonga',
				'TT' => 'Trinidad and Tobago',
				'TN' => 'Tunisia',
				'TR' => 'Turkey',
				'TM' => 'Turkmenistan',
				'TC' => 'Turks and Caicos Islands',
				'TV' => 'Tuvalu',
				'UG' => 'Uganda',
				'UA' => 'Ukraine',
				'AE' => 'United Arab Emirates',
				'GB' => 'United Kingdom',
				'US' => 'United States',
				'UM' => 'United States Minor Outlying Islands',
				'UY' => 'Uruguay',
				'UZ' => 'Uzbekistan',
				'VU' => 'Vanuatu',
				'VE' => 'Venezuela',
				'VN' => 'Viet Nam',
				'VG' => 'Virgin Islands, British',
				'VI' => 'Virgin Islands, U.S.',
				'WF' => 'Wallis and Futuna',
				'EH' => 'Western Sahara',
				'YE' => 'Yemen',
				'ZM' => 'Zambia',
				'ZW' => 'Zimbabwe',
			);

			// Get country code if user input a country name
			if ( strlen( $country ) > 2 ) {

				// Capitalize input to check against our array
				$country = ucwords( $country );

				// Get code from array if input country exists
				if ( $code = array_search( $country, $country_codes ) ) {
					$country = $code;
				}

			}
			
			// Return country in lowercaps
			return strtolower( $country );

		}

	}

	$wpex_svg_flag_icons = new WPEX_SVG_Flag_Icons;

endif;