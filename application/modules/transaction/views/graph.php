<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <a href="#" class="domtoimage btn btn-info" data-target="transactionGraph" data-name="Transactions">Save &nbsp;<i class="ti-save"></i></a>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-6 align-self-start"> </div>
                    <div class="col-md-4 align-self-end">

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
        <div id="transactionGraph">
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
                            <h4 class="card-title">Average Check</h4>
                            <canvas id="avg_check"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Transaction Count on Each Location</h4>
                            <canvas id="sales_chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
