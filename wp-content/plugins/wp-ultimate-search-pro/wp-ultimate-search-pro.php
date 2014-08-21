<?php
/*
Plugin Name: WP Ultimate Search Pro
Plugin URI: http://mindsharelabs.com/products/wp-ultimate-search-pro/
Description: Premium version of WP Ultimate Search, includes advanced options for searching within taxonomies and post_meta fields.
Version: 1.6
Author: Mindshare Studios, Inc.
Author URI: http://mindsharelabs.com/
*/
if(!defined('WPUS_PRO_STORE_URL'))
	define( 'WPUS_PRO_STORE_URL', 'http://mindsharelabs.com' );

if(!defined('WPUS_PRO_ITEM_NAME'))
define( 'WPUS_PRO_ITEM_NAME', 'WP Ultimate Search Pro' );

if(!defined('WPUS_PRO_VERSION'))
	define( 'WPUS_PRO_VERSION', '1.6' );

if(!file_exists(WP_PLUGIN_DIR.'/wp-ultimate-search/wp-ultimate-search.php')) {
	echo '<div id="message" class="error"><p>Please <a target="_parent" href="plugin-install.php?tab=search&s=wp+ultimate+search">install WP Ultimate Search</a> before attempting to activate the pro upgrade.</p></div>';
	exit;
}
if(!function_exists('is_plugin_active')) {
	include_once(ABSPATH.'wp-admin/includes/plugin.php');
}

if(!class_exists("WPUltimateSearchPro") && class_exists("WPUltimateSearch")) :

	/**
	 * Class WPUltimateSearchPro
	 */
	class WPUltimateSearchPro extends WPUltimateSearch {

		public $options;
		public $wpus_updater;
		public $license_key;

		private $radius_facet;

		/**
		 * Activate automatic updates
		 *
		 */
		function __construct() {

			if( !class_exists( 'WPUS_PRO_Updater' ) ) {
				include_once( WPUS_PRO_PATH . 'lib/WPUS_Pro_Updater.php' );
			}

			$this->options = get_option('wpus_options');
			if(isset($this->options["license_key"])) {
				$license_key = trim( $this->options["license_key"] );
				$this->license_key = $license_key;
			} else {
				$this->license_key = 'null';
			}

			// setup the updater
			$wpus_updater = new WPUS_PRO_Updater( WPUS_PRO_STORE_URL, __FILE__, array( 
					'version' 	=> WPUS_PRO_VERSION,
					'license' 	=> $this->license_key,
					'item_name' => WPUS_PRO_ITEM_NAME,
					'author' 	=> 'Mindshare Studios, Inc.'
				)
			);
		}

		private function sort_posts( $posts, $orderby, $order = 'ASC', $unique = true ) {
			if ( ! is_array( $posts ) ) {
				return false;
			}
			
			usort( $posts, array( new WPUS_Sort_Posts( $orderby, $order ), 'sort' ) );
			
			// use post ids as the array keys
			if ( $unique && count( $posts ) ) {
				$posts = array_combine( wp_list_pluck( $posts, 'ID' ), $posts );
			}
			
			return $posts;
		}

		private function lat_long_to_distance($lat1, $lng1, $lat2, $lng2, $format) {

			$latFrom = deg2rad($lat1);
			$lonFrom = deg2rad($lng1);
			$latTo = deg2rad($lat2);
			$lonTo = deg2rad($lng2);

			$lonDelta = $lonTo - $lonFrom;
			$a = pow(cos($latTo) * sin($lonDelta), 2) +
			pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
			$b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

			$angle = atan2(sqrt($a), $b);
			
			$earthRadius = 6371000;

			$dist = $angle * $earthRadius;

			if($format == 'mi') {
				return $dist / 1609.34;
			} elseif ($format == 'km') {
				return $dist / 1000;
			} elseif ($format == 'km') {
				return $dist;
			}
		}


		private function prepare_meta_value($facet, $data) {
			$options = $this->options;

			foreach($options['metafields'] as $metafield => $value) {

				if($metafield == $facet) {

					if($value['type'] == 'checkbox') {

						$data = serialize(array($data));
						return $data;

					} elseif($value['type'] == 'date') {

						return(date(apply_filters('wpus_date_save_format'), strtotime($data)));

					} elseif($value['type'] == 'true-false') {

						if($data == "True") {
							return '1';
						} else {
							return '0';
						}

					} elseif($value['type'] == 'radius') {

				        $prepaddr = urlencode($data);
				        $geocode=file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$prepaddr.'&sensor=false');
				        $output= json_decode($geocode);
				        $lat = $output->results[0]->geometry->location->lat;
				        $long = $output->results[0]->geometry->location->lng;
				        $this->radius_facet = $facet;
				        return array($data,$lat,$long);
				        
					}
				}
			}

			return $data;
		}


		private function filter_radius($results, $location, $radius) {

			if($radius == null) {
				$radius = $this->options['radius_dist'];
			}

			foreach($results as $id => $result) {

				if($result_location = get_post_meta( $result->ID, $this->radius_facet )) {

					if(!empty($result_location[0]['lat'])) {
						// New ACF GMaps field
						$distance = $this->lat_long_to_distance($location[1], $location[2], $result_location[0]['lat'], $result_location[0]['lng'], $this->options['radius_format']);;
					} elseif(is_string($result_location[0])) {
						// Other maps field. Try to get lat long data from string
						$noaddr = strstr($result_location[0], '|');
						$noaddr = trim($noaddr, '|');
						$latlng = explode(",", $noaddr);
						$distance = $this->lat_long_to_distance($location[1], $location[2], $latlng[0], $latlng[1], $this->options['radius_format']);
					}

					if($distance >= $radius || empty($result_location[0]['lat'])) {
						unset($results[$id]);
					} else {
						// Put the geo data in the output array in case the user wants to do something with it
						$results[$id]->distance = $distance;
						$results[$id]->location = $location[0];
					}
				}
			}
			return $results;
		}

		private function get_user_by_display_name($display_name) {
			global $wpdb;

			if ( ! $user = $wpdb->get_row( $wpdb->prepare(
				"SELECT `ID` FROM $wpdb->users WHERE `display_name` = %s", $display_name
			) ) )
			return false;

			return $user->ID;
		}

		/**
		 * @param $searcharray
		 */
		public function execute_query_pro($searcharray) {

			$radius = null;

			foreach($searcharray as $index) { // iterate through the search query array and separate the taxonomies into their own array
				foreach($index as $facet => $data) {
					$facet = esc_sql($facet);
					if($facet == "tag") {
						$facet = "post_tag";
					}

					$type = parent::determine_facet_type($facet); // determine if we're dealing with a taxonomy or a metafield

					switch($type) {
						case "text" :
							// $keywords = parent::string_to_keywords($data);
							$keywords = $data;
							break;
						case "taxonomy" :
							$facet = parent::get_taxonomy_name($facet);
							$data = preg_replace('/_/', " ", $data); // in case there are underscores in the value (from a permalink), remove them
							$term = get_term_by('name', $data, $facet);
							if($term != false) {
								$taxonomies[$facet][] = $term->term_id;
							}
							break;
						case "metafield" :
							$data = preg_replace('/_/', " ", $data); // in case there are underscores in the value (from a permalink), remove them
							$facet = parent::get_metafield_name($facet);
							$data = $this->prepare_meta_value($facet, $data);
							$metafields[][$facet] = $data;
							break;
						case "radius" :
							$radius = $data;
							break;
						case "user" :
							$users[] = $this->get_user_by_display_name($data);
					}
				}
			}

			$query = array(
				'posts_per_page'	=> -1,
				'post_status'		=> 'publish'
			);

			// Text search
			if(isset($keywords)) {

				$query['s'] = $keywords;

			}

			// Taxonomy search
			if(isset($taxonomies)) {

				$query['tax_query'] = array();

				// Create an AND relation between different taxonomies
				if(count($taxonomies) > 1)
					$query['tax_query']['relation'] = "AND";

				foreach($taxonomies as $taxonomy => $terms) {

					// By default, use an OR operation on terms w/in the same taxonomy
					$operator = "IN";
					$include_children = true;

					if(count($terms) > 1 && $this->options['and_or'] == "and") {

						$query['tax_query']['relation'] = "AND";

						foreach($terms as $term) {

							$query['tax_query'][] = array(
								'taxonomy'	        => $taxonomy,
								'terms'		        => $term,
							    'operator'          => "IN",
							    'include_children'	=> true
							);

						}

					} else {

						$query['tax_query'][] = array(
							'taxonomy'	        => $taxonomy,
							'terms'		        => $terms,
						    'operator'          => $operator,
						    'include_children'  => $include_children
						);
					}
				}
			}

			// Meta fields
			if(isset($metafields)) {

				$query['meta_query'] = array();

				if($this->options['and_or'] == "and" && count($metafields) > 1) {
					$query['meta_query']['relation'] = "AND";
				} elseif ($this->options['and_or'] == "or" && count($metafields) > 1) {
					$query['meta_query']['relation'] = "OR";
				}


				foreach($metafields as $metafield) {

					foreach($metafield as $name => $metadata) {

						// Since there's no way to do logical operations on geodata stored in a serialized array, we need to pull out every post that Has geodata at all, and then process them one by one.

						if(is_array($metadata)) {

							$location = $metadata;

							$location_query_results = get_posts( 'meta_key='.$name.'&posts_per_page=-1&post_type=any' );

						} else {

							$query['meta_query'][] = array(
								'key'		=> $name,
								'value'		=> $metadata,
								'compare'	=> 'LIKE'
							);

						}
					}
				}
			}

			// Post types
			if(isset($this->options['posttypes'])) {

				$posttypes = $this->options['posttypes'];
				$query['post_type'] = array();

				foreach($posttypes as $type => $data) {
					if(isset($data['enabled'])) {

						$query['post_type'][] = $type;

					}
				}
			}

			// Users
			if(isset($users) && count($users) > 0) {
				$query['author__in'] = $users;
			}

			// Pass it all through to WP_Query
			$wpus_results = new WP_Query( $query );


			if(!isset($keywords)) {
				$keywords = NULL;
			}

			$location_arr = array();

			// If we're conducting a radius search, we need to run a separate query and then merge it back into the other results
			if(isset($this->options['radius']) && $this->options['radius'] != false && isset($location)) {

				if($radius == null)
					$radius = $this->options['radius_dist'];

				// filter_radius() returns an array of posts, where any post outside of the specified radius from the origin has been removed
				$geo_filtered_posts = $this->filter_radius($location_query_results, $location, $radius);

				// These variables will be passed to the results template for integration with mapping
				$location_arr['address'] = $location[0];
				$location_arr['lat'] = $location[1];
				$location_arr['lng'] = $location[2];
				$location_arr['radius'] = $radius;

				// Grab an array of post ID's from our geographically elligible posts
				$location_ids = wp_list_pluck($geo_filtered_posts, 'ID');

				// Iterate through the results delivered by the main query, and remove any results that don't qualify
				foreach ($wpus_results->posts as $key=>$value){
					if (!in_array($value->ID,$location_ids)){
						unset($wpus_results->posts[$key]);
					}
				}

				// Reorder the array so it's organized again

				$wpus_results->posts = array_values($this->sort_posts($wpus_results->posts, 'ID'));
				$wpus_results->post_count = count($wpus_results->posts);

			}

			parent::print_results($wpus_results, $keywords, $location_arr); // format and output the search results

			die(); // wordpress may print out a spurious zero without this - can be particularly bad if using json
		}
	}

	class WPUS_Sort_Posts extends WPUltimateSearchPro {
		var $order, $orderby;
		
		function __construct( $orderby, $order ) {
			$this->orderby = $orderby;
			$this->order = ( 'desc' == strtolower( $order ) ) ? 'DESC' : 'ASC';
		}
		
		function sort( $a, $b ) {
			if ( $a->{$this->orderby} == $b->{$this->orderby} ) {
				return 0;
			}
			
			if ( $a->{$this->orderby} < $b->{$this->orderby} ) {
				return ( 'ASC' == $this->order ) ? -1 : 1;
			} else {
				return ( 'ASC' == $this->order ) ? 1 : -1;
			}
		}
	}

endif;
