<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="col-6">
                    <div class="form-group">
                        <label for="select-label">Status:</label>
                        <select class="form-control form-control-sm status" name="status" style="min-width:200px;">
                            <option value="all" >All</option>
                            <option value="0" selected>For Delivery</option>
                            <option value="1">Received</option>
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
        <div class="row">
            <div class="col-12 text-right">
                <h3 class="font-weight-bold">Total Cost: <span class="total_cost"></span></h3>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <table id="delivertbl" class="table table-bordered table-striped override-width">
                    <thead>
                        <tr>
                            <th>Transfer ID</th>
                            <th>Transfer By</th>
                            <th>Transferred From</th>
                            <th>Transferred To</th>
                            <th>Date Transferred</th>
                            <th>Total Cost (₱)</th>
                            <th>Status</th>
                            <th width="10%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="<?= assets_url("js/stock_transfer.js") ?>" charset="utf-8"></script>
<?php include('modal.php') ?>
