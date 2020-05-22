<!-- Vendor CSS -->
<link rel="stylesheet" href="/porto/vendor/bootstrap/css/bootstrap.min.css">

<link rel="stylesheet" href="/porto/vendor/owl.carousel/assets/owl.carousel.min.css">
<link rel="stylesheet" href="/porto/vendor/owl.carousel/assets/owl.theme.default.min.css">

<!-- Theme CSS -->
<link rel="stylesheet" href="/porto/css/theme-elements.css">
<!-- Skin CSS -->
<link rel="stylesheet" href="/porto/css/skins/default.css">

<div class="container py-2">

    <ul class="nav nav-pills sort-source sort-source-style-3 justify-content-center" data-sort-id="portfolio"
        data-option-key="filter" data-plugin-options="{'layoutMode': 'fitRows', 'filter': '*'}">
<!--        <li class="nav-item active" data-option-value="*"><a class="nav-link text-1 text-uppercase active"-->
<!--                                                             href="/porto/#">Show-->
<!--                All</a></li>-->
        <?php foreach ($brands as $value) { ?>
            <li class="nav-item" data-option-value=".<?php echo $value?>"><a class="nav-link text-1 text-uppercase"
                                                                  href="/porto/#"><?php echo $value?></a></li>
        <?php } ?>
    </ul>

    <div class="sort-destination-loader sort-destination-loader-showing mt-4 pt-2">
        <div class="row portfolio-list sort-destination" data-sort-id="portfolio">
            <?php foreach ($games as $value) { ?>
                <div class="col-sm-6 col-lg-3 isotope-item <?php echo $value['SectionId']?>">
                    <div class="portfolio-item">
                        <a href="content/createSession?GameId=<?php echo $value['Id']; ?>" target="_blank">
                            <span class="thumb-info thumb-info-lighten border-radius-0">
                                <span class="thumb-info-wrapper border-radius-0">
                                    <img src="/Casino/games/<?php echo $value['Id'] ?>.jpg"
                                         class="img-fluid border-radius-0"
                                         alt="">
<!--                                    <span class="thumb-info-title">-->
<!--                                        <span class="thumb-info-inner">Presentation</span>-->
<!--                                        <span class="thumb-info-type">Brand</span>-->
<!--                                    </span>-->
<!--                                    <span class="thumb-info-action">-->
<!--                                        <span class="thumb-info-action-icon bg-dark opacity-8"><i-->
<!--                                                    class="fas fa-plus"></i></span>-->
<!--                                    </span>-->
                                </span>
                            </span>
                        </a>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

</div>

<!-- Vendor -->
<script src="/porto/vendor/jquery/jquery.min.js"></script>

<script src="/porto/vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="/porto/vendor/common/common.min.js"></script>


<script src="/porto/vendor/isotope/jquery.isotope.min.js"></script>
<script src="/porto/vendor/owl.carousel/owl.carousel.min.js"></script>

<!-- Theme Base, Components and Settings -->
<script src="/porto/js/theme.js"></script>
<!-- Theme Initialization Files -->
<script src="/porto/js/theme.init.js"></script>

