<div id="purchaseModal"  class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered animated bounceIn show" role="document">
        <div class="modal-content">
            <input type="hidden" class="type" name="" value="">

          <form id="createPurchase">
          <div class="modal-body">
              New Purchase Order
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              <div class="form-group row" style="padding-top: 40px">
                    <label for="example-month-input2" class="col-2 col-form-label">Supplier</label>
                    <div class="col-4">
                        <select required name="supplier" class="custom-select col-12">
                        <option hidden >Select Supplier</option>
                        <option value="Oryspa">Oryspa</option>
                        </select>
                    </div>
              </div>
              <div class="row">
                 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                     <table class="table table-bordered table-striped purchaseTB">
                         <thead>
                             <tr>
                                 <th>Item Name</th>
                                 <th>Quantity</th>
                                 <th>After</th>
                                 <th>Cost per Product</th>
                                 <th>Total Price</th>
                                 <th></th>
                             </tr>
                         </thead>
                         <tbody>
                             <tr style="text-align:center">
                                 <td colspan="6"><b><a class="text-info PO_addmore" name="button">
                                    <i class=" fas fa-plus"></i> Add More</a></b></td>
                             </tr>
                         </tbody>
                     </table>
                 </div>
              </div>


          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-info dyna_btn">Create</button>
            <button type="button" class="btn btn-inverse" data-dismiss="modal">Close</button>
          </div>
          </form>
        </div>
    </div>
</div>


<div id="viewPurchaseMod" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered animated bounceIn show" role="document">
        <div class="modal-content">
          <div class="modal-body">

              <button type="button" class="close prep-me" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              <div class="row">
                  <div class="col-md-12">
                      <div class="row">
                          <div class="col-md-6">
                              List Of Items
                          </div>
                          <div class="col-md-6" style="text-align: right">
                              Supplier: <strong class="supplier"></strong>
                          </div>
                      </div>
                      <hr>
                      <table id="viewTbl" border="0"  class="table"></table>
                  </div>
              </div>
              <hr>
              <div class="row under_items m-t-50">
                  <div class="col-md-6">

                  </div>
                  <div class="col-md-6">
                      <div class="row text_decor">
                          <div class="col-md-8">
                              <b style="margin-left:20px">Total Unit:</b>
                          </div>
                          <div class="col-md-2">
                            <span class="unit"></span>
                          </div>
                      </div>

                      <div class="row text_decor">
                          <div class="col-md-8">
                              <b style="margin-left:20px">Total Amount:</b>
                          </div>
                          <div class="col-md-2">
                            <span class="amt"></span>
                          </div>
                      </div>

                  </div>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-info editPurchase">Edit</button>
            <button type="button" class="btn btn-inverse" data-dismiss="modal">Close</button>
          </div>
        </div>
    </div>
</div>

<div id="receivePurchaseMod" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered animated bounceIn show" role="document">
        <div class="modal-content">
        <form id="recieveForm">
          <div class="modal-body">
              <div class="modal-header">
                  <h3>Receive Items</h3>
                  <button type="button" class="close prep-me" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="row">
                  <div class="col-md-12">
                      <div class="row m-b-20 m-t-40">
                          <div class="col-md-6">
                              List Of Items
                          </div>
                          <div class="col-md-6" style="text-align: right">
                              Supplier: <strong class="supplier"></strong>
                          </div>
                      </div>
                      <div class="row">
                          <table class="table receive uni_rec" border="0">
                              <thead>
                                  <td>Item Name</td>
                                  <td>To Receive</td>
                                  <td>Actual Qty.</td>
                                  <td>Cost per Product</td>
                                  <td>Total Price</td>
                              </thead>
                              <tbody></tbody>
                          </table>
                      </div>
                  </div>
              </div>
              <hr>
              <div class="row under_items m-t-50">
                  <div class="col-md-6">
                      <div class="alert alert-info greetSector1">
                        <h3 class="text-info"><i class="fa fa-exclamation-triangle"></i> Eyes Here</h3>
                        <div class="p-t-10">
                            Hello there!. If you have noticed that there are items that dont exist on your order or the delivered product didn't have the correct quantity. Please click on this button
                            <br> <button  class="btn rec_in btn-info btn-sm m-t-10" >Record Discrepancy</button>

                        </div>
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="row text_decor">
                          <div class="col-md-8">
                              <b style="margin-left:20px">Total Unit:</b>
                          </div>
                          <div class="col-md-2">
                            <span class="unit"></span>
                          </div>
                      </div>

                      <div class="row text_decor">
                          <div class="col-md-8">
                              <b style="margin-left:20px">Total Amount:</b>
                          </div>
                          <div class="col-md-2">
                            <span class="amt"></span>
                          </div>
                      </div>

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


<div id="anomalySector1" class="modal" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered animated bounceIn show" role="document">
        <div class="modal-content">
        <form id="incidentSector">
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
                    <li>2) For each item, enter the number of items that you've received in the Actual Qty Received column.</li>
                    <li>3) Click "Add more" button if you received extra items you didn't order.</li>
                </ul>
              </div>
              <input type="hidden" class="poid_anom" name="poid" value="">
              <div class="row">
                  <div class="col-md-12">
                      <table class="table anomalySector1" border="0">
                          <thead>
                              <td>Item Name</td>
                              <td>To Receive</td>
                              <td>Actual Qty.</td>
                              <td>Cost per Product</td>
                              <td>Total Price</td>
                          </thead>
                          <tbody></tbody>
                      </table>
                  </div>
              </div>

          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-info">Confirm</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          </div>
        </form>
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
                              <th>Received Qty.</th>
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
