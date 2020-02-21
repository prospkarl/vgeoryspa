<div class="card">
    <div class="card-body">
        <div class="row ">
            <div class="col-lg-6 col-sm-12"> </div>
            <div class="col-lg-4 col-sm-12"> </div>
            <div class="col-lg-2 col-sm-12 text-right">
                <select class="form-control form-control-sm" name="status">
                    <option value="all">All</option>
                    <option value="2">Void</option>
                    <option value="1" selected>Completed</option>
                </select>
            </div>
        </div>
        <div class="row">
            <table id="transfers" class="table table-bordered table-striped datatable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Location</th>
                        <th>Issued By</th>
                        <th>Issued To</th>
                        <th>Date Issued</th>
                        <th>Total Items</th>
                        <th>Total Amount</th>
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
