<div class="container-fluid">
    <!-- BEGIN PAGE HEADER-->
    <div class="row-fluid">
        <div class="span12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <?php  echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => $this->Admin->getSingularName())))); ?>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div id="page" class="dashboard">

                <div class="alert alert-info">
                    <button class="close" data-dismiss="info">�</button>
                    <strong>Info!</strong> Dashboard interactive statistics and charts are not completed yet. Developement of this section is in progress. For more information please contact project manager.
                </div>
				<!-- BEGIN SQUARE STATISTIC BLOCKS
                    <div class="square-state">
                        <div class="row-fluid">
                            <a class="icon-btn span2" href="#">
                                <i class="icon-user"></i>
                                <div>Users</div>
                                <span class="badge badge-success"><?php echo isset($usersCount) ? $usersCount : 0; ?></span>
                            </a>
                            <a class="icon-btn span2" href="#">
                                <i class="icon-file-alt"></i>
                                <div>Tickets</div>
                                <span class="badge badge-success"><?php echo isset($ticketsCount) ? $ticketsCount : 0; ?></span>
                            </a>
                            <a class="icon-btn span2" href="#">
                                <i class="icon-money"></i>
                                <div>Deposits</div>
								<span class="badge badge-success"><?php echo isset($depositsCount) ? $depositsCount : 0; ?></span>
                            </a>
                            <a class="icon-btn span2" href="#">
                                <i class="icon-money"></i>
                                <div>Withdraws</div>
								<span class="badge badge-success"><?php echo isset($withdrawsCount) ? $withdrawsCount : 0; ?></span>
                            </a>
                            
                        </div>
                    </div>
                <END SQUARE STATISTIC BLOCKS-->
					
					
					
					
			<!-- START USERS-->
					<div class="row-fluid">
					<div class="span4">
                            <div class="widget">
                                <div class="widget-title">
                                    <h4><i class="icon-user"></i> TODAY's USER STATISTICS</h4>
									<span class="tools">
									<a href="javascript:;" class="icon-chevron-down"></a>
									</span>
                                </div>
                                <div class="widget-body">
                                    <table class="table table-striped">
                                        <tbody>
                                        <tr>
                                            <td>New users:</td>
                                            <td><strong>number</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Logged users</td>
                                            <td><strong>number</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Active users:</td>
                                            <td><strong>number</strong></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
						<div class="span8">
							<div class="widget">
								<div class="widget-title">
									<h4><i class="icon-bar-chart"></i> USERS CHART FOR PREVIOUS MONTH</h4>
									<span class="tools">
									<a href="javascript:;" class="icon-chevron-down"></a>
									</span>
								</div>
								<div class="widget-body">
									<div id="site_statistics_loading">
										<img src="/theme/Admin/img/chart.png" alt="chart" />
									</div>
									<div id="site_statistics_content" class="hide">
										<div id="site_statistics" class="chart"></div>
									</div>
								</div>
							</div>	
						</div>
                    </div>
			<!-- END USERS -->
					
			<!-- START BETTING-->
					<div class="row-fluid">
					<div class="span4">
                            <div class="widget">
                                <div class="widget-title">
                                    <h4><i class="icon-file-alt"></i> TODAY's BETTING STATISTICS</h4>
									<span class="tools">
									<a href="javascript:;" class="icon-chevron-down"></a>
									</span>
                                </div>
                                <div class="widget-body">
                                    <table class="table table-striped">
                                        <tbody>
                                        <tr>
                                            <td>Ticket placed:</td>
                                            <td><strong>number</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Lost tickets:</td>
                                            <td><strong>number</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Won tickets:</td>
                                            <td><strong>number</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Pending tickets:</td>
                                            <td><strong>number</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Cancelled tickets:</td>
                                            <td><strong>number</strong></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
						<div class="span8">
							<div class="widget">
								<div class="widget-title">
									<h4><i class="icon-bar-chart"></i> BETTING CHART FOR PREVIOUS MONTH</h4>
									<span class="tools">
									<a href="javascript:;" class="icon-chevron-down"></a>
									</span>
								</div>
								<div class="widget-body">
									<div id="site_statistics_loading">
										<img src="/theme/Admin/img/chart.png" alt="chart" />
									</div>
									<div id="site_statistics_content" class="hide">
										<div id="site_statistics" class="chart"></div>
									</div>
								</div>
							</div>	
						</div>
                    </div>
			<!-- END BETTING -->
					
			<!-- START FINANCE-->
					<div class="row-fluid">
					<div class="span4">
                            <div class="widget">
                                <div class="widget-title">
                                    <h4><i class="icon-file-alt"></i> TODAY's FINANCIAL STATISTICS</h4>
									<span class="tools">
									<a href="javascript:;" class="icon-chevron-down"></a>
									</span>
                                </div>
                                <div class="widget-body">
                                    <table class="table table-striped">
                                        <tbody>
                                        <tr>
                                            <td>Total deposits:</td>
                                            <td><strong>number+currency</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Pending deposits:</td>
                                            <td><strong>number+currency</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Average deposit:</td>
                                            <td><strong>number+currency</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Highest deposit:</td>
                                            <td><strong>number+currency</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Total withdrawals: </td>
                                            <td><strong>number+currency</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Pending withdrawals:</td>
                                            <td><strong>number+currency</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Average withdrawal:</td>
                                            <td><strong>number+currency</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Highest withdrawal:</td>
                                            <td><strong>number+currency</strong></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
						<div class="span8">
							<div class="widget">
								<div class="widget-title">
									<h4><i class="icon-bar-chart"></i> FINANCIAL CHART FOR PREVIOUS MONTH</h4>
									<span class="tools">
									<a href="javascript:;" class="icon-chevron-down"></a>
									</span>
								</div>
								<div class="widget-body">
									<div id="site_statistics_loading">
										<img src="/theme/Admin/img/chart.png" alt="chart" />
									</div>
									<div id="site_statistics_content" class="hide">
										<div id="site_statistics" class="chart"></div>
									</div>
								</div>
							</div>	
						</div>
                    </div>
			<!-- END FINANCE -->
					
					
					
					
					
					
					

  <!--- <img src="/theme/Admin/img/events-chart.png" alt=""> ---!>
  
  

</div>
<!-- END PAGE CONTENT-->
</div>
