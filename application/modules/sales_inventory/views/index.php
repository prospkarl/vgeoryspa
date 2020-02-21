<!-- Daily Section -->
<div id="daily" class="card toggle-hide">
    <div class="card-body">
      <div class="row justify-content-between">
        <div class="col-md-6 col-sm-12">
          <h3 class="font-weight-bold">Daily Inventory</h3>
        </div>
        <div class="col-md-2 col-sm-12 text-right"> </div>
      </div>
      <div class="row">
        <table id="salesInventory" class="table table-bordered table-striped d_inventory" >
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

<!-- Monthly Section -->
<div id="monthly" class="card toggle-hide hidden">
    <div class="card-body">
      <div class="row justify-content-between m-b-40">
        <div class="col-md-6 col-sm-12">
          <h3 class="font-weight-bold">Monthly Inventory</h3>
        </div>
        <div class="col-md-2 col-sm-12 text-right">
          <select class="form-control form-control-sm" name="year">
            <?php render_options($year_options, 'year', 'year', Date('Y')); ?>
          </select>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <table id="monthlyInventory" class="table table-bordered table-striped" >
            <thead>
              <tr>
                <th>Month Period</th>
                <th width="15%">Status</th>
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
