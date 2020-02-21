<div id="salesViewRecord" data-id="<?= $id ?>" class="card">
    <div class="card-body print_to">
        <div class="">
            <div class="row m-b-40">
                <div class="col-md-4 col-sm-6">
                    <h4 class="text-themecolor font-weight-bold month"></h4>
                    <h6 class="coverage">Coverage: </h6>
                </div>
                <div class="col-md-4 col-sm-6">
                    <h4 class="text-themecolor font-weight-bold">Recorded by: </h4>
                    <h6 class="record_by"></h6>
                </div>
                <div class="col-md-4 col-sm-6 text-right">
                    <button type="button" name="button" class="btn btn-info print-all"><i class="ti-printer"></i> Print All</button>
                </div>
            </div>

            <div class="row m-b-40 colors_title">
                <div class="col-md-4">
                    <h5 class="text-themecolor font-weight-bold">Total Amount Of Products - System Count</h5>
                    <h6 class="system_count"><h6>
                </div>
                <div class="col-md-4">
                    <h5 class="text-themecolor font-weight-bold">Total Amount Of Products - Physical Count</h5>
                    <h6 class="physical_count"><h6>
                </div>
                <div class="col-md-4">
                    <h5 class="text-themecolor font-weight-bold">Discrepancy</h5>
                    <h6 class="discrepancy_amt"><h6>
                </div>

                <hr>
            </div>

            <div class="row m-b-40 colors_title">
                <div class="col-md-4">
                    <h5 class="text-themecolor font-weight-bold">Total Products - System Count</h5>
                    <h6 class="item_count"><h6>
                </div>
                <div class="col-md-4">
                    <h5 class="text-themecolor font-weight-bold">Total Products - Physical Count</h5>
                    <h6 class="item_phy_count"><h6>
                </div>
                <div class="col-md-4">
                    <h5 class="text-themecolor font-weight-bold">Discrepancy</h5>
                    <h6 class="discrepancy_item"><h6>
                </div>
            </div>

            <div class="row m-b-40 colors_title">
                <div class="col-md-4">
                    <h5 class="text-themecolor font-weight-bold">Total Purchase</h5>
                    <h6 class="total_purchase"><h6>
                </div>
                <div class="col-md-4">
                    <h5 class="text-themecolor font-weight-bold">Total Sales</h5>
                    <h6 class="total_sales"><h6>
                </div>
                <div class="col-md-4">
                    <h5 class="text-themecolor font-weight-bold">Profit</h5>
                    <h6 class="profit"><h6>
                </div>
            </div>

        </div>


        <!-- <hr> -->

        <ul class="ableprint nav nav-tabs" role="tablist">
            <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#list_of_items" role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">List of Items</span></a> </li>
            <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#statistics" role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Inventory Statistics</span></a> </li>
            <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#pullouts" role="tab"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">Pull Outs</span></a> </li>
            <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#purchase" role="tab"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">Purchases</span></a> </li>
            <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#summary" role="tab"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">Summary</span></a> </li>
        </ul>

        <div class="tab-content tabcontent-border">
            <!-- tab 1 -->
            <div class="tab-pane active p-20" id="list_of_items" role="tabpanel">
                <div class="row m-b-40 justify-content-md-center">
                    <div class="col-md-3"></div>
                    <div class="col-md-6" style="text-align:center">
                        <h4 class="text-themecolor font-weight-bold">LIST OF ALL ITEMS</h4>
                        <h6>Listed below are all the items that are recorded within the coverage date</h6>
                    </div>
                    <div class="col-md-3 text-right">
                        <button type="button" data-trig='list_of_items' class="btn print"><i class="ti-printer"></i></button>
                    </div>
                </div>
                <div class="row justify-content-md-center m-b-40">
                    <div class="col-md-12">
                        <table id="all_rec_items"  class="table table-bordered table-striped table-responsive"></table>
                    </div>
                </div>
            </div>
            <!-- end tab 1 -->
            <!-- tab 2 -->
            <div class="tab-pane  p-20" id="statistics" role="tabpanel">
                <div class="row m-b-40  justify-content-md-center">
                    <div class="col-md-3"></div>
                    <div class="col-md-6" style="text-align:center">
                        <h4 class="text-themecolor font-weight-bold">Inventory Statistics</h4>
                        <h6>Listed below are all the items that are recorded within the coverage date</h6>
                    </div>
                    <div class="col-md-3 text-right">
                        <button type="button" data-trig='2' class="btn print"><i class="ti-printer"></i></button>
                    </div>
                </div>
                <div class="row justify-content-md-center m-b-40">
                    <div class="col-md-12">
                        <table id="all_goods" class="table table-bordered table-striped"></table>
                    </div>
                </div>
            </div>
            <!-- end tab 2 -->
            <!-- tab 3 -->
            <div class="tab-pane  p-20" id="pullouts" role="tabpanel">
                <div class="row m-b-40 justify-content-md-center">
                    <div class="col-md-3"></div>
                    <div class="col-md-6" style="text-align:center">
                        <h4 class="text-themecolor font-weight-bold">LIST OF ALL PULL OUTS</h4>
                        <h6>Listed below are all the items that are recorded within the coverage date</h6>
                    </div>
                    <div class="col-md-3 text-right">
                        <button type="button" data-trig='3' class="btn print"><i class="ti-printer"></i></button>
                    </div>
                </div>

                <div class="row justify-content-md-center m-b-40">
                    <div class="col-md-12">
                        <table id="all_pull_items" class="table table-bordered table-striped"></table>
                    </div>
                </div>
            </div>
            <!-- end tab 3 -->
            <!-- tab 4 -->
            <div class="tab-pane  p-20" id="purchase" role="tabpanel">
                <div class="row m-b-40 justify-content-md-center">
                    <div class="col-md-3"></div>
                    <div class="col-md-6" style="text-align:center">
                        <h4 class="text-themecolor font-weight-bold">LIST OF ALL PURCHASES</h4>
                        <h6>Listed below are all the items that are recorded within the coverage date</h6>
                    </div>
                    <div class="col-md-3 text-right">
                        <button type="button" data-trig='4' class="btn print"><i class="ti-printer"></i></button>
                    </div>
                </div>

                <div class="row justify-content-md-center m-b-40">
                    <div class="col-md-12">
                        <table id="all_purchase"  class="table table-bordered table-striped "></table>
                    </div>
                </div>
            </div>
            <div class="tab-pane  p-20" id="summary" role="tabpanel">
                <div class="row m-b-40 justify-content-md-center">
                    <div class="col-md-3"></div>
                    <div class="col-md-6" style="text-align:center">
                        <h4 class="text-themecolor font-weight-bold">INVENTORY SUMMARY</h4>
                        <h6>Listed below are all the items that are recorded within the coverage date</h6>
                    </div>
                    <div class="col-md-3 text-right">
                        <button type="button" data-trig='4' class="btn print"><i class="ti-printer"></i></button>
                    </div>
                </div>

                <div class="row justify-content-md-center m-b-40">
                    <div class="col-md-12">
                        <table id="inventory_summary"  class="table table-bordered table-striped "></table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div>
