<div class="card">
    <div class="card-body">
        <div class="row m-b-30">
            <div class="col-md-6">
                <div class="col-6">
                    <div class="form-group validate">
                        <label for="select-label">Status:</label>
                        <select class="form-control form-control-sm status" name="status" style="min-width:200px;" aria-invalid="false">
                            <option value="all">All</option>
                            <option value="po">Purchase Order</option>
                            <option value="transfer">Deliveries</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label class="control-label">From:</label>
                            <input class="fromDate form-control form-control-sm date-ranges" name="from_date" placeholder="Enter Date From" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="control-label">To:</label>
                            <input class="toDate form-control form-control-sm date-ranges" name="to_date" placeholder="Enter Date To" autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>
      </div>
      <div class="tab-pane active p-20" id="list_of_items" role="tabpanel">
            <div class="row m-b-40 m-t-40 justify-content-md-center">
                <div class="col-md-6" style="text-align:center">
                    <h4 class="text-themecolor font-weight-bold">LIST OF ITEMS</h4>
                    <h6>Listed below are all the items that are recorded within the coverage date</h6>
                </div>
            </div>

            <div class="row justify-content-md-center m-b-40">
                <div class="col-md-12 responsive-table">
                    <table id="discrepancy_table" class="table table-bordered table-striped stat">
                        <td colspan="100%" style="text-align:center">
                            <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
                              <span class="sr-only">Loading...</span>
                            </div>
                        </td>
                     </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('modals.php') ?>
