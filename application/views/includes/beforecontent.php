<!-- Base Url -->
<input type="hidden" id="base_url" value="<?= base_url(); ?>">
<input type="hidden" id="user_type" value="<?= $this->session->type?>">

<!-- Sales Modal -->
<?php if ($this->session->type == 2 && !isset($this->session->location)): ?>
    <div class="modal-mask">
        <div class="modal-dialog animated bounceIn location-container">
            <form id="setlocationform" action="<?= base_url('sales/setlocation') ?>" method="post">
                <div class="modal-content">
                    <div class="row m-b-20 text-center">
                        <i class="fas fa-map-pin faa-vertical animated" style="font-size:5em; margin:0 auto"></i>
                    </div>
                    <div class="row">
                        <h4>Choose your location</h4>
                    </div>

                    <div class="row m-t-10">
                        <select class="form-control select-picker" name="location">
                            <option hidden disabled selected>Select a location</option>
                            <?php foreach ($locations as $loc): ?>
                                <option value="<?= $loc['location_id'] ?>"><?= $loc['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="row m-t-20">
                        <button class="btn btn-info form-control esc-input" style="color:#fff" type="submit">Proceed </button>
                    </div>
                    <div class="row m-t-5">
                        <a href="<?= base_url('logout') ?>" class="btn btn-warning form-control esc-input" style="color:#fff">Logout </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>


<!-- PreLoader -->
<div class="preloader">
    <div class="loader">
        <div class="loader__figure"></div>
        <p class="loader__label">VGE Trading Ventures</p>
    </div>
</div>
