<!-- View Modal -->
<div id="addProduct" class="modal" tabindex="-1" role="dialog" aria-labelledby="vcenter" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered animated bounceIn">
        <div class="modal-content">
            <div id="inModal" class="custom-modal hidewhentyped modal">
                <div class="custom-modal-content animated bounceIn text-center">
                    <div class="barcode-loader">
                        <i class="mdi mdi-barcode-scan icon-lg" style=" font-size: 7em; "></i>
                        <div class="loader-container">
                            <div class="lds-facebook"><div></div><div></div><div></div><div></div></div>
                        </div>
                    </div>
                    <p>Please Scan Barcode</p>
                    <div id="interactive" class="viewport"></div>
                </div>
            </div>
            <form action="<?= base_url('products/add') ?>" method="post" novalidate>
                <div class="modal-header">
                    <h4 class="modal-title" id="vcenter">Product Information</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-8">
                            <div class="form-group">
                                <p class="m-0">Barcode No.</p>
                                <input type="text" class="form-control m-t-5 esc-input" name="barcode_no" >
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <p class="m-0">Scan Barcode</p>
                                <a href="javascript:;" data-toggle="modal" data-target="#inModal">
                                    <button class="btn btn-info form-control esc-input m-t-5" style="color:#fff" type="button">Scan <i class="mdi mdi-barcode-scan"></i></button>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <p class="m-0">SKU</p>
                                <input type="text" class="form-control m-t-5 esc-input" name="sku" />
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="d-flex justify-content-between">
                                <p class="m-0">Category</p>
                                <a tabindex="-1" href="<?= base_url('categories') ?>">Manage Categories</a>
                            </div>
                            <select class="form-control m-t-5" name="category" data-validation-required-message="This field is required" required>
                                <option value="">Select a category</option>
                                <?php render_options($categories, 'category_id', 'name'); ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <p class="m-0">Product Name</p>
                                <input type="text" class="form-control m-t-5" name="product_name" data-validation-required-message="This field is required" required>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <p class="m-0">Initial Quantity</p>
                                <input type="number" min="1" class="form-control m-t-5" name="initial_quantity" data-validation-required-message="This field is required" required>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-12">
                            <div class="form-group">
                                <p class="m-0">CSO Min Stock</p>
                                <input type="number" min="1" class="form-control m-t-5" name="min_stock_warehouse" data-validation-required-message="This field is required" required>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-lg-3  col-md-12">
                            <div class="form-group">
                                <p class="m-0">Kiosk Min Stock</p>
                                <input type="number" min="1" class="form-control m-t-5" name="min_stock_kiosk" data-validation-required-message="This field is required" required>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <p class="m-0">Product Description</p>
                            <textarea name="description" class="form-control m-t-5 esc-input" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="row m-t-40">
                        <div class="col-12">
                            <h3>Pricing</h3>
                        </div>
                    </div>
                    <div class="row m-t-10">
                        <?php if ($this->session->type != 2): ?>
                            <div class="col-6">
                                <p class="m-0">SUPPLIER'S PRICE</p>
                                <input type="text" class="form-control m-t-5" name="supplier_price" value="" data-validation-required-message="This field is required" required>
                            </div>
                        <?php endif; ?>
                        <div class="col-6">
                            <p class="m-0">Price (SRP)</p>
                            <input type="text" class="form-control m-t-5" name="price" value="" data-validation-required-message="This field is required" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="display:block">
                    <div class="row m-t-10">
                        <div class="col-6"> </div>
                        <div class="col-6 text-right">
                            <button type="submit" class="btn btn-info waves-effect action-btn">Add Product</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editProduct" class="modal" tabindex="-1" role="dialog" aria-labelledby="vcenter" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered animated bounceIn">
        <div class="modal-content">
            <div id="inEditModal" class="custom-modal hidewhentyped modal">
                <div class="custom-modal-content animated bounceIn text-center">
                    <div class="barcode-loader">
                        <i class="mdi mdi-barcode-scan icon-lg" style=" font-size: 7em; "></i>
                        <div class="loader-container">
                            <div class="lds-facebook"><div></div><div></div><div></div><div></div></div>
                        </div>
                    </div>
                    <p>Please Scan Barcode</p>
                </div>
            </div>
            <div id="void" class="custom-modal modal">
                <div class="custom-modal-content animated bounceIn text-center">
                    <div class="barcode-loader">
                        <i class="fas fa-key" style=" font-size: 5em; margin-bottom: 20px"></i>
                    </div>
                    <p>Please enter administrator password:</p>
                    <input id="void_password" type="password" class="form-control text-center" name="void_password" value="">
                </div>
            </div>
            <form action="<?= base_url('products/update') ?>" method="post" novalidate>
                <input type="hidden" class="data-product_id" name="product_id" value="">
                <div class="modal-header">
                    <h4 class="modal-title" id="vcenter">Product Information</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-8">
                            <div class="form-group">
                                <p class="m-0">Barcode No.</p>
                                <input type="text" class="form-control m-t-5 esc-input data-barcode" name="barcode_no" >
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <p class="m-0">Scan Barcode</p>
                                <a href="javascript:;" data-toggle="modal" data-target="#inEditModal">
                                    <button class="btn btn-info form-control esc-input m-t-5" style="color:#fff" type="button">Scan <i class="mdi mdi-barcode-scan"></i></button>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <p class="m-0">SKU</p>
                                <input type="text" class="form-control m-t-5 esc-input data-sku" name="sku" />
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="d-flex justify-content-between">
                                <p class="m-0">Category</p>
                                <a tabindex="-1" href="<?= base_url('categories') ?>">Manage Categories</a>
                            </div>
                            <select class="form-control m-t-5 data-category_id" name="category" data-validation-required-message="This field is required" required>
                                <option value="">Select a category</option>
                                <?php render_options($categories, 'category_id', 'name'); ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <p class="m-0">Product Name</p>
                                <input type="text" class="form-control m-t-5 data-name" name="product_name" data-validation-required-message="This field is required" required>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <div class="d-flex justify-content-between">
                                    <p class="m-0">CSO Quantity</p>
                                    <a id="openvoid" tabindex="-1" href="javascript:;" data-toggle="modal" data-target="#void"><i class="fa fa-edit"></i> Edit</a>
                                </div>
                                <input type="number" min="1" name="quantity" class="form-control m-t-5 data-qty esc-input" disabled>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-12">
                            <div class="form-group">
                                <p class="m-0">CSO Min Stock</p>
                                <input type="number" min="1" class="form-control m-t-5 data-min_stock_warehouse" name="min_stock_warehouse" data-validation-required-message="This field is required" required>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-lg-3  col-md-12">
                            <div class="form-group">
                                <p class="m-0">Kiosk Min Stock</p>
                                <input type="number" min="1" class="form-control m-t-5 data-min_stock_kiosk" name="min_stock_kiosk" data-validation-required-message="This field is required" required>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <p class="m-0">Product Description</p>
                            <textarea name="description" class="form-control m-t-5 esc-input data-description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="row m-t-40">
                        <div class="col-12">
                            <h3>Pricing</h3>
                        </div>
                    </div>
                    <div class="row m-t-10">
                        <div class="col-6">
                            <p class="m-0">Supplier's Price</p>
                            <input type="text" class="form-control m-t-5 data-supplier_price" name="supplier_price" value="" data-validation-required-message="This field is required" required>
                        </div>
                        <div class="col-6">
                            <p class="m-0">Price (SRP)</p>
                            <input type="text" class="form-control m-t-5 data-price" name="price" value="" data-validation-required-message="This field is required" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="display:block">
                    <div class="row m-t-10">
                        <div class="col-6"></div>
                        <div class="col-6 text-right">
                            <button type="submit" class="btn btn-info waves-effect action-btn">Update</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Modal -->
<div id="viewProduct" class="modal" tabindex="-1" role="dialog" aria-labelledby="vcenter" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered animated bounceIn">
        <div class="modal-content">
            <div class="preloader-container">
                <div class="preloader">
                    <i class="fa fa-wrench faa-wrench animated" style="font-size:5em"></i>
                </div>
            </div>
            <div class="modal-header">
                <h4 class="modal-title" id="vcenter">Product Information</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6 col-md-12">
                        <div class="form-group">
                            <small class="text-grey">BARCODE NUMBER</small>
                            <h4 class="form-data" id="barcode">Please wait...</h4>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="form-group">
                            <small class="text-grey">SKU</small>
                            <h4 class="form-data" id="sku">Please wait...</h4>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-12">
                        <div class="form-group">
                            <small class="text-grey" >PRODUCT NAME</small>
                            <h4 class="text-justify form-data" id="name">Please wait...</h4>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="form-group">
                            <small class="text-grey">CATEGORY</small>
                            <h4 class="text-justify form-data" id="category">Please wait...</h4>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-12">
                        <div class="form-group">
                            <small class="text-grey">LOCATION</small>
                            <h4 class="text-justify form-data" id="location">Please wait...</h4>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="form-group">
                            <small class="text-grey">QUANTITY</small>
                            <div class="d-flex">
                                <h4 class="text-justify form-data m-r-10"> <span id="qty">Please wait...</span> pc(s) </h4>
                                <a class="mytooltip" href="javascript:void(0)">
                                    <i class="fas fa-exclamation-circle text-green" style="cursor:pointer"></i>
                                    <span class="tooltip-content5">
                                        <span class="tooltip-text3">
                                            <span class="tooltip-inner2 text-left">
                                                <strong>Minimum Stocks</strong>
                                                <?php if ($this->session->type != 2): ?>
                                                    <br>
                                                    <small>CSO: <strong id="min_stock_warehouse">20</strong></small>
                                                <?php endif; ?>
                                                <br>
                                                <small>Kiosks: <strong id="min_stock_kiosk">10</strong></small>
                                            </span>
                                        </span>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <small class="text-grey">PRODUCT DESCRIPTION</small>
                            <h4 class="text-justify form-data" id="description">Please wait...</h4>
                        </div>
                    </div>
                    <?php if ($this->session->type != 2): ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <small class="text-grey">SUPPLIER'S PRICE</small>
                                <h4>₱ <span id="supplier_price"></span></h4>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="col-md-3">
                        <div class="form-group">
                            <small class="text-grey">PRICE (SRP)</small>
                            <h4>₱ <span id="price"></span></h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="display:block">
                <div class="row m-t-10">
                    <div class="col-3 text-left">
                        <small>ADDED BY</small>
                        <h5 id="added_by">John Doe</h5>
                    </div>
                    <div class="col-3 text-left">
                        <small>LAST MODIFIED</small>
                        <h5 id="date_modified">January 20, 2019</h5>
                    </div>
                    <div class="col-6 text-right">
                        <?php if ($this->session->type != 2): ?>
                            <button type="submit" data-toggle="modal" data-target="#editProduct" data-dismiss="modal" class="btn btn-warning waves-effect action-edit">Edit</button>
                        <?php endif; ?>
                        <button type="submit" data-dismiss="modal" class="btn btn-info waves-effect action-btn">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
