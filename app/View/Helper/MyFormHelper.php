<?php

App::uses('FormHelper', 'View/Helper');

class MyFormHelper extends FormHelper
{
    /**
     * Helper name
     *
     * @var string
     */
    public $name = 'MyForm';

    /**
     * Returns an HTML FORM element.
     *
     * @param mixed $model  The model name for which the form is being defined. Should
     *   include the plugin name for plugin models. e.g. `ContactManager.Contact`.
     *   If an array is passed and $options argument is empty, the array will be used as options.
     *   If `false` no model is used.

     * @param array $options An array of html attributes and options.
     *
     * @return string An formatted opening FORM tag.
     */
    public function create($model = null, $options = array())
    {
        if (!isset($options['url']['language'])) {
            $options['url']['language'] = Configure::read('Config.language');
        }

        return parent::create($model, $options);
    }

    /**
     * Overrides a form input element complete with label and wrapper div
     *
     * @param string $fieldName
     * @param array $options
     * @return string
     */
    public function input($fieldName, $options = array()) {
        if(!isset($options['required'])) {
            $options['required'] = false; // Finger bitch!
        }

        return parent::input($fieldName, $options);
    }


    /**
     * Returns a set of SELECT elements for a full datetime setup: day, month and year, and then time.
     *
     * @param string $fieldName  Prefix name for the SELECT element
     * @param string $dateFormat DMY, MDY, YMD, or null to not generate date inputs.
     * @param string $timeFormat 12, 24, or null to not generate time inputs.
     * @param null   $selected   -
     * @param array  $attributes -
     *
     * @return string - Generated set of select boxes for the date and time formats chosen.
     */
    public function dateTime($fieldName, $dateFormat = 'DMY', $timeFormat = '12', $selected = null, $attributes = array())
    {
        if ($timeFormat != null) {
            return $this->input($fieldName, array('type' => 'text', 'label' => false, 'class' => 'input-small flexy_datetimepicker_input'));
        }

        return $this->input($fieldName, array('type' => 'text', 'label' => false, 'class' => 'input-small flexy_datepicker_input'));
    }
}