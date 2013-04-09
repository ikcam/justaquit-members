<?php
function get_memberships(){
	global $wpdb;
	$table = $wpdb->prefix.'jm_memberships';

	$query = "SELECT * FROM $table ORDER BY ID";

	return $wpdb->get_results( $query );
}

function get_membership( $ID ){
	global $wpdb;
	$table = $wpdb->prefix.'jm_memberships';

	$query = "SELECT * FROM $table WHERE ID = %d";

	return $wpdb->get_row( $wpdb->prepare( $query, $ID ) );
}

function get_packages( $args = NULL ){
	global $wpdb;
	$table = $wpdb->prefix.'jm_packages';

	$defaults = array(
		'membership' => 0,
		'count'      => -1
	);

	$args = wp_parse_args( $args, $defaults );
	
	extract($args, EXTR_SKIP);
	
	if( $membership == 0 && $count == -1 )
		return $wpdb->get_results( "SELECT * FROM $table ORDER BY ID" );

	if( $count == -1 ):
		$query = "SELECT * FROM $table WHERE membership_id = %d ORDER BY ID";
		return $wpdb->get_results( $wpdb->prepare( $query, $membership ) );
	endif;

	$query = "SELECT * FROM $table WHERE membership_id = %d LIMIT 0, %d ORDER BY ID";
	return $wpdb->get_results( $wpdb->prepare( $query, $membership, $count ) );
}

function get_package( $ID ){
	global $wpdb;
	$table = $wpdb->prefix.'jm_packages';

	$ID = (int) $ID;

	$query = "SELECT * FROM $table WHERE ID = %d";

	return $wpdb->get_row( $wpdb->prepare( $query, $ID ) );
}

function get_package_name( $ID ){
	$package = get_package( $ID );
	$membership = get_membership( $package->membership_id );

	$output = $membership->name.' - ';
	$output .= $package->price.' USD ';
	
	if( $package->duration_type == 0 ):
		$output .= __( 'per Lifetime', 'jmembers' );
		return $output;
	endif;

	$output .= __( 'per', 'jmembers' );
	$output .= ' '.$package->duration.' ';

	switch( $package->duration_type ){
		case 1:
			$output .= __( 'years', 'jmembers' );
			break;
		case 2:
			$output .= __( 'months', 'jmembers' );
			break;
		case 3:
			$output .= __( 'weeks', 'jmembers' );
			break;
		case 4:
			$output .= __( 'days', 'jmembers' );
			break;
	}

	$output .= ' - ';

	switch( $package->billing	){
		case 0:
			$output .= __( 'Auto Renew', 'jmembers' );
			break;
		case 1:
			$output .= __( 'One Time', 'jmembers' );
			break;
	}

	return $output;
}

function get_package_period( $ID ){
	$package = get_package( $ID );

	if( $package == NULL )
		return FALSE;

	switch( $package->duration_type ){
		case 1:
			$output = 'Year';
			break;
		case 2:
			$output = 'Month';
			break;
		case 3:
			$output = 'Week';
			break;
		case 4:
			$output = 'Day';
			break;
	}

	return $output;
}

function get_payment_form( $processor, $data ){
	if( $processor == 'pppro' && class_exists('JM_Payment_PayPalPro') )
		return JM_Payment_PayPalPro::form( $data );

	if( $processor == 'ppstandard' && class_exists('JM_Payment_PayPalStandard') )
		return JM_Payment_PayPalStandard::form( $data );

	if( $processor == '1sc' && class_exists('JM_Payment_ShoppingCart') )
		return JM_Payment_ShoppingCart::form( $data );
}

function get_user_membership( $user_id = NULL ){
	if( $user_id == NULL )
		$user_id = get_current_user_id();

	if( $user_id == 0 )
		return FALSE;

	$package_id = get_user_meta( $user_id, '_package_id', true );

	if( $package_id == ''  )
		return FALSE;

	$package = get_package( $package_id );

	if( $package == NULL )
		return FALSE;

	return (int) $package->membership_id;
}

function get_countries(){
	$countries = array(
		'AD' => 'Andorra',
		'AE' => 'United Arab Emirates',
		'AF' => 'Afghanistan',
		'AG' => 'Antigua and Barbuda',
		'AI' => 'Anguilla',
		'AL' => 'Albania',
		'AM' => 'Armenia',
		'AN' => 'Netherlands Antilles',
		'AO' => 'Angola',
		'AQ' => 'Antarctica',
		'AR' => 'Argentina',
		'AS' => 'American Samoa',
		'AT' => 'Austria',
		'AU' => 'Australia',
		'AW' => 'Aruba',
		'AZ' => 'Azerbaijan',
		'BA' => 'Bosnia and Herzegovina',
		'BB' => 'Barbados',
		'BD' => 'Bangladesh',
		'BE' => 'Belgium',
		'BF' => 'Burkina Faso',
		'BG' => 'Bulgaria',
		'BH' => 'Bahrain',
		'BI' => 'Burundi',
		'BJ' => 'Benin',
		'BM' => 'Bermuda',
		'BN' => 'Brunei Darrussalam',
		'BO' => 'Bolivia',
		'BR' => 'Brazil',
		'BS' => 'Bahamas',
		'BT' => 'Bhutan',
		'BV' => 'Bouvet Island',
		'BW' => 'Botswana',
		'BY' => 'Belarus',
		'BZ' => 'Belize',
		'CA' => 'Canada',
		'CC' => 'Cocos (keeling) Islands',
		'CD' => 'Congo, Democratic PeopleÕs Republic',
		'CF' => 'Central African Republic',
		'CG' => 'Congo, Republic of',
		'CH' => 'Switzerland',
		'CI' => 'Cote dÕIvoire',
		'CK' => 'Cook Islands',
		'CL' => 'Chile',
		'CM' => 'Cameroon',
		'CN' => 'China',
		'CO' => 'Colombia',
		'CR' => 'Costa Rica',
		'CS' => 'Serbia and Montenegro',
		'CU' => 'Cuba',
		'CV' => 'Cap Verde',
		'CS' => 'Christmas Island',
		'CY' => 'Cyprus Island',
		'CZ' => 'Czech Republic',
		'DE' => 'Germany',
		'DJ' => 'Djibouti',
		'DK' => 'Denmark',
		'DM' => 'Dominica',
		'DO' => 'Dominican Republic',
		'DZ' => 'Algeria',
		'EC' => 'Ecuador',
		'EE' => 'Estonia',
		'EG' => 'Egypt',
		'EH' => 'Western Sahara',
		'ER' => 'Eritrea',
		'ES' => 'Spain',
		'ET' => 'Ethiopia',
		'FI' => 'Finland',
		'FJ' => 'Fiji',
		'FK' => 'Falkland Islands (Malvina)',
		'FM' => 'Micronesia, Federal State of',
		'FO' => 'Faroe Islands',
		'FR' => 'France',
		'GA' => 'Gabon',
		'GB' => 'United Kingdom (GB)',
		'GD' => 'Grenada',
		'GE' => 'Georgia',
		'GF' => 'French Guiana',
		'GG' => 'Guernsey',
		'GH' => 'Ghana',
		'GI' => 'Gibraltar',
		'GL' => 'Greenland',
		'GM' => 'Gambia',
		'GN' => 'Guinea',
		'GP' => 'Guadeloupe',
		'GQ' => 'Equatorial Guinea',
		'GR' => 'Greece',
		'GS' => 'South Georgia',
		'GT' => 'Guatemala',
		'GU' => 'Guam',
		'GW' => 'Guinea-Bissau',
		'GY' => 'Guyana',
		'HK' => 'Hong Kong',
		'HM' => 'Heard and McDonald Islands',
		'HN' => 'Honduras',
		'HR' => 'Croatia/Hrvatska',
		'HT' => 'Haiti',
		'HU' => 'Hungary',
		'ID' => 'Indonesia',
		'IE' => 'Ireland',
		'IL' => 'Israel',
		'IM' => 'Isle of Man',
		'IN' => 'India',
		'IO' => 'British Indian Ocean Territory',
		'IQ' => 'Iraq',
		'IR' => 'Iran (Islamic Republic of)',
		'IS' => 'Iceland',
		'IT' => 'Italy',
		'JE' => 'Jersey',
		'JM' => 'Jamaica',
		'JO' => 'Jordan',
		'JP' => 'Japan',
		'KE' => 'Kenya',
		'KG' => 'Kyrgyzstan',
		'KH' => 'Cambodia',
		'KI' => 'Kiribati',
		'KM' => 'Comoros',
		'KN' => 'Saint Kitts and Nevis',
		'KP' => 'Korea, Democratic PeopleÕs Republic',
		'KR' => 'Korea, Republic of',
		'KW' => 'Kuwait',
		'KY' => 'Cayman Islands',
		'KZ' => 'Kazakhstan',
		'LA' => 'Lao PeopleÕs Democratic Republic',
		'LB' => 'Lebanon',
		'LC' => 'Saint Lucia',
		'LI' => 'Liechtenstein',
		'LK' => 'Sri Lanka',
		'LR' => 'Liberia',
		'LS' => 'Lesotho',
		'LT' => 'Lithuania',
		'LU' => 'Luxembourgh',
		'LV' => 'Latvia',
		'LY' => 'Libyan Arab Jamahiriya',
		'MA' => 'Morocco',
		'MC' => 'Monaco',
		'MD' => 'Moldova, Republic of',
		'MG' => 'Madagascar',
		'MH' => 'Marshall Islands',
		'MK' => 'Macedonia',
		'ML' => 'Mali',
		'MM' => 'Myanmar',
		'MN' => 'Mongolia',
		'MO' => 'Macau',
		'MP' => 'Northern Mariana Islands',
		'MQ' => 'Martinique',
		'MR' => 'Mauritania',
		'MS' => 'Montserrat',
		'MT' => 'Malta',
		'MU' => 'Mauritius',
		'Mv' => 'Maldives',
		'MW' => 'malawi',
		'MX' => 'Mexico',
		'MY' => 'Malaysia',
		'MZ' => 'Mozambique',
		'NA' => 'Namibia',
		'NC' => 'New Caledonia',
		'NE' => 'Niger',
		'NF' => 'Norfolk Island',
		'NG' => 'Nigeria',
		'NI' => 'Nicaragua',
		'NL' => 'Netherlands',
		'NO' => 'Norway',
		'NP' => 'Nepal',
		'NR' => 'Nauru',
		'NU' => 'Niue',
		'NZ' => 'New Zealand',
		'OM' => 'Oman',
		'PA' => 'Panama',
		'PE' => 'Peru',
		'PF' => 'French Polynesia',
		'PG' => 'papua New Guinea',
		'PH' => 'Phillipines',
		'PK' => 'Pakistan',
		'PL' => 'Poland',
		'PM' => 'St. Pierre and Miquelon',
		'PN' => 'Pitcairn Island',
		'PR' => 'Puerto Rico',
		'PS' => 'Palestinian Territories',
		'PT' => 'Portugal',
		'PW' => 'Palau',
		'PY' => 'Paraguay',
		'QA' => 'Qatar',
		'RE' => 'Reunion Island',
		'RO' => 'Romania',
		'RU' => 'Russian Federation',
		'RW' => 'Rwanda',
		'SA' => 'Saudi Arabia',
		'SB' => 'Solomon Islands',
		'SC' => 'Seychelles',
		'SD' => 'Sudan',
		'SE' => 'Sweden',
		'SG' => 'Singapore',
		'SH' => 'St. Helena',
		'SI' => 'Slovenia',
		'SJ' => 'Svalbard and Jan Mayen Islands',
		'SK' => 'Slovak Republic',
		'SL' => 'Sierra Leone',
		'SM' => 'San Marino',
		'SN' => 'Senegal',
		'SO' => 'Somalia',
		'SR' => 'Suriname',
		'ST' => 'Sao Tome and Principe',
		'SV' => 'El Salvador',
		'SY' => 'Syrian Arab Republic',
		'SZ' => 'Swaziland',
		'TC' => 'Turks and Caicos Islands',
		'TD' => 'Chad',
		'TF' => 'French Southern Territories',
		'TG' => 'Togo',
		'TH' => 'Thailand',
		'TJ' => 'Tajikistan',
		'TK' => 'Tokelau',
		'TM' => 'Turkmenistan',
		'TN' => 'Tunisia',
		'TO' => 'Tonga',
		'TP' => 'East Timor',
		'TR' => 'Turkey',
		'TT' => 'Trinidad and Tobago',
		'TV' => 'Tuvalu',
		'TW' => 'Taiwan',
		'TZ' => 'Tanzania',
		'UA' => 'Ukraine',
		'UG' => 'Uganda',
		'UM' => 'US Minor Outlying Islands',
		'US' => 'United States',
		'UY' => 'Uruguay',
		'UZ' => 'Uzbekistan',
		'VA' => 'Holy See (City Vatican State)',
		'VC' => 'Saint Vincent and the Grenadines',
		'VE' => 'Venezuela',
		'VG' => 'Virgin Islands (British)',
		'VI' => 'Virgin Islands (USA)',
		'VN' => 'Vietnam',
		'VU' => 'Vanuatu',
		'WF' => 'Wallis and Futuna Islands',
		'WS' => 'Western Samoa',
		'YE' => 'Yemen',
		'YT' => 'Mayotte',
		'YU' => 'Yugoslavia',
		'ZA' => 'South Africa',
		'ZM' => 'Zambia',
		'ZW' => 'Zimbabwe'
	);

	return $countries;
}

function get_states(){
	$states = array(
		'AL' => 'Alabama', 
		'AK' => 'Alaska', 
		'AZ' => 'Arizona', 
		'AR' => 'Arkansas', 
		'CA' => 'California', 
		'CO' => 'Colorado', 
		'CT' => 'Connecticut', 
		'DE' => 'Delaware', 
		'DC' => 'District Of Columbia', 
		'FL' => 'Florida', 
		'GA' => 'Georgia', 
		'HI' => 'Hawaii', 
		'ID' => 'Idaho', 
		'IL' => 'Illinois', 
		'IN' => 'Indiana', 
		'IA' => 'Iowa', 
		'KS' => 'Kansas', 
		'KY' => 'Kentucky', 
		'LA' => 'Louisiana', 
		'ME' => 'Maine', 
		'MD' => 'Maryland', 
		'MA' => 'Massachusetts', 
		'MI' => 'Michigan', 
		'MN' => 'Minnesota', 
		'MS' => 'Mississippi', 
		'MO' => 'Missouri', 
		'MT' => 'Montana',
		'NE' => 'Nebraska',
		'NV' => 'Nevada',
		'NH' => 'New Hampshire',
		'NJ' => 'New Jersey',
		'NM' => 'New Mexico',
		'NY' => 'New York',
		'NC' => 'North Carolina',
		'ND' => 'North Dakota',
		'OH' => 'Ohio', 
		'OK' => 'Oklahoma', 
		'OR' => 'Oregon', 
		'PA' => 'Pennsylvania', 
		'RI' => 'Rhode Island', 
		'SC' => 'South Carolina', 
		'SD' => 'South Dakota',
		'TN' => 'Tennessee', 
		'TX' => 'Texas', 
		'UT' => 'Utah', 
		'VT' => 'Vermont', 
		'VA' => 'Virginia', 
		'WA' => 'Washington', 
		'WV' => 'West Virginia', 
		'WI' => 'Wisconsin', 
		'WY' => 'Wyoming'
	);

	return $states;
}