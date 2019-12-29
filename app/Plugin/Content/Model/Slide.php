<?php
/**
 * Slide Model
 *
 * Handles Slide Data Source Actions
 *
 * @package    Slides.Model
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppModel', 'Model');

class Slide extends AppModel
{

    /**
     * Model name
     *
     * @var string
     */
    public $name = 'Slide';

    /**
     * Model schema
     *
     * @var array
     */
    protected $_schema = array(
        'id'    => array(
            'type'      => 'int',
            'length'    => 11,
            'null'      => false
        ),
        'title' => array(
            'type'      => 'string',
            'length'    => 100,
            'null'      => false
        ),
        'description' => array(
            'type'      => 'text',
            'length'    => false,
            'null'      => false
        ),
        'url'   => array(
            'type'      => 'string',
            'length'    => 255,
            'null'      => false
        ),
        'active'    => array(
            'type'      => 'tinyint',
            'length'    => 1,
            'null'      => false
        ),
        'order'  => array(
            'type'      => 'int',
            'length'    => 1,
            'null'      => false
        ),
    );

    /**
     * Slides sizes
     *
     * @var array
     */
    public $sizes = array(
        0   =>  array(
            'width'     =>  '545',
            'height'    =>  '270',
            'fit'       =>  'fill',
            'scale'     =>  'any'
        ),
        1   =>  array(
            'width'     =>  '465',
            'height'    =>  '200',
            'fit'       =>  'fill',
            'scale'     =>  'any'
        ),
        2   =>  array(
            'width'     =>  '131',
            'height'    =>  '50',
            'fit'       =>  'fill',
            'scale'     =>  'any'
        )
    );

    /**
     * List of behaviors to load when the model object is initialized.
     *
     * @var $actsAs array
     */
    public $actsAs = array();

    /**
     * List of validation rules.
     *
     * @var array
     */
    public $validate = array(
        'title'         =>  array(
            'rule'      =>  'notEmpty',
            'message'   =>  'This field cannot be left blank'
        ),
        'description'   =>  array(
            'rule'      => 'notEmpty',
            'message'   => 'This field cannot be left blank'
        ),
        'image'         =>  array(
            'rule'      => array(
                'extension',
                array('gif', 'jpeg', 'png', 'jpg')),
            'message'   => 'This field cannot be left blank'
        )
    );

    /**
     * Returns slides
     *
     * @return array
     */
    public function getSlides()
    {
        return $this->find('all', array(
            'conditions'    =>  array('Slide.active' => 1),
            'order'         =>  'Slide.order ASC'
        ));
    }

    /**
     * Model scaffold search fields wrapper
     *
     * @return array
     */
    public function getSearch()
    {
        return array(
            'Slide.id'          =>  array(
                'type'  =>  'number',
                'label' =>  __('Identity number'),
                'min'   =>  1
            ),

            'Slide.title'       =>  array(
                'type'  => 'text',
                'label' => __('Words in title')
            ),

            'Slide.description' =>  array(
                'type'  => 'text',
                'label' =>  __('Words in description')
            ),

            'Slide.url'         =>  array(
                'type'  => 'text',
                'label' =>  __('Url link')
            ),

            'Slide.active'      =>  array(
                'style'     =>  '',
                'div'       =>  'control-group',
                'before'    =>  '<div class="controls" style="margin-top: 15px"><label style="position: absolute; top: 0px;">Active</label><div style="margin-top: 12px;" class="transition-value-toggle-button">',
                'type'      =>  'checkbox',
                'class'     => 'toggle',
                'after'     =>  '</div></div>',
                'label'     =>  false
            )


        );
    }

    /**
     * Returns admin scaffold add fields
     *
     * @return array
     */
    public function getAdd()
    {

        return array(
            'Slide.title'       =>  array(
                'type'  =>  'text'
            ),

            'Slide.description' =>  array(
                'class' => 'span12 ckeditor'
            ),

            'Slide.url'         =>  array(
                'type'  =>  'text',
                'div' =>  array('style' => 'margin-top: 15px; float: left;')
            ),

            'Slide.image'       =>  array(
                'type'      =>  'file',
                'div'       =>  array('style' => 'position: relative; left: 15px; top: 18px; float: left;'),
                'after'     =>  '<div style="clear: both;"></div>'
            ),

            'Slide.active'      =>  array(
                'div'       =>  'control-group',
                'before'    =>  '<div class="controls" style="margin-top: 15px; clear: both;"><div class="transition-value-toggle-button">',
                'type'      =>  'checkbox',
                'class'     => 'toggle',
                'after'     =>  '</div></div>',
                'label'     =>  false,
            )
        );
    }

    /**
     * Returns admin scaffold edit fields
     *
     * @return array
     */
    public function getEdit($data)
    {
        return array(
            'Slide.title'       =>  array(
                'type'  =>  'text'
            ),

            'Slide.description' =>  array(
                'class' => 'span12 ckeditor'
            ),

            'Slide.url'         =>  array(
                'style'     =>  '',
                'type'  =>  'text',
                'div' =>  array('style' => 'margin-top: 15px; float: left;')
            ),

            'Slide.image'       =>  array(
                'type'      =>  'file',
                'div'       =>  array('style' => 'position: relative; left: 15px; top: 18px; float: left;'),
                'after'     =>  '<div style="clear: both;"></div>'
            ),

            'Slide.active'      =>  array(
                'style'     =>  '',
                'div'       =>  'control-group',
                'before'    =>  '<div class="controls" style="margin-top: 15px; clear: both;"><div class="transition-value-toggle-button">',
                'type'      =>  'checkbox',
                'class'     => 'toggle',
                'after'     =>  '</div></div>',
                'label'     =>  false
            )
        );
    }
}