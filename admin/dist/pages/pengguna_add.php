<?php
if (isset($_POST['tombol_tambah'])) {
    try {
        // Generate ID Pengguna
        $stm = $db->prepare("SELECT max(id_pengguna) as maxKode FROM sys_user");
        $stm->execute();
        $data = $stm->fetch();
        $pengguna_id = $data["maxKode"];
        $noUrut = (int)substr($pengguna_id, 1, 4);
        $noUrut++;
        $char = "U";
        $id_pengguna = $char . sprintf("%03s", $noUrut);

        // Validate Password
        $password_user = htmlentities($_POST['password_user']);
        $uppercase = preg_match('@[A-Z]@', $password_user);
        $lowercase = preg_match('@[a-z]@', $password_user);
        $number = preg_match('@[0-9]@', $password_user);
        $specialChars = preg_match('@[^\w]@', $password_user);

        if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password_user) < 8) {
            echo "<script>
            alert('Kata sandi harus setidaknya 8 karakter dan harus mencakup setidaknya satu huruf besar, satu angka, dan satu karakter khusus.');
            document.location.href='?page=pengguna_add';
            </script>";
        } else {
            // Define all variables
            $nama_user = htmlentities($_POST['nama_user']);
            $nama_lengkap = htmlentities($_POST['nama_lengkap']);
            $jabatan = htmlentities($_POST['jabatan']);
            $password_user = password_hash($password_user, PASSWORD_DEFAULT);
            $status_login = htmlentities($_POST['status_login']);
            $status_aktif = htmlentities($_POST['status_aktif']);
            $tgl_dibuat = date("Y-m-d");
            $tgl_expired = htmlentities($_POST['tgl_expired']);
            $latency = htmlentities($_POST['latency']);
            $kode_kantor = htmlentities($_POST['kode_kantor']);
            $kode_kas = htmlentities($_POST['kode_kas']);
            $username_cbs = htmlentities($_POST['username_cbs']);
            $id_user_cbs = htmlentities($_POST['id_user_cbs']);
            $first_use = "0";

            // Insert data
            $query = $db->prepare("INSERT INTO sys_user(
                id_pengguna, nama_user, nama_lengkap, password_user, flag, 
                tgl_dibuat, tgl_expired, status_aktif, latency, jabatan, 
                kode_kantor, kode_kas, username_cbs, id_user_cbs, first_use
            ) VALUES (
                :id_pengguna, :nama_user, :nama_lengkap, :password_user, :flag, 
                :tgl_dibuat, :tgl_expired, :status_aktif, :latency, :jabatan, 
                :kode_kantor, :kode_kas, :username_cbs, :id_user_cbs, :first_use
            )");

            // Bind parameters
            $query->bindParam(":id_pengguna", $id_pengguna);
            $query->bindParam(":nama_user", $nama_user);
            $query->bindParam(":nama_lengkap", $nama_lengkap);
            $query->bindParam(":password_user", $password_user);
            $query->bindParam(":flag", $status_login);
            $query->bindParam(":tgl_dibuat", $tgl_dibuat);
            $query->bindParam(":tgl_expired", $tgl_expired);
            $query->bindParam(":status_aktif", $status_aktif);
            $query->bindParam(":latency", $latency);
            $query->bindParam(":jabatan", $jabatan);
            $query->bindParam(":kode_kantor", $kode_kantor);
            $query->bindParam(":kode_kas", $kode_kas);
            $query->bindParam(":username_cbs", $username_cbs);
            $query->bindParam(":id_user_cbs", $id_user_cbs);
            $query->bindParam(":first_use", $first_use);
            $query->execute();

            echo "<script>
            alert('Data disimpan');
            document.location.href='?page=pengguna';
            </script>";
        }
    } catch (PDOException $e) {
        echo "<script>
        alert('Data gagal disimpan: " . $e->getMessage() . "');
        document.location.href='?page=pengguna_add';
        </script>";
    }
}
?>

                  <div class="container-fluid">
    <h2 class="mt-4">Tambah Pengguna</h2>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">
            <a href="?page=pengguna" class="btn btn-danger"><i class="fas fa-arrow-left"></i> Kembali</a>
        </li>
    </ol>

    <div class="card mb-4">
        <div class="card-header"><i class="fas fa-user mr-1"></i>Detail</div>
        <div class="card-body">
            <form method="post" action="" enctype="multipart/form-data">
                

                <!-- Nama Lengkap -->
                <div class="row mb-3">
                    <label class="form-label col-sm-3 text-end f_w_500 f_s_15">Nama Lengkap</label>
                    <div class="col-sm-9">
                        <input type="text" name="nama_lengkap" class="form-control" placeholder="Nama Lengkap" required>
                    </div>
                </div>

                <!-- Jabatan -->
                <div class="row mb-3">
                    <label class="form-label col-sm-3 text-end f_w_500 f_s_15">Jabatan</label>
                    <div class="col-sm-9">
                        <select class="form-control" name="jabatan" required>
                            <?php
                            $sql = "SELECT * FROM sys_jabatan ORDER BY nama_jabatan asc";
                            foreach ($db->query($sql) as $row) {
                            ?>
                                <option value="<?php echo $row['kode_jabatan'] ?>"><?php echo $row['kode_jabatan'] ?> &middot <?php echo $row['nama_jabatan'] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <!-- Nama User -->
                <div class="row mb-3">
                    <label class="form-label col-sm-3 text-end f_w_500 f_s_15">Nama User (untuk login)</label>
                    <div class="col-sm-9">
                        <input type="text" name="nama_user" class="form-control" placeholder="Nama User" required>
                    </div>
                </div>

                <!-- Password User -->
                <div class="row mb-3">
                    <label class="form-label col-sm-3 text-end f_w_500 f_s_15">Password User (untuk login)</label>
                    <div class="col-sm-9">
                        <input type="password" name="password_user" id="password_user" class="form-control" placeholder="Password" maxlength="10" required>
                        <div class="form-check mt-2">
                        <input type="checkbox" name="show_password" id="show_password" class="form-check-input">
                        <label class="form-check-label" for="show_password">Tampilkan Password</label>
                        </div>
                        <small><i><u>Kata sandi harus setidaknya 8 karakter dan harus mencakup setidaknya satu huruf besar, satu angka, dan satu karakter khusus.</u></i></small>
                    </div>
                </div>

                <!-- Status Login -->
                <div class="row mb-3">
                    <label class="form-label col-sm-3 text-end f_w_500 f_s_15">Status Login</label>
                    <div class="col-sm-9">
                        <select name="status_login" class="form-control" required>
                            <option value="1">Aktif</option>
                            <option value="0">Non-Aktif</option>
                        </select>
                    </div>
                </div>

                <!-- Tanggal Expired -->
                <div class="row mb-3">
                    <label class="form-label col-sm-3 text-end f_w_500 f_s_15">Tanggal Expired</label>
                    <div class="col-sm-9">
                        <input type="date" name="tgl_expired" class="form-control" required>
                    </div>
                </div>

                <!-- Latency -->
                <div class="row mb-3">
                    <label class="form-label col-sm-3 text-end f_w_500 f_s_15">Latency</label>
                    <div class="col-sm-9">
                                            <select id="latency" name="latency" class="form-control"> 
                                            <option value="1">SUPEREDP</option> 
                                            <option value="2" selected>ADMIN</option> 
                                            <option value="3">SECURITY</option> 
                                            <option value="4">MANAGER</option> 
                                        </select> 
                    </div>
                </div>


                <!-- CBS Kode Kantor dan Kode Kas -->
                <div class="row mb-3">
                    <label class="form-label col-sm-3 text-end f_w_500 f_s_15">Kode Kantor</label>
                    <div class="col-sm-9">
                        <select name="kode_kantor" class="form-control" required>
                            <option value="001">Kantor Pusat</option>
                            <option value="002">Kantor Cabang</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="form-label col-sm-3 text-end f_w_500 f_s_15">Kode Kas</label>
                    <div class="col-sm-9">
                    <input type="text" name="kode_kas" class="form-control" placeholder="Kode Kas" required>
                    </div>
                </div>

                <!-- Username CBS & ID User CBS -->
                <div class="row mb-3">
                    <label class="form-label col-sm-3 text-end f_w_500 f_s_15">Username CBS & ID User CBS</label>
                    <div class="col-sm-9">
                        <input type="text" name="username_cbs" class="form-control" placeholder="Username CBS" required>
                        <input type="text" name="id_user_cbs" class="form-control" placeholder="ID User CBS" required>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="row">
                                        <label for="" class="col-sm-2 col-form-label"></label>
                                        <div class="col-sm-9 offset-sm-3">
                                            <input type="submit" name="tombol_tambah" id="btnSubmit" class="btn btn-primary" value="Tambah">                                            
                                        </div>
                                    </div>
            </form>
        </div>
    </div>
</div>


<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){		
		    $("#show_password").click(function(){
			  if($(this).is(':checked')){
          $("#password_user").attr('type','text');
          $("#confirm_password_user").attr('type','text');
			  }else{
          $("#password_user").attr('type','password');
          $("#confirm_password_user").attr('type','password');
			  }
		    });
	      });

    $("#jabatan").select2({
    allowClear:false,
    });

  </script>                         