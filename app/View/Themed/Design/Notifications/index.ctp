<div class="blue-box">
    <div class="blue-in">
        <h5><i class="header-icon icon-faqw"></i> <?php echo __("Notifications"); ?></h5>
        <div class="white-in">
            <?php echo $this->element('flash_message'); ?>
            <div class="tgl-content table-cnt open-cell slenkama-lentele">
                <table class="table-list">
                    <tr>
                        <th><?php echo $this->Paginator->sort('Notification.id', __('ID')); ?></th>
                        <th><?php echo $this->Paginator->sort('Notification.text', __('Text')); ?></th>
                        <th><?php echo $this->Paginator->sort('Deposit.date', __('Date')); ?></th>
                    </tr>
                    <?php if (!empty($data)  AND is_array($data)): ?>
                        <?php foreach ($data as $row): ?>
                            <tr>
                                <td><?php echo $row['Notification']['id']; ?></td>
                                <td><?php echo $row['Notification']['text']; ?></td>
                                <td><?php echo $row['Notification']['date']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </table>
                <div>
                    <?php echo $this->element('paginator'); ?>
                </div>
            </div>
        </div>
    </div>
</div>