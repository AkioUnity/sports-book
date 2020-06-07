<!-- Vendor CSS -->
<link rel="stylesheet" href="/porto/vendor/bootstrap/css/bootstrap.min.css">

<ul class="nav justify-content-center">
    <?php foreach ($brands as $value) { ?>
        <a class="nav-link text-uppercase"
           href="/<?= Configure::read('Config.language'); ?>/casino/content?brand=<?php echo $value ?>"><?php echo $value ?>
        </a>

    <?php } ?>
</ul>

<div class="container">
    <div class="row">
        <?php foreach ($games as $value) { ?>
            <div class="col-md-4 col-sm-6 col-xl-3">
                <a href="content/createSession?GameId=<?php echo $value['Id']; ?>" target="_blank">
                    <img src="/Casino/games/<?php echo $value['Id'] ?>.jpg" alt=""
                         style="width: 100%;padding-top: 20px">
                </a>
            </div>
        <?php } ?>
    </div>
</div>
