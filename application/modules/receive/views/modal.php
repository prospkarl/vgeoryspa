<!-- View Modal -->
<div id="sd" class="modal" tabindex="-1" role="dialog" aria-labelledby="vcenter" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered animated bounceIn">
        <div class="modal-content">
            <form class="" action="<?= base_url('receive/receiveItems') ?>" method="post">
                <input type="hidden" name="transfer_id" value="">
                <div class="preloader-container">
                    <div class="preloader">
                        <i class="fa fa-wrench faa-wrench animated" style="font-size:5em"></i>
                    </div>
                </div>
                <div class="modal-header">
                    <h4 class="modal-title" id="vcenter">Receive Items</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Expected Quantity</th>
                                        <th width="30%">Quantity Received</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row m-t-20">
                        <div class="col-12">
                            <h4 class="font-weight-bold">Remarks:</h4>
                            <h5 class="remarks">-</h5>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="display:block">
                    <div class="row m-t-10">
                        <div class="col-3 text-left">
                            <small>Created By:</small>
                            <h5 class="transfer_by">John Doe</h5>
                        </div>
                        <div class="col-3 text-left">
                            <small>Date Created:</small>
                            <h5 class="date_created">-</h5>
                        </div>
                        <div class="col-6 text-right">
                            <button type="submit" data-toggle="modal" data-target="#editProduct" class="btn btn-info waves-effect action-edit">Receive</button>
                            <button type="submit" data-dismiss="modal" class="btn btn-inverse waves-effect action-btn">Cancel</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Modal -->
<div id="viewTransfer" class="modal" tabindex="-1" role="dialog" aria-labelledby="vcenter" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered animated bounceIn">
        <div class="modal-content">
            <input type="hidden" name="transfer_id" value="">
            <div class="preloader-container">
                <div class="preloader">
                    <i class="fa fa-wrench faa-wrench animated" style="font-size:5em"></i>
                </div>
            </div>
            <div class="modal-header">
                <h4 class="modal-title" id="vcenter">Receive Items</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <div class="row m-b-20">
                    <div class="col-6">
                        <h4 class="font-weight-bold">List of Items</h4>
                    </div>
                    <div class="col-6 text-right">
                        <button
                            type="button"
                            data-toggle="modal"
                            data-target="#viewLogs"
                            data-tablename="tbl_stocktransfer"
                            data-referrer=""
                            class="btn btn-sm btn-info toggle-log"
                        >View History</i></button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Expected Quantity</th>
                                    <th width="30%">Quantity Received</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <div class="row m-t-20">
                    <div class="col-12">
                        <h4 class="font-weight-bold">Remarks:</h4>
                        <h5 class="remarks">-</h5>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="display:block">
                <div class="row m-t-10">
                    <div class="col-3 text-left">
                        <small>Created By:</small>
                        <h5 class="transfer_by">John Doe</h5>
                    </div>
                    <div class="col-3 text-left">
                        <small>Date Received:</small>
                        <h5 class="date_created">-</h5>
                    </div>
                    <div class="col-6 text-right">
                        <button type="submit" data-dismiss="modal" class="btn btn-inverse waves-effect action-btn">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="anomalySector2" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal modal-dialog-centered animated bounceIn show" role="document">
        <div class="modal-content">
        <form id="descSector">
          <div class="modal-body">
              <div class="modal-header">
                  <h3>Discrepancy Items</h3>
                  <button type="button" class="close prep-me" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>

              <input type="hidden" class="poid_anom" name="poid" value="">
              <div class="row">
                  <div class="col-md-12">
                      <table class="table">
                          <thead>
                              <th>Product Name</th>
                              <th>Expected Qty.</th>
                              <th>Recieved Qty.</th>
                          </thead>
                          <tbody class="ari_ibutang">

                          </tbody>
                      </table>
                  </div>
              </div>
              <hr />
              <div class="row">
                  <div class="col-12">
                      <div class="form-group">
                          <h3 class="font-weight-bold">Reason:</h3>
                          <h2 class="reason_for_disc">-</h2>
                      </div>
                  </div>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          </div>
        </form>
        </div>
    </div>
</div>

<div id="receiveModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered animated bounceIn show" role="document">
        <div class="modal-content">
        <form id="incidentSector">
            <input type="hidden" name="transfer_id" value="">
          <div class="modal-body">
              <div class="modal-header">
                  <h3>Received Items</h3>
                  <button type="button" class="close prep-me" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div style="display:none" class="alert alert-warning greetSector1">
                <h3 class="text-warning"><i class="fa fa-exclamation-triangle"></i> Eyes Here!</h3>Please follow the instructions below when receiving the items:<br><a href="#" class="showInstruction" style="display: none">Show Instruction</a><a href="#" class="hideInstruction">Hide Instruction</a>
                <ul class="instruction">
                    <li>1) Do a physical count of the merchandise to verify the quantity and type of product received.</li>
                    <li>2) For each item, enter the number of items that you've received in the Quantity Received column.</li>
                    <li>3) Click "Add more" button if you received extra items you didn't order.</li>
                </ul>
              </div>
              <div class="row">
                  <div class="col-md-12">
                      <table class="table anomalySector1" border="0">
                          <thead>
                              <td>Item Name</td>
                              <td>Expected Quantity</td>
                              <td>Quantity Received</td>
                          </thead>
                          <tbody></tbody>
                      </table>
                  </div>
              </div>

          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-info" >Confirm</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          </div>
        </form>
        </div>
    </div>
</div>
