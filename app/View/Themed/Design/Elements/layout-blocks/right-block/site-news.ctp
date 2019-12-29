<?php if(isset($getNews) AND is_array($getNews) AND !empty($getNews)): ?>
<h3><?php echo __("Site News"); ?></h3>
<ul class="news">
    <?php foreach ($getNews as $new): ?>
        <li>
            <div class="comm">
                <h6>
                    <a href="<?php echo $this->MyHtml->url(array('language' => Configure::read('Config.language'), 'plugin' => 'content', 'controller' => 'news', 'action' => 'view', $new['News']['id']), array('class' => 'rmore')); ?>">
                        <?php echo $new['News']['title']; ?>
                    </a>
                </h6>
                <a href="#" onclick="return false;" style="cursor: text;">
                    <p><?php echo $new['News']['summary']; ?></p>
                </a>
            </div>
            <div class="clear"></div>
        </li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>