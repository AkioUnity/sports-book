<div id="user-info">

    <div class="search">
        <table>
            <tr>
                <td>
                    <?php echo $this->MyForm->create('Ticket', array('url' => array('plugin' => false, 'action' => 'admin_search'))); ?>
                    <?php echo __('Ticket ID'); ?>
                </td>
                <td>
                    <?php echo $this->MyForm->input('id', array('type' => 'text', 'label' => false)); ?>
                </td>
                <td class="va0">
                    <input type="submit" onClick="showTicket(); return false" value="Search" />
                    <?php echo $this->MyForm->end(); ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?php echo $this->MyForm->create('Event', array('url' => array('plugin' => false, 'action' => 'admin_view'))); ?>
                    <?php echo __('Event ID'); ?>
                </td>
                <td><?php echo $this->MyForm->input('id', array('type' => 'text', 'label' => false)); ?></td>
                <td class="va0">
                    <input type="submit" value="Search" />
                    <?php echo $this->MyForm->end(); ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?php echo $this->MyForm->create('Event', array('url' => array('plugin' => false, 'action' => 'admin_search'))); ?>
                    <?php echo __('Event name'); ?>
                </td>
                <td><?php echo $this->MyForm->input('name', array('type' => 'text', 'label' => false)); ?></td>
                <td class="va0">
                    <input type="submit" value="Search" />
                    <?php echo $this->MyForm->end(); ?>
                </td>
            </tr>
        </table>
    </div>
    <div class="info">
        <table>
            <tr>
                <td><?php echo __('User:'); ?></td>
                <td></td>
            </tr>
            <tr>
                <td><?php echo __('Level:'); ?></td>
                <td><?php echo $this->Session->read('Auth.User.Group.name'); ?></td>
            </tr>
            <tr>
                <td><?php echo __('Current Time:'); ?></td>
                <td><span class="jclock"></span></td>
            </tr>
            <tr>
                <td><?php echo __('Current Language:'); ?></td>
                <td><?php echo Configure::read('Auth.User.language'); ?></td>
            </tr>
            <tr>
                <td class="actions" colspan="2">
                    <?php echo $this->MyHtml->link(__('Front Page', true), '/'); ?>
                    |

                    |

                </td>
            </tr>

        </table>
    </div>

</div>
