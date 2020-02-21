<div id="viewMonthInventory" data-start="<?= $start_date ?>" data-end="<?= $end_date ?>" data-location="<?= $location ?>" class="card">
    <div class="card-body print_to">
        <div class="">
            <div class="row m-b-40">
                <div class="col-md-4 col-sm-6">
                    <h4 class="text-themecolor font-weight-bold month">Coverage: </h4>
                    <h5 class="coverage"><?= date('F d, Y', strtotime($start_date)) . ' to ' . date('F d, Y', strtotime($end_date)) ?></h5>
                </div>
                <div class="col-md-4 col-sm-6 text-center loading">
                    <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
                      <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
            <!-- <div class="row m-b-40 colors_title">
                <div class="col-md-4">
                    <h5 class="text-themecolor font-weight-bold">Total Deliveries</h5>
                    <h6 class="total_purchase">-<h6>
                </div>
                <div class="col-md-4">
                    <h5 class="text-themecolor font-weight-bold">Total Sales</h5>
                    <h6 class="total_sales"><h6>
                </div>
                <div class="col-md-4">
                    <h5 class="text-themecolor font-weight-bold">Profit</h5>
                    <h6 class="profit"><h6>
                </div>
            </div> -->
        </div>
        <div class="tab-content tabcontent-border">
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
                        <table id="all_rec_items"  class="table table-bordered table-striped"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div>
