<?php
require_once('../../config.php');
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT * from `supplier_list` where id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }
}
?>
<style>
	img#cimg{
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
            <label for="name" class="control-label">
                <i class="fas fa-user"></i> Nom du Fournisseur
            </label>
            <input name="name" id="name" class="form-control rounded-0" value="<?php echo isset($name) ? $name : ''; ?>" required>
        </div>

        <div class="form-group">
            <label for="address" class="control-label">
                <i class="fas fa-map-marker-alt"></i> Adresse
            </label>
            <textarea name="address" id="address" cols="30" rows="2" class="form-control no-resize" required><?php echo isset($address) ? $address : ''; ?></textarea>
        </div>

        <div class="form-group">
            <label for="cperson" class="control-label">
                <i class="fas fa-user-tie"></i> Personne de Contact
            </label>
            <input name="cperson" id="cperson" class="form-control rounded-0" value="<?php echo isset($cperson) ? $cperson : ''; ?>" required>
        </div>

        <div class="form-group">
            <label for="contact" class="control-label">
                <i class="fas fa-phone"></i> Numéro de Contact
            </label>
            <input name="contact" id="contact" class="form-control rounded-0" value="<?php echo isset($contact) ? $contact : ''; ?>" required>
        </div>

        <div class="form-group">
            <label for="status" class="control-label">
                <i class="fas fa-toggle-on"></i> Statut
            </label>
            <select name="status" id="status" class="custom-select">
                <option value="1" <?php echo isset($status) && $status == 1 ? 'selected' : '' ?>>Actif</option>
                <option value="0" <?php echo isset($status) && $status == 0 ? 'selected' : '' ?>>Inactif</option>
            </select>
        </div>

        <div class="form-group text-center">
            <button type="submit" class="btn btn-success btn-flat">
                <i class="fas fa-save"></i> Enregistrer
            </button>
        </div>
    </form>
</div>
<script>
	$(document).ready(function(){
		$('#supplier-form').submit(function(e){
			e.preventDefault();
            var _this = $(this)
			 $('.err-msg').remove();
			start_loader();
			$.ajax({
				url:_base_url_+"classes/Master.php?f=save_supplier",
				data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
				error:err=>{
					console.log(err)
					alert_toast("An error occured",'error');
					end_loader();
				},
				success:function(resp){
					if(typeof resp =='object' && resp.status == 'success'){
						location.reload();
					}else if(resp.status == 'failed' && !!resp.msg){
                        var el = $('<div>')
                            el.addClass("alert alert-danger err-msg").text(resp.msg)
                            _this.prepend(el)
                            el.show('slow')
                            end_loader()
                    }else{
						alert_toast("An error occured",'error');
						end_loader();
                        console.log(resp)
					}
				}
			})
		})
	})
</script>