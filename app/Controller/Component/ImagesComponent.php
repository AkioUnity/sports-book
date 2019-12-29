<?php
/**
 * Images Component
 *
 * This is Images Component Class
 * Class provides reusable bits of controller logic that can be composed into another controllers.
 * Class provides common and independent misc methods from any controller
 *
 * @package     App.Controller
 * @author      Deividas Petraitis <deividas@laiskai.lt>
 * @copyright   2013 The ChalkPro Betting Scripts
 * @license     http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version     Release: @package_version@
 * @link        http://www.chalkpro.com/
 * @see         Controller::$components
 */

App::uses('Component', 'Controller');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

class ImagesComponent extends Component
{
    /**
     * Other Components this component uses.
     *
     * @var array
     */
    public $components = array('Session');

    /**
     * WideImage Library
     *
     * @var WideImage
     */
    public $ImageHandler = null;

    /**
     * Default image resize settings
     *
     * @var array
     */
    public $resizeSettings = array(
        'width'     =>  '545',
        'height'    =>  '160',
        'fit'       =>  'inside',
        'scale'     =>  'any'
    );

    /**
     * Called before the Controller::beforeFilter()
     *
     * @param Controller $controller
     */
    public function initialize(Controller $controller) {
        App::import('Vendor', 'WideImage', array('file' => 'WideImage/WideImage.php'));

        $this->ImageHandler = new WideImage();

        parent::initialize($controller);
    }

    /**
     * Save image handler
     *
     * @param $source
     * @param $name
     * @param array $sizes
     * @param $uploadPath
     * @param string $ext
     * @return bool
     */
    public function save($source, $name, array $sizes, $uploadPath, $ext = '.jpg')
    {
        try {
            $Folder = new Folder();
            $original = $uploadPath . DS . 'original' . DS;

            if (!$Folder->cd($original)) {
                new Folder($original, true, 0777);
            }

            $this->ImageHandler->load($source)->saveToFile($original . $name . $ext);

            foreach ($sizes AS $size) {
                $size = array_merge($this->resizeSettings, $size);
                $sizeUploadPath = $uploadPath . DS . $size['width'] . 'x' . $size['height'] . DS;

                if (!$Folder->cd($sizeUploadPath)) {
                    new Folder($sizeUploadPath, true, 0777);
                }
                $this->ImageHandler->load($source)->resize($size['width'], $size['height'], $size['fit'], $size['scale'])->saveToFile($sizeUploadPath. $name . $ext);
            }
        } catch(Exception $e) {
            CakeLog::write('SlidesController', $e->getFile() . ':' . $e->getLine() . ' ' . $e->getMessage());
            return false;
        }
        return true;
    }
}