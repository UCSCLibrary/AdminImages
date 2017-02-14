<?php
/**
 * AdminImages plugin
 *
 * @package     AdminImages
 * @copyright Copyright 2014 UCSC Library Digital Initiatives
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * AdminImages plugin class
 * 
 * @package AdminImages
 */
class AdminImagesPlugin extends Omeka_plugin_AbstractPlugin
{
    public function __toString() 
    {
        return $this->name;
    }


    public function hookInitialize()
    {
        add_shortcode('admin_image','admin_image_tag_shortcode');
        require_once(dirname(__FILE__)."/helpers/AdminImageFunctions.php");
        get_view()->addHelperPath(dirname(__FILE__) . '/views/helpers/', 'AdminImages_View_Helper_');
    }
    
    /**
     * @var array Hooks for the plugin.
     */
    protected $_hooks = array(
        'define_acl',
        'admin_head',
        'install',
        'uninstall',
        'initialize'
    );

    /**
     * @var array Filters for the plugin.
     */
    protected $_filters = array('admin_navigation_main');

    public function hookAdminHead()
    {
      queue_css_file('admin-images');
      queue_js_file('admin-images');
    }
    public function hookInstall($args)
    {
      try{
	$sql = "
            CREATE TABLE IF NOT EXISTS `{$this->_db->AdminImage}` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `title` text,
                `alt` text,
                `href` text,
                `file_id` int,
                `creator_id` int,
                PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
	$this->_db->query($sql);
      }catch(Exception $e) {
	throw $e;
      }
    }

    /**
     * When the plugin uninstalls, delete the database tables 
     *which store the logs
     * 
     * @return void
     */
    public function hookUninstall()
    {
      try{
	$db = get_db();
	$sql = "DROP TABLE IF EXISTS `$db->AdminImage` ";
	$db->query($sql);
      }catch(Exception $e) {
	throw $e;	
      }
    }

    /**
     * Define the plugin's access control list.
     *
     * @param array $args This array contains a reference to
     * the zend ACL under it's 'acl' key.
     * @return void
     */
    public function hookDefineAcl($args)
    {
        $args['acl']->addResource('AdminImages_Index');
    }

    /**
     * Add the AdminImages link to the admin main navigation.
     * 
     * @param array $nav Array of links for admin nav section
     * @return array $nav Updated array of links for admin nav section
     */
    public function filterAdminNavigationMain($nav)
    {
        $nav[] = array(
            'label' => __('Admin Images'),
            'uri' => url('admin-images'),
            'resource' => 'AdminImages_Index',
            'privilege' => 'index'
        );
        return $nav;
    }
  

}
