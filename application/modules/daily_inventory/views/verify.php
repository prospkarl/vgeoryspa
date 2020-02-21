<div class="card">
    <div class="card-body">
        <form id="verifyInventoryForm" method="post">
            <input type="hidden" name="inventory_id" value="<?= $inventory['daily_id'] ?>">
            <div class="row m-b-30">
                <div class="col-md-6 col-sm-12">
                    <h2 class="font-weight-bold">Verify Inventory</h2>
                    <h4>Please verify recorded items.</h4>
                </div>
                <div class="col-md-6 col-sm-12 text-right">
                    <h5><strong>Coverage: </strong><?= date('F d, Y', strtotime($inventory['date'])) ?></h5>
                    <h5><strong>Recorded by: </strong><?= ucwords($inventory['fname'] . ' ' . $inventory['lname']) ?></h5>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <table id="recordDaily" data-action="verify" class="table table-bordered table-striped" style="font-size: 15px"></table>
                </div>
            </div>
            <div class="row m-t-30">
                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-info btn-md m-r-20">
                        <i class="mdi mdi-check-all"></i>  Verify
                    </button>
                    <a href="<?= base_url() ?>daily_inventory" type="submit" class="btn btn-inverse btn-md"><i class="mdi mdi-keyboard-return"></i> Return</a>
                </div>
            </div>
        </form>
    </div>
</div>
