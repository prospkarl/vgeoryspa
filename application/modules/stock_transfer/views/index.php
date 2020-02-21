<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6"></div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-6 align-self-end"></div>
                    <div class="col-md-2 align-self-end">

                    </div>
                    <div class="col-md-4 align-self-end">
                        <div class="form-group">
                            <select class="form-control form-control-sm status">
                                <option selected value="all">All</option>
                                <option  value="0">For Delivery</option>
                                <option  value="1">Recieved</option>

                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <table id="stockTransfer" class="table table-bordered table-striped override-width">
            <thead>
                <tr>
                    <th>Transfer ID</th>
                    <th>Transfer By</th>
                    <th>Transferred From</th>
                    <th>Transferred To</th>
                    <th>Date Transferred</th>
                    <th>Status</th>
                    <th width="10%">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<?= include('modal.php') ?>
