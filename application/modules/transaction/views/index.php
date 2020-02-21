<div class="card">
    <div class="card-body">

        <div class="row">
            <div class="col-md-6"></div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-6 align-self-end"></div>
                    <div class="col-md-4 align-self-end">
                        <div class="form-group">
                            <label class="control-label">Location</label>
                            <select name="location"  class="form-control-sm">
                                <option selected value="0">All</option>
                                <?= render_options($location_from, 'location_id', 'name') ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 align-self-end">
                        <div class="form-group">
                            <label class="control-label">Year</label>
                            <input required class="yearSel form-control form-control-sm" placeholder="Enter Year">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" style="display:none">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Average Transaction Count</h4>
                        <canvas id="morris-line-chart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Transaction Count on Each Location</h4>
                        <canvas id="sales_chart"></canvas>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-12">
                <h2 class="text-themecolor font-weight-bold">Impact of Cashless Transaction to Revenue</h2>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12" style="text-align:center">
                <h3 class="text-themecolor font-weight-bold">Sales</h3>
            </div>
        </div>

        <table id="transactiontbl" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Sales</th>
                    <th>Jan</th>
                    <th>Feb</th>
                    <th>Mar</th>
                    <th>Apr</th>
                    <th>May</th>
                    <th>Jun</th>
                    <th>Jul</th>
                    <th>Aug</th>
                    <th>Sep</th>
                    <th>Oct</th>
                    <th>Nov</th>
                    <th>Dec</th>
                    <th>Running Total</th>
                </tr>
            </thead>
            <tbody>
                <?= $string ?>
            </tbody>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-md-12" style="text-align:center">
        <h3 class="text-themecolor font-weight-bold">Transactions</h3>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <table id="card_sales" class="table table-bordered table-striped debitcard">
            <thead>
                <tr>
                    <th colspan="14">Card Sales</th>
                </tr>
                <tr>
                    <th></th>
                    <th>Jan</th>
                    <th>Feb</th>
                    <th>Mar</th>
                    <th>Apr</th>
                    <th>May</th>
                    <th>Jun</th>
                    <th>Jul</th>
                    <th>Aug</th>
                    <th>Sep</th>
                    <th>Oct</th>
                    <th>Nov</th>
                    <th>Dec</th>
                </tr>
            </thead>
            <tbody>
                <?= $card ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table id="card_sales" class="table table-bordered table-striped gcash">
            <thead>
                <tr>
                    <th colspan="14">GCash</th>
                </tr>
                <tr>
                    <th></th>
                    <th>Jan</th>
                    <th>Feb</th>
                    <th>Mar</th>
                    <th>Apr</th>
                    <th>May</th>
                    <th>Jun</th>
                    <th>Jul</th>
                    <th>Aug</th>
                    <th>Sep</th>
                    <th>Oct</th>
                    <th>Nov</th>
                    <th>Dec</th>
                </tr>
            </thead>
            <tbody>
                <?= $gcash ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table id="card_sales" class="table table-bordered table-striped cash">
            <thead>
                <tr>
                    <th colspan="14">Cash</th>
                </tr>
                <tr>
                    <th></th>
                    <th>Jan</th>
                    <th>Feb</th>
                    <th>Mar</th>
                    <th>Apr</th>
                    <th>May</th>
                    <th>Jun</th>
                    <th>Jul</th>
                    <th>Aug</th>
                    <th>Sep</th>
                    <th>Oct</th>
                    <th>Nov</th>
                    <th>Dec</th>
                </tr>
            </thead>
            <tbody>
                <?= $cash ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table id="card_sales" class="table table-bordered table-striped cheque">
            <thead>
                <tr>
                    <th colspan="14">Cheque</th>
                </tr>
                <tr>
                    <th></th>
                    <th>Jan</th>
                    <th>Feb</th>
                    <th>Mar</th>
                    <th>Apr</th>
                    <th>May</th>
                    <th>Jun</th>
                    <th>Jul</th>
                    <th>Aug</th>
                    <th>Sep</th>
                    <th>Oct</th>
                    <th>Nov</th>
                    <th>Dec</th>
                </tr>
            </thead>
            <tbody>
                <?= $cheque ?>
            </tbody>
        </table>
    </div>
</div>

<!-- <div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Average Transaction Count</h4>
                        <canvas id="morris-line-chart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Transaction Count on Each Location</h4>
                        <canvas id="sales_chart"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div> -->
