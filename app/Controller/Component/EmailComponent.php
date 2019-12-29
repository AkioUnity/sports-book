<?php
/**
 * Email Component
 *
 * This is base Email Component Class
 * Class provides reusable bits of controller logic that can be composed into another controllers.
 * Class provides common methods for emails handling
 *
 * @package     App.Controller
 * @author      Deividas Petraitis <deividas@laiskai.lt>
 * @copyright   2013 The ChalkPro Betting Scripts
 * @license     http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version     Release: @package_version@
 * @link        http://www.chalkpro.com/
 * @see         Controller::$components
 */

App::import('Model', 'Template');
App::uses('Validation', 'Utility');
App::uses('CakeEmail', 'Network/Email');
App::uses('Component', 'Controller');

class EmailComponent extends Component
{
    /**
     * Other Components this component uses.
     *
     * @var array
     */
    public $components = array('Session');

    /**
     * Inserts variables to email template and sends email
     * Returns email send status
     *
     * @param $templateName
     * @param $to
     * @param $vars
     * @param $attachments
     * @return array|bool
     */
    public function sendMail($templateName, $to, $vars, $attachments = array())
    {
        $Template = new Template();

        $vars["website"] = Configure::read('Settings.websiteName');

        $template = $Template->find('first', array('conditions' => array('title' => $templateName)));

        if(!empty($template)) {
            $subject = $template['Template']['subject'];
            $subject = $this->insertVariables($subject, $vars);

            $content = $template['Template']['content'];
            $content = $this->insertVariables($content, $vars);
        }else{

            $subject = $this->insertVariables('{content}', $vars);
            $content = $this->insertVariables('{content}', $vars);
        }

        return $this->send($to, $subject, $content, array(), $attachments);
    }

    /**
     * Sends email and returns send status
     *
     * @param $to
     * @param $subject
     * @param $content
     * @param array $bcc
     * @param array $attachments
     * @return array|bool
     */
    public function send($to, $subject, $content, $bcc = array(), $attachments = array())
    {
        if (Validation::email($to))
        {
            try {
                $email = new CakeEmail();

                $email  ->  config('smtp')
                        ->  to($to)
                        ->  subject($subject)
                        ->  bcc($bcc);

                $email  ->  replyTo(Configure::read('Settings.contactMail'))
                        ->  from(Configure::read('Settings.contactMail'))
                        ->  emailFormat('both');

                $email  ->  addAttachments($attachments);

                return $email->send($content);

            }catch (Exception $e) {
                CakeLog::write('mail', var_export($e->getMessage(), true));
            }
        }

        return false;
    }

    /**
     * Inserts variables to email template
     *
     * @param $template
     * @param array $vars
     * @return mixed
     */
    public function insertVariables($template, $vars = array()) {
        foreach ($vars as $key => $value) {
            if (is_string($value))
                $template = str_replace('{' . $key . '}', $value, $template);
        }
        return $template;
    }
}