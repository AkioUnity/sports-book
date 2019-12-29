<div>
    <h3><?php echo __('Available cultures'); ?></h3>

    <p><?php echo __('Add a culture'); ?></p>
    <?php echo $this->MyForm->create('Language'); ?>
    <?php echo $this->MyForm->input('name', array('options' => $localesList)); ?>
    <?php echo $this->MyForm->end(__('Add', true)); ?>

    <?php if (isset($locales)): ?>

        <p><?php echo __('Cultures this site supports'); ?></p>
        <ul>
            <?php foreach ($locales as $locale): ?>
                <li>
                    <span><?php echo $locale['name']; ?></span>
                    <?php if ($locale['id'] != 1): ?>
                        <?php echo $this->MyHtml->link(__('X', true), array('action' => 'admin_delete', $locale['id']), NULL, __('Are you sure you want to delete ', true) . $locale['name']); ?>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>

    <?php endif; ?>

</div>