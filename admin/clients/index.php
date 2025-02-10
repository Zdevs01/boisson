<?php if($_settings->chk_flashdata('success')): ?>
<script>
    alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>

<style>
    .img-avatar{
        width:45px;
        height:45px;
        object-fit:cover;
        object-position:center center;
        border-radius:100%;
    }
</style>

<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Suivi des Clients</h3>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date d'Inscription</th>
                        <th>Avatar11</th>
                        <th>Nom du Client</th>
                        <th>Telephone</th>
                        <th>Somme Versée</th>
                        <th>Commandes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $i = 1;
                        // Récupérer les informations des clients depuis la table `sales_list`
                        $qry = $conn->query("SELECT * FROM `sales_list` ORDER BY date_created ASC ");
                        while($row = $qry->fetch_assoc()):
                            // Total des ventes effectuées par client (total_amount des ventes)
                            $total_paid = $conn->query("SELECT SUM(amount) AS total FROM sales_list WHERE client = '{$row['client']}'")->fetch_assoc()['total'] ?? 0;
                            
                            // Nombre de commandes effectuées par le client
                            $order_count = $conn->query("SELECT COUNT(id) AS total FROM sales WHERE id IN (SELECT stock_ids FROM sales_list WHERE client = '{$row['client']}')")->fetch_assoc()['total'] ?? 0;
                    ?>
                        <tr>
                            <td class="text-center"><?php echo $i++; ?></td>
                            <td class="text-right"><?php echo date("Y-m-d H:i", strtotime($row['date_created'])) ?></td>
                            <td class="text-center"><img src="avatar.jpg" class="img-avatar img-thumbnail p-0 border-2" alt="avatar_client"></td>
                            
							
							<td><?php echo $row['client'] ?></td>
                            <td><?php echo $row['num'] ?></td> <!-- Email ou autre info du client -->
                            <td class="text-right"><?php echo number_format($total_paid, 2) ?> €</td>
                            <td class="text-center"><?php echo $order_count ?> Commandes</td>
                            <td align="center">
                                 <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                    Actions
                                    <span class="sr-only">Toggle Dropdown</span>
                                  </button>
                                  <div class="dropdown-menu" role="menu">
                                    <a class="dropdown-item view_details" href="javascript:void(0)" data-id ="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> Voir</a>
									<a class="dropdown-item print_data" href="/boisson/admin/print_client.php?id=<?php echo $row['id']; ?>" target="_blank"><span class="fa fa-print text-primary"></span> Imprimer</a>


                                    <a class="dropdown-item contact_client" href="mailto:<?php echo $row['client'] ?>"><span class="fa fa-envelope text-success"></span> Contacter</a>
                                  </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('.view_details').click(function(){
            uni_modal("Détails du Client","clients/view_details.php?id="+$(this).attr('data-id'))
        })
        $('.table td,.table th').addClass('py-1 px-2 align-middle')
        $('.table').dataTable();
    })
</script>
