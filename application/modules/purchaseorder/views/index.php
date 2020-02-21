<!-- <h1 style="font-style: italic;color: red;">Coming soon..</h1> -->
<input type="hidden" class="test" name="" value="">
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 text-left">
                <a href="<?= base_url('purchaseorder/show_desc') ?>" class="btn btn-sm btn-info"><i class="fas fa-cube"></i> Discrepancy</a>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-6 align-self-end"></div>
                    <div class="col-md-2 align-self-end"></div>
                    <div class="col-md-4 align-self-end">
                        <div class="form-group">
                            <select class="form-control form-control-sm status">
                                <option selected value="all">All</option>
                                <option  value="0">Pending</option>
                                <option  value="3">Received</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <table id="purchaseOrder" class="table table-bordered table-striped override_width">
            <thead>
                <tr>
                    <th>PO ID</th>
                    <th>Supplier Name</th>
                    <th>Total Quantity</th>
                    <th>Total Cost (₱)</th>
                    <th>Last Updated</th>
                    <th>Status</th>
                    <th width="10%">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<?php include('modal.php') ?>
