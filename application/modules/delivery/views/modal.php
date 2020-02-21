<div id="transferMod"  class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl modal-dialog-centered animated bounceIn show" role="document">
        <div class="modal-content">
            <input type="hidden" class="type" name="" value="">
          <form id="transferForm">
          <div class="modal-body">
              Transfer Stocks
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>

              <div class="form-group row" style="padding-top: 20px;padding-bottom: 10px; margin-left: 9px;border-bottom: 1px solid #909a9f">
                    <div class="col-md-2">
                        <b>Transfer Date:</b>
                    </div>
                    <div class="col-md-10">
                        <b><?= date('Y-m-d')?></b>
                    </div>
              </div>
              <div class="form-group row" style="padding-top: 5px">
                    <div class="col-md-4">
                        <div class="form-group">
                            <input type="hidden" name="location_from" value="1">
                            <p class="m-0">Location From :</p>
                            <select required name="loc_from" class="custom-select">
                                <option hidden value="" disabled>Select Location</option>
                                <?= render_options($location_from, 'location_id', 'name') ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="form-group row">
                              <p class="m-0">Location To:</p>
                              <select required name="loc_to" class="custom-select">
                                  <option hidden value="" disabled>Select Location</option>
                                  <?= render_options($location, 'location_id', 'name') ?>
                              </select>
                        </div>
                    </div>
              </div>
              <div class="row">
                <table class="table table-bordered table-striped stockTB">
                  <thead>
                    <tr>
                      <th>Product Name</th>
                      <th>Quantity To Transfer</th>
                      <th>Source Stock</th>
                      <th>After Transfer</th>
                      <th>Destination Stock</th>
                      <th>After Transfer</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr style="text-align:center">
                      <td colspan="9"><b><a class="text-info ST_addmore" name="button">
                        <i class=" fas fa-plus"></i> Add More</a></b></td>
                      </tr>
                    </tbody>
                  </table>
              </div>

          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-info dyna_btn">Submit</button>
            <button type="button" class="btn btn-inverse" data-dismiss="modal">Close</button>
          </div>
          </form>
        </div>
    </div>
</div>


<div id="viewTransfer"  class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl modal-dialog-centered animated bounceIn show" role="document">
        <div class="modal-content">
            <input type="hidden" class="type" name="" value="">
          <form id="transferForm">
          <div class="modal-body">
              Transfer Stocks
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              <div class="form-group row" style="padding-top: 20px;padding-bottom: 10px; margin-left: 9px;border-bottom: 1px solid #909a9f">
                    <div class="col-md-2">
                        <b>Transfer Date:</b>
                    </div>
                    <div class="col-md-10 dater">
                        <b><?= date('Y-m-d')?></b>
                    </div>
              </div>
              <div class="form-group row" style="padding-top: 5px">
                    <div class="col-md-4">
                        <div class="form-group">
                            <input type="hidden" name="location_from" value="1">
                            <p class="m-0">Location From :</p>
                            <h3 class="from">NULL</h3>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <input type="hidden" name="location_from" value="1">
                            <p class="m-0">Location To :</p>
                            <h3 class="to">NULL</h3>
                        </div>
                    </div>

                    <div class="col-md-4 text-right">
                      <div class="form-group">
                          <h3 class="status"><span class="label label-info label-rounded">For Delivery</span></h3>
                      </div>
                    </div>
              </div>

              <table class="table table-bordered table-striped stockTbl"></table>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
          </form>
        </div>
    </div>
</div>


<div id="receivedView"  class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl modal-dialog-centered animated bounceIn show" role="document">
        <div class="modal-content">
            <input type="hidden" class="type" name="" value="">
          <form id="transferForm">
          <div class="modal-body">
              Transfer Stocks
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              <div class="form-group row" style="padding-top: 20px;padding-bottom: 10px; margin-left: 9px;border-bottom: 1px solid #909a9f">
                    <div class="col-md-2">
                        <b>Transfer Date:</b>
                    </div>
                    <div class="col-md-10 dater">
                        <b><?= date('Y-m-d')?></b>
                    </div>
              </div>
              <div class="form-group row" style="padding-top: 5px">
                    <div class="col-md-4">
                      <div class="form-group">
                          <p class="m-0">Location From:</p>
                          <h3 class="from">NULL</h3>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                          <p class="m-0">Location To:</p>
                          <h3 class="to">NULL</h3>
                      </div>
                    </div>
                    <div class="col-md-4 text-right">
                      <div class="form-group">
                          <h3 class="status"><span class="label label-success label-rounded">Received</span></h3>
                      </div>
                    </div>
              </div>

              <table class="table table-bordered table-striped recTbl"></table>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
          </form>
        </div>
    </div>
</div>
