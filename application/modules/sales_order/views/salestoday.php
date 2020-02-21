<?php
$total_items = 0;
$total_amt = 0;


foreach ($list as $invoice):

    $total_items = $total_items + $invoice['total_items'];
    $total_amt = $total_amt + $invoice['total_amount'];

endforeach; ?>

<div class="card">
    <div class="card-body">
        <div class="row m-b-40">
            <div class="col-md-6 col-sm-12">
                <h1 class="text-themecolor font-weight-bold">Sales Today (<?= date("F d, Y") ?>)</h1>
            </div>
            <div class="col-md-6 col-sm-12 text-right">
                <a href="<?= base_url('sales_order') ?>" class="btn btn-sm btn-info">View Sales</a>
            </div>
        </div>
        <div class="row m-t-40">
            <div class="col-lg-8 col-md-12"></div>
            <div class="col-lg-4 col-md-6 align-bottom">
                <table class="table">
                    <tr>
                        <input type="hidden" name="total-items" value="">
                        <td>Total Items Sold:</td>
                        <td class="text-right total-items"><?= $total_items ?></td>
                    </tr>
                    <tr class="border-bottom table-success">
                        <input type="hidden" name="total-amount" value="">
                        <td><h2 class="text-themecolor font-weight-bold">Total:</h2></td>
                        <td class="text-right"><h2><span class="total-amount"><?= money_format('%i', $total_amt); ?></span></h2></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <table id="transfers" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Issued By</th>
                            <th>Issued To</th>
                            <th>Date Issued</th>
                            <th>Total Items</th>
                            <th>Total Amount</th>
                            <th width="10%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($list as $invoice):

                            $total_items = $total_items + $invoice['total_items'];
                            $total_amt = $total_amt + $invoice['total_amount'];

                            ?>
                            <tr>
                                <td><?= $invoice['display_id'] ?></td>
                                <td><?= ucwords($invoice['fname'] . ' ' . $invoice['lname']) ?></td>
                                <td><?= $invoice['customer_name'] ?></td>
                                <td><?= date("F d, Y", strtotime($invoice['date_issued'])) ?></td>
                                <td><?= $invoice['total_items'] ?></td>
                                <td><?= $invoice['total_amount'] ?></td>
                                <td><a class="btn btn-sm btn-rounded btn-outline-success" href="<?= base_url('sales_order/view/' . $invoice['sales_id']) ?>"><i class="fa fa-eye"></i> View</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
