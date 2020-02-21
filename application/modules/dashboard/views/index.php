                <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Info box -->
            <!-- ============================================================== -->
            <div class="row">
                <!-- Column -->
                <div class="col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex p-10 no-block">
                                <div class="align-slef-center">
                                    <h1 class="m-b-1"><?= $product_count['warehouse_qty'] ?></h1>
                                    <h5 class="text-muted m-b-0">Warehouse Products</h5>
                                </div>
                                <div class="align-self-center display-6 ml-auto"><i class="text-success fas fa-warehouse"></i></div>
                            </div>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-success" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width:70%; height:3px;"> <span class="sr-only">50% Complete</span></div>
                        </div>
                    </div>
                </div>
                <!-- Column -->
                <!-- Column -->
                <div class="col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex p-10 no-block">
                                <div class="align-slef-center">
                                    <h1 class="m-b-1">Php 6,759 <span class="text-info" style="font-size: 15px; vertical-align: top; display: inline-block;"> <i class="fas fa-long-arrow-alt-up text-sm"></i> Php 200.00</span></h1>
                                    <h5 class="text-muted m-b-0">Sales This <strong>Week</strong></h5>
                                </div>
                                <div class="align-self-center display-6 ml-auto"><i class="text-info fas fa-calendar-check"></i></div>
                            </div>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-info" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width:70%; height:3px;"> <span class="sr-only">50% Complete</span></div>
                        </div>
                    </div>
                </div>
                <!-- Column -->
                <!-- Column -->
                <div class="col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex p-10 no-block">
                                <div class="align-slef-center">
                                    <h1 class="m-b-1">100 <span class="text-danger" style="font-size:15px; vertical-align:top"> <i class="fas fa-long-arrow-alt-down text-sm"></i> 55</span></h1>
                                    <h5 class="text-muted m-b-0">Items Sold This <strong>Year</strong></h5>
                                </div>
                                <div class="align-self-center display-6 ml-auto"><i class="text-primary fas fa-calendar-alt"></i></div>
                            </div>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-primary" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width:70%; height:3px;"> <span class="sr-only">50% Complete</span></div>
                        </div>
                    </div>
                </div>
                <!-- Column -->
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div>
                                <h5 class="card-title m-b-0">Sales</h5>
                            </div>
                            <div id="bar-chart" style="width: 100%; height: 400px; -webkit-tap-highlight-color: transparent; user-select: none; background-color: rgba(0, 0, 0, 0); cursor: default;" _echarts_instance_="1574818418767"> </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Column -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex no-block">
                                <div>
                                    <h5 class="card-title m-b-0">Sales Chart</h5>
                                </div>
                                <div class="ml-auto">
                                    <ul class="list-inline text-center font-12">
                                        <li><i class="fa fa-circle text-success"></i> SM City</li>
                                        <li><i class="fa fa-circle text-info"></i> Ayala</li>
                                        <li><i class="fa fa-circle text-primary"></i> Robinsons</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="" id="sales-chart" style="height: 355px;"></div>
                        </div>
                    </div>
                </div>
                <!-- Column -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Sales Agent</h5>
                            <table class="table browser m-t-30 no-border">
                                <tbody>
                                    <tr>
                                        <td style="width:40px"><img style="width:40px; border-radius:25px" src="<?= assets_url('images/users/1.jpg') ?>" alt="logo"></td>
                                        <td>John document</td>
                                        <td class="text-right">P 4,110.00</td>
                                    </tr>
                                    <tr>
                                        <td><img style="width:40px; border-radius:25px" src="<?= assets_url('images/users/1.jpg') ?>" alt="logo"></td>
                                        <td>Nico Bal</td>
                                        <td class="text-right">P 4,322.00</td>
                                    </tr>
                                    <tr>
                                        <td><img style="width:40px; border-radius:25px" src="<?= assets_url('images/users/1.jpg') ?>" alt="logo"></td>
                                        <td>John Doe</td>
                                        <td class="text-right">P 4,222.00</td>
                                    </tr>
                                    <tr>
                                        <td><img style="width:40px; border-radius:25px" src="<?= assets_url('images/users/1.jpg') ?>" alt="logo"></td>
                                        <td>Nicole Sile</td>
                                        <td class="text-right">P 2,110.00</td>
                                    </tr>
                                    <tr>
                                        <td><img style="width:40px; border-radius:25px" src="<?= assets_url('images/users/1.jpg') ?>" alt="logo"></td>
                                        <td>Johny Tan</td>
                                        <td class="text-right">P 1,110.00</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div>
                                <h5 class="card-title m-b-0">Sales</h5>
                            </div>
                            <div id="morris-bar-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
