<style>
    .img-avatar {
        width: 45px;
        height: 45px;
        object-fit: cover;
        object-position: center center;
        border-radius: 100%;
        transition: transform 0.3s ease-in-out;
    }
    .img-avatar:hover {
        transform: scale(1.1);
    }
    .card-title {
        font-size: 1.5rem;
        font-weight: bold;
    }
    .btn-primary {
        transition: background 0.3s, transform 0.2s;
    }
    .btn-primary:hover {
        background: #ff9800;
        transform: scale(1.05);
    }
    .table tbody tr:hover {
        background: #f0f0f0;
        transition: background 0.3s;
    }
</style>

<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Liste des utilisateurs du système</h3>
        <div class="card-tools">
            <a href="?page=user/manage_user" class="btn btn-flat btn-primary">
                <span class="fas fa-plus"></span> Ajouter un nouvel utilisateur
            </a>
        </div>
    </div>
   <div class="card-body">
    <div class="container-fluid">
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
                        $qry = $conn->query("SELECT *, concat(firstname,' ',lastname) as name FROM users WHERE id != '1' ORDER BY concat(firstname,' ',lastname) ASC");
                        while($row = $qry->fetch_assoc()):
                    ?>
                    <tr class="table-row">
                        <td class="text-center"><?php echo $i++; ?></td>
                        <td class="text-center">
                            <div class="avatar-container">
                                <img src="<?php echo validate_image($row['avatar']) ?>" class="img-avatar img-thumbnail rounded-circle shadow-sm" alt="avatar_utilisateur">
                            </div>
                        </td>
                        <td class="text-capitalize"><?php echo ucwords($row['name']) ?></td>
                        <td><?php echo $row['username'] ?></td>
                        <td>
                            <span class="badge <?php echo ($row['type'] == 1) ? 'badge-success' : 'badge-info'; ?>">
                                <?php echo ($row['type'] == 1 ) ? "Administrateur" : "Employé" ?>
                            </span>
                        </td>
                        <td align="center">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fa fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="?page=user/manage_user&id=<?php echo $row['id'] ?>">
                                            <i class="fa fa-edit text-primary"></i> Modifier
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">
                                            <i class="fa fa-trash text-danger"></i> Supprimer
                                        </a>
                                    </li>
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
    /* Table Styling */
    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.1);
        transition: background 0.3s ease-in-out;
    }

    .thead-dark {
        background-color: #343a40;
        color: white;
    }

    .table-row {
        transition: transform 0.2s ease-in-out;
    }

    .table-row:hover {
        transform: scale(1.02);
    }

    /* Avatar Styling */
    .avatar-container {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .img-avatar {
        width: 45px;
        height: 45px;
        object-fit: cover;
        transition: transform 0.3s ease-in-out;
    }

    .img-avatar:hover {
        transform: scale(1.1);
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    }

    /* Dropdown Styling */
    .dropdown-menu {
        min-width: 120px;
        text-align: left;
    }

    .dropdown-item {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .dropdown-item:hover {
        background-color: rgba(0, 123, 255, 0.1);
    }

    /* Badges */
    .badge {
        font-size: 0.9rem;
        padding: 5px 10px;
        border-radius: 12px;
    }
</style>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script>
    $(document).ready(function(){
        $('.delete_data').click(function(){
            _conf("Êtes-vous sûr de vouloir supprimer cet utilisateur définitivement ?", "delete_user", [$(this).attr('data-id')])
        });
        $('.table td, .table th').addClass('py-1 px-2 align-middle');
        $('.table').dataTable();
        
        gsap.from(".card", { opacity: 0, y: 50, duration: 1, ease: "power3.out" });
        gsap.from(".table tbody tr", { opacity: 0, y: 20, duration: 0.5, stagger: 0.1 });
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
