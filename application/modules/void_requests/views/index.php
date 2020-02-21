<div class="card">
    <div class="card-body">
        <div class="row m-b-30">
            <div class="col-12 text-right">
                <a href="<?= base_url('sales_order') ?>" class="btn btn-sm btn-info">View All Sales</a>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <table id="table_requests" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
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
                        <?= $render_row; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
