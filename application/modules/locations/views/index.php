<div class="card">
    <div class="card-body">
        <table id="locations" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="20%">Location ID</th>
                    <th width="50%">Location Name</th>
                    <th width="20%">Date Added</th>
                    <th width="10%">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="addLocation" class="modal" tabindex="-1" role="dialog" aria-labelledby="vcenter" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered animated bounceIn">
        <div class="modal-content">
            <form action="<?= base_url('locations/location') ?>" method="post" novalidate>
                <input type="hidden" class="form-control" name="location_id" value="">
                <div class="modal-header">
                    <h4 class="modal-title" id="vcenter">Add New Location</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <p class="m-0">Location Name</p>
                        <input type="text" class="form-control m-t-5" name="location_name" data-validation-required-message="This field is required" required>
                        <div class="help-block"></div>
                    </div>
                    <div class="m-t-30 text-right">
                        <button type="submit" class="btn btn-info waves-effect action-btn"></i> Create</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
