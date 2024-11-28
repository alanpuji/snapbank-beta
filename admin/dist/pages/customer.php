                    <div class="container-fluid">
                        <h2 class="mt-4">Customer</h2>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active"><a href="?page=customer_add" class="btn btn-success"><i class="fas fa-plus"></i> Tambah</a></li>
                        </ol>
                        <div class="card mb-4">
                            <div class="card-header"><i class="fas fa-table mr-1"></i>Preview</div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th width="5%">No.</th>
                                                <th width="10%">ID</th>
                                                <th>CIF</th>
                                                <th>Nomor Identitas</th>
                                                <th>Nama</th>
                                                <th>Alamat</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>No.</th>
                                                <th>ID</th>
                                                <th>CIF</th>
                                                <th>Nomor Identitas</th>
                                                <th>Nama</th>
                                                <th>Alamat</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php
                                            try
                                            {
                                              $no = 1;		
                                              $sql = "SELECT * 
                                              FROM customer 
                                              ORDER BY tgl_daftar desc";
                                              foreach ($db->query($sql) as $row) {
                                                $id_customer=$row['id_customer'];
                                                if($row['status_aktif']=="1"){
                                                    $status_aktif="<a href='dist/pages/customer_update_status.php?id=$row[id_customer]&status_aktif=$row[status_aktif]' title='Aktif' class='btn btn-success' onclick='return konfirmasi()'>Aktif</a>";
                                                }else{
                                                    $status_aktif="<a href='dist/pages/customer_update_status.php?id=$row[id_customer]&status_aktif=$row[status_aktif]' title='Tidak Aktif' class='btn btn-danger' onclick='return konfirmasi()'>Tidak Aktif</a>";
                                                }

                                            ?>
                                                <tr>
                                                <td><?php echo $no;?></td>
                                                <td><?php echo $id_customer?></td>
                                                <td><?php echo $row['id_nasabah']?></td>
                                                <td><?php echo $row['nomor_identitas']?></td>
                                                <td><a href="?page=customer_detail&id=<?php echo $id_customer?>" title="Detail"><?php echo $row['nama_lengkap']?></a></td>
                                                <td><?php echo $row['alamat']?></td>
                                                <td><?php echo $status_aktif?></td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="fas fa-info"></i>
                                                        </button>
                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                            <a class="dropdown-item" href="?page=customer_generate&id=<?php echo $id_customer?>" title="Generate"><i class="fas fa-cog"></i> &nbsp Generate</a>
                                                            <a class="dropdown-item" href="?page=customer_qrcode&id=<?php echo $id_customer?>" title="QRCode"><i class="fas fa-qrcode"></i> &nbsp QRCode</a>
                                                            <a class="dropdown-item" href="?page=customer_detail&id=<?php echo $id_customer?>" title="Detail"><i class="fas fa-eye"></i> &nbsp Detail</a>
                                                            <a class="dropdown-item" href="?page=customer_edit&id=<?php echo $id_customer?>" title="Edit"><i class="fas fa-edit"></i> &nbsp Koreksi</a>
                                                        </div>
                                                    </div>                                                
                                                </td>
                                                </tr>                                            
                                                <?php
                                                $no++;			
                                                }		
                                            }
                                            catch (PDOException $e)
                                            {
                                                echo "There is some problem in connection: " . $e->getMessage();
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
<script language="JavaScript" type="text/javascript">
function konfirmasi(){
    return confirm('Koreksi data ini?');
}
</script>                      