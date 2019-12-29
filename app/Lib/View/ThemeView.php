<?php
 /**
 * ThemeView
 *
 * A custom view class that is used for theme'ing
 *
 * @package    Cake.View
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('View', 'View');

class ThemeView extends View
{
    /**
     * Return all possible paths to find view files in order
     *
     * @param string $plugin Optional plugin name to scan for view files.
     * @param boolean $cached Set to true to force a refresh of view paths.
     * @return array paths
     */
    public function _paths($plugin = null, $cached = true)
    {
        $paths = parent::_paths($plugin, $cached);

        foreach(array_values(array_unique(App::objects('plugin'))) AS $buildInPlugin) {
            foreach(App::path('Plugin') AS $pluginPath) {
                $dirPath = $pluginPath. DS . $buildInPlugin . DS . 'View' . DS . 'Themed' . DS . $this->theme . DS;
                if (is_dir($dirPath)) {
                    if( !isset($this->params['plugin']) || $this->params['plugin'] == null) {
                        $paths[] = $dirPath;
                    }else{
                        array_unshift($paths, $dirPath);
                    }
                }
            }
        }
        return $paths;
    }
}