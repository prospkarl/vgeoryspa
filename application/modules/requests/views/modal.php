<div id="viewRequest"  class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl modal-dialog-centered animated bounceIn show" role="document">
        <div class="modal-content">
          <div class="modal-body">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              <div class="form-group row" style="padding-top: 20px;padding-bottom: 10px; margin-left: 9px;border-bottom: 1px solid #909a9f">
                  <div class="col-md-2">
                      <b>Requested Date:</b>
                  </div>
                  <div class="col-md-4">
                      <b class="req_date"></b>
                  </div>
                  <div class="col-md-2">
                      <b>Requested By:</b>
                  </div>
                  <div class="col-md-4">
                      <b class="by"></b>
                  </div>
              </div>
              <div class="form-group row" style="padding-top: 5px">
                    <div class="col-md-4">
                        <div class="form-group">
                            <input type="hidden" name="location_requested" value="">
                            <p class="m-0">Requested From :</p>
                            <h3 class='from'>NULL</h3>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <p class="m-0">Status:</p>
                            <h3 class='status'>NULL</h3>
                        </div>
                    </div>
              </div>
              <div class="row">
                  <div class="col-md-12 col-sm-12">
                      <table class="table table-bordered table-striped not_declined reqTbl" ></table>
                  </div>
              </div>

          </div>
          <div class="modal-footer m-t-20">
              <button type="button" class="btn btn-info approveReq" data-action="CSO" data-toggle="modal" data-target="#approveModal" data-dismiss="modal">Approve from CSO</button>
              <button type="button" class="btn btn-info approveReq" data-action="Transfer" data-toggle="modal" data-target="#approveModal" data-dismiss="modal">Approve from Transfer</button>
              <button type="button" class="btn btn-danger btn_decline not_declined">Decline</button>
              <button type="button" class="btn btn-inverse" data-dismiss="modal">Close</button>
          </div>
        </div>
    </div>
</div>

<div id="approveRequest"  class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl modal-dialog-centered animated bounceIn show" role="document">
        <div class="modal-content">
          <div class="modal-body">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              <div class="form-group row" style="padding-top: 20px;padding-bottom: 10px; margin-left: 9px;border-bottom: 1px solid #909a9f">
                  <div class="col-md-2">
                      <b>Requested Date:</b>
                  </div>
                  <div class="col-md-4">
                      <b class="req_date"></b>
                  </div>
                  <div class="col-md-2">
                      <b>Requested By:</b>
                  </div>
                  <div class="col-md-4">
                      <b class="by"></b>
                  </div>
              </div>

              <div class="form-group row" style="padding-top: 5px">
                    <div class="col-md-4">
                        <div class="form-group">
                            <p class="m-0">Requested From :</p>
                            <h3 class='from'>NULL</h3>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <p class="m-0">Status:</p>
                            <h3 class='status'>NULL</h3>
                        </div>
                    </div>
              </div>
              <form id="approveStocks">
              <table class="table table-bordered table-striped approveTbl">
                  <thead>
                      <tr>
                          <th>Product Name</th>
                          <th>Quantity Requested</th>
                          <th>Quantity To Transfer</th>
                          <th>CSO Stock</th>
                          <th>After Transfer</th>
                          <th>Destination Stock</th>
                          <th>After Transfer</th>
                      </tr>
                  </thead>
                  <tbody>
                  </tbody>
              </table>

              <div class="row">
                  <div class="col-md-12">
                      <div class="form-group">
                          <label for="">Remark</label>
                          <textarea class="form-control" name="remark" rows="8" cols="80"></textarea>
                      </div>
                  </div>

              </div>
          </div>
          <div class="modal-footer">
              <button type="submit" class="btn btn-info">Transfer Stock</button>
              <button type="button" class="btn btn-inverse" data-dismiss="modal">Return</button>
          </div>
          </form>
        </div>
    </div>
</div>

<div id="declineView"  class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal modal-dialog-centered animated bounceIn show" role="document">
        <div class="modal-content">
          <div class="modal-body">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              <div class="form-group row" style="padding-top: 5px">
                    <div class="col-md-6">
                        <div class="form-group">
                            <p class="m-0">Requested Date:</p>
                            <h4 class='req_date'></h4>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <p class="m-0">Requested By:</p>
                            <h4 class='by'></h4>
                        </div>
                    </div>
              </div>
              <div class="form-group row" style="padding-top: 5px">
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="hidden" name="location_from" value="1">
                            <p class="m-0">Requested From :</p>
                            <h4 class='from'>Washington Dc</h4>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="hidden" name="location_from" value="1">
                            <p class="m-0">Status:</p>
                            <h4 class='status'>NULL</h4>
                        </div>
                    </div>
              </div>
              <h3 class='declined'>Remark</h3>
              <div class="form-group row">
                  <div class="col-md-12 remark_view" style="text-align: justify; height: 130px; border:1px solid #dcdcdc; padding: 5px; margin-bottom: 10px">

                  </div>
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-inverse" data-dismiss="modal">Return</button>
          </div>
        </div>
    </div>
</div>

<div id="declineModal"  class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered animated bounceIn show" role="document">
        <div class="modal-content">
            <input type="hidden" class="type" name="" value="">
          <form id="declineRequest">
          <div class="modal-body">
              Decline Form
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              <div class="form-group row" style="padding-top: 20px;padding-bottom: 10px; margin-left: 9px;border-bottom: 1px solid #909a9f">

              </div>
              <div class="form-group row" style="padding-top: 5px">
                    <div class="col-md-8">
                        <div class="form-group">
                            <h3>Remarks</h3>
                            <p>State the reason why you have declined this request</p>
                        </div>
                    </div>
              </div>

              <div class="form-group row">
                    <div class="col-md-12">
                        <textarea required class="form-control remark" name="remark" rows="8" cols="80"></textarea>
                    </div>
              </div>

          </div>
          <input type="hidden" id="req_hid_id" name="id" value="">
          <div class="modal-footer">
              <button type="submit" class="btn btn-info">Submit</button>
              <button type="button" class="btn btn-inverse btn_cancel_dec" data-dismiss="">Cancel</button>
          </div>
          </form>
        </div>
    </div>
</div>


<div id="approveModal"  class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl modal-dialog-centered animated bounceIn show" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="font-weight-bold modal-title" id="vcenter">Stock Transfer</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
          <form id="transferForm">
              <input type="hidden" name="req_id" value="">
          <div class="modal-body">
              <div class="form-group row" style="padding-top: 5px">
                    <div class="col-md-4">
                        <div class="form-group">
                            <p class="m-0">Location From :</p>
                            <select required name="loc_from" class="custom-select">
                                <?php foreach ($location as $loc): ?>
                                    <option
                                        value="<?= $loc['location_id'] ?>"
                                        <?= ($loc['location_id'] == 1) ? 'hidden' : '' ?>
                                        <?= ($loc['location_id'] == 2) ? 'selected' : '' ?>
                                    >
                                        <?= $loc['name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="form-group row">
                              <p class="m-0">Location To:</p>
                              <select required name="loc_to" class="custom-select">
                                  <?php foreach ($location as $loc): ?>
                                      <option
                                          value="<?= $loc['location_id'] ?>"
                                          hidden
                                      >
                                          <?= $loc['name'] ?>
                                      </option>
                                  <?php endforeach; ?>
                              </select>
                        </div>
                    </div>
              </div>
              <div class="row m-b-30" id="outofstockitems">
                  <div class="col-md-12 col-sm-12">
                      <h3 class="font-weight-bold">Out of stock items</h3>
                      <table class="table table-bordered table-striped">
                          <thead>
                              <tr>
                                  <th>Product Name</th>
                                  <th width="30%">Status</th>
                              </tr>
                          </thead>
                          <tbody>
                          </tbody>
                      </table>
                  </div>
              </div>
              <div class="row m-b-20">
                  <div class="col-md-12 col-sm-12">
                      <h3 class="font-weight-bold">List of Items</h3>
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
              <div class="row">
                  <div class="col-12">
                      <h3 class="font-weight-bold">Remarks</h3>
                      <textarea class="form-control remark" name="remark" rows="5" cols="80" placeholder="Input remarks here"></textarea>
                  </div>
              </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-info dyna_btn">Submit</button>
            <button type="button" class="btn btn-inverse" data-toggle="modal" data-target="#viewRequest" data-dismiss="modal">Return</button>
          </div>
          </form>
        </div>
    </div>
</div>
