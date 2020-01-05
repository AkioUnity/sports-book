<div class="blue-box">
    <?php if(isset($SportsLiveMenu) && is_array($SportsLiveMenu) && !empty($SportsLiveMenu) && !empty($_GET["live"])): ?>
    <div class="top-important sports-menu important4">
        <a href="/<?=Configure::read('Config.language'); ?>/live-betting"><?=__('LIVE BETTING');?></a>
    </div>
    <ul class="sports-list">
        <?php foreach ($SportsLiveMenu AS $i => $Sport): ?>
            <li>
                <a href="/<?=Configure::read('Config.language'); ?>/live-betting/<?=$Sport["Sport"]["id"];?>" class="icon-sport<?=str_replace(' ', '', $Sport["SportI18n"][0]["content"]);?> <?php if($SportId == $Sport["Sport"]["id"]): ?>active<?php endif;?>"><span class="right"></span><?php echo $Sport["Sport"]["name"]; ?><span class="clear"></span></a>
                <?php if($SportId == $Sport["Sport"]["id"]  && isset($LeaguesLiveMenu) && is_array($LeaguesLiveMenu) && !empty($LeaguesLiveMenu)):?>
                    <div class="filter-box">
                        <?php foreach($LeaguesLiveMenu["League"] AS $League): ?>
                            <div class="filter-con-a"><a <?php if($LeagueId == $League['id']): ?>class="active"<?php endif;?>  href="/<?=Configure::read('Config.language');?>/live-betting/<?=$LeaguesLiveMenu['Sport']['id'];?>/<?=$League['id'];?>"><?=$League['name'];?></a></div>
                        <?php endforeach;?>
                    </div>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>
        <div class="top_five">
        <div class="fiveHead">
            <h2><?php echo __('Top Five');?></h2>
            <div class="fiveSub">
                <div class="top-important sports-menu TunisianLeague"><a href="/<?=Configure::read('Config.language'); ?>/sports/1/58">Tunisian league1</a></div>
                <div class="top-important sports-menu TunisianLeague"><a href="/<?=Configure::read('Config.language'); ?>/sports/1/69">Tunisian league2</a></div>
                <div class="top-important sports-menu premierleague"><a href="/<?=Configure::read('Config.language'); ?>/sports/1/1">Premier League</a></div>
                <div class="top-important sports-menu championsleague"><a href="/<?=Configure::read('Config.language'); ?>/sports/1/6">Champions League</a></div>
                <div class="top-important sports-menu primeradivision"><a href="/<?=Configure::read('Config.language'); ?>/sports/1/5">Primera Division</a></div>
                <div class="top-important sports-menu bundesliga"><a href="/<?=Configure::read('Config.language'); ?>/sports/1/3">Bundesliga</a></div>
                <div class="top-important sports-menu seriaa"><a href="/<?=Configure::read('Config.language'); ?>/sports/1/4">Italian Serie A</a></div>
                <div class="top-important sports-menu seriaa"><a href="/<?=Configure::read('Config.language'); ?>/sports/1/13">Italian Serie B</a></div>
                <div class="top-important sports-menu league1"><a href="/<?=Configure::read('Config.language'); ?>/sports/1/2">French Ligue</a></div>
            </div>
        </div>
    </div>
    <?php if (isset($SportsMenu) && is_array($SportsMenu)): ?> 
    <ul class="sports-list">
        <?php foreach ($SportsMenu AS $i => $Sport): ?>
            <li>
                <a href="/<?=Configure::read('Config.language'); ?>/sports/<?=$Sport["Sport"]["id"];?>" class="icon-sport<?=str_replace(' ', '', $Sport["SportI18n"][0]["content"]);?> <?php if($SportId == $Sport["Sport"]["id"]): ?>active<?php endif;?>"><span class="right"></span><?php echo $Sport["Sport"]["name"]; ?><span class="clear"></span></a>
                <?php if($SportId == $Sport["Sport"]["id"]  && isset($LeaguesMenu) && is_array($LeaguesMenu) && !empty($LeaguesMenu)):?>
                    <div class="filter-box">
                        <?php foreach($LeaguesMenu["League"] AS $League): ?>
                            <div class="filter-con-a"><a <?php if($LeagueId == $League['id']): ?>class="active"<?php endif;?>  href="/<?=Configure::read('Config.language');?>/sports/<?=$LeaguesMenu['Sport']['id'];?>/<?=$League['id'];?>"><?=$League['name'];?></a></div>
                        <?php endforeach;?>
                    </div>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>
</div>

