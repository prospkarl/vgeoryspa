<div class="row m-b-40">
    <div class="col-12 text-right">
        <button type="button" name="button" class="btn btn-info" data-toggle="modal" data-target="#underMaintenance">Save as pdf <i class="mdi mdi-printer"></i></button>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-6 d-flex align-items-center">
                <h1 class="text-themecolor font-weight-bold">Void Sale: #<?= $invoice['display_id'] ?></h1>
                <div class="m-l-20 align-middle">
                    <span class="label label-<?= $invoice['status_color'] ?> label-rounded" style="font-size: 13px; padding: 6px 15px;"><?= $invoice['status'] ?></span>
                </div>
            </div>
            <div class="col-lg-6 text-right">
                <div class="row justify-content-end" style="margin:0">
                    <h4 class="font-weight-bold muted"><?= date("F d, Y", strtotime($invoice['date_issued'])) ?></h4> &nbsp; » &nbsp;
                    <h4 class="font-weight-bold"><?= date("F d, Y", strtotime($new_invoice['date_issued'])) ?></h4>
                </div>
                <small class="font-weight-bold">By: <?= ucwords($invoice['fname'] . ' ' . $invoice['lname']) ?></small>
            </div>
        </div>
        <hr>
        <div class="row m-t-20 m-b-40">
            <div class="col-lg-3 col-md-12">
                <h4 class="font-weight-bold">FROM:</h4>
                <h4>VGE Trading</h4>
                <h4>
                    <?php if ($invoice['location'] != $new_invoice['location']): ?>
                        <span class="muted"><?= $invoice['location'] ?></span> -
                    <?php endif; ?>
                    <?= $new_invoice['location'] ?>
                </h4>
            </div>
            <div class="col-lg-3 col-md-12">
                <h4 class="font-weight-bold">TO:</h4>
                <h4>
                    <?php if ($invoice['customer_name'] != $new_invoice['customer_name']): ?>
                        <span class="muted"><?= ucwords($invoice['customer_name']) ?> »
                    <?php endif; ?>
                    </span><?= $new_invoice['customer_name'] ? ucwords($new_invoice['customer_name']) : '' ?>
                </h4>
                <h4>
                    <?php if ($invoice['customer_contact'] != $new_invoice['customer_contact']): ?>
                        <span class="muted"><?= ucwords($invoice['customer_contact']) ?></span> »
                    <?php endif; ?>
                    <?= $new_invoice['customer_contact'] ? ucwords($new_invoice['customer_contact']) : '' ?>
                </h4>
                <h4>
                    <?php if ($invoice['customer_email'] != $new_invoice['customer_email']): ?>
                        <span class="muted"><?= ucwords($invoice['customer_email']) ?> »
                    <?php endif; ?>
                    </span><?= $new_invoice['customer_email'] ? ucwords($new_invoice['customer_email']) : '' ?>
                </h4>
            </div>
            <div class="col-lg-3 col-md-12"></div>
            <div class="col-lg-3 col-md-12 text-right">
                <?php if ($new_invoice['payment_method'] != $invoice['payment_method']): ?>
                    <span class="btn btn-inverse btn-rounded"><?= strtoupper($invoice['payment_method']) ?></span> »
                <?php endif; ?>
                <span class="btn btn-success btn-rounded"><?= strtoupper($new_invoice['payment_method']) ?></span>
            </div>
        </div>
        <br>
        <hr>
        <div class="row m-t-20 m-b-20">
            <div class="col-md-6 col-sm-12">
                <div class="mask muted-mask"> </div>
                <h2 class="font-weight-bold muted">PREVIOUS ITEMS:</h2>
                <table class="table table-hover table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center muted" width="10%">#</th>
                            <th class="text-left muted">Product Name</th>
                            <th class="text-right muted" width="10%">Quantity</th>
                            <th class="text-right muted" width="15%">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($invoice['items_parsed'] as $key => $item): ?>
                            <tr>
                                <td class="text-center muted"><?= $key + 1 ?></td>
                                <td class="text-left muted"><?= $item->item_name ?></td>
                                <td class="text-right muted"><?= $item->qty ?> </td>
                                <td class="text-right muted">₱ <?= $item->total + $item->discount  ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6 col-sm-12">
                <h2 class="font-weight-bold">NEW ITEMS:</h2>
                <table class="table table-hover table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center" width="10%">#</th>
                            <th class="text-left">Product Name</th>
                            <th class="text-right" width="10%">Quantity</th>
                            <th class="text-right" width="15%">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($new_invoice['new_items_parsed'] as $key => $item): ?>
                            <tr>
                                <td class="text-center"><?= $key + 1 ?></td>
                                <td class="text-left"><?= $item->item_name ?></td>
                                <td class="text-right"><?= $item->qty ?> </td>
                                <td class="text-right">₱ <?= $item->total + $item->discount  ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <hr />
        <div class="row m-t-40">
            <div class="col-md-6 col-sm-12">
                <h3 class="font-weight-bold">REASON FOR VOID:</h3>
                <h4><?= $invoice['remarks'] != '' ? $invoice['remarks'] : 'None' ?></h4>
            </div>
            <div class="col-md-6 col-sm-12 align-bottom">
                <table class="table">
                    <tr>
                        <input type="hidden" name="total-items" value="">
                        <td>Total Items:</td>
                        <td class="text-right total-items"><?= $invoice['total_items'] ?></td>
                    </tr>
                    <tr>
                        <input type="hidden" name="total-items" value="">
                        <td>Subtotal:</td>
                        <td class="text-right total-items"><?= number_format($invoice['total_amount'] + $invoice['total_discount']) ?></td>
                    </tr>
                    <tr>
                        <input type="hidden" name="total-items" value="">
                        <td>Discount:</td>
                        <td class="text-right total-items">- <?= number_format($invoice['total_discount']) ?></td>
                    </tr>
                    <tr class="border-bottom table-success">
                        <input type="hidden" name="total-amount" value="">
                        <td><h2 class="text-themecolor font-weight-bold">Total:</h2></td>
                        <td class="text-right"><h2><span class="total-amount"><?= money_format('%i', $invoice['total_amount']) ?></span></h2></td>
                    </tr>
                </table>
            </div>
        </div>
        <hr>
        <div class="row m-t-30">
            <div class="col-12 text-center">
                <button type="button" class="btn btn-md btn-info m-r-20 action-btn" data-action="approve" data-sales_id="<?= $invoice['sales_id'] ?>">Approve</button>
                <button type="button" class="btn btn-md btn-danger m-r-20 action-btn" data-action="decline" data-sales_id="<?= $invoice['sales_id'] ?>">Decline</button>
            </div>
        </div>
    </div>
</div>
