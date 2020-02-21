<!-- <h1 style="font-style:italic; color:red">Coming soon..</h1> -->
<div class="card">
    <div class="card-body">
        <div class="row ">
            <div class="col-lg-6 col-sm-12"> </div>
            <div class="col-lg-4 col-sm-12"> </div>
            <div class="col-lg-2 col-sm-12 text-right">
                <select class="form-control form-control-sm" name="status">
                    <option value="all">All</option>
                    <option value="0">Pending</option>
                    <option value="1">Approved</option>
                    <option value="2">Received</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <table id="requestData" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Request ID</th>
                            <th>Date Requested</th>
                            <th>Total Qty Requested</th>
                            <th>Requested By</th>
                            <th>Requested From</th>
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

<?php include('modal.php') ?>
