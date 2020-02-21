<div class="card record">
    <div class="card-body">
        <form id="form_rec_mo" method="post">
        <div class="row m-b-40">
            <div class="col-md-6">
                <h4 class="text-themecolor font-weight-bold">Coverage: </h4>
                <h6 class="coverage"></h6>
            </div>
        </div>
        <div class="row m-b-30">
          <table id="recordSales" class="table table-bordered table-striped" style="font-size: 15px"></table>
        </div>
        <hr>
        <div class="row m-t-30">
          <div class="col-md-12 text-center">
            <button type="submit" class="btn btn-info btn-md m-r-20">
                <i class="mdi mdi-checkbox-marked-circle-outline"></i>  Record
            </button>
            <a href="<?= base_url() ?>monthly_inventory" class="btn btn-inverse btn-md"><i class="mdi mdi-close-circle-outline"></i> Cancel</a>
          </div>
        </div>
        </form>
    </div>
</div>
