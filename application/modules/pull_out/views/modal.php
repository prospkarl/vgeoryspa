<div id="pullOutModal"  class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered animated bounceIn show" role="document">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                  Pull Out Items
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                    <div class="row m-b-40">
                        <div class="col-md-6">
                            <h4>Select a location:</h4>
                            <select class="form-control" name="location">
                                <?php render_options($locations, "location_id", "name"); ?>
                            </select>
                        </div>
                    </div>
                  <div class="row m-b-40">
                      <div class="col-12">
                          <h4>List of Items</h4>
                          <table class="table table-bordered table-striped purchaseTB">
                              <thead>
                                  <tr>
                                      <th>Item Name</th>
                                      <th width="10%">Before</th>
                                      <th width="20%">Quantity</th>
                                      <th width="10%">After</th>
                                      <th width="5%">Action</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <tr style="text-align:center">
                                      <td colspan="9"><b><a class="text-info add_more" name="button">
                                         <i class=" fas fa-plus"></i> Add More</a></b>
                                       </td>
                                  </tr>
                              </tbody>
                          </table>
                      </div>
                  </div>
                  <div class="row remarks-container">
                      <div class="col-12">
                          <h4>Remarks:</h4>
                          <textarea name="remarks" class="form-control" rows="4" cols="80" required></textarea>
                      </div>
                  </div>
                </div>
                <div class="modal-footer" style="display:block">
                    <div class="row m-t-10">
                        <div class="col-12 text-right">
                            <button type="submit" class="btn btn-info waves-effect action-btn">Submit</button>
                            <button data-dismiss="modal" class="btn btn-inverse waves-effect action-btn">Close</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- View -->
<div id="viewModal"  class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered animated bounceIn show" role="document">
        <div class="modal-content">
            <div class="modal-header">
              Pull Out Items
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="row m-b-40">
                  <div class="col-12">
                      <h4>List of Items</h4>
                      <table class="table table-bordered table-striped purchaseTB">
                          <thead>
                              <tr>
                                  <th>Item Name</th>
                                  <th width="20%">Quantity</th>
                              </tr>
                          </thead>
                          <tbody></tbody>
                      </table>
                  </div>
              </div>
              <div class="row remarks-container">
                  <div class="col-6">
                      <h4>Remarks:</h4>
                      <h3 class="remarks"></h3>
                  </div>
                  <div class="col-6">
                      <h4>Location:</h4>
                      <h3 class="location"></h3>
                  </div>
              </div>
            </div>
            <div class="modal-footer" style="display:block">
                <div class="row m-t-10">
                    <div class="col-3 text-left">
                        <small>Created By:</small>
                        <h5 class="created_by">-</h5>
                    </div>
                    <div class="col-3 text-left">
                        <small>Date Created:</small>
                        <h5 class="date_created">-</h5>
                    </div>
                    <div class="col-6 text-right">
                        <button type="submit" data-dismiss="modal" class="btn btn-inverse waves-effect">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
