<?php
defined('ABSPATH') or die("What are you looking here I am just plugin!");
/**
Plugin Name: Google maps integration
Plugin URI: https://github.com/dmilkovski/google-maps-with-wordpress
Description: Integrate wordpress with Google maps api
Author: Dimo Milkovski
Author URI: https://github.com/dmilkovski/
License: GPLv2 or later
**/

class GoogleMapVisualization {
  
  public function __construct () {
    //init data
    add_action ('init', array($this, 'register_gpm_post'));
    add_action ('init', array($this, 'create_gpm_taxonomies'));
  }
	function parser () {
	}
  //register data types in WP
  public function register_gpm_post() {
	  $labels = array(
	      'name' => _x('Places'),
	      'singular_name' => _x('Place'),
	      'add_new' => __('Add new'),
	      'all_items' => __('View all'),
	      'menu_name' => 'Places',
	      'not_found' => 'Place not found.'
	  );

	  $args = array(
	      'labels' => $labels,
	      'public' => true,
	      'exclude_from_search' => true,
	      'supports' => array('custom-fields', 'title', 'editor'),
	  );
		
	  register_post_type( 'place', $args );
  }
  
  public function create_gpm_taxonomies() {
	// Add new taxonomy, make it hierarchical (like categories)
	$labels = array(
	    
	);

	$args = array(
	    
	);

	register_taxonomy( 'place_type', array( 'place' ), $args );
  }
}

new GoogleMapVisualization();
