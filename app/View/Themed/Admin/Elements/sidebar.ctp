<?php
$menu = array(

    -1  =>  array(
        'title'     =>  __('Dashboard'),
        'class'     =>  'icon-book',
        'sub-menu'  =>  array(
            0   =>  array(
                'title'         =>  __('Tickets'),
                'plugin'        =>  null,
                'controller'    =>  'dashboard',
                'action'        =>  'admin_tickets'
            )
        )
    ),

    0   =>  array(
        'title'     =>  __('Content'),
        'class'     =>  'icon-book',
        'sub-menu'  =>  array(
 /*           0   =>  array(
                'title'         =>  __('News management'),
                'plugin'        =>  'content',
                'controller'    =>  'news',
                'action'        =>  'admin_index'
            ), 
*/
            1   =>  array(
                'title'         =>  __('Content management'),
                'plugin'        =>  'content',
                'controller'    =>  'pages',
                'action'        =>  'admin_index'
            ),
/* 
            2   =>  array(
                'title'         =>  __('Slider management'),
                'plugin'        =>  'content',
                'controller'    =>  'slides',
                'action'        =>  'admin_index'
            ),
*/
            3   =>  array(
                'title'         =>  __('Menus management'),
                'plugin'        =>  'content',
                'controller'    =>  'menus',
                'action'        =>  'admin_index'
            ),
/*
            5   =>  array(
                'title'         =>  __('Sidebars'),
                'plugin'        =>  null,
                'controller'    =>  'settings',
                'action'        =>  'admin_promo'
            )
*/
        )
    ),

    1   =>  array(
        'title'     =>  __('Users'),
        'class'     =>  'icon-user',
        'sub-menu'  =>  array(
            0   =>  array(
                'title'         =>  __('Users'),
                'plugin'        =>  null,
                'controller'    =>  'users',
                'action'        =>  'admin_index'
            ),

            1  =>  array(
                'title'         =>  __('Staff'),
                'plugin'        =>  null,
                'controller'    =>  'staffs',
                'action'        =>  'admin_index'
            ),

            2   =>  array(
                'title'         =>  __('Create user'),
                'plugin'        =>  null,
                'controller'    =>  'users',
                'action'        =>  'admin_add'
            ),

            3   =>  array(
                'title'         =>  __('Create staff'),
                'plugin'        =>  null,
                'controller'    =>  'staffs',
                'action'        =>  'admin_add'
            )
        )
    ),

    2   =>  array(
        'title'     =>  __('Live Events'),
        'class'     =>  'icon-bolt',
        'sub-menu'  =>  array(

            0   =>  array(
                'title'         =>  __('Live Events'),
                'plugin'        =>  null,
                'controller'    =>  'live',
                'action'        =>  'admin_index'
            )
        )
    ),

    3   =>  array(
        'title'     =>  __('Prematch Events'),
        'class'     =>  'icon-bolt',
        'sub-menu'  =>  array(
/*
            0   =>  array(
                'title'         =>  __('Countries'),
                'plugin'        =>  null,
                'controller'    =>  'countries',
                'action'        =>  'admin_index'
            ),
*/
            1   =>  array(
                'title'         =>  __('Prematch events'),
                'plugin'        =>  'events',
                'controller'    =>  'events',
                'action'        =>  'admin_index'
            ),
			
            2   =>  array(
                'title'         =>  __('Sports and Leagues'),
                'plugin'        =>  null,
                'controller'    =>  'sports',
                'action'        =>  'admin_index'
            ),

            3   =>  array(
                'title'         =>  __('Events Printing'),
                'plugin'        =>  'events',
                'controller'    =>  'events',
                'action'        =>  'admin_print'
            ),
			4   =>  array(
                'title'         =>  __('Results'),
                'plugin'        =>  null,
                'controller'    =>  'results',
                'action'        =>  'admin_allSports'
            )
        )
    ),


    4   =>  array(
        'title'     =>  __('Tickets'),
        'class'     =>  'icon-file-alt',
        'sub-menu'  =>  array(
            0   =>  array(
                'title'         =>  __('Tickets'),
                'plugin'        =>  null,
                'controller'    =>  'tickets',
                'action'        =>  'admin_index'
            ),
            1   =>  array(
                'title'         =>  __('Reservation Tickets'),
                'plugin'        =>  null,
                'controller'    =>  'ReservationTickets',
                'action'        =>  'admin_index'
            )
        )
    ),

    5   =>  array(
        'title'     =>  __('Finance'),
        'class'     =>  'icon-money',
        'sub-menu'  =>  array(
            0   =>  array(
                'title'         =>  __('Deposits'),
                'plugin'        =>  null,
                'controller'    =>  'deposits',
                'action'        =>  'admin_index'
            ),

            1   =>  array(
                'title'         =>  __('Withdrawals'),
                'plugin'        =>  null,
                'controller'    =>  'withdraws',
                'action'        =>  'admin_index'
            ),
//            1   =>  array(
//                'title'         =>  'Payment gateways',
//                'plugin'        =>  null,
//                'controller'    =>  'settings',
//                'action'        =>  'admin_deposits'
//            ),

            2   =>  array(
                'title'         =>  __('Deposit settings'),
                'plugin'        =>  null,
                'controller'    =>  'settings',
                'action'        =>  'admin_depositsRisks'
            ),
            3   =>  array(
                'title'         =>  __('Withdraw settings'),
                'plugin'        =>  null,
                'controller'    =>  'settings',
                'action'        =>  'admin_withdrawsRisks'
            )
        )
    ),
	
	6   =>  array(
        'title'     =>  __('Casino'),
        'class'     =>  'icon-play-circle',
        'sub-menu'  =>  array(
            0   =>  array(
                'title'         =>  __('Settings'),
                'plugin'        =>  'casino',
                'controller'    =>  'GameSettings',
                'action'        =>  'admin_highlow',
            ),
            1   =>  array(
                'title'         =>  __('Logs'),
                'plugin'        =>  'casino',
                'controller'    =>  'GameLogs',
                'action'        =>  'admin_highlow'
            )
        )
    ),
    7  =>  array(
            'title'     =>  __('Poker'),
            'class'     =>  'icon-play-circle',
            'sub-menu'  =>  array(
                0   =>  array(
                    'title'         =>  __('Settings'),
                    'plugin'        =>  'poker',
                    'controller'    =>  'PokerSettings',
                    'action'        =>  'admin_settings',
                ),
                1   =>  array(
                    'title'         =>  __('Tables'),
                    'plugin'        =>  'poker',
                    'controller'    =>  'PokerTables',
                    'action'        =>  'admin_tables'
                )
            )
        ),
    8  =>  array(
        'title'     =>  __('Settings'),
        'class'     =>  'icon-wrench',
        'sub-menu'  =>  array(
            0   =>  array(
                'title'         =>  __('General settings'),
                'plugin'        =>  null,
                'controller'    =>  'settings',
                'action'        =>  'admin_index'
            ),

            1   =>  array(
                'title'         =>  __('Ticket settings'),
                'plugin'        =>  null,
                'controller'    =>  'settings',
                'action'        =>  'admin_tickets'
            ),
/*
            2   =>  array(
                'title'         =>  __('Currencies'),
                'plugin'        =>  null,
                'controller'    =>  'currencies',
                'action'        =>  'admin_index'
            ),

            3   =>  array(
                'title'         =>  __('SEO settings'),
                'plugin'        =>  null,
                'controller'    =>  'settings',
                'action'        =>  'admin_seo'
            ),
*/
            4   =>  array(
                'title'         =>  __('Email templates'),
                'plugin'        =>  null,
                'controller'    =>  'templates',
                'action'        =>  'admin_index'
            ) 
        )
    ),

    9  =>  array(
        'title'     =>  __('Risk management'),
        'class'     =>  'icon-fire',
        'sub-menu'  =>  array(
            0   =>  array(
                'title'         =>  __('General Settings'),
                'plugin'        =>  null,
                'controller'    =>  'risks',
                'action'        =>  'admin_index'
            ),

            1   =>  array(
                'title'         =>  __('Limits for Sports'),
                'plugin'        =>  null,
                'controller'    =>  'risks',
                'action'        =>  'admin_sports'
            ),

            2   =>  array(
                'title'         =>  __('Limits for Leagues'),
                'plugin'        =>  null,
                'controller'    =>  'risks',
                'action'        =>  'admin_leagues'
            )
        )
    ),

    10  =>  array(
        'title'     =>  __('Marketing'),
        'class'     =>  'icon-comment-alt',
        'sub-menu'  =>  array(
            0   =>  array(
                'title'         =>  __('Create bonus code'),
                'plugin'        =>  null,
                'controller'    =>  'bonusCodes',
                'action'        =>  'admin_index'
            ),
/*
            1   =>  array(
                'title'         =>  __('Promotion letter'),
                'plugin'        =>  null,
                'controller'    =>  'mails',
                'action'        =>  'admin_index'
            )
			
*/
        )
    ),
/*
    11  =>  array(
        'title'     =>  __('Warnings'),
        'class'     =>  'icon-warning-sign',
        'sub-menu'  =>  array(
            0   =>  array(
                'title'         =>  __('List warnings'),
                'plugin'        =>  null,
                'controller'    =>  'risks',
                'action'        =>  'admin_warnings'
            ),

            1   =>  array(
                'title'         =>  __('Warnings settings'),
                'plugin'        =>  null,
                'controller'    =>  'settings',
                'action'        =>  'admin_warnings'
            )
        )
    ),
*/
    12  =>  array(
        'title'     =>  'Reports',
        'class'     =>  'icon-paper-clip',
        'sub-menu'  =>  array(
            0   =>  array(
                'title'         =>  __('Users report'),
                'plugin'        =>  'reports',
                'controller'    =>  'reports',
                'action'        =>  'admin_users',
            ),

            1   =>  array(
                'title'         =>  __('Tickets report'),
                'plugin'        =>  'reports',
                'controller'    =>  'reports',
                'action'        =>  'admin_tickets',
            ),

            2   =>  array(
                'title'         =>  __('Deposits report'),
                'plugin'        =>  'reports',
                'controller'    =>  'reports',
                'action'        =>  'admin_deposits'
            ),

            3   =>  array(
                'title'         =>  __('Cashier report'),
                'plugin'        =>  'reports',
                'controller'    =>  'reports',
                'action'        =>  'admin_cashier_daily'
            ),

            4   =>  array(
                'title'         =>  __('Operator report'),
                'plugin'        =>  'reports',
                'controller'    =>  'reports',
                'action'        =>  'admin_operator_daily'
            ),

            5   =>  array(
                'title'         =>  __('Sportsbook report'),
                'plugin'        =>  'reports',
                'controller'    =>  'reports',
                'action'        =>  'admin_sportsbook_daily'
            ),

            6   =>  array(
                'title'         =>  __('Admin financial report'),
                'plugin'        =>  'reports',
                'controller'    =>  'reports',
                'action'        =>  'admin_financial_report'
            ),

            7   =>  array(
                'title'         =>  __('Staff report'),
                'plugin'        =>  'reports',
                'controller'    =>  'reports',
                'action'        =>  'admin_staff'
            ),

            8  =>  array(
                'title'         =>  __('User report'),
                'plugin'        =>  'reports',
                'controller'    =>  'reports',
                'action'        =>  'admin_user'
            ),

            9  =>  array(
                'title'         =>  __('Agent report'),
                'plugin'        =>  'reports',
                'controller'    =>  'reports',
                'action'        =>  'admin_agent'
            ),

            10 =>  array(
                'title'         =>  __('Agent report'),
                'plugin'        =>  'reports',
                'controller'    =>  'reports',
                'action'        =>  'admin_agents'
            )
        )
    )
);

if (!Configure::read('Settings.reservation_ticket_mode')) {
    unset($menu[5]["sub-menu"][1]);
}
?>

<div id="sidebar" class="nav-collapse collapse">

    <div class="sidebar-toggler hidden-phone"></div>

    <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
    <!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->
    <div class="navbar-inverse">

    </div>
    <!-- END RESPONSIVE QUICK SEARCH FORM -->

    <!-- BEGIN SIDEBAR MENU -->
	
    <ul class="sidebar-menu loading">
        <?php if(isset($menu) AND is_array($menu)): ?>
            <?php foreach($menu AS $menuData): ?>
                <li<?php if(isset($menuData['sub-menu']) AND is_array($menuData['sub-menu'])):?> class="has-sub hidden" <?php else: ?> class="hidden" <?php endif; ?>>
                    <a href="javascript:;" class="">
                        <span class="icon-box"><i class="<?php echo $menuData['class']?>"></i></span>
                        <?php echo $menuData['title']; ?>
                        <span class="arrow"></span>
                    </a>
                    <?php if(isset($menuData['sub-menu']) AND is_array($menuData['sub-menu'])):?>
                        <ul class="sub">
                            <?php foreach($menuData['sub-menu'] AS $subMenu): ?>
                                <?php if(!isset($subMenu['acl-allow'])): ?>
                                    <?php if($this->MyHtml->checkAcl(array('plugin' => $subMenu['plugin'], 'controller' => $subMenu['controller'], 'action' => $subMenu['action'])) !== false): ?>
                                        <li><?php echo $this->MyHtml->link($subMenu['title'], array('plugin' => $subMenu['plugin'], 'controller' => $subMenu['controller'], 'action' => $subMenu['action'])); ?></li>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <li><?php echo $this->MyHtml->link($subMenu['title'], array('plugin' => $subMenu['plugin'], 'controller' => $subMenu['controller'], 'action' => $subMenu['action'])); ?></li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>

    <script type="text/javascript">
        $(function() {
            var container = $('.sidebar-menu');
            container.find('a[href="<?php echo $this->request->here;?>"]').parent().parent().toggle('open');
            $('.sidebar-menu ul').each(function() {
                if($(this).find('ul li').context.childElementCount == 0) {
                    $(this).parent().css({'display' : 'none'});
                }else{

                }
            });
            container.removeClass('loading');
            container.find('li').removeClass('hidden');
        }());
    </script><br><br>

    <!-- END SIDEBAR MENU -->
</div>