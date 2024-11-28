                    <div class="container-fluid">
                        <h2 class="mt-4">Pengguna</h2>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active"><a href="?page=pengguna_add" class="btn btn-success"><i class="fas fa-plus"></i> Tambah</a></li>
                        </ol>
                        <div class="alert alert-info">
                            <p class="mb-0"><i class="fas fa-info mr-1"></i> Sistem otomatis melakukan update apabila terdapat pengguna yang sedang login atau salah login 3 kali di keesokan harinya.</p>
                        </div>
                        <div class="card mb-4">
                            <div class="card-header"><i class="fas fa-table mr-1"></i>Preview</div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th width="5%">No.</th>
                                                <th width="10%">ID</th>
                                                <th>Nama User</th>
                                                <th>Nama Lengkap</th>
                                                <th>Jabatan</th>
                                                <th>Latency</th>
                                                <th>ID User CBS</th>
                                                <th>Kode Kas</th>
                                                <th>Last Login</th>
                                                <th>Login</th>
                                                <th>Edit</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>No.</th>
                                                <th>ID</th>
                                                <th>Nama User</th>
                                                <th>Nama Lengkap</th>
                                                <th>Jabatan</th>
                                                <th>Latency</th>
                                                <th>ID User CBS</th>
                                                <th>Kode Kas</th>
                                                <th>Last Login</th>
                                                <th>Login</th>
                                                <th>Edit</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php
                                            try {
                                                $no = 1;
                                                $sql = "SELECT A.id_pengguna,A.nama_user,A.nama_lengkap,A.flag,A.last_login,B.nama_jabatan,A.latency,A.id_user_cbs,A.kode_kas 
                                              FROM sys_user A 
                                              LEFT JOIN sys_jabatan B ON B.kode_jabatan=A.jabatan 
                                              ORDER BY flag desc";
                                                foreach ($db->query($sql) as $row) {
                                                    if ($row['flag'] == "1") {
                                                        $login = "<a href='dist/pages/pengguna_update_flag.php?id=$row[id_pengguna]&flag=$row[flag]' title='On line' class='btn btn-success' onclick='return konfirmasi()'>Online</a>";
                                                    } else {
                                                        $login = "<a href='dist/pages/pengguna_update_flag.php?id=$row[id_pengguna]&flag=$row[flag]' title='Off line' class='btn btn-danger' onclick='return konfirmasi()'>Offline</a>";
                                                    }
                                                    $latency = "<a href='#' title='Latency' class='btn btn-info'>" . $row['latency'] . "</a>";
                                            ?>
                                                    <tr>
                                                        <td><?php echo $no; ?></td>
                                                        <td><?php echo $row['id_pengguna'] ?></td>
                                                        <td><?php echo $row['nama_user'] ?></td>
                                                        <td><?php echo $row['nama_lengkap'] ?></td>
                                                        <td><?php echo $row['nama_jabatan'] ?></td>
                                                        <td><?php echo $latency ?></td>
                                                        <td><?php echo $row['id_user_cbs'] ?></td>
                                                        <td><?php echo $row['kode_kas'] ?></td>
                                                        <td><?php echo DateToIndo(date($row['last_login'])) ?></td>
                                                        <td><?php echo $login ?></td>
                                                        <td>
                                                            <a href="?page=pengguna_edit&id=<?php echo $row['id_pengguna'] ?>" title="Edit" class="btn btn-info"><i class="fas fa-edit"></i></a>
                                                        </td>
                                                    </tr>
                                            <?php
                                                    $no++;
                                                }
                                            } catch (PDOException $e) {
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
                        function konfirmasi() {
                            return confirm('Koreksi data ini?');
                        }
                    </script>