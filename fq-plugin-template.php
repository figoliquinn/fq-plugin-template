<?php
/*
Plugin Name: FQ Plugin Template
Plugin URI: https://github.com/figoliquinn/fq-plugin-template
Description: A light-weight object-oriented wordpress plugin template
Version: 1.0
Author: Figoli Quinn
Author URI: http://www.figoliquinn.com
License: GPL2
*/

/*
Copyright 2016 Figoli Quinn  (email : info@figoliquinn.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/








if(!class_exists('FQ_Plugin_Template'))
{


	class FQ_Plugin_Template
	{



		/* For example:
		$notices = array(
			array('This is an error!','notice-error',true),
			array('This is a warning!','notice-warning',true),
			array('This is a success!','notice-success',true),
		); // message, class, dismissable?
		*/
		public $notices = array();


		public $required_plugins = array(
			array(
				'name' 				=> 'AJAX Thumbnail Rebuild',
				'slug' 				=> 'ajax-thumbnail-rebuild',
				'required'           => true,
				'force_activation'   => true,
			),
			array(
				'name'				=> 'Duplicate Post',
				'slug' 				=> 'duplicate-post',
				'required'           => true,
				'force_activation'   => true,
			),
			array(
				'name' 				=> 'Dynamic Featured Image',
				'slug' 				=> 'dynamic-featured-image',
				'required'           => true,
				'force_activation'   => true,
			),
			array(
				'name' 				=> 'Simple Page Ordering',
				'slug' 				=> 'simple-page-ordering',
				'required'           => true,
				'force_activation'   => true,
			),
			array(
				'name' 				=> 'Wordpress Reset',
				'slug' 				=> 'wordpress-reset',
				'required'           => true,
				'force_activation'   => true,
			),
			array(
				'name' 				=> 'Google Sitemap Generator',
				'slug' 				=> 'google-sitemap-generator',
				'required'           => true,
				'force_activation'   => true,
			),
			array(
				'name' 				=> 'FQ Custom Post Types',
				'slug' 				=> 'fq-custom-post-types-master', 
				'source' 			=> 'https://github.com/figoliquinn/fq-custom-post-types/archive/master.zip',
				'external_url' 		=> 'https://github.com/figoliquinn/fq-custom-post-types',
				'required'           => true,
				'force_activation'   => true,
			),
			array(
				'name' 				=> 'FQ Form Builder',
				'slug' 				=> 'fq-form-builder-master',
				'source' 			=> 'https://github.com/figoliquinn/fq-form-builder/archive/master.zip',
				'external_url' 		=> 'https://github.com/figoliquinn/fq-form-builder',
				'required'           => true,
				'force_activation'   => true,
			),
			array(
				'name' 				=> 'FQ Settings Page',
				'slug' 				=> 'fq-settings-page-master',
				'source' 			=> 'https://github.com/figoliquinn/fq-settings-page/archive/master.zip',
				'external_url' 		=> 'https://github.com/figoliquinn/fq-settings-page',
				'required'           => true,
				'force_activation'   => true,
			),
		);





		/**
		 * Construct the plugin object
		 */
		public function __construct()
		{


			require_once dirname( __FILE__ ) . '/class-tgm-plugin-activation.php';
			add_action( 'tgmpa_register', array($this,'required_plugins') );

			add_action( 'admin_notices', array($this,'plugin_admin_notices') );

			add_action('init', array($this,'start_up'));

			add_filter('plugin_row_meta', array($this,'plugin_row_meta_links'), 10, 2);

		} // END public function __construct






		/**
		 * Start up the plugin
		 */
		public function start_up()
		{

			// Initialize Settings Here
			if(class_exists('FQ_Settings')){
				$s = new FQ_Settings();
				$s->parent_slug = 'edit.php?post_type=event';
				$s->settings = array(
					array(
						'label' => 'Event Setting',
						'name' => 'event-setting',
						'type' => 'text', // select, radio, checkbox, textarea, upload, OR text
						'class' => 'regular-text', // large-text, regular-text
						'value' => '', // default value
						'description' => 'Enter a comma-seperated list of email addresses to send contact form submissions.',
						'options' => array("Small","Medium","Large"),
						'rows' => 5,
					),
				);
			}
			else {
			
				#$this->notices[] = array('The FQ Setting plugin needs to be installed!','notice-error',true);
			}


			// Register custom post types Here
			if(class_exists('FQ_Custom_Post_Type')){
				$t = new FQ_Custom_Post_Type('event');
				#$t->args['public']=false;
				#$t->args['show_ui']=true;
				$t->add_category('kind');
				$t->add_category('color');
				$t->add_custom_fields(array(
					'sample_checkbox'=>array(
						'type'=>'checkbox',
						'label'=>'Sample Checkbox',
						'inline'=>true,
						'options'=>array(1=>'Yes','No','Maybe'),
					),
					'sample_wysiwyg'=>array(
						'type'=>'wysiwyg',
						'label'=>'Sample Wysiwyg',
					),
				));
				$t->register();
			}
			else {
			
				#$this->notices[] = array('The FQ Custom Post Type plugin needs to be installed!','notice-error',true);
			}

			if(class_exists('FQ_Form_Builder')){
			}
			else {
			
				#$this->notices[] = array('The FQ Form Builder plugin needs to be installed!','notice-error',true);
			}


		} // END public static function __init





		/**
		 * Activate the plugin
		 */
		public function activate()
		{

		} // END public static function activate






		/**
		 * Deactivate the plugin
		 */
		public function deactivate()
		{

		} // END public static function deactivate






		// Add the settings link to the plugins page
		function plugin_settings_link($links)
		{
			return $links;
			$settings_link = '<a href="'.admin_url('options-general.php?page=wp_plugin_template').'">Settings</a>';
			array_unshift($links, $settings_link);
			return $links;
		}




		function plugin_row_meta_links($links, $file) {

			if ( $file == plugin_basename(dirname(__FILE__).'/fq-plugin-template.php') ) {
				$links[] = '<a href="'.admin_url('options-general.php?page=plugin-options').'">Help</a>';
				$links[] = '<a href="'.admin_url('options-general.php?page=plugin-options').'">Settings</a>';
			}
			return $links;
		}





		function plugin_option_page() {
			
			add_options_page('Plugin Options','Plugin Options','manage_options','plugin-options', array($this,'display_options_page'));
		}




		function display_options_page() {
			

		}





		function plugin_admin_notices() {
			
			if(!$this->notices) return;
			foreach($this->notices as $n => $notice) {			
				echo '
					<div class="notice '.$notice[1].' '.($notice[2]?'is-dismissible':'').'">
						<p>'.$notice[0].'</p>
					</div>
				';	
			}

		}


		function required_plugins(){
		
			tgmpa( $this->required_plugins , array('has_notices'=>true,'is_automatic'=>true,'dismissable'=>false) );
		}



	} // END class FQ_Plugin_Template

} // END if(!class_exists('FQ_Plugin_Template'))





if(class_exists('FQ_Plugin_Template'))
{
	// Installation and uninstallation hooks
	register_activation_hook(__FILE__, array('FQ_Plugin_Template', 'activate'));
	register_deactivation_hook(__FILE__, array('FQ_Plugin_Template', 'deactivate'));

	// instantiate the plugin class
	$fq_plugin_template = new FQ_Plugin_Template();

}


