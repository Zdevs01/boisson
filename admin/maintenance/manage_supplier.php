<?php
require_once('../../config.php');
if (isset($_GET['id']) && $_GET['id'] > 0) {
    $qry = $conn->query("SELECT * FROM `supplier_list` WHERE id = '{$_GET['id']}' ");
    if ($qry->num_rows > 0) {
        foreach ($qry->fetch_assoc() as $k => $v) {
            $$k = $v;
        }
    }
}
?>

<style>
    .form-group label {
        font-weight: bold;
    }
    img#cimg {
        height: 15vh;
        width: 15vh;
        object-fit: scale-down;
        object-position: center center;
    }
</style>

<div class="container-fluid">
    <form action="" id="supplier-form">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">

        <div class="form-group">
            <label for="name"><i class="fas fa-industry"></i> Nom du Fournisseur 🍾</label>
            <input type="text" name="name" id="name" class="form-control rounded-0" value="<?php echo isset($name) ? $name : ''; ?>" required>
        </div>

        <div class="form-group">
            <label for="category"><i class="fas fa-tags"></i> Catégorie 📦</label>
            <input type="text" name="category" id="category" class="form-control rounded-0" value="<?php echo isset($category) ? $category : ''; ?>" required>
        </div>

        <div class="form-group">
            <label for="address"><i class="fas fa-map-marker-alt"></i> Adresse 📍</label>
            <textarea name="address" id="address" cols="30" rows="2" class="form-control no-resize" required><?php echo isset($address) ? $address : ''; ?></textarea>
        </div>

        <div class="form-group">
            <label for="country"><i class="fas fa-globe"></i> Pays 🌍</label>
            <input type="text" name="country" id="country" class="form-control rounded-0" value="<?php echo isset($country) ? $country : ''; ?>" required>
        </div>

        <div class="form-group">
            <label for="cperson"><i class="fas fa-user-tie"></i> Personne de Contact 🤵</label>
            <input type="text" name="cperson" id="cperson" class="form-control rounded-0" value="<?php echo isset($cperson) ? $cperson : ''; ?>" required>
        </div>

        <div class="form-group">
            <label for="contact"><i class="fas fa-phone"></i> Numéro de Contact 📞</label>
            <input type="text" name="contact" id="contact" class="form-control rounded-0" value="<?php echo isset($contact) ? $contact : ''; ?>" required>
        </div>

        <div class="form-group">
            <label for="phone_number"><i class="fas fa-mobile-alt"></i> Téléphone Portable 📱</label>
            <input type="text" name="phone_number" id="phone_number" class="form-control rounded-0" value="<?php echo isset($phone_number) ? $phone_number : ''; ?>" required>
        </div>

        <div class="form-group">
            <label for="email"><i class="fas fa-envelope"></i> Email ✉️</label>
            <input type="email" name="email" id="email" class="form-control rounded-0" value="<?php echo isset($email) ? $email : ''; ?>" required>
        </div>

        <div class="form-group">
            <label for="status"><i class="fas fa-toggle-on"></i> Statut ⚡</label>
            <select name="status" id="status" class="custom-select">
                <option value="1" <?php echo isset($status) && $status == 1 ? 'selected' : '' ?>>Actif ✅</option>
                <option value="0" <?php echo isset($status) && $status == 0 ? 'selected' : '' ?>>Inactif ❌</option>
            </select>
        </div>

        <div class="form-group text-center">
            <button type="submit" class="btn btn-success btn-flat">
                <i class="fas fa-save"></i> Enregistrer 💾
            </button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function () {
        $('#supplier-form').submit(function (e) {
            e.preventDefault();
            var _this = $(this);
            $('.err-msg').remove();
            start_loader();

            $.ajax({
                url: _base_url_ + "classes/Master.php?f=save_supplier",
                data: new FormData(this),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                dataType: 'json',
                error: function (err) {
                    console.log(err);
                    alert_toast("❌ Une erreur est survenue", 'error');
                    end_loader();
                },
                success: function (resp) {
                    if (typeof resp === 'object' && resp.status === 'success') {
                        location.reload();
                    } else if (resp.status === 'failed' && resp.msg) {
                        var el = $('<div>').addClass("alert alert-danger err-msg").text(resp.msg);
                        _this.prepend(el);
                        el.show('slow');
                        end_loader();
                    } else {
                        alert_toast("❌ Une erreur est survenue", 'error');
                        end_loader();
                        console.log(resp);
                    }
                }
            });
        });
    });
</script>
