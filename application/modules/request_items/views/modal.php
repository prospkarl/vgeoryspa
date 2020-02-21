<div id="viewModal"  class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered animated bounceIn show" role="document">
        <div class="modal-content">
            <div class="modal-header">
              Request Items
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="row m-b-20">
                 <div class="col-6">
                     <h3>List of Items</h3>
                 </div>
                 <div class="col-6 text-right">
                     <h3 class="status"></h3>
                 </div>
              </div>
              <div class="row">
                  <div class="col-12">
                      <table class="table table-bordered table-striped purchaseTB">
                          <thead>
                              <tr>
                                  <th>Item Name</th>
                                  <th width="25%">Quantity</th>
                              </tr>
                          </thead>
                          <tbody> </tbody>
                      </table>
                  </div>
              </div>
              <div class="row remarks-container">
                  <div class="col-12">
                      <h4>Remarks:</h4>
                      <h6 class="remarks"></h6>
                  </div>
              </div>
            </div>
            <div class="modal-footer" style="display:block">
                <div class="row m-t-10">
                    <div class="col-3 text-left">
                        <small>Created By:</small>
                        <h5 class="requested_by">-</h5>
                    </div>
                    <div class="col-3 text-left">
                        <small>Date Created:</small>
                        <h5 class="date_created">-</h5>
                    </div>
                    <div class="col-6 text-right">
                        <a href="<?= base_url('receive') ?>" class="btn btn-info waves-effect receive-btn m-r-10">Receive</a>
                        <button type="submit" data-dismiss="modal" class="btn btn-inverse waves-effect action-btn">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="createRequest"  class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered animated bounceIn show" role="document">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                  Request Items
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <div class="row m-b-20">
                     <div class="col-6">
                         <h3>List of Items</h3>
                     </div>
                     <div class="col-6 text-right">
                         <h3 class="status"></h3>
                     </div>
                  </div>
                  <div class="row">
                      <div class="col-12">
                          <table class="table table-bordered table-striped purchaseTB">
                              <thead>
                                  <tr>
                                      <th>Item Name</th>
                                      <th width="30%">Available Items (CSO)</th>
                                      <th width="15%">Qty</th>
                                      <th width="10%">After</th>
                                      <th width="10%">Action</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <tr style="text-align:center">
                                      <td colspan="9"><b><a class="text-info add_more" name="button">
                                         <i class=" fas fa-plus"></i> Add More</a></b></td>
                                  </tr>
                              </tbody>
                          </table>
                      </div>
                  </div>
                </div>
                <div class="modal-footer" style="display:block">
                    <div class="row m-t-10">
                        <div class="col-3 text-left"> </div>
                        <div class="col-3 text-left"> </div>
                        <div class="col-6 text-right">
                            <button type="submit" class="btn btn-info waves-effect action-btn">Create</button>
                            <button data-dismiss="modal" class="btn btn-inverse waves-effect action-btn">Close</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
