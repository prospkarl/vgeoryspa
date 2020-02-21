<div class="row">
    <!-- Column -->
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex p-10 no-block">
                    <div class="align-slef-center">
                        <h1 class="m-b-1"><?= $warehouse_products['warehouse_qty']; ?></h1>
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
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex p-10 no-block">
                    <div class="align-slef-center">
                        <h1 class="m-b-1">₱ <?= $stock_on_hand ?> </h1>
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
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex p-10 no-block">
                    <div class="align-slef-center">
                        <h1 class="m-b-1"><?= $low_stock_count ?></h1>
                        <h5 class="text-muted m-b-0">Low stock Items</h5>
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
    <div class="col-lg-3 col-md-6">
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
    <!-- Column -->
</div>
<hr class="m-b-40">
<div class="row">
    <?php foreach ($locations as $key => $loc): ?>
        <?php if (count($loc['items'])): ?>
            <div class="col-6">
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
                                <?php foreach ($loc['items'] as $item): ?>
                                    <tr>
                                        <td><?= $item['name'] ?></td>
                                        <td class="text-right">
                                            <span class="label label-light-danger" style="font-weight:900; font-size:1em; padding: 9px 18px">Low Stock</span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body" style="padding:4em 3em;">
                <h5 class="card-title font-weight-bold">Top Selling Products</h5>

                <div id="morris-bar-chart" class=""></div>
            </div>
        </div>
    </div>
</div>
