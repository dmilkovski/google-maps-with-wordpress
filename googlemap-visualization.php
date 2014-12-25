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

    add_action ('admin_init', array ($this, 'register_custom_fileds'));
    add_action ('save_post', array ($this, 'save_details'));

    add_shortcode( 'places_map', array ($this, 'places_map_func') );
  }
  //short code init [places_map]
  function places_map_func () {
  	$query_data = new WP_Query(array('post_type' => 'place'));
  	$counter = 0;
  	$point_data = array ();	  	
  	while ($query_data->have_posts()) : $query_data->the_post();
  		$latitude = get_post_custom_values('latitude_filed');
  		$longitude = get_post_custom_values('longitude_filed');
  		$point_data[$counter]['latitude'] = $latitude[0];
  		$point_data[$counter]['longitude'] = $longitude[0];
  		$counter++;
  	endwhile;
  	$jsonData = json_encode($point_data);
  	?>
  	

  	<style type="text/css">
  		#map-canvas { width:604px; height:318px; margin: 0; padding: 0;}
    </style>
    <script type="text/javascript"
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCi9JuJgDzZa7H6t4ImiN2V7gGqs7TraX8">
    </script>
    <script type="text/javascript">
      function initialize() {
        var mapOptions = {
          center: { lat: 42.733883, lng: 25.485830},
          zoom: 5
        };

        var map = new google.maps.Map(document.getElementById('map-canvas'),
            mapOptions);

        var pointsJson = <?php echo $jsonData; ?>, marker;
        //add markers on the map
        for (var i = 0; i < pointsJson.length; i++)
        {
        	marker = new google.maps.Marker ({
        		position: new google.maps.LatLng (pointsJson[i].latitude, pointsJson[i].longitude),
        		map: map
        	});
        }
        
        
      }
      google.maps.event.addDomListener(window, 'load', initialize);
    </script>
	<div id="map-canvas"></div>
	<?php
  	return '';
  }

  //end short code init

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
	    'show_ui' => true,
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

  public function register_custom_fileds () {
  	add_meta_box("latitude_meta", "Latitude", array ($this, "latitude_filed"), "place", "normal", "high");
  	add_meta_box("longitude_meta", "Longitude", array ($this, "longitude_filed"), "place", "normal", "high");
  }

  function latitude_filed () {
  	global $post;
  	$latitude_f = get_post_custom ($post->ID);
  	$latitude = $latitude_f["latitude_filed"][0];
  	?>
  	<label>Latitude</label>
  	<input type="text" name="latitude_filed" value="<?php echo $latitude; ?>" />
  	<?php
  }

  function longitude_filed () {
  	global $post;
  	$longitude_f = get_post_custom ($post->ID);
  	$longitude = $longitude_f["longitude_filed"][0];
  	?>
  	<label>Longitude</label>
  	<input type="text" name="longitude_filed" value="<?php echo $longitude; ?>" />
  	<?php
  }

  function save_details(){
	  global $post;
	 
	  update_post_meta($post->ID, "latitude_filed", $_POST["latitude_filed"]);
	  update_post_meta($post->ID, "longitude_filed", $_POST["longitude_filed"]);

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
