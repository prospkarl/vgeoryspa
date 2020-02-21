<div class="card">
    <div class="card-body">
        <div class="row m-b-10">
            <div class="col-12 text-right">
                <a href="<?= base_url('daily_inventory/verify') ?>" class="btn btn-info m-r-10 action-btn <?= !$need_verification ? 'disabled' : '' ?>" data-action="verify"><i class="mdi mdi-checkbox-marked-outline"></i> Verify Inventory </a>
                <a href="<?= base_url('daily_inventory/viewDaily') ?>" class="btn btn-info action-btn <?= $need_verification ? 'disabled' : '' ?>" data-action="ending"><i class="mdi mdi-checkbox-multiple-marked-outline"></i> Ending Inventory </a>
            </div>
        </div>
        <div class="row">
            <table id="dailyInventory" class="table table-bordered table-striped d_inventory" >
                <thead>
                    <tr>
                        <th>Date Recorded</th>
                        <th>Recorded By</th>
                        <th>End Item Count</th>
                        <th>Physical Item Count</th>
                        <th>Variance</th>
                        <th>End Balance </th>
                        <th>Physical Balance </th>
                        <th>Verified </th>
                        <th width="10%">Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php //include('modal.php'); ?>
