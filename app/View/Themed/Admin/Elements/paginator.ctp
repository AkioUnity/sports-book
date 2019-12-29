<p>
    <?php
    echo $this->Paginator->counter(array(
        'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
    ));
    ?>
</p>
<?php if ($this->Paginator->hasPage(2)): ?>
    <div class="paging">
        <?php if ($this->Paginator->hasPrev()): ?>
            <?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class' => 'disabled')) . "\n"; ?>
            |
        <?php endif; ?>
        <?php echo $this->Paginator->numbers(); ?>
        |
        <?php if ($this->Paginator->hasNext()): ?>
            <?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled')) . "\n"; ?>
        <?php endif; ?>
    </div>
<?php endif; ?>