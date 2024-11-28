<div class="container-fluid">
    <h2 class="mt-4">Generate QRCode</h2>
    <div class="card mb-4">
        <div class="card-header"><i class="fas fa-user mr-1"></i>Detail</div>
        <div class="card-body">
            <form method="post" action="" enctype="multipart/form-data">
                <div class="form-group row">
                    <label for="nik" class="col-sm-2 col-form-label">NIK</label>
                    <div class="col-sm-10">
                        <input type="text" name="nik" class="form-control" id="nik" placeholder="Nomor Induk Kependudukan" value="3510170107930007" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-10">
                        <input type="button" name="cek_nasabah_from_core" id="cek_nasabah_from_core" class="btn btn-success" value="Cek Nasabah">                                            
                    </div>
                </div>
                <div id="detail_nasabah_from_cbs"></div>

                <div class="form-group row">
                    <label for="" class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-10">
                        <input type="submit" name="tombol_tambah" id="btnSubmit" class="btn btn-primary" value="Generate">
                    </div>
                </div>
            </form>            
        </div>
    </div>
</div>
