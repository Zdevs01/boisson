<?php 
if(isset($_GET['id']) && $_GET['id'] > 0){
    $user = $conn->query("SELECT * FROM users where id ='{$_GET['id']}'");
    foreach($user->fetch_array() as $k =>$v){
        $meta[$k] = $v;
    }
}
?>

<?php if($_settings->chk_flashdata('success')): ?>
<script>
    Swal.fire({
        icon: 'success',
        title: 'Succès',
        text: "<?php echo $_settings->flashdata('success') ?>",
        showConfirmButton: false,
        timer: 2000
    });
</script>
<?php endif; ?>

<style>
    /* Thème industriel */
    .card {
        border: none;
        background: linear-gradient(135deg, #1E3D59, #4A6572);
        color: white;
        box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.2);
        border-radius: 10px;
    }

    .card-header {
        background-color: #F57C00;
        color: white;
        font-weight: bold;
        font-size: 18px;
        border-radius: 10px 10px 0 0;
    }

    .btn-primary {
        background-color: #F57C00;
        border: none;
        transition: transform 0.2s ease-in-out;
    }

    .btn-primary:hover {
        background-color: #FF9800;
        transform: scale(1.05);
    }

    .btn-secondary:hover {
        transform: scale(1.05);
    }

    .form-control {
        border-radius: 5px;
    }

    img.preview-img {
        height: 120px;
        width: 120px;
        object-fit: cover;
        border-radius: 50%;
        box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.3);
        transition: transform 0.3s ease-in-out;
    }

    img.preview-img:hover {
        transform: rotate(3deg) scale(1.1);
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .form-group {
        animation: fadeIn 0.5s ease-in-out;
    }
</style>

<div class="card shadow-lg rounded">
    <div class="card-header">
        <h5 class="card-title"><i class="fas fa-user-cog"></i> Gestion de l'utilisateur</h5>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <div id="msg"></div>
            <form action="" id="manage-user" class="needs-validation" novalidate>    
                <input type="hidden" name="id" value="<?php echo isset($meta['id']) ? $meta['id']: '' ?>">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="firstname"><i class="fas fa-user"></i> Prénom</label>
                        <input type="text" name="firstname" id="firstname" class="form-control"
                               value="<?php echo isset($meta['firstname']) ? $meta['firstname']: '' ?>" required>
                        <div class="invalid-feedback">Veuillez entrer un prénom.</div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="lastname"><i class="fas fa-user-tag"></i> Nom</label>
                        <input type="text" name="lastname" id="lastname" class="form-control"
                               value="<?php echo isset($meta['lastname']) ? $meta['lastname']: '' ?>" required>
                        <div class="invalid-feedback">Veuillez entrer un nom.</div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="username"><i class="fas fa-user-circle"></i> Nom d'utilisateur</label>
                        <input type="text" name="username" id="username" class="form-control"
                               value="<?php echo isset($meta['username']) ? $meta['username']: '' ?>" required>
                        <div class="invalid-feedback">Veuillez entrer un nom d'utilisateur.</div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="password"><i class="fas fa-lock"></i> Mot de passe</label>
                        <input type="password" name="password" id="password" class="form-control"
                               <?php echo isset($meta['id']) ? "" : 'required' ?>>
                        <?php if(isset($_GET['id'])): ?>
                        <small class="text-info">Laissez vide si vous ne souhaitez pas changer le mot de passe.</small>
                        <?php endif; ?>
                        <div class="invalid-feedback">Veuillez entrer un mot de passe.</div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
    <label for="type"><i class="fas fa-users-cog"></i> Type d'utilisateur</label>
    <select name="type" id="type" class="custom-select" required>
        <option value="1" <?php echo isset($meta['type']) && $meta['type'] == 1 ? 'selected': '' ?>>Administrateur</option>
        <option value="2" <?php echo isset($meta['type']) && $meta['type'] == 2 ? 'selected': '' ?>>Gestionnaire</option>
        <option value="3" <?php echo isset($meta['type']) && $meta['type'] == 3 ? 'selected': '' ?>>Livreur</option>
    </select>
</div>
                    <div class="col-md-6 mb-3">
                        <label for="customFile"><i class="fas fa-camera"></i> Avatar</label>
                        <input type="file" class="form-control-file" id="customFile" name="img" onchange="displayImg(this)">
                    </div>
                </div>
                
                <div class="text-center">
                    <img src="<?php echo validate_image(isset($meta['avatar']) ? $meta['avatar'] :'') ?>" alt="" id="cimg" 
                         class="preview-img img-thumbnail">
                </div>
                
                <div class="mt-4 text-center">
                    <button class="btn btn-lg btn-primary" type="submit"><i class="fas fa-save"></i> Enregistrer</button>
                    <a class="btn btn-lg btn-secondary" href="./?page=user/list"><i class="fas fa-times"></i> Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function displayImg(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('cimg').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    (function() {
        'use strict';
        window.addEventListener('load', function() {
            var forms = document.getElementsByClassName('needs-validation');
            Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
</script>


<script>
    $(function(){
        $('.select2').select2({
            width:'resolve'
        })
    })
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
        var _this = $(this)
        start_loader()
        $.ajax({
            url:_base_url_+'classes/Users.php?f=save',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success:function(resp){
                if(resp ==1){
                    location.href = './?page=user/list';
                }else{
                    $('#msg').html('<div class="alert alert-danger">Username already exist</div>')
                    $("html, body").animate({ scrollTop: 0 }, "fast");
                }
                end_loader()
            }
        })
    })

</script>