<?php
$url = null;
if (isset($this->params['pass'][0]))
    $url = array($this->params['pass'][0]);

echo $this->MyForm->create($model, array('url' => $url));
echo $this->MyForm->input(__('locale', true), array('type' => 'select', 'options' => $locales));
echo $this->MyForm->inputs(array_merge($fields, array('legend' => __('Translate %s', $this->Admin->getSingularName()))));
echo $this->MyForm->submit(__('Submit', true), array('class' => 'btn btn-primary', 'style' => 'margin-top: 15px;'));
echo $this->MyForm->end();
