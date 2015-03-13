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
    
    /**
     * @var array Hooks for the plugin.
     */
    protected $_hooks = array(
        'install',
        'uninstall',
        'define_acl'
    );

    /**
     * @var array Filters for the plugin.
     */
    protected $_filters = array('admin_navigation_main');

    /**
     * @var array Options and their default values.
     */
    protected $_options = array(
    );

    /**
     * Install the plugin's options
     *
     * @return void
     */
    public function hookInstall() {
        $this->_installOptions();

                // Create the table.
        $db = $this->_db;
        $sql = "
        CREATE TABLE IF NOT EXISTS `$db->AdminImage` (
          `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
          `creator_id` int(10) unsigned NOT NULL,
          `title` tinytext COLLATE utf8_unicode_ci NOT NULL,
          `slug` tinytext COLLATE utf8_unicode_ci NOT NULL,
          PRIMARY KEY (`id`),
          KEY `creator_id` (`creator_id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
        $db->query($sql);
    }

    /**
     * Uninstall the options
     *
     * @return void
     */
    public function hookUninstall()
    {
        $this->_uninstallOptions();
        // Drop the table.
        $db = $this->_db;
        $sql = "DROP TABLE IF EXISTS `$db->AdminImage`";
        $db->query($sql);

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