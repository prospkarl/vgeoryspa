<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-12 text-right m-b-10">
                <a href="<?= base_url('receive/show_desc') ?>" class="btn btn-sm btn-info"><i class="fas fa-cube"></i> Discrepancy</a>
            </div>
        </div>
        <div class="row">
            <input type="hidden" class="test" name="" value="">

            <table id="transfers" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Transfer ID</th>
                        <th>Created By</th>
                        <th>Date Created</th>
                        <th width="10%">Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include('modal.php'); ?>
