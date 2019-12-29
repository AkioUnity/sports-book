<?php
/**
 * Files Component
 *
 * This is Common Files Component Class
 * Class provides reusable bits of controller logic that can be composed into another controllers.
 * Class provides common methods for files handling
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

class FilesComponent extends Component
{
    public $extensions = array();

    /**
     * Other Components this component uses.
     *
     * @var array
     */
    public $components = array('Session');


    /**
     * @param $folder
     * @param $formData
     * @param null $itemId
     * @return mixed
     * @deprecated use image component instead. Will be removed soon.
     */
    public function uploadFiles($folder, $formData, $itemId = null) {
        // setup dir names absolute and relative
        $folder_url = WWW_ROOT . $folder;
        $rel_url = $folder;

        // create the folder if it does not exist
        if (!is_dir($folder_url)) {
            mkdir($folder_url);
        }

        // if itemId is set create an item folder
        if ($itemId) {
            // set new absolute folder
            $folder_url = WWW_ROOT . $folder . '/' . $itemId;
            // set new relative folder
            $rel_url = $folder . '/' . $itemId;
            // create directory
            if (!is_dir($folder_url)) {
                mkdir($folder_url);
            }
        }

        // list of permitted file types, this is only images but documents can be added
        $permitted = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/png');

        // loop through and deal with the files
        foreach ($formData as $file) {
            // replace spaces with underscores
            $filename = str_replace(' ', '_', $file['name']);
            // assume filetype is false
            $typeOK = false;
            // check filetype is ok
            foreach ($permitted as $type) {
                if ($type == $file['type']) {
                    $typeOK = true;
                    break;
                }
            }

            // if file type ok upload the file
            if ($typeOK) {
                // switch based on error code
                switch ($file['error']) {
                    case 0:
                        // check filename already exists
                        if (!file_exists($folder_url . '/' . $filename)) {
                            // create full filename
                            $full_url = $folder_url . '/' . $filename;
                            // upload the file
                            $success = move_uploaded_file($file['tmp_name'], $full_url);
                        } else {
                            // create unique filename and upload file
                            $now = (int) gmdate('U');
                            $filename = $now . $filename;
                            $full_url = $folder_url . '/' . $filename;
                            $success = move_uploaded_file($file['tmp_name'], $full_url);
                        }
                        // if upload was successful
                        if ($success) {
                            // save the url of the file
                            $result['urls'][] = $filename;
                        } else {
                            $result['errors'][] = "Error uploaded $filename. Please try again.";
                        }
                        break;
                    case 3:
                        // an error occured
                        $result['errors'][] = "Error uploading $filename. Please try again.";
                        break;
                    default:
                        // an error occured
                        $result['errors'][] = "System error uploading $filename. Contact webmaster.";
                        break;
                }
            } elseif ($file['error'] == 4) {
                // no file was selected for upload
                $result['nofiles'][] = "No file Selected";
            } else {
                // unacceptable file type
                $result['errors'][] = "$filename cannot be uploaded. Acceptable file types: gif, jpg, png.";
            }
        }
        return $result;
    }
}