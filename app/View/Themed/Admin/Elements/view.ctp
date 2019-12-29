<?php if (!empty($fields)) : ?>

        <?php if (isset($noTranslation)): ?>
            <div>No translation for this language, <?php echo $this->MyHtml->link('create', array('action' => 'admin_edit', $this->params['pass'][0])); ?></div>
        <?php else: ?>
            <table class="table table-bordered table-striped" cellpadding="0" cellspacing="0">
                <?php $i = 1; ?>
                <?php foreach ($fields[$model] as $key => $value): ?>
                    <?php
                    $class = '';
                    if ($i++ % 2 == 0)
                        $class = 'alt';
                    ?>

                    <tr>
                        <td class="specalt"><?php echo Inflector::humanize($key); ?></td>
                        <td><?php echo $value; ?></td>
                    </tr>

                <?php endforeach; ?>
            </table>
        <?php endif; ?>
<?php endif; ?>