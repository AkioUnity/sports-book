<?php
$configuration_query = mysql_query("select Xkey as cfgKey, Xvalue as cfgValue from " . DB_SETTINGS);
while ($configuration = mysql_fetch_array($configuration_query)) {
    define($configuration['cfgKey'], stripslashes($configuration['cfgValue']));
}
$smallbetfunc = 0;
if (STAKESIZE == 'tiny')
    $smallbetfunc = 1;
if (STAKESIZE == 'low')
    $smallbetfunc = 2;
if (STAKESIZE == 'med')
    $smallbetfunc = 3;
?>