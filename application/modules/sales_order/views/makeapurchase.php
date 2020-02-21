<form id="makeapurchase" action="<?= base_url() . 'sales_order/submit' ?>" method="post">
    <div class="card">
        <div class="card-body">
            <div class="row m-b-40">
                <div class="col-6">
                    <div class="form-group">
                        <h1 class="text-themecolor font-weight-bold">Purchase Information </h1>
                    </div>
                </div>
                <div class="col-6 text-right">
                    <input type="hidden" name="display_id" value="<?= $display_id ?>">
                    <div class="form-group">
                        <h1 class="text-themecolor font-weight-bold">#<?= $display_id ?> </h1>
                    </div>
                </div>
            </div>
            <div class="row m-b-20">
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="hidden" name="issued_by" value="<?= $this->session->id ?>">
                        <h5 class="text-themecolor font-weight-bold">Issued By:</h5>
                        <h3><?= $issued_by_name ?></h3>
                    </div>
                </div>
                <div class="col-md-6 text-right">
                    <div class="form-group">
                        <input type="hidden" name="date_issued" value="<?= date('Y-m-d') ?>">
                        <h5 class="text-themecolor font-weight-bold">Date Issued:</h5>
                        <h3><?= date('F d, Y') ?></h3>
                    </div>
                </div>
            </div>
            <div class="row m-b-20">
                <div class="col-lg-3 col-md-6">
                    <div class="form-group">
                        <h5 class="text-themecolor font-weight-bold">Payment:</h5>
                        <select class="form-control" name="payment">
                            <option value="cash">Cash</option>
                            <option value="gcash">Gcash</option>
                            <option value="card">Debit / Credit</option>
                            <option value="cheque">Cheque</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="form-group">
                        <h5 class="text-themecolor font-weight-bold">Sold To:</h5>
                        <input class="form-control" type="text" name="sold_to" value="" required>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="form-group">
                        <h5 class="text-themecolor font-weight-bold">Contact No (Optional):</h5>
                        <input class="form-control" type="text" name="contact_no" value="">
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="form-group">
                        <h5 class="text-themecolor font-weight-bold">Email (Optional):</h5>
                        <input class="form-control" type="text" name="email" value="">
                    </div>
                </div>
            </div>
            <hr class="m-b-40" />
            <div class="row">
                <div class="col-6">
                    <h5 class="text-themecolor font-weight-bold m-b-30">List of Items:</h5>
                </div>
                <div class="col-6 text-right">
                    <button class="btn btn-sm btn-danger" type="button" name="button" onclick="clearItems()">
                        Clear Items <i class="mdi mdi-close"></i>
                    </button>
                </div>
            </div>
            <div class="row m-b-40">
                <div class="col-12">
                    <table id="items" class="table table-bordered table-striped sales-order-items">
                        <thead>
                            <tr>
                                <th width="40%">Item Name</th>
                                <th width="10%">Quantity</th>
                                <th width="20%">Available</th>
                                <th>Price</th>
                                <th>Discount (%)</th>
                                <th>Total</th>
                                <th width="10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="100%" class="text-center add-more" style="cursor: pointer;background:#bcefbb;">
                                    <button class="btn btn-sm btn-outline text-green" type="button" name="button"><i class="fa fa-plus"></i> Add Item</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <hr>
            <div class="row m-t-40">
                <div class="col-6">
                    <h5 class="text-themecolor font-weight-bold">Remarks:</h5>
                    <textarea name="remarks" class="form-control" rows="3" cols="80" placeholder="Remarks"></textarea>
                </div>
                <div class="col-6 align-bottom">
                    <table class="table">
                        <tr>
                            <input type="hidden" name="total-items" value="">
                            <td>Total Items:</td>
                            <td class="text-right total-items">0</td>
                        </tr>
                        <tr>
                            <input type="hidden" name="total-items" value="">
                            <td>Subtotal:</td>
                            <td class="text-right subtotal">0</td>
                        </tr>
                        <tr>
                            <input type="hidden" name="total-items" value="">
                            <td>Discount:</td>
                            <input type="hidden" name="total-discount" value="">
                            <td class="text-right">- <span class="overall_discount">0</span></td>
                        </tr>
                        <tr class="border-bottom">
                            <input type="hidden" name="total-amount" value="">
                            <td><h2 class="text-themecolor font-weight-bold">Total:</h2></td>
                            <td class="text-right"><h2>₱ <span class="total-amount">0</span></h2></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="row m-t-40">
                <div class="col-12 text-right">
                    <button class="btn btn-md btn-info" type="submit" name="button">Submit</button>
                </div>
            </div>
        </div>
    </div>
</form>
