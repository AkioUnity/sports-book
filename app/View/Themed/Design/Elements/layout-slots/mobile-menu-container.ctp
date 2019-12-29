	<div id="mobile-view">

        <ul class="tabs">
            <li class="tab-link current" data-tab="tab-1"><?php echo __('Soccer');?></li>
           <li>
                <a href="/<?=Configure::read('Config.language');?>/live-betting">
                    
                    <?php echo __(' Live Odds'); ?>
                </a>
            </li>
            
            <li class="tab-link" data-tab="tab-2"><?php echo __('Sports');?>
            </li>
            <li class="tab-link" data-tab="tab-3"><?php echo __('Casino');?></li>
            <li>
                <a href="/<?=Configure::read('Config.language');?>/contact">
                    
                    <?php echo __(' Support'); ?>
                </a>
            </li>
        </ul>
            
            

        <div id="tab-1" class="tab-content current">
            <div id="Mobile-sports-menu" class="mbox">
                <?php echo $this->element('layout-slots/primier-div-sports'); ?>
            </div>
        </div>
        <div id="tab-2" class="tab-content">
            <div id="Mobile-sports-menu" class="mbox">
            <?php echo $this->element('layout-blocks/left-block/sports-menu'); ?>
            </div>
        </div>
        <div id="tab-3" class="tab-content">
            <ul id="navbarSupportedContent">

                <?php if($this->MyHtml->checkAcl(array('plugin' => 'casino', 'controller' => 'content', 'action' => 'index'))): ?>
                <li>
                    <a href="/<?=Configure::read('Config.language');?>/casino/content">
                        
                        <?php echo __(' Casino'); ?>
                    </a>
                </li>
                <?php endif; ?>
                
                <li>
                    <a href="/<?=Configure::read('Config.language');?>/livecasino">
                        
                        <?php echo __(' Live casino'); ?>
                    </a>
                </li>
                
                <li>
                    <a href="#">
                        
                        <?php echo __(' Virtual Sports'); ?>
                    </a>
                </li>
                <li>
                    <a href="#">
                        
                        <?php echo __(' Keno'); ?>
                    </a>
                </li>
                 <li>
                    <a href="#">
                        
                        <?php echo __(' Bingo'); ?>
                    </a>
                </li>
                
                <?php if($this->MyHtml->checkAcl(array('plugin' => 'poker', 'controller' => 'pokerlogs', 'action' => 'index'))): ?>
                <li>
                    <a href="/poker/index.php">
                        <span class="menutxt"><?php echo __(' Poker'); ?></span>
                    </a>
                </li>
                <?php endif; ?>
                
                
            </ul>
        </div>




		<!-- Start Language -->
        <?php $Languages = $this->Language->getLanguages(); ?>
            <nav id="lang_nav">
                <?php if(isset($Languages) AND is_array($Languages) AND !empty($Languages)): ?>
                <div class="dropbtn">
                    <a href="/<?php echo $this->Language->getLanguage(); ?>"><img src="/theme/Design/img/flags/<?php echo $this->Language->getLanguage2Img($this->Language->getLanguage()) ; ?>.png" alt="<?php echo $this->Language->getLanguage(); ?>">
                    <span> <?php echo $this->Language->getLanguage(); ?></span>
                    <span><i class="fas fa-caret-down"></i></span></a>
                    <div class="dropup-content">
                    <?php foreach($Languages AS $Language): ?>
                        <?php if($Language['name'] != $this->Language->getLanguage()): ?>
                            <a href="/<?php echo $Language['name']; ?>"><img src="/theme/Design/img/flags/<?php echo $this->Language->getLanguage2Img($Language['name']); ?>.png" alt="">
    
                                <span><?php echo  $Language['name'];?></span>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    </div>
                </div>
                
                <?php endif; ?>
            </nav>
        <div class="clear"></div>
	</div>