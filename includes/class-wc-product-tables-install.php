<?php
/**
 * Installation related functions and actions
 *
 * @author Automattic
 **/

class WC_Product_Tables_Install {

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct() {
		register_activation_hook( WC_PRODUCT_TABLES_FILE, array( $this, 'activate' ) );
	}

	/**
	 * Activate function, runs on plugin activation
	 *
	 * @return void
	 */
	public function activate() {
		global $wpdb;

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$collate = '';

		if ( $wpdb->has_cap( 'collation' ) ) {
			$collate = $wpdb->get_charset_collate();
		}

		$tables = "
			CREATE TABLE {$wpdb->prefix}wc_products (
			  `product_id` bigint(20) NOT NULL,
			  `sku` varchar(100) NOT NULL default '',
			  `image_id` bigint(20) NOT NULL default 0,
			  `height` double NULL default NULL,
			  `width` double NULL default NULL,
			  `length` double NULL default NULL,
			  `weight` double NULL default NULL,
			  `stock_quantity` double NULL default NULL,
			  `type` varchar(100) NOT NULL default 'simple',
			  `virtual` tinyint(1) NOT NULL default 0,
			  `downloadable` tinyint(1) NOT NULL default 0,
			  `tax_class` varchar(100) NOT NULL default '',
			  `tax_status` varchar(100) NOT NULL default 'taxable',
			  `total_sales` double NOT NULL default 0,
			  `price` double NULL default NULL,
			  `regular_price` double NULL default NULL,
			  `sale_price` double NULL default NULL,
			  `date_on_sale_from` datetime NULL default NULL,
			  `date_on_sale_to` datetime NULL default NULL,
			  `average_rating` float NOT NULL default 0,
			  `stock_status` varchar(100) NOT NULL default 'instock',
			  PRIMARY KEY  (`product_id`)
			) $collate;

			CREATE TABLE {$wpdb->prefix}wc_product_attributes (
			  `attribute_id` bigint(20) NOT NULL,
			  `product_id` bigint(20) NOT NULL,
			  `name` varchar(1000) NOT NULL,
			  `is_visible` tinyint(1) NOT NULL,
			  `is_variation` tinyint(1) NOT NULL,
			  `taxonomy_id` bigint(20) NOT NULL,
			  PRIMARY KEY  (`attribute_id`)
			) $collate;

			CREATE TABLE {$wpdb->prefix}wc_product_attribute_values (
			  `attribute_value_id` bigint(20) NOT NULL,
			  `product_id` bigint(20) NOT NULL,
			  `product_attribute_id` bigint(20) NOT NULL,
			  `value` text NOT NULL,
			  `priority` int(11) NOT NULL,
			  `is_default` tinyint(1) NOT NULL,
			  PRIMARY KEY  (`attribute_value_id`)
			) $collate;

			CREATE TABLE {$wpdb->prefix}wc_product_downloads (
			  `download_id` bigint(20) NOT NULL,
			  `product_id` bigint(20) NOT NULL,
			  `name` varchar(1000) NOT NULL,
			  `url` text NOT NULL,
			  `limit` int(11) NOT NULL,
			  `expires` int(11) NOT NULL,
			  `priority` int(11) NOT NULL,
			  PRIMARY KEY  (`download_id`)
			) $collate;

			CREATE TABLE {$wpdb->prefix}wc_product_relationships (
			  `relationship_id` bigint(20) NOT NULL,
			  `type` varchar(100) NOT NULL,
			  `product_id` bigint(20) NOT NULL,
			  `object_id` bigint(20) NOT NULL,
			  `priority` int(11) NOT NULL,
			  PRIMARY KEY  (`relationship_id`)
			) $collate;

			CREATE TABLE {$wpdb->prefix}wc_product_variation_attribute_values (
			  `variation_attribute_value_id` bigint(20) NOT NULL,
			  `product_id` bigint(20) NOT NULL,
			  `value` text NOT NULL,
			  `product_attribute_id` bigint(20) NOT NULL,
			  PRIMARY KEY  (`variation_attribute_value_id`)
			) $collate;
		";

		dbDelta( $tables );
	}
}

new WC_Product_Tables_Install();
