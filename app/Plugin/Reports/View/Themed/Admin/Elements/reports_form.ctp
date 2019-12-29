    <?php echo $this->Form->create('Report'); ?>
    <?php $From = isset($this->data['Report']['from'])  ? $this->data['Report']['from'] : null; ?>
    <?php $To   = isset($this->data['Report']['to'])    ? $this->data['Report']['to'] : null; ?>

    <?php if(isset($users)): ?>
    <?php echo $this->Form->input(
        'user_id',
        array(
            'default'  =>  CakeSession::read('Auth.User.id'),
            'options' => $users['data'],
            'type' => 'select',
            'empty' => $users['label'],
            'label' => $users['label']
        )
    ); ?>
    <?php endif; ?>
    <br />
    <?php echo __('Please select date range:'); ?>
    <br />
    <br />

    <div class="control-group">
        <label class="control-label"><?php echo __('From'); ?></label>
        <div class="controls">
            <div class="input-append date date-picker" data-date-format="yyyy-mm-dd">
                <input  name="data[Report][from]" class=" m-ctrl-medium date-picker" size="16" type="text" data-date-format="yyyy-mm-dd" value="<?php echo $From; ?>" />
                <span class="add-on"><i class="icon-calendar"></i></span>
            </div>
        </div>
    </div>


    <div class="control-group">
        <label class="control-label"><?php echo __('To'); ?></label>
        <div class="controls">
            <div class="input-append date date-picker" data-date-format="yyyy-mm-dd">
                <input  name="data[Report][to]" class=" m-ctrl-medium date-picker" size="16" type="text" data-date-format="yyyy-mm-dd" value="<?php echo $To; ?>" />
                <span class="add-on"><i class="icon-calendar"></i></span>
            </div>
        </div>
    </div>

<?php echo $this->Form->submit(__('Show', true), array('class' => 'btn')); ?>
<?php echo $this->Form->end(); ?>