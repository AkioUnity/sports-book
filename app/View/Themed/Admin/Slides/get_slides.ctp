<div id="cycle_slider">
    <?php foreach ($slides as $slide): ?>
        <div>
            <div class="cycle-slider-container">
                <div class="cycle-slider-bg">
                    <?php echo $slide['Slide']['description']; ?>
                </div>
                <div class="cycle-slider-text">
                    <?php echo $slide['Slide']['description']; ?>
                </div>
                <?php echo $this->Html->image('slides' . DS . $slide['Slide']['image'], array('url' => $this->MyHtml->customUrl($slide['Slide']['url']))); ?>                
            </div>
        </div>
    <?php endforeach; ?>
</div>