<div class="blue-box">
    <div class="blue-in">
        <h5><i class="header-icon icon-faqw"></i> <?php echo __("Poker Log"); ?></h5>
        <div class="white-in">
            <div class="tgl-content table-cnt open-cell slenkama-lentele">
                    <?php if (!empty($pokerlogs)  AND is_array($pokerlogs)): ?>
                    <table class="table-list">
                    <tr>
                        <th style="text-align: center;"><?php echo $this->Paginator->sort('PokerLog.created', __('Date')); ?></th>
                        <th style="text-align: center;"><?php echo $this->Paginator->sort('PokerLog.message', __('Entry')); ?></th>
                    </tr>
                    <?php if (!empty($pokerlogs)  AND is_array($pokerlogs)): ?>
                    <?php foreach ($pokerlogs as $gamelog): ?>
                        <?php $gamelog = $gamelog['PokerLog']; ?>
                        <tr>
                            <td><center><?php echo $this->TimeZone->convertDateTime($gamelog['created']); ?></center></td>
                            <td><center><?php echo $gamelog['message']; ?></center></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </table>
                    <?php else: ?>
                        <div class="dark-table"><?php echo __("There are no played Poker games."); ?></div>
                    <?php endif; ?>

                    <div class="dark-table">
                        <?php echo $this->element('paginator'); ?>
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
            </div>

        </div>
    </div>
</div>