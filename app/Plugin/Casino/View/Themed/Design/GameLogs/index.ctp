<div class="blue-box">
    <div class="blue-in">
        <h5><i class="header-icon icon-faqw"></i> <?php echo __("Casino Log"); ?></h5>
        <div class="white-in">
            <div class="tgl-content table-cnt open-cell slenkama-lentele">
                    <?php if (!empty($gamelogs)  AND is_array($gamelogs)): ?>
                    <table class="table-list">
                    <tr>
                        <th style="text-align: center;"><?php echo $this->Paginator->sort('GameLog.created', __('Date')); ?></th>
                        <th style="text-align: center;"><?php echo $this->Paginator->sort('GameLog.message', __('Entry')); ?></th>
                        <th style="text-align: center;"><?php echo $this->Paginator->sort('GameLog.game', __('Game')); ?></th>
                    </tr>
                    <?php if (!empty($gamelogs)  AND is_array($gamelogs)): ?>
                    <?php foreach ($gamelogs as $gamelog): ?>
                        <?php $gamelog = $gamelog['GameLog']; ?>
                        <tr>
                            <td><center><?php echo $this->TimeZone->convertDateTime($gamelog['created']); ?></center></td>
                            <td><center><?php echo $gamelog['message']; ?></center></td>
                            <td><center><?php echo $gamelog['game']; ?></center></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </table>
                    <?php else: ?>
                        <div class="dark-table"><?php echo __("There are played Casino games."); ?></div>
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