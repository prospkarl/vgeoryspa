
<div class="card">
    <div class="card-body">
        <h3 class="font-wieght-bold "></h3>
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">From Date</label>
                            <input required name="start_date" class="select-date form-control form-control-sm" placeholder="Enter Month" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">To Date</label>
                            <input required name="end_date" class="select-date form-control form-control-sm" placeholder="Enter Month" autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr class="m-t-40 m-b-40">
        <div class="row">
            <div class="col-md-6">
                <h2>Top Sellers</h2>
                <table id="topsellers" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Full Name</th>
                            <th>Total Sales</th>
                            <th>Total Product Sold</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <h2>Top Locations With High Selling Rate</h2>
                <table id="toplocation" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Location</th>
                            <th>Total Sales</th>
                            <th>Total Product Sold</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
