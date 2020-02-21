<!-- <h1 style="font-style:italic; color:red">Coming soon..</h1> -->
<div class="card">
    <div class="card-body">
        <!-- <button type="submit" id="recMonthly_btn" class="btn btn-info">Record Monthly inventory</button> -->
        <form class="setAc">
            <div class="row m-b-40">
                <div class="col-md-6" style="text-align:right">
                </div>
                <div class="col-md-6" style="text-align:right">
                    <button class="btn btn-info set_it"><i class="fa fa-edit"></i> Edit</button>
                    <button style="display:none; " class="btn btn-info set_cancel">Cancel</button>
                    <button style="display:none; " type="submit" class="btn btn-info set_save">Save</button><br>
                </div>
            </div>
            <table id="acquisition" class="table table-bordered table-striped " ></table>
        </form>

    </div>
</div>

<?php include 'modal.php'; ?>
