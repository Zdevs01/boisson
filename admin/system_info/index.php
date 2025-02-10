<?php if ($_settings->chk_flashdata('success')): ?>
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
    /* Thème industriel pour l'entrepôt */
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

    img.preview-img {
        height: 150px;
        width: 150px;
        object-fit: cover;
        border-radius: 50%;
        box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.3);
        transition: transform 0.3s ease-in-out;
    }

    img.preview-img:hover {
        transform: rotate(3deg) scale(1.1);
    }

    .custom-file-label::after {
        content: "Parcourir";
    }

    /* Animation de chargement */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .form-group {
        animation: fadeIn 0.5s ease-in-out;
    }
</style>

<div class="col-lg-12">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title"><i class="fas fa-warehouse"></i> Paramètres de l'Entrepôt</h5>
        </div>
        <div class="card-body">
            <form action="" id="system-frm">
                <div id="msg" class="form-group"></div>
                
                <div class="form-group">
                    <label for="name"><i class="fas fa-industry"></i> Nom de l'Entrepôt</label>
                    <input type="text" class="form-control form-control-lg" name="name" id="name" value="<?php echo $_settings->info('name') ?>">
                </div>

                <div class="form-group">
                    <label for="short_name"><i class="fas fa-tag"></i> Nom abrégé</label>
                    <input type="text" class="form-control form-control-lg" name="short_name" id="short_name" value="<?php echo $_settings->info('short_name') ?>">
                </div>

                <div class="form-group">
                    <label><i class="fas fa-box"></i> Logo de l'Entrepôt</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="logoFile" name="img" onchange="previewImage(this, '#cimg')">
                        <label class="custom-file-label" for="logoFile">Choisir un fichier</label>
                    </div>
                </div>
                <div class="text-center">
                    <img src="<?php echo validate_image($_settings->info('logo')) ?>" alt="Logo" id="cimg" class="preview-img img-thumbnail">
                </div>

                <div class="form-group mt-4">
                    <label><i class="fas fa-truck-loading"></i> Image de couverture</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="coverFile" name="cover" onchange="previewImage(this, '#cimg2')">
                        <label class="custom-file-label" for="coverFile">Choisir un fichier</label>
                    </div>
                </div>
                <div class="text-center">
                    <img src="<?php echo validate_image($_settings->info('cover')) ?>" alt="Cover" id="cimg2" class="img-fluid img-thumbnail">
                </div>
            </form>
        </div>
        <div class="card-footer text-right">
            <button class="btn btn-lg btn-primary" form="system-frm"><i class="fas fa-save"></i> Mettre à jour</button>
        </div>
    </div>
</div>

<script>
    function previewImage(input, imgSelector) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $(imgSelector).attr('src', e.target.result).hide().fadeIn(500);
                $(input).siblings('.custom-file-label').html(input.files[0].name);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    $(document).ready(function () {
        $('.summernote').summernote({
            height: 200,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ol', 'ul', 'paragraph', 'height']],
                ['table', ['table']],
                ['view', ['undo', 'redo', 'fullscreen', 'codeview', 'help']]
            ]
        });
    });
</script>
