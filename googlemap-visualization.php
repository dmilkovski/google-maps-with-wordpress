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
    add_action ();
    
  }

  public function register_custom_fileds () {
  	add_meta_box("latitude_meta", "Latitude", "latitude_filed", "place", "normal", "high");
  	add_meta_box("longitude_meta", "Longitude", "longitude_filed", "place", "normal", "high");
  }

  public function latitude_filed () {

  }

  public function longitude_filed () {

  }

  //register data types in WP
  public function register_gpm_post() {
	  $labels = array(
	      'name' => _x('Places'),
	      'singular_name' => _x('Place'),
	      'add_new_item' => __('Add new place item'),
	      'add_new' => __('Add new'),
	      'edit_item' => __('Edit place Item'),
	      'menu_name' => __('Places'),
	      'not_found' => __('Place not found.'),
	      'not_found_in_trash' => __('Nothing found in trash.')
	  );

	  $args = array(
	      'labels' => $labels,
	      'public' => true,
	      'publicly_queryable' => true,
	      'query_var' => true,
	      'capability_type' => 'post',
	      'hierarchical' => false,
	      'exclude_from_search' => true,
	      'supports' => array('title', 'editor')
	  );
		
	  register_post_type( 'place', $args );
  }
  
  public function create_gpm_taxonomies() {
	// Add new taxonomy, make it hierarchical (like categories)
	$args = array(
	    'hierarchical' => true,
	    'label' => 'Place Type',
	    'singular_label' => 'Place Type'
	);

	register_taxonomy( 'place_type', array( 'place' ), $args );
  }

  public function map () {
  	?>
  	<style type="text/css">
  	#map-canvas { width:604px; height:318px; margin: 0; padding: 0;}
    </style>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCi9JuJgDzZa7H6t4ImiN2V7gGqs7TraX8"></script>
    <script type="text/javascript">

      function initialize() {
        var mapOptions = {
          center: { lat: -34.397, lng: 150.644},
          zoom: 8
        };
        var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
      }
      google.maps.event.addDomListener(window, 'load', initialize);
    </script>
	<div id="map-canvas"></div>
  	<?php
  }

}

new GoogleMapVisualization();
