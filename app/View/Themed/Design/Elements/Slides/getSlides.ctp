<?php //print_r($slides) ?>
<?php if (isset($slides) AND is_array($slides)): ?>
    <div class="slider-main">
        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <?php foreach ($slides AS $index => $slide): ?>
                    <li data-target="#carousel-example-generic" data-slide-to="<?= $index; ?>"
                        <?php if ($index == 0): ?>class="active"<?php endif; ?>></li>
                <?php endforeach; ?>
            </ol>
            <div class="carousel-inner" role="listbox">
                <?php foreach ($slides AS $index => $slide): ?>
                    <div class="item <?php if ($index == 0): ?>active<?php endif; ?>">
                        <a href="<?= Router::url(array('language' => Configure::read('Config.language'), 'plugin' => 'events', 'controller' => 'events', 'action' => 'display', $slide["Event"]["id"])); ?>">
                            <img src="<?= $this->Html->url('/theme/Design/img/uploads/'); ?><?php if (isset($slide["League"]["id"])): ?>inside-slider<?= $slide["League"]["id"]; ?><?php else: ?>inside-slider-default<?php endif; ?>.jpg"
                                 alt=""/>
                        </a>
                        <div class="carousel-caption">
                            <div class="slider-box">
                                <h1><?php echo strtoupper($slide["Event"]["name"]); ?></h1>
                                <div class="tstamp"><?php echo $this->TimeZone->convertDate($slide["Event"]['date'], 'j/n/Y H:i'); ?></div>
                                <table class="small-tbl">
                                    <tbody>
                                    <tr>
                                        <td class="l-corn"><?php echo $this->TimeZone->convertDate($slide["Event"]['date'], 'j/n/Y H:i'); ?></td>
                                        <?php if (isset($slide["Bet"][0]) && is_array($slide["Bet"][0])): ?>
                                            <?php foreach ($slide["Bet"][0]["BetPart"] AS $BetPart): ?>
                                                <td class="on-click addBet"
                                                    id="<?php echo $BetPart["BetPart"]['id']; ?>">
                                                    <span><?php echo $this->Beth->convertOdd($BetPart["BetPart"]["odd"]); ?></span>
                                                    <?php echo $BetPart["BetPart"]["name"]; ?>
                                                    <div class="clear"></div>
                                                </td>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                        <td class="r-corn"
                                            onclick="window.location.href='<?php echo Router::url(array('language' => Configure::read('Config.language'), 'plugin' => 'events', 'controller' => 'events', 'action' => 'display', $slide["Event"]["id"])); ?>'">
                                            +
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev"><span
                            class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span></a>
                <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next"><span
                            class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></a>
            </div>
        </div>
    </div>
<?php endif; ?>

<iframe src="https://www.scorebat.com/embed/" frameborder="0" width="600" height="760" allowfullscreen  allow="autoplay; fullscreen" style="width:600px;height:760px;overflow:hidden;display:block;" class="_scorebatEmbeddedPlayer_">
</iframe>
<script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = 'https://www.scorebat.com/embed/embed.js?v=m2to';
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'scorebat-jssdk'));

    window.onload = function(e){
        let head = jQuery("#iFrameResizer0").contents().find("head");
        console.log(head);
        var css = '<style type="text/css">' +
            '.EmbedCodeWrapper{display: none};' +
            '</style>';
        jQuery(head).append(css);
    };
</script>



<!--<iframe src='https://www.scorebat.com/embed/g/832498/' frameborder='0' width='560' height='590' allowfullscreen-->
<!--        allow='autoplay; fullscreen' style='width:560px;height:590px;overflow:hidden;display:block;'-->
<!--        class='_scorebatEmbeddedPlayer_'>-->
<!--</iframe>-->
<!--<script>(function (d, s, id) {-->
<!--        var js, fjs = d.getElementsByTagName(s)[0];-->
<!--        if (d.getElementById(id)) return;-->
<!--        js = d.createElement(s);-->
<!--        js.id = id;-->
<!--        js.src = 'https://www.scorebat.com/embed/embed.js?v=m2to';-->
<!--        fjs.parentNode.insertBefore(js, fjs);-->
<!--    }(document, 'script', 'scorebat-jssdk'));</script>-->

