<?php
    if(!isset($_GET['id'])){
        die("Error: ID Tidak Dimasukkan");
    }
    $id = $_GET['id'];

    $query = $db->prepare("SELECT * FROM sys_user WHERE id_pengguna=:id_pengguna");
    $query->bindParam(":id_pengguna", $id);
    $query->execute();
    if($query->rowCount() == 0){
        die("Error: Pengguna Tidak Ditemukan");
    } else {
        $data = $query->fetch();
    }

    if (isset($_POST['tombol_edit'])) {
        $nama_user = htmlentities($_POST['nama_user']);
        $nama_lengkap = htmlentities($_POST['nama_lengkap']);
        $jabatan = htmlentities($_POST['jabatan']);
        $password_user = htmlentities($_POST['password_user']);
        $status_login = htmlentities($_POST['status_login']);
        $tgl_expired = htmlentities($_POST['tgl_expired']);
        $status_aktif = htmlentities($_POST['status_aktif']);
        $latency = htmlentities($_POST['latency']);
        $login_failure = htmlentities($_POST['login_failure']);
        $password_user_awal = htmlentities($_POST['password_user_awal']);
        $first_use = htmlentities($_POST['first_use']);
        $kode_kantor = htmlentities($_POST['kode_kantor']);
        $kode_kas = htmlentities($_POST['kode_kas']);
        $username_cbs = htmlentities($_POST['username_cbs']);
        $id_user_cbs = htmlentities($_POST['id_user_cbs']);
        
        if ($password_user_awal == $password_user) {
            $query = $db->prepare("UPDATE sys_user SET nama_user=:nama_user, nama_lengkap=:nama_lengkap, flag=:flag, tgl_expired=:tgl_expired, status_aktif=:status_aktif, latency=:latency, 
            login_failure=:login_failure, jabatan=:jabatan, kode_kantor=:kode_kantor, kode_kas=:kode_kas, username_cbs=:username_cbs, id_user_cbs=:id_user_cbs, first_use=:first_use WHERE id_pengguna=:id_pengguna");
            $query->bindParam(":nama_user", $nama_user);
            $query->bindParam(":nama_lengkap", $nama_lengkap);
            $query->bindParam(":flag", $status_login);
            $query->bindParam(":tgl_expired", $tgl_expired);
            $query->bindParam(":status_aktif", $status_aktif);
            $query->bindParam(":latency", $latency);
            $query->bindParam(":login_failure", $login_failure);
            $query->bindParam(":id_pengguna", $id);
            $query->bindParam(":jabatan", $jabatan);
            $query->bindParam(":first_use", $first_use);
            $query->bindParam(":kode_kantor", $kode_kantor);
            $query->bindParam(":kode_kas", $kode_kas);
            $query->bindParam(":username_cbs", $username_cbs);
            $query->bindParam(":id_user_cbs", $id_user_cbs);
            $query->execute();
            echo "<script>
            alert('Data dikoreksi');
            document.location.href='index.php?page=pengguna';
            </script>";
        } else {
            // Validate password strength
            $uppercase = preg_match('@[A-Z]@', $password_user);
            $lowercase = preg_match('@[a-z]@', $password_user);
            $number = preg_match('@[0-9]@', $password_user);
            $specialChars = preg_match('@[^\w]@', $password_user);

            if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password_user) < 8) {
                echo "<script>
                alert('Kata sandi harus setidaknya 8 karakter dan harus mencakup setidaknya satu huruf besar, satu angka, dan satu karakter khusus.');
                document.location.href='?page=pengguna_edit&id=$id';
                </script>";
            } else {
                $password_user = password_hash($password_user, PASSWORD_DEFAULT);
                $query = $db->prepare("UPDATE sys_user SET nama_user=:nama_user, nama_lengkap=:nama_lengkap, password_user=:password_user, flag=:flag, tgl_expired=:tgl_expired, status_aktif=:status_aktif, latency=:latency,
                login_failure=:login_failure, jabatan=:jabatan, kode_kantor=:kode_kantor, kode_kas=:kode_kas, username_cbs=:username_cbs, id_user_cbs=:id_user_cbs, first_use=:first_use WHERE id_pengguna=:id_pengguna");
                $query->bindParam(":nama_user", $nama_user);
                $query->bindParam(":nama_lengkap", $nama_lengkap);
                $query->bindParam(":password_user", $password_user);
                $query->bindParam(":flag", $status_login);
                $query->bindParam(":tgl_expired", $tgl_expired);
                $query->bindParam(":status_aktif", $status_aktif);
                $query->bindParam(":latency", $latency);
                $query->bindParam(":login_failure", $login_failure);
                $query->bindParam(":id_pengguna", $id);
                $query->bindParam(":jabatan", $jabatan);
                $query->bindParam(":kode_kantor", $kode_kantor);
                $query->bindParam(":kode_kas", $kode_kas);
                $query->bindParam(":username_cbs", $username_cbs);
                $query->bindParam(":id_user_cbs", $id_user_cbs);
                $query->execute();
                echo "<script>
                alert('Data dikoreksi');
                document.location.href='index.php?page=pengguna';
                </script>";
            }
        }
    }
?>
                   <div class="container-fluid">
    <h2 class="mt-4">Edit Pengguna</h2>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">
            <a href="?page=pengguna" class="btn btn-danger"><i class="fas fa-arrow-left"></i> Kembali</a>
        </li>
    </ol>

    <div class="card mb-4">
        <div class="card-header"><i class="fas fa-user mr-1"></i>Detail</div>
        <div class="card-body">
            <form method="post" action="" enctype="multipart/form-data">
                <div class="row mb-3">
                    <label class="form-label col-sm-3 text-end f_w_500 f_s_15">ID Pengguna</label>
                    <div class="col-sm-9">
                        <input type="text" name="id_pengguna" class="form-control" id="id_pengguna" placeholder="Wajib 4 Digit Angka" maxlength="4" readonly="true" value="<?php echo $id?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="form-label col-sm-3 text-end f_w_500 f_s_15">Nama Lengkap</label>
                    <div class="col-sm-9">
                        <input type="text" name="nama_lengkap" class="form-control" id="nama_lengkap" placeholder="Nama Lengkap" value="<?php echo $data['nama_lengkap']?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="form-label col-sm-3 text-end f_w_500 f_s_15">Jabatan</label>
                    <div class="col-sm-9">
                        <select class="form-control" id="jabatan" name="jabatan">
                            <?php
                            try {
                                $sql = "SELECT * FROM sys_jabatan ORDER BY nama_jabatan asc";
                                foreach ($db->query($sql) as $row) {
                            ?>  
                                <option value="<?php echo $row['kode_jabatan']?>" <?php if($data['jabatan']==$row['kode_jabatan']){ echo "selected"; } ?>><?php echo $row['kode_jabatan']?> &middot <?php echo $row['nama_jabatan']?></option>        
                            <?php
                            }
                            } catch (PDOException $e) {
                                echo "There is some problem in connection: " . $e->getMessage();
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="form-label col-sm-3 text-end f_w_500 f_s_15">Nama User (untuk login)</label>
                    <div class="col-sm-9">
                        <input type="text" name="nama_user" class="form-control" id="nama_user" placeholder="Nama User" value="<?php echo $data['nama_user']?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="form-label col-sm-3 text-end f_w_500 f_s_15">Password User (untuk login)</label>
                    <div class="col-sm-9">
                        <input type="password" name="password_user" class="form-control" id="password_user" maxlength="10" value="<?php echo $data['password_user']?>" required>
                        <div class="form-check mt-2">
                            <input type="checkbox" name="show_password" id="show_password" class="form-check-input">
                            <label class="form-check-label" for="show_password">Tampilkan Password</label>
                        </div>
                        <small><i><u>Kata sandi harus setidaknya 8 karakter dan harus mencakup setidaknya satu huruf besar, satu angka, dan satu karakter khusus.</u></i></small>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="form-label col-sm-3 text-end f_w_500 f_s_15">Status Login</label>
                    <div class="col-sm-9">
                        <select id="status_login" name="status_login" class="form-control" required>
                            <option value="1" <?php echo ($data['status_login'] == '1' ? 'selected' : '') ?>>Aktif</option>
                            <option value="0" <?php echo ($data['status_login'] == '0' ? 'selected' : '') ?>>Non-Aktif</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="form-label col-sm-3 text-end f_w_500 f_s_15">Tanggal Expired</label>
                    <div class="col-sm-9">
                        <input type="date" name="tgl_expired" class="form-control" value="<?php echo $data['tgl_expired']?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="form-label col-sm-3 text-end f_w_500 f_s_15">Status Akun</label>
                    <div class="col-sm-9">
                        <select id="status_aktif" name="status_aktif" class="form-control">
                            <option value="1" <?php echo ($data['status_aktif'] == '1' ? 'selected' : '') ?>>Aktif</option>
                            <option value="0" <?php echo ($data['status_aktif'] == '0' ? 'selected' : '') ?>>Non-Aktif</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="form-label col-sm-3 text-end f_w_500 f_s_15">Latency</label>
                    <div class="col-sm-9">
                        <select id="latency" name="latency" class="form-control">
                            <option value="1" <?php if($data['latency']=="1"){echo "selected";}?>>SUPEREDP</option>
                            <option value="2" <?php if($data['latency']=="2"){echo "selected";}?>>ADMIN</option>
                            <option value="3" <?php if($data['latency']=="3"){echo "selected";}?>>SECURITY</option>
                            <option value="4" <?php if($data['latency']=="4"){echo "selected";}?>>MANAGER</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="form-label col-sm-3 text-end f_w_500 f_s_15">Login Failure</label>
                    <div class="col-sm-9">
                        <input type="number" name="login_failure" class="form-control" value="<?php echo $data['login_failure']?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="form-label col-sm-3 text-end f_w_500 f_s_15">ID CBS</label>
                    <div class="col-sm-9">
                        <input type="number" name="id_user_cbs" class="form-control" value="<?php echo $data['id_user_cbs']?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="form-label col-sm-3 text-end f_w_500 f_s_15">USERNAME CBS</label>
                    <div class="col-sm-9">
                        <input type="text" name="username_cbs" class="form-control" value="<?php echo $data['username_cbs']?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="form-label col-sm-3 text-end f_w_500 f_s_15">KODE KANTOR</label>
                    <div class="col-sm-9">
                        <input type="number" name="kode_kantor" class="form-control" value="<?php echo $data['kode_kantor']?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="form-label col-sm-3 text-end f_w_500 f_s_15">KODE KASTELLER</label>
                    <div class="col-sm-9">
                        <input type="number" name="kode_kas" class="form-control" value="<?php echo $data['kode_kas']?>" required>
                    </div>
                </div>

                                    <div class="form-group row">
                                        <label for="" class="col-sm-2 col-form-label"></label>
                                        <div class="col-sm-10">
                                            <input type="hidden" name="password_user_awal" class="form-control" id="password_user_awal" value="<?php echo $data['password_user']?>">
                                            <input type="submit" name="tombol_edit" id="btnSubmit" class="btn btn-primary" value="Edit">                                            
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