<div class="card card-outline card-primary shadow-sm animate__animated animate__fadeIn">
    <div class="card-header bg-primary text-white text-center">
        <h4><i class="fas fa-user-cog"></i> Gestion du Profil</h4>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <div id="msg"></div>
            <form action="" id="manage-user" class="animate__animated animate__fadeInUp">   
                <input type="hidden" name="id" value="<?php echo $_settings->userdata('id') ?>">
                <div class="form-group">
                    <label for="firstname"><i class="fas fa-user"></i> Prénom</label>
                    <input type="text" name="firstname" id="firstname" class="form-control" value="<?php echo isset($meta['firstname']) ? $meta['firstname']: '' ?>" required>
                </div>
                <div class="form-group">
                    <label for="lastname"><i class="fas fa-user"></i> Nom</label>
                    <input type="text" name="lastname" id="lastname" class="form-control" value="<?php echo isset($meta['lastname']) ? $meta['lastname']: '' ?>" required>
                </div>
                <div class="form-group">
                    <label for="username"><i class="fas fa-user-tag"></i> Nom d'utilisateur</label>
                    <input type="text" name="username" id="username" class="form-control" value="<?php echo isset($meta['username']) ? $meta['username']: '' ?>" required autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Mot de passe</label>
                    <input type="password" name="password" id="password" class="form-control" value="" autocomplete="off">
                    <small><i>Laissez ce champ vide si vous ne souhaitez pas modifier le mot de passe.</i></small>
                </div>
                <div class="form-group">
                    <label for="avatar" class="control-label"><i class="fas fa-image"></i> Avatar</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="customFile" name="img" onchange="displayImg(this,$(this))">
                        <label class="custom-file-label" for="customFile">Choisissez un fichier</label>
                    </div>
                </div>
                <div class="form-group d-flex justify-content-center">
                    <img src="<?php echo validate_image(isset($meta['avatar']) ? $meta['avatar'] :'') ?>" alt="" id="cimg" class="img-fluid img-thumbnail shadow">
                </div>
            </form>
        </div>
    </div>
    <div class="card-footer text-center">
        <button class="btn btn-primary btn-lg btn-rounded" form="manage-user"><i class="fas fa-save"></i> Mettre à jour</button>
    </div>
</div>

<style>
    img#cimg {
        height: 100px;
        width: 100px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #007bff;
        transition: transform 0.3s;
    }
    img#cimg:hover {
        transform: scale(1.1);
    }
    .btn-rounded {
        border-radius: 50px;
    }
</style>

<script>
    function displayImg(input,_this) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#cimg').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $('#manage-user').submit(function(e){
        e.preventDefault();
        start_loader();
        $.ajax({
            url: _base_url_+'classes/Users.php?f=save',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            success: function(resp){
                if(resp ==1){
                    location.reload();
                } else {
                    $('#msg').html('<div class="alert alert-danger">Nom d\'utilisateur déjà existant</div>');
                    end_loader();
                }
            }
        });
    });
</script>
