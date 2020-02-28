<!-- ============================================================== -->
<!-- End Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- Info box -->
<!-- ============================================================== -->
<div class="row">
<!-- Column -->
<div class="col-lg-4 col-md-6 dashboard-btn" data-link="products">
    <div class="card">
        <div class="card-body">
            <div class="d-flex p-10 no-block">
                <div class="align-slef-center">
                    <h1 class="m-b-1"><?= $product_count['warehouse_qty'] ?></h1>
                    <h5 class="text-muted m-b-0">CSO Products</h5>
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
<div class="col-lg-4 col-md-6 dashboard-btn" data-link="reports">
    <div class="card">
        <div class="card-body">
            <div class="d-flex p-10 no-block">
                <div class="align-slef-center">
                    <h1 class="m-b-1">
                        <span class="sales">₱ 0.00</span>
                        <span class="text-info difference sales_difference">
                            -
                        </span>
                    </h1>
                    <h5 class="text-muted m-b-0">Consolidated Sales This <strong class="display_time_frame">Week</strong></h5>
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
<div class="col-lg-4 col-md-6 dashboard-btn" data-link="sales_order">
    <div class="card">
        <div class="card-body">
            <div class="d-flex p-10 no-block">
                <div class="align-slef-center">
                    <h1 class="m-b-1">
                        <span class="sold">None</span>
                        <span class="text-info difference sold_difference">

                        </span>
                    </h1>
                    <h5 class="text-muted m-b-0">Consolidated Items Sold This <strong class="display_time_frame">Week</strong></h5>
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
    <!-- Column -->
    <div class="col-lg-4 col-md-6 dashboard-btn" data-link="products">
        <div class="card">
            <div class="card-body">
                <div class="d-flex p-10 no-block">
                    <div class="align-slef-center">
                        <h1 class="m-b-1">₱ <?= number_format($stock_on_hand) ?> </h1>
                        <h5 class="text-muted m-b-0">Stock on hand</h5>
                    </div>
                    <div class="align-self-center display-6 ml-auto"><i class="text-primary fas fa-boxes"></i></div>
                </div>
            </div>
            <div class="progress">
                <div class="progress-bar bg-primary" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width:70%; height:3px;"> <span class="sr-only">50% Complete</span></div>
            </div>
        </div>
    </div>
    <!-- Column -->
    <!-- Column -->
    <div class="col-lg-4 col-md-6 dashboard-btn" data-link="products?loc=1">
        <div class="card">
            <div class="card-body">
                <div class="d-flex p-10 no-block">
                    <div class="align-slef-center">
                        <h1 class="m-b-1"><?= $low_stock_count ?></h1>
                        <h5 class="text-muted m-b-0">Low Stock Items</h5>
                    </div>
                    <div class="align-self-center display-6 ml-auto"><i class="text-danger mdi mdi-package-down"></i></div>
                </div>
            </div>
            <div class="progress">
                <div class="progress-bar bg-danger" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width:70%; height:3px;"> <span class="sr-only">50% Complete</span></div>
            </div>
        </div>
    </div>
    <!-- Column -->
    <!-- Column -->
    <div class="col-lg-4 col-md-6 dashboard-btn" data-link="delivery">
        <div class="card">
            <div class="card-body">
                <div class="d-flex p-10 no-block">
                    <div class="align-slef-center">
                        <h1 class="m-b-1"><?= $for_delivery_count ?></h1>
                        <h5 class="text-muted m-b-0">For Delivery</h5>
                    </div>
                    <div class="align-self-center display-6 ml-auto"><i class="text-info mdi mdi-truck-delivery"></i></div>
                </div>
            </div>
            <div class="progress">
                <div class="progress-bar bg-info" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width:70%; height:3px;"> <span class="sr-only">50% Complete</span></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
<div class="col-12">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between m-b-40">
                <h5 class="card-title m-b-0">Sales (₱)</h5>
                <div class="d-flex justify-content-between">
                    <h4><i class="fa fa-bullseye" style="color:#FF0000"></i> Target this <span class="display_time_frame">Week</span>:</h4>
                    <form id="editTargetForm" method="post">
                        <div style="margin-left:1em">
                            <strong class="target_sales" style=" font-weight: bold; font-size: 18px; ">P 0.00 </strong>
                                <div class="input-group target-input" style="display:none">
                                    <input type="hidden" class="form-control form-control-sm target_sales_value display_time_frame_value" name="time_frame" value="">
                                    <input type="text" class="form-control form-control-sm target_sales_value" name="new_target" value="">
                                    <div class="input-group-append">
                                        <button class="btn btn-xs btn-info action-submit" type="submit"><i class="mdi mdi-check"></i></button>
                                    </div>
                                </div>
                            <a href="javascript:;" class="edit-target">
                                <i class="fa fa-edit text-green" style="font-size:12px; vertical-align:top; margin-left:5px"></i>
                            </a>
                        </div>
                    </form>
                </div>
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
                <div class="d-flex no-block justify-content-between">
                    <h5 class="card-title font-weight-bold">Items Sold</h5>
                    <a
                        href="javascript:;"
                        data-target="items-sold"
                        data-name="Items Sold"
                        class="font-weight-bold domtoimage"
                    ><i class="ti-save"></i></a>
                </div>
                <canvas id="items-sold" class="chartjs" width="undefined" height="undefined"></canvas>
            </div>
        </div>
    </div>
    <!-- Column -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title font-weight-bold">Top Sellers (<?= date('F') ?>)</h5>
                <table id="top-sellers" class="table browser m-t-30 no-border sales-order-items">
                    <tbody>
                        <tr>
                            <td colspan="100%">No Sales Yet</td>
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
            <div class="card-body" style="padding:4em 3em;">
                <div class="d-flex justify-content-between">
                    <h5 class="card-title font-weight-bold">Top Selling Products</h5>
                    <a
                        href="javascript:;"
                        data-target="morris-bar-chart"
                        data-name="Top Selling Products"
                        class="font-weight-bold domtoimage"
                    ><i class="ti-save"></i></a>
                </div>
                <div>
                    <div id="morris-bar-chart" class="hide-hover"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<hr style="margin: 2em 0 4em" />

<div class="row">
    <?php foreach ($locations as $key => $loc): ?>
        <?php if (count($loc['items'])): ?>
            <div class="col-6 dashboard-btn" data-link="products?loc=<?= $loc['id'] ?>">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title" style="padding:10px 2em 0">
                            <h1 class="m-b-0" style="font-size:2em"><?= $key ?></h1>
                            <h4 class="m-t-0 text-grey">Low on Stock</h4>
                        </div>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-<?= $loc['color'] ?>" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width:80%; height:3px;"> <span class="sr-only">50% Complete</span></div>
                    </div>
                    <div class="card-body" style="padding: 1em 2.6em">
                        <table class="table v-middle no-border">
                            <tbody>
                                <?php
                                $max_display = 8;
                                $all_items = count($loc['items']);

                                foreach ($loc['items'] as $key => $item): ?>
                                    <tr>
                                        <td><?= $item['name'] ?></td>
                                        <td class="text-right">
                                            <span class="label label-light-danger" style="font-weight:900; font-size:1em; padding: 9px 18px">Low Stock</span>
                                        </td>
                                    </tr>
                                <?php
                                if ($max_display == $key) { ?>
                                    <tr>
                                        <td colspan="100%" class="font-weight-bold"><?= ($all_items - $max_display) ?> More..</td>
                                    </tr>
                                    <?php
                                    break;
                                }

                                endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>
