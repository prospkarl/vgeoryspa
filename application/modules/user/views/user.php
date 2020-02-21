<div class="card ">
    <div class="card-body">
        <table class="table table-striped table-bordered override_width"  id="userTable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Age</th>
                    <th>Start Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>



<div id="addUser" class="modal dynaModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg modal-dialog-centered animated bounceIn show" role="document">
    <div class="modal-content">
      <div class="modal-body">
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true">&times;</span>
         </button>
         <form id="addUserForm" data-action=''>
         <div class="row">
               <div class="col-md-12">
                  <h3>Personal Info</h3>
                  <hr>
               </div>
         </div>
         <div class="row">
               <div class="col-md-6">
                     <div class="form-group">
                         <h5>First Name<span class="text-danger">*</span></h5>
                         <div class="controls">
                             <input placeholder="First Name" type="text" name="fname" class="form-control" required data-validation-required-message="This field is required"> </div>
                     </div>
               </div>
               <div class="col-md-6">
                     <div class="form-group">
                         <h6>Last Name<span class="text-danger">*</span></h6>
                         <div class="controls">
                             <input placeholder="Last Name" type="text" name="lname" class="form-control" required data-validation-required-message="This field is required"> </div>
                     </div>
               </div>
         </div>

         <div class="row">
               <div class="col-md-6">
                     <div class="form-group">
                         <h5>Gender<span class="text-danger">*</span></h5>
                         <div class="controls">
                             <select name="gender" required class="form-control">
                                 <option value="" disabled hidden selected>What is your gender</option>
                                 <option value="Male">Male</option>
                                 <option value="Female">Female</option>
                             </select>
                         </div>
                     </div>
               </div>
               <div class="col-md-6">
                     <div class="form-group">
                         <h6>Birthdate<span class="text-danger">*</span></h6>
                         <div class="controls">
                             <input type="date" name="bday" class="form-control" required data-validation-required-message="This field is required"> </div>
                     </div>
               </div>
         </div>

         <div class="row">
             <div class="col-md-6">
                   <div class="form-group">
                       <h6>Position<span class="text-danger">*</span></h6>
                       <div class="controls">
                           <select name="position" required class="form-control">
                               <option value="" disabled hidden selected>Hired as</option>
                               <option value="Sales">Sales</option>
                               <option value="Administrator">Admin</option>
                           </select>
                       </div>
                   </div>
             </div>
             <div class="col-md-6 " >
                <div class="form-group location" style="display: none;">
                    <h5>Location<span class="text-danger">*</span></h5>
                    <div class="controls">
                        <select name="location"  class="form-control">
                         <option value="">Assign to</option>
                         <?= render_options($location, 'location_id', 'name') ?>
                        </select>
                    </div>
                </div>
             </div>


         </div>

         <div class="row">
               <div class="col-md-12">
                  <h3>User Access</h3>
                  <hr>
               </div>
         </div>

         <div class="row">
               <div class="col-md-6">
                     <div class="form-group">
                         <h5>Username<span class="text-danger">*</span></h5>
                         <div class="controls">
                             <input type="text"  name="username" class="form-control" required data-validation-required-message="This field is required"> </div>
                     </div>
               </div>
               <div class="col-md-6">
                     <div class="form-group">
                         <h6>Password<span class="text-danger">*</span></h6>
                         <div class="controls">
                             <input type="text"  name="password" class="form-control" required data-validation-required-message="This field is required" value="123"> </div>
                     </div>
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

<!-- ========================================================================================= -->
