<?php
 
/**
 * The main plugin controller
 *
 * @package MVC Example
 * @subpackage Main Plugin Controller
 * @since 0.1
 */
class fooController
{
	/**
	 * the class constructor
	 *
	 * @package MVC Example
	 * @subpackage Main Plugin Controller
	 *
	 * @since 0.1
	 */
	public function __construct()
	{
		if( !is_admin() ):
			add_action( 'wp', array( $this, 'init' ) );
		endif;
	}
	
	/**
	 * callback for the 'wp' action
	 *
	 * In this function, we determine what WordPress is doing and add plugin actions depending upon the results.
	 * This helps to keep the plugin code as light as possible, and reduce processing times.
	 *
	 * @package MVC Example
	 * @subpackage Main Plugin Controller
	 *
	 * @since 0.1
	 */
	public function init()
	{
		//is this a post display page? If so, then filter the content
		if( is_single() )
			add_filter( 'the_content', array(&$this, 'render_foo_single_post' ) );
	}
	
	/**
	 * filter the content on single posts
	 *
	 * @package MVC Example
	 * @subpackage Main Plugin Controller
	 *
	 * @param string $content passed by WP in 'the_content' filter
	 * @return string the modified post content
	 * @since 0.1
	 */
	public function render_foo_single_post( $content )
	{
		//require_once our model
		require_once( 'models/foo-model.php' );
		
		//instantiate the model
		$fooModel = new fooModel;
		
		//set the message
		date_default_timezone_set('America/Los_Angeles');
		$fooModel->set_message(date('m-d-Y h:ia'));

		//get the message
		$message = $fooModel->get_message();
	
		//include our view
		require_once( 'views/foo-single-post-html.php' );

		//render the view
		$content = fooSinglePostHtmlView::render( $message ) . $content ;

		//return the result
		return $content;
	}
}
 
$foo = new fooController;
?>