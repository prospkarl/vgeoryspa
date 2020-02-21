<div class="card" id="dailyR" data->
    <div class="card-body">
        <?php if ($this->session->type == 2): ?>
            <div class="row">
                <div class="col-12 text-right">
                    <a href="<?= base_url('daily_inventory') ?>" class="btn btn-sm btn-info"><i class="fas fa-cube"></i> Daily Inventory</a>
                    <a href="<?= base_url('request_items') ?>" class="btn btn-sm btn-info"><i class="fas fa-cube"></i> Request More Items</a>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($this->session->type != 2): ?>
            <div class="row">
                <div class="col-12 text-right">
                    <a href="<?= base_url('acquisition') ?>" class="btn btn-sm btn-info"><i class="fas fa-cube"></i> Set Pricing Option</a>
                </div>
            </div>
        <?php endif; ?>

        <div class="row">
            <table id="products" class="table table-bordered table-striped" style="overflow: scroll;">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Item Name</th>
                        <th>Price</th>
                        <th class="beg_col">
                            Beg Balance
                            <!-- <button type="button" name="button" class="btn btn-sm btn-info m-l-20 calibrate-btn">Calibrate</button> -->
                        </th>
                        <th>Quantity</th>
                        <th>Status</th>
                        <th>Date Modified</th>
                        <th width="10%">Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once('modal.php'); ?>
