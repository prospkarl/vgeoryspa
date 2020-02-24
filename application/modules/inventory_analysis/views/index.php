<div class="card">
    <div class="card-body">
        <div class="row m-b-40">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="select-label">Status:</label>
                            <select class="form-control form-control-sm status" name="location" style="min-width:200px;">
                                <option value="" selected>All</option>
                                <?php render_options($locations, 'location_id', 'name') ?>
                            </select>
                        </div>
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
        <div class="row">
            <div class="col-12">
                <h3 class="font-weight-bold">Top Selling Items:</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <table id="topSellingItems" class="table table-striped">
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
