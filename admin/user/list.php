<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<div class="card shadow-lg border-0 rounded-4">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Liste des utilisateurs du système</h3>
        <a href="?page=user/manage_user" class="btn btn-warning shadow-sm">
            <i class="fas fa-plus"></i> Ajouter un nouvel utilisateur
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped table-bordered text-center align-middle">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Avatar</th>
                        <th>Nom</th>
                        <th>Nom d'utilisateur</th>
                        <th>Type d'utilisateur</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $i = 1;
                        $qry = $conn->query("SELECT *, concat(firstname,' ',lastname) as name FROM users WHERE id != '1' ORDER BY name ASC");
                        while($row = $qry->fetch_assoc()):
                            $user_types = [
                                1 => ['label' => 'Administrateur', 'class' => 'bg-success text-white'],
                                2 => ['label' => 'Gestionnaire', 'class' => 'bg-warning text-dark'],
                                3 => ['label' => 'Livreur', 'class' => 'bg-info text-dark']
                            ];
                            $type_label = $user_types[$row['type']]['label'] ?? 'Inconnu';
                            $type_class = $user_types[$row['type']]['class'] ?? 'bg-secondary text-white';
                    ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td>
                            <img src="<?php echo validate_image($row['avatar']) ?>" class="img-avatar rounded-circle shadow-sm" alt="avatar_utilisateur">
                        </td>
                        <td class="text-capitalize"> <?php echo ucwords($row['name']) ?> </td>
                        <td> <?php echo $row['username'] ?> </td>
                        <td>
                            <span class="badge <?php echo $type_class; ?> p-2"> <?php echo $type_label; ?> </span>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fa fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item text-primary" href="?page=user/manage_user&id=<?php echo $row['id'] ?>">
                                        <i class="fa fa-edit"></i> Modifier</a></li>
                                    <li><a class="dropdown-item text-danger delete_data" href="#" data-id="<?php echo $row['id'] ?>">
                                        <i class="fa fa-trash"></i> Supprimer</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .card {
        background: linear-gradient(135deg, #e3f2fd, #bbdefb);
    }
    .thead-dark {
        background-color: #0d47a1;
        color: white;
    }
    .table tbody tr:hover {
        background: rgba(33, 150, 243, 0.1);
    }
    .img-avatar {
        width: 50px;
        height: 50px;
        object-fit: cover;
        transition: transform 0.3s ease-in-out;
    }
    .img-avatar:hover {
        transform: scale(1.1);
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    }
    .badge {
        font-size: 0.9rem;
        border-radius: 12px;
    }
    .btn-warning:hover {
        background-color: #ff9800;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.delete_data').forEach(btn => {
            btn.addEventListener('click', function() {
                let id = this.getAttribute('data-id');
                if (confirm("Êtes-vous sûr de vouloir supprimer cet utilisateur définitivement ?")) {
                    delete_user(id);
                }
            });
        });
    });
    function delete_user(id){
        start_loader();
        $.ajax({
            url: _base_url_+"classes/Users.php?f=delete",
            method: "POST",
            data: {id: id},
            dataType: "json",
            error: err => {
                console.log(err);
                alert_toast("Une erreur est survenue.", 'error');
                end_loader();
            },
            success: function(resp){
                if(typeof resp == 'object' && resp.status == 'success'){
                    location.reload();
                } else {
                    alert_toast("Une erreur est survenue.", 'error');
                    end_loader();
                }
            }
        });
    }
</script>







