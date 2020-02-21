<div class="row m-b-40">
    <div class="col-12 text-right">
        <?php if ($invoice['status'] == 'COMPLETED'): ?>
            <a href="<?= base_url('sales_order/edit/' . $this->uri->segment(3)) ?>" class="btn btn-inverse edit-order">Void Order <i class="mdi mdi-close-circle-outline"></i></a> &nbsp;
        <?php endif; ?>
        <button type="button" name="button" class="btn btn-info domtoimage" data-target="invoiceContainer" data-name="invoice">Save <i class="mdi mdi-printer"></i></button>
        <!-- <button type="button" name="button" class="btn btn-info print-invoice">Save as pdf <i class="mdi mdi-printer"></i></button> -->
    </div>
</div>
<div id="invoiceContainer" class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-6 d-flex align-items-center">
                <h1 class="text-themecolor font-weight-bold">#<?= $invoice['display_id'] ?></h1>
                <div class="m-l-20 align-middle">
                    <span class="label label-<?= $invoice['status_color'] ?> label-rounded" style="font-size: 13px; padding: 6px 15px;"><?= $invoice['status'] ?></span>
                    <?php if ($invoice['status'] == 'VOID'): ?>
                        <span class="label label-warning label-rounded" style="font-size: 13px; padding: 6px 15px;"><?= strtoupper('APPROVED BY: ' . $invoice['voided_by']) ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-6 text-right">
                <h4 class="font-weight-bold"><?= date("F d, Y", strtotime($invoice['date_issued'])) ?></h4>
                <small class="font-weight-bold">By: <?= ucwords($invoice['fname'] . ' ' . $invoice['lname']) ?></small>
            </div>
        </div>
        <hr>
        <div class="row m-t-20 m-b-40">
            <div class="col-md-3 col-sm-12">
                <h4 class="font-weight-bold">FROM:</h4>
                <h4>VGE Trading</h4>
                <h4><?= $invoice['location'] ?></h4>
            </div>
            <div class="col-md-3 col-sm-12">
                <h4 class="font-weight-bold">TO:</h4>
                <h4><?= ucwords($invoice['customer_name']) ?></h4>
                <h4><?= ucwords($invoice['customer_contact']) ?></h4>
                <h4><?= ucwords($invoice['customer_email']) ?></h4>
            </div>
            <div class="col-md-3 col-sm-12">
                <h4 class="font-weight-bold"><?= isset($invoice['void_to']) ? 'REASON FOR VOID' : 'REMARKS' ?>:</h4>
                <h4><?= !empty($invoice['remarks']) ? $invoice['remarks'] : 'None' ?></h4>
            </div>
            <div class="col-md-3 col-sm-12 text-right">
                <span class="btn btn-success btn-rounded"><?= strtoupper($invoice['payment_method']) ?></span>
            </div>
        </div>
        <br>
        <div class="row m-t-40">
            <div class="col-12 text-center">
                <h2 class="font-weight-bold">LIST OF ITEMS:</h2>
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
                        <?php foreach ($invoice['items_parsed'] as $key => $item): ?>
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
        <div class="row m-t-40">
            <div class="col-6"></div>
            <div class="col-6 align-bottom">
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
    </div>
</div>
