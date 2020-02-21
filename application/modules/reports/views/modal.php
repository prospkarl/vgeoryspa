<div id="viewSales" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered animated bounceIn show" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class='sales_id text-themecolor font-weight-bold'></h3>
                <button type="button" class="close prep-me" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

          <div class="modal-body">
              <div class="row">
                  <div class="col-md-12">
                      <div class="row">
                          <div class="col-md-6 text-themecolor font-weight-bold">
                              List Of Items
                          </div>
                      </div>
                      <hr>
                      <table class="table viewSalesTb" border="0">
                          <thead class="text-themecolor font-weight-bold">
                              <td>Item Name</td>
                              <td>Quantity</td>
                              <td>Price</td>
                              <td>Total Cost</td>
                          </thead>
                          <tbody></tbody>
                      </table>
                  </div>
              </div>
              <hr>
              <div class="row under_items m-t-50">
                  <div class="col-md-6">
                      <span class="text-themecolor font-weight-bold">Remark:</span><br>
                      <p class="remark_p"></p>

                  </div>
                  <div class="col-md-6">
                      <div class="row text_decor">
                          <div class="col-md-8 text-themecolor font-weight-bold">
                              <b style="margin-left:20px">Total Unit:</b>
                          </div>
                          <div class="col-md-4">
                            <span class="unit"></span>
                          </div>
                      </div>

                      <div class="row text_decor">
                          <div class="col-md-8 text-themecolor font-weight-bold">
                              <b style="margin-left:20px">Total Amount:</b>
                          </div>
                          <div class="col-md-4">
                            <span class="amt"></span>
                          </div>
                      </div>

                  </div>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-inverse" data-dismiss="modal">Close</button>
          </div>
        </div>
    </div>
</div>
