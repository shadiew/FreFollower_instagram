<?php
    /**
     * @var \Wow\Template\View $this
     * @var array              $model
     */
    $this->set("title", "Add Manual");
?>
<div class="card">
    <div class="card-header">
        Add Users Secara Manual
    </div>
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">User:Pass Pengguna</label>
            <textarea id="userpassList" class="form-control" rows="10"></textarea>
            <span class="form-text">Tulis dengan format username:password</span>
        </div>
        <div class="my-3">
            <button type="button" class="btn btn-primary" id="btnAddUserPass" onclick="addUserPass();">Submit</button>
        </div>
        <div id="listUserPass"></div>
    </div>
</div>


<?php $this->section("section_scripts");
    $this->parent(); ?>
<script type="text/javascript">
    var listAddUserPass, countUserPass, AddUserInterval;

    function addUserPass() {
        if($('#userpassList').val() == '') {
            alert('The list is empty! ');
            return;
        }
        $('#btnAddUserPass').attr('disabled', 'disabled').html('<i class="fa fa-spinner fa-spin"></i> Sending..');
        countUserPass   = 0;
        listAddUserPass = $.trim($('#userpassList').val()).split(/\r|\n/);
        $('#listUserPass').html('');
        $('#listUserPass').prepend('<p class="text-primary">' + listAddUserPass.length + ' Transfer started for user .</p>');
        addUserPassRC();
        AddUserInterval = setInterval(function() {
            addUserPassRC();
        }, 5000);

    }

    function addUserPassRC() {
        if(listAddUserPass.length < 1) {
            return addUserPassComplete();
        }
        $user = listAddUserPass[0];
        listAddUserPass.splice(0, 1);
        if($user.length > 0) {
            $.ajax({type: 'POST', dataType: 'json', url: '?formType=addUserPass', data: 'userpass=' + encodeURIComponent($user)}).done(function(data) {
                if(data.status == 'success') {
                    $('#listUserPass').prepend('<p><a href="/user/' + data.instaID + '">' + data.userNick + '</a> Berhasil. Status: <span class="text text-success">' + data.message + '</span></p>');
                    countUserPass++;
                } else {
                    $('#listUserPass').prepend('<p><a href="/user/' + data.instaID + '">' + data.userNick + '</a> Berhasil. Status: <span class="text text-danger">' + data.message + '</span></p>');
                }
                addUserPassRC();
            });
        } else {
            addUserPassRC();
        }
    }

    function addUserPassComplete() {
        clearInterval(AddUserInterval);
        $('#btnAddUserPass').prop("disabled", false).html('Submit');
        $('#listUserPass').prepend('<p class="text-success">The transfer is complete. Number of newly added users:  ' + countUserPass + '</p>');
    }
</script>
<?php $this->endSection(); ?>
