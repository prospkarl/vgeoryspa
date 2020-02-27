<div class="card">
    <div class="card-body">
        <div class="row m-b-30">
          <div class="col-md-4">
              <h4 class="text-themecolor font-weight-bold month">Coverage: </h4>
              <h6 class="coverage"><?= $date_from ?> to <?= $date_to ?></h6>
          </div>
      </div>
      <div class="tab-pane active p-20" id="list_of_items" role="tabpanel">
                <div class="row m-b-40 m-t-40 justify-content-md-center">
                    <div class="col-md-6" style="text-align:center">
                        <h4 class="text-themecolor font-weight-bold">LIST OF ITEMS</h4>
                        <h6>Listed below are all the items that are recorded within the coverage date</h6>
                    </div>
                </div>

                <div class="row justify-content-md-center m-b-40">
                    <div class="col-md-12 responsive-table">
                        <table id="inventory_movement_table" class="table table-bordered table-striped stat">
                            <td colspan="100%" style="text-align:center">
                                <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
                                  <span class="sr-only">Loading...</span>
                                </div>
                            </td>
                         </table>
                    </div>
                </div>
            </div>
    </div>
</div>
