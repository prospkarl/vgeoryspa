<!-- <h1 style="font-style: italic;color: red;">Coming soon..</h1> -->
<div class="card">
    <div class="card-body">

        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Location</label>
                            <select class="form-control form-control-sm" name="loc">
                                <option value="0">All</option>
                                <?= render_options($location, 'location_id', 'name') ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-6 align-self-end">
                        <div class="form-group">
                            <label class="control-label">From</label>
                            <input required class="fromDate form-control form-control-sm" placeholder="Enter Date From">
                        </div>
                    </div>
                    <div class="col-md-6 align-self-end">
                        <div class="form-group">
                            <label class="control-label">To</label>
                            <input required class="toDate form-control form-control-sm" placeholder="Enter Date To">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-10"></div>
            <div  class="col-md-2"><h1 class="text-themecolor font-weight-bold ">Total</h1><h2 class="total_sales">&#8369; 00.00</h2></div>
        </div>

        <table id="salesOrderTbl" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Sales Id</th>
                    <th>Sales Personnel</th>
                    <th>Location</th>
                    <th>Total Quantity</th>
                    <th>Total Cost</th>
                    <th>Payment Method</th>
                    <th>Date Of Purchase</th>
                    <th width="10%">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>

    </div>
</div>

<?php include('modal.php') ?>
