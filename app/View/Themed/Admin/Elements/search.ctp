<div class="hidden-phone">
<?php

if(!isset($search_fields) || !is_array($search_fields))
    return;

echo $this->MyForm->create($model, array('type' => 'get', 'url' => array('plugin' => false), 'id' => 'search-form'));

foreach($search_fields AS $i => $field)
{
    if(!is_array($field)) {
        $search_fields[$i] = array($field);
    }

    $class = isset($field['class']) ? $field['class'] : null;

    $search_fields[$i]['div'] = array('class' => 'search-inputs '. $class .'' );
    $search_fields[$i]['required'] = false;
}

echo $this->MyForm->inputs($search_fields, null, array('fieldset' => false, 'legend' => false));
echo $this->MyForm->button(__('Search', true), array('type' => 'submit', 'id' => 'search_button', 'class' => 'btn'));
echo $this->MyForm->end();
?>
</div>
<style type="text/css">
    form#search-form {
        position: relative;
        right: 5px;
    }

    form#search-form .search-inputs {
        float: left;
        padding-left: 5px;;

    }

    form#search-form #search_button {
        float: left;
        margin: 25px 0 10px 10px;
    }
</style>