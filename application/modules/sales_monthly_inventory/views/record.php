<div class="card record" >
    <div class="card-body">
        <form id="form_rec" method="post">
        <div class="row m-b-40">
            <div class="col-md-6">
                <h4 class="text-themecolor font-weight-bold">Coverage: </h4>
                <h6 class="coverage"></h6>
            </div>
            <div class="col-md-6" style="text-align:right">
                <a href="<?= base_url() ?>sales_monthly_inventory" type="submit" class="btn btn-info">Cancel</a>
                <button type="submit" class="btn btn-info">Record</button><br>
            </div>
        </div>

        <h4><h4>
            <table id="recordSales" class="table table-bordered table-striped" style="font-size: 15px"></table>
        <div id="pager">
            <ul id="pagination" class="pagination-sm"></ul>
        </div>
        </form>
    </div>
</div>
