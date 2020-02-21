<?php
$location_name = '';

foreach ($locations as $loc_info) {
    if ($invoice['location'] == $loc_info['location_id']) {
        $location_name = $loc_info['name'];
    }
}

 ?>
<div class="card invoice-container">
    <div class="card-body">
        <form action="<?= base_url('sales_order/editsubmit') ?>" method="post">
            <div class="row m-b-40">
                <div class="col-6">
                    <div class="form-group">
                        <h1 class="text-themecolor font-weight-bold">Request Void </h1>
                    </div>
                </div>
                <div class="col-6 text-right">
                    <?php
                    $inv_id = $this->uri->segment(3);
                     ?>
                    <input type="hidden" name="void_invoice" value="<?= $inv_id  ?>">
                    <div class="form-group">
                        <h1 class="text-themecolor font-weight-bold">#<?= $invoice['display_id'] ?> </h1>
                    </div>
                </div>
            </div>
            <div class="row m-b-20">
                <div class="col-lg-4 col-md-12">
                    <div class="form-group">
                        <input type="hidden" name="issued_by" value="<?= $this->session->id ?>">
                        <h5 class="text-themecolor font-weight-bold">Issued By:</h5>
                        <h3><?= ucwords($invoice['fname'] . ' ' . $invoice['lname']) ?></h3>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12">
                    <div class="form-group">
                        <input type="hidden" name="date_issued" value="<?= date('Y-m-d') ?>">
                        <h5 class="text-themecolor font-weight-bold">Date Issued:</h5>
                        <h3><?= date("F d, Y", strtotime($invoice['date_issued'])) ?></h3>
                        <input type="text" name="date_issued" value="<?= date("Y-m-d", strtotime($invoice['date_issued'])) ?>" class="form-control" hidden>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12">
                    <h5 class="font-weight-bold">Location Sold:</h5>
                    <h3><?= $location_name ?></h3>
                    <select class="form form-control" name="location" hidden>
                        <?php foreach ($locations as $loc): ?>
                            <option value="<?= $loc['location_id'] ?>" <?= ($invoice['location'] == $loc['location_id']) ? 'selected' : '' ?>><?= $loc['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <hr>
            <div class="row m-t-40">
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
                        <input class="form-control" type="text" name="sold_to" value="<?= $invoice['customer_name'] ?>" required>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="form-group">
                        <h5 class="text-themecolor font-weight-bold">Contact No (Optional):</h5>
                        <input class="form-control" type="text" name="contact_no" value="<?= ucwords($invoice['customer_contact']) ?>">
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="form-group">
                        <h5 class="text-themecolor font-weight-bold">Email (Optional):</h5>
                        <input class="form-control" type="text" name="email" value="<?= ucwords($invoice['customer_email']) ?>">
                    </div>
                </div>
            </div>
            <div class="row m-t-40 m-b-40">
                <div class="col-12 text-left">
                    <div class="d-flex justify-content-between">
                        <h2 class="font-weight-bold">CURRENT ITEMS:</h2>
                    </div>
                    <table class="table table-hover table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center" width="10%">#</th>
                                <th class="text-left">Product Name</th>
                                <th class="text-right" width="10%">Quantity</th>
                                <th class="text-right" width="10%">Sub-Total</th>
                                <th class="text-right" width="10%">Discount</th>
                                <th class="text-right" width="15%">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($invoice['items_parsed'] as $key => $item):?>
                                <tr>
                                    <td class="text-center"><?= $key + 1 ?></td>
                                    <td class="text-left"><?= $item->item_name ?></td>
                                    <td class="text-right"><?= $item->qty ?> </td>
                                    <td class="text-right"><?= $item->total + $item->discount ?> </td>
                                    <td class="text-right"><?= $item->discount ? '-' . $item->discount : '0' ?> </td>
                                    <td class="text-right">₱ <?= $item->total  ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <hr>
            <div class="row m-b-40 m-t-40">
                <div class="col-12">
                    <div class="d-flex justify-content-between">
                        <h2 class="font-weight-bold text-themecolor">ADD/REPLACE ITEMS HERE:</h2>
                        <div class="">
                            <button class="btn btn-sm btn-danger" type="button" name="button" onclick="clearItems()">
                                Clear Items <i class="mdi mdi-close"></i>
                            </button>
                        </div>
                    </div>
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
            <hr />
            <div class="row m-t-40">
                <div class="col-lg-7 col-sm-12">
                    <h5 class="text-themecolor font-weight-bold">Reason for void:</h5>
                    <textarea name="edit_remarks" class="form-control" rows="4" cols="80" placeholder="Remarks"></textarea>
                </div>
                <div class="col-lg-5 col-sm-12 m-t-30">
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
            <div class="row m-b-30 m-t-40">
                <div class="col-12 text-right">
                    <button class="btn btn-md btn-info" type="submit" name="button">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
