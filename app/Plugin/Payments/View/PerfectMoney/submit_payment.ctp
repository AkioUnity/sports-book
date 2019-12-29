<?php echo $this->Form->create(null, array('url' => $submitUrl)); ?>
<?php if (isset($fields) AND is_array($fields)): ?>

    <?php foreach ($fields AS $fieldName => $fieldValue): ?>
        <?php echo $this->MyForm->input(null, array('name' => $fieldName, 'value' => $fieldValue, 'type' => 'hidden')); ?>
    <?php endforeach; ?>
<?php endif; ?>
<?php echo $this->Form->end(); ?>
<script type="text/javascript">
    var form = document.forms[0];
    form.submit();
</script>