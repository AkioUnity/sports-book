<?php if (isset($tabs) AND is_array($tabs)): ?>
    <ul class="nav nav-tabs">
        <?php if (!empty($tabs)): ?>
            <?php foreach ($tabs as $i => $tab): ?>
                <li <?php if (isset($tab['active']) AND $tab['active'] == true): ?>class="active"<?php endif; ?>>
                    <?php echo $this->MyHtml->link($tab['name'], $tab['url']); ?>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
<?php endif; ?>