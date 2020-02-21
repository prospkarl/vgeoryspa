var url = $("#base_url").val();

function toggleUserStatus(id, status){
    Swal.fire({
        title: "Wait!",
        text: "Are you sure to change user status?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#A4A23C",
        confirmButtonText: "Yes",
    }).then((confirm) => {
        if (confirm.value) {
            $.ajax({
                url: url + 'user/toggleUserStatus',
                type:'post',
                dataType:'json',
                data: {
                    id: id,
                    toStatus: status ? 0 : 1
                },
                success: function(data){
                    showNotification(data);
                    initDatatable();
                }
            })
        }
    });
}

function initDatatable(){
    $('#userTable').DataTable({
          "responsive"     : true,
          "destroy"        : true,
          "processing"     : true,
          "serverSide"     : true,
          "order"          : [[0,'desc']],
          "columns"        :[
                {"data":"fname","render":function(data, type, row){
                    var fullname = row.fname + " " +row.lname;
                    fullname = fullname.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                        return letter.toUpperCase();
                    });
                    return fullname;
                }},
                {"data":"position"},
                {"data":"birthdate","render" : function (data, type, row) {
                   dob = new Date(row.birthdate);
                   var today = new Date();
                   var age = Math.floor((today-dob) / (365.25 * 24 * 60 * 60 * 1000));
                   return  age;
                }},
                {"data":"date_added"},
                {"data":"user_status", "render": function(data,type,row){
                    var html = '<a href="javascript:;" onclick="toggleUserStatus('+row.user_id+', '+data+');">' + getStatus(data) +'</a>';
                    return html
                }},
                {"data":"date_added","render" : function (data, type, row) {
                    var html = '<a href="javascript:;" class="editUser" data-id="'+row.user_id+'"><i class="fa fa-edit" data-toggle="tooltip" title="Edit"></i></a> ';
                    html += '<a href="javascript:;" class="trashUser" class="m-l-5" data-id="'+row.user_id+'"><i class="fa fa-trash" data-toggle="tooltip" title="Trash"></i></a>';
                    return html
                }},
          ],

          "ajax": {
                "url"   : url+"user/userDataTable",
                "type"  : "POST"
          },

          "columnDefs": [
                {
                     "targets"   : [4, 5],
                     "orderable" : false,
                 },
            ],
            "initComplete" : function () {
                $('[data-toggle="tooltip"]').tooltip()
            }
   });
}

function getStatus (status) {
    let html = '';

    switch (status) {
        case '1':
        html = '<span class="label label-success label-rounded">Active</span>';
        break;
        case '0':
        html = '<span class="label label-danger label-rounded">Inactive</span>';
        break;
    }

    return html;
}

$(document).ready(function() {
    initDatatable();

    $('#addUser').on('hidden.bs.modal', function () {
        $('#addUserForm').removeAttr('data-action');
        $('#addUserForm')[0].reset();
        if ($("select[name='location']").val() == "Sales") {
            $(".location").show();
            $("select[name='location']").attr('required', true);
        }else {
            $(".location").hide();
            $("select[name='location']").removeAttr('required');
        }
    })

    $(document).on('click','.editUser', function (e) {
        e.preventDefault();
        $('.dynaModal').modal('show');
        var id = $(this).attr("data-id");
        $('#addUserForm').attr('data-id', id);
        $('.dyna_btn').text("Edit");
        $.ajax({
            async: false,
            url:url + "user/getUser",
            method: "post",
            data:{id: id},
            dataType: "json",
            success:function(data){
                $('input[name="fname"]').val(data['fname']);
                $('input[name="lname"]').val(data['lname']);
                $('select[name="gender"]').val(data['gender']);
                $('input[name="bday"]').val(data['birthdate']);
                $('select[name="position"]').val(data['position']);
                $('input[name="username"]').val(data['username']);
                $('input[name="password"]').val(data['password']);
                $('#addUserForm').attr('data-action',"edit");
            }
        });
    });


    $('#addUserForm').on('submit',function(e) {
       e.preventDefault();
       const self = $(this);
       if (self.attr('data-action') == 'edit') {
           $.ajax({
               async: false,
               url:url + "user/updUser",
               method: "post",
               data:self.serialize()+ "&user_id=" + self.attr('data-id'),
               dataType: "json",
               success:function(data){
                   if (data.type == 'success') {
                       initDatatable();
                       self[0].reset();
                       $('#addUser').modal('hide');
                       $('#addUserForm').removeAttr('data-action');
                   }

                   showNotification(data);
               }
           });
       }else {
           $.ajax({
              async: false,
              url:url + "user/addUser",
              method: "post",
              data:$(this).serialize(),
              dataType: "json",
              success:function(data){
                  if (data.type == 'success') {
                      initDatatable();
                      self[0].reset();
                      $('#addUser').modal('hide');
                      showNotification(data);
                  }
              }
           })
       }
    });

    var fname= '';
    $('input[name="fname"]').on('change',function() {
        var x = '';
        var value = $(this).val().split(" ");
        for (var i = 0; i < value.length; i++) {
            x += value[i].charAt(0);
        }
        fname = x;
    });

    $('.create-new').on('click', function(){
        $('.dyna_btn').html('Create');
    });

    $('input[name="lname"]').on('change',function() {
        var username = lname = fname + $(this).val();
        $('input[name="username"]').val(username.toLowerCase());
    });

    $('input[name="username"]').on('change',function() {
        $(this).val($(this).val().toLowerCase());
    });


    $(document).on('click','.trashUser', function() {
        var id = $(this).attr("data-id");

        Swal.fire({
            title: "Delete User?",
            text: "You will not be able to recover this user",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#A4A23C",
            confirmButtonText: "Yes",
        }).then((confirm) => {
            if (confirm.value) {
                $.ajax({
                    url: url + 'user/removeUsers',
                    type:'post',
                    dataType:'json',
                    data: {
                        id: id
                    },
                    success: function(data){
                        $.toast({
                            heading: "user",
                            text: data.message,
                            position: 'top-right',
                            loaderBg: '#178472',
                            icon: "delete",
                            hideAfter: 2000,
                            stack: 6
                        })
                        initDatatable();
                    }
                })
            }
        });
    });
    //
    // $('.editForm').on('submit', function (e) {
    //     e.preventDefault();
    //     const self = $(this);
    //
    // });
});
