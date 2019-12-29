<?php
$url = null;
if (isset($this->params['pass']))
    $url = ($this->params['pass']);

echo $this->MyForm->create($model, array('url' => $url, 'type' => 'file'));
echo $this->MyForm->inputs(array_merge($fields, array('legend' => false)));

echo $this->MyForm->submit(__('Create', true), array('class' => 'btn', 'style' => 'margin-top: 15px;'));

echo $this->MyForm->end();