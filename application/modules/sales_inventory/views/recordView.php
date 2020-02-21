
<div class="card record" >
    <div class="card-body">
        <form id="form_rec_daily" method="post">
            <div class="row m-b-40">
                <div class="col-md-6">
                    <h4 class="text-themecolor font-weight-bold">Coverage: </h4>
                    <h6 class="coverage"><?= date("Y-m-d") ?></h6>
                </div>

            </div>

            <h4><h4>
            <div class="row">
                <div class="col-12">
                    <table id="recordDaily" class="table table-bordered table-striped" style="font-size: 15px"></table>
                </div>
            </div>
            <div class="row m-t-30">
                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-info btn-md m-r-20">
                        <i class="mdi mdi-checkbox-marked-circle-outline"></i>  Record
                    </button>
                    <a href="<?= base_url() ?>daily_inventory" type="submit" class="btn btn-inverse btn-md"><i class="mdi mdi-close-circle-outline"></i> Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
