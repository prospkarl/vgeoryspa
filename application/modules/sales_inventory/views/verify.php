<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <h1 class="font-weight-bold">Verify Inventory</h1>
            </div>
            <div class="col-md-6 col-sm-12 text-right">
                <h5 class="text-themecolor"><strong>Coverage: </strong><?= date('F d, Y', strtotime($inventory['date'])) ?></h5>
                <h5 class="text-themecolor"><strong>Recorded by: </strong><?= ucwords($inventory['fname'] . ' ' . $inventory['lname']) ?></h5>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <table id="inventory_items" data-id="<?= $inventory['daily_id'] ?>">
                    <thead>
                        <th>Item Name</th>
                    </thead>
                    <td>
                        <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
                          <span class="sr-only">Loading...</span>
                        </div>
                    </td>
                </table>
            </div>
        </div>
    </div>
</div>
