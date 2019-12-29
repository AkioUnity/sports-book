<?php if (!empty($fields)) : ?>
    <div class="<?php echo $this->Admin->getPluralName(); ?> view">
        <h2><?php echo $this->Admin->getSingularName(); ?></h2>
        <?php if (isset($noTranslation)): ?>
            <div>No translation for this language, <?php echo $this->MyHtml->link('create', array('action' => 'admin_edit', $this->params['pass'][0])); ?></div>
        <?php else: ?>    

            <table class="items" cellpadding="0" cellspacing="0">
                <?php $i = 1; ?>
                <?php foreach ($fields[$model] as $key => $value): ?>
                    <?php
                    $class = '';
                    if ($i++ % 2 == 0)
                        $class = 'alt';
                    ?>
                    <tr>
                        <th class="specalt"><?php echo Inflector::humanize($key); ?></th>
                        <td><?php echo $value; ?></td>
                    </tr>

                <?php endforeach; ?>    
                <tr>
                    <th class="specalt">First Name</th>
                    <td><?php echo $user['first_name'] ?></td>
                </tr>
                <tr>
                    <th class="specalt">Last Name</th>
                    <td><?php echo $user['last_name'] ?></td>
                </tr>
                <tr>
                    <th class="specalt">Bank Name</th>
                    <td><?php echo $user['bank_name'] ?></td>
                </tr>
                <tr>
                    <th class="specalt">Bank code</th>
                    <td><?php echo $user['bank_code'] ?></td>
                </tr>
                <tr>
                    <th class="specalt">Account number</th>
                    <td><?php echo $user['account_number'] ?></td>
                </tr>
            </table>

        <?php endif; ?>
    </div>
<?php endif; ?>