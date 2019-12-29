<div class="row">
<?php
$staq = mysql_query("select player, winpot from " . DB_STATS . " order by winpot desc");
$i    = 1;
while (($star = mysql_fetch_array($staq)) && ($i < 9)) {
    $prefix = $i . PLACE_POSI;
    if ($i == 1)
        $prefix = $i . PLACE_POSI_1;
    if ($i == 2)
        $prefix = $i . PLACE_POSI_2;
    if ($i == 3)
        $prefix = $i . PLACE_POSI_3;
    $name = $star['player'];
    $win  = money_small($star['winpot']);
    $ava  = display_ava($name);
    echo '
  <div class="col-xs-6 col-md-3">
    <span class="thumbnail">
    ' . $ava . '<b>' . $prefix . ' ' . PLACE . '</b> ' . $name . '' . $win . '
        </span>
  </div>';
    $i++;
}
?>
</div>