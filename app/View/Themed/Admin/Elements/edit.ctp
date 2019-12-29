<?php
echo $this->MyForm->create($model, array('type' => 'file'));
echo $this->MyForm->inputs(array_merge($fields, array('legend' => false)));

echo "<button type='button' class='btn' style='float: left; margin-right: 15px; margin-top: 15px;' onclick='window.location.href=\"".$referer."\"'>".__("Go Back")."</button>";
echo $this->MyForm->submit(__('Submit', true), array('class' => 'btn', 'style' => 'margin-top: 15px;'));
echo $this->MyForm->end();
