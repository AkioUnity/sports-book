
<!-- BEGIN OVERVIEW STATISTIC BLOCKS-->
<div class="row-fluid circle-state-overview">
    <div class="span2 responsive" data-tablet="span3" data-desktop="span2">
        <div class="circle-stat block">
            <div class="visual">
                <div class="circle-state-icon">
                    <a href="/admin/users">
                        <i class="icon-user gray-color"></i>
                    </a>
                </div>
                <input class="knob" data-width="100" data-height="100" data-displayPrevious=true  data-thickness=".2" value="100" data-fgColor="#b9baba" data-bgColor="#ddd">
            </div>
            <div class="details">
                <div class="number"><?php echo isset($usersCount) ? $usersCount : 0; ?></div>
                <div class="title"><?php echo __('Users'); ?></div>
            </div>
        </div>
    </div>
    <div class="span2 responsive" data-tablet="span3" data-desktop="span2">
        <div class="circle-stat block">
            <div class="visual">
                <div class="circle-state-icon">
                    <a href="/admin/tickets">
                        <i class="icon-tags gray-color"></i>
                    </a>
                </div>
                <input class="knob" data-width="100" data-height="100" data-displayPrevious=true  data-thickness=".2" value="100" data-fgColor="#b9baba" data-bgColor="#ddd"/>
            </div>
            <div class="details">
                <div class="number"><?php echo isset($ticketsCount) ? $ticketsCount : 0; ?></div>
                <div class="title"><?php echo __('Tickets'); ?></div>
            </div>

        </div>
    </div>


    <div class="span2 responsive" data-tablet="span3" data-desktop="span2">
        <div class="circle-stat block">
            <div class="visual">
                <div class="circle-state-icon">
                    <a href="/admin/deposits">
                        <i class="icon-shopping-cart gray-color"></i>
                    </a>
                </div>
                <input class="knob" data-width="100" data-height="100" data-displayPrevious=true  data-thickness=".2" value="100" data-fgColor="#b9baba" data-bgColor="#ddd"/>
            </div>
            <div class="details">
                <div class="number"><?php echo isset($depositsCount) ? $depositsCount : 0; ?></div>
                <div class="title"><?php echo __('Deposits'); ?></div>
            </div>

        </div>
    </div>

    <div class="span2 responsive" data-tablet="span3" data-desktop="span2">
        <div class="circle-stat block">
            <div class="visual">
                <div class="circle-state-icon">
                    <a href="/admin/withdraws">
                        <i class="icon-comments-alt gray-color"></i>
                    </a>
                </div>
                <input class="knob"  data-width="100" data-height="100" data-displayPrevious=true  data-thickness=".2" value="100"  data-fgColor="#b9baba" data-bgColor="#ddd"/>
            </div>
            <div class="details">
                <div class="number"><?php echo isset($withdrawsCount) ? $withdrawsCount : 0; ?></div>
                <div class="title"><?php echo __('Withdraws'); ?></div>
            </div>

        </div>
    </div>
    <div style="clear: both;"></div>
    <style type="text/css">
        .span2.responsive .visual a { text-decoration: none; }
    </style>
    <!-- END OVERVIEW STATISTIC BLOCKS-->