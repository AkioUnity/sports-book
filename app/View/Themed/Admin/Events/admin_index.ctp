<div class="container-fluid">
    <!-- BEGIN PAGE HEADER-->
    <div class="row-fluid">
        <div class="span12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <?php  echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => $this->Admin->getSingularName(), 2 => __('List %s', $this->Admin->getPluralName()))))); ?>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div id="page" class="dashboard">
        <?php echo $this->element('flash_message'); ?>
        <div class="row-fluid ">
            <div class="span12">
                <!-- BEGIN INLINE TABS PORTLET-->
                <div class="widget">
                    <div class="widget-body">
                        <?php echo $this->element('search');?>
                        <div class="row-fluid">
                            <div class="span12">
                                <!--BEGIN TABS-->
                                <div class="table table-custom">
                                    <?php echo $this->element('tabs');?>
                                    <div class="tab-content">
                                        <?php if (!empty($data)): ?>
                                            <div class="row-fluid">
                                                <div class="span12">
                                                    <!-- BEGIN TABLE widget-->
                                                    <div class="widget">
                                                        <div class="widget-title">
                                                            <h4>
                                                                <i class="icon-reorder"></i>
                                                                <?php if(isset($title)): ?>
                                                                    <?php echo $title; ?>
                                                                <?php else: ?>
                                                                    <?php echo __('Data list'); ?>
                                                                <?php endif; ?>
                                                            </h4>

                                                        </div>
                                                        <div class="widget-body">
                                                            <table class="table table-hover">
                                                                <thead>
                                                                <tr>
                                                                    <?php
                                                                    $model = array_keys($data[0]);
                                                                    $model = $model[0];
                                                                    $titles = $data[0][$model];
                                                                    foreach ($titles as $title => $value):
                                                                        if (($title != 'locale')):
                                                                            ?>
                                                                            <th>
                                                                                <?php echo $this->Paginator->sort($title); ?>
                                                                            </th>
                                                                            <?php
                                                                        endif;
                                                                    endforeach;
                                                                    ?>

                                                                    <?php if(isset($actions) AND is_array($actions) AND !empty($actions)): ?>
                                                                        <th>
                                                                            <?php echo __('Actions'); ?>
                                                                        </th>
                                                                    <?php endif; ?>

                                                                    <?php if (isset($translate) AND $translate == true): ?>
                                                                        <th><?php echo __('Translations'); ?></th>
                                                                    <?php endif; ?>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <?php
                                                                $i = 1;
                                                                foreach ($data as $field):
                                                                    $class = null;
                                                                    if ($i++ % 2 == 0) {
                                                                        $class = ' alt';
                                                                    }
                                                                    echo "<tr>";

                                                                    $k = 0;
//                                                                    var_dump($field[$model]); exit;
                                                                    foreach ($field[$model] as $key => $var) {
                                                                        if ($key != 'locale') {
                                                                            $t = $var; // $this->Text->truncate(strip_tags($var), 100, array('ending' => '...', 'exact' => false));

                                                                            echo "<td class=\"{$class}\">";
                                                                            if ($key == 'order') {
                                                                                ?>
                                                                                <a href="<?php echo $this->Html->url(array('action' => 'moveUp', $field[$model]['id']));?>">
                                                                                    <i class="icon-arrow-up"></i>
                                                                                </a>
                                                                                <a href="<?php echo $this->Html->url(array('action' => 'moveDown', $field[$model]['id']));?>">
                                                                                    <i class="icon-arrow-down"></i>
                                                                                </a>
                                                                                <?php
                                                                            } else {
                                                                                if($key == 'amount' OR $key == 'return' OR $key == 'balance') {
                                                                                    $t = __("%s %s", number_format((float)$t, intval(Configure::read('Settings.balance_decimal_places')), '.', ''), Configure::read('Settings.currency'));
                                                                                }

                                                                                if($key == 'service_fee') {
                                                                                    $t = $t . ' ' . '%';
                                                                                }
                                                                                if($key == 'min_bet' || $key == 'max_bet') {
                                                                                    $t = $t > 0 ? $t : '-';
                                                                                }
                                                                                if($key == 'active') {
                                                                                    $t = $t == 0 ? __('No') : __('Yes');
                                                                                }
                                                                                if($key == 'user_id' && isset($field["User"]["username"])) {
                                                                                    $t = $field["User"]["username"];
                                                                                }
                                                                                if($key=="feed_type") {
                                                                                    $t = 'Source';
                                                                                }
                                                                                echo $t;
                                                                            }
                                                                            echo "</td>";
                                                                        }
                                                                        $k++;
                                                                    }

                                                                    if(isset($actions) AND is_array($actions) AND !empty($actions)) {
                                                                        echo "<td style='min-width: 190px;' class=\"actions {$class}\">\n";
                                                                        foreach ($actions AS $action) {
                                                                            $showAction = true;
                                                                            if(isset($action['conditions'])) {
                                                                                foreach($action['conditions'] AS $ConditionKey => $conditionValue) {
                                                                                    if(!is_array($conditionValue)) {
                                                                                        $conditionValue = array($conditionValue);
                                                                                    }

                                                                                    $conditionField = explode('.', $ConditionKey);
                                                                                    if(count($conditionField) != 2) { continue; }

                                                                                    if(isset($field[$conditionField[0]]) && isset($field[$conditionField[0]][$conditionField[1]])) {
                                                                                        if( !in_array($field[$conditionField[0]][$conditionField[1]], $conditionValue, true) ) {
                                                                                            $showAction = false;
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }

                                                                            if($showAction == false) { continue; }

                                                                            if ($action['action'] == 'admin_delete' || $action['action'] == 'admin_cancel' || $action['action'] == 'admin_complete') {
                                                                                $delete = __('Are you sure?');
                                                                            } else {
                                                                                $delete = null;
                                                                            }

                                                                            if (isset($action['controller'])) {
                                                                                $controller = $action['controller'];
                                                                            } else {
                                                                                $controller = $this->params['controller'];
                                                                            }

                                                                            if (isset($action['admin'])) {
                                                                                $admin = $action['admin'];
                                                                            } else {
                                                                                $admin = 'admin';
                                                                            }

                                                                            if (isset($action['plugin'])) {
                                                                                $plugin = $action['plugin'];
                                                                            } else {
                                                                                $plugin = null;
                                                                            }

                                                                            echo $this->MyHtml->link($action['name'], array('admin' => $admin, 'plugin' => $plugin, 'controller' => $controller, 'action' => $action['action'], $field[$model]['id']), array('class' => isset($action['class']) ? $action['class'] : ''), $delete);

                                                                            echo ' ';
                                                                        }
                                                                        if (isset($translate) AND $translate == true) {
                                                                            echo $this->MyHtml->link(__('Translate', true), array('action' => 'translate', $field[$model]['id']), array('class' => "btn btn-mini btn-inverse"));
                                                                        }
                                                                        echo "</td>";
                                                                    }

                                                                    if (isset($translate) AND $translate == true AND isset($locales)) {
                                                                        echo "<td class=\"actions {$class}\">";
                                                                        foreach ($locales as $locale => $title) {
                                                                            if ($locale != Configure::read('Config.language')) {
                                                                                echo $this->MyHtml->link(__($title), array('action' => 'translate', $field[$model]['id'], $locale));
                                                                                echo ' ';
                                                                            }
                                                                        }
                                                                        echo "</td>";
                                                                    }
                                                                    ?>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                                <tr>
                                                                    <?php foreach($field[$model] AS $key => $var): ?>
                                                                        <td>
                                                                            <?php if($key == 'amount' OR $key == 'return' OR $key == 'balance'): ?>
                                                                                <?php if(isset(${'total' . ucfirst($key)})): ?>
                                                                                    <?php echo __("Total: %s %s", number_format((float)${'total' . ucfirst($key)}, intval(Configure::read('Settings.balance_decimal_places')), '.', ''), Configure::read('Settings.currency')); ?>
                                                                                <?php endif; ?>
                                                                            <?php endif; ?>
                                                                        </td>
                                                                    <?php endforeach; ?>
                                                                    <?php if(isset($actions) AND is_array($actions) AND !empty($actions)): ?>
                                                                        <td></td>
                                                                    <?php endif; ?>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <!-- END TABLE widget-->
                                                </div>
                                            </div>
                                            <?php echo $this->element('paginator'); ?>

                                        <?php else: ?>
                                            <p><?php echo __('No records found'); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <!--END TABS-->
                            </div>
                            <div class="space10 visible-phone"></div>
                        </div>
                    </div>
                </div>
                <!-- END INLINE TABS PORTLET-->
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>

