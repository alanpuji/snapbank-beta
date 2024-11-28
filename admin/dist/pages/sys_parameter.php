                    <div class="container-fluid">
                        <h2 class="mt-4">Parameter</h2>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active"><a href="?page=sys_parameter_add" class="btn btn-success"><i class="fas fa-plus"></i> Tambah</a></li>
                        </ol>
                        <div class="card mb-4">
                            <div class="card-header"><i class="fas fa-table mr-1"></i>Preview</div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th width="5%">No</th>
                                                <th>Kode</th>
                                                <th>Nama</th>
                                                <th>Value</th>
                                                <th>Keterangan</th>
                                                <th width="8%">Edit</th>                                                
                                                <th width="8%">Hapus</th>                                                
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>No</th>
                                                <th>Kode</th>
                                                <th>Nama</th>
                                                <th>Value</th>
                                                <th>Keterangan</th>
                                                <th>Edit</th>                                                
                                                <th>Hapus</th>                                                
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php
                                            try
                                            {
                                              $no = 1;		
                                              $sql = "SELECT * FROM sys_parameter 
                                              ORDER BY kode_parameter ASC";
                                              foreach ($db->query($sql) as $row) {
                                                ?>
                                                <tr>
                                                <td><?php echo $no;?></td>
                                                <td><?php echo $row['kode_parameter']?></td>
                                                <td><?php echo $row['nama_parameter']?></td>
                                                <td><?php echo $row['value_parameter']?></td>
                                                <td><?php echo $row['keterangan']?></td>
                                                <td>
                                                    <a href="?page=sys_parameter_edit&id=<?php echo $row['kode_parameter']?>" title="Edit" class="btn btn-danger"><i class="fas fa-edit"></i></a>  
                                                </td>
                                                <td>
                                                    <a href="dist/pages/sys_parameter_delete.php?id=<?php echo $row['kode_parameter']?>" title="Hapus" class="btn btn-danger" onclick="return checkDelete()"><i class="fas fa-trash"></i></a>  
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
       