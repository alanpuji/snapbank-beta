<div class="container-fluid">
    <h2 class="mt-4">Transaksi Tabungan</h2>

    <form id="transactionForm" action="" method="POST">
        <div class="card mb-4">
            <div class="card-header"><i class="fas fa-user mr-1"></i>Detail</div>
            <div class="card-body">
                <div class="form-group row">
                    <label for="" class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-10">
                        <video id="preview" style="width: 100%; max-width: 200px; border: 1px solid #ddd; margin-top: 20px; display: none;"></video>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="id_trans" class="col-sm-2 col-form-label">ID Transaksi</label>
                    <div class="col-sm-10">
                        <input type="text" name="id_trans" class="form-control" id="id_trans" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="tgl_trans" class="col-sm-2 col-form-label">Tanggal Transaksi</label>
                    <div class="col-sm-10">
                        <input type="text" name="tgl_trans" class="form-control" id="tgl_trans" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="jam_trans" class="col-sm-2 col-form-label">Jam Transaksi</label>
                    <div class="col-sm-10">
                        <input type="text" name="jam_trans" class="form-control" id="jam_trans" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="jenis_trans" class="col-sm-2 col-form-label">Jenis Transaksi</label>
                    <div class="col-sm-10">
                        <input type="text" name="jenis_trans" class="form-control" id="jenis_trans" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="no_rekening" class="col-sm-2 col-form-label">Nomor Rekening</label>
                    <div class="col-sm-10">
                        <input type="text" name="no_rekening" class="form-control" id="no_rekening" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="nama_lengkap" class="col-sm-2 col-form-label">Nama Lengkap</label>
                    <div class="col-sm-10">
                        <input type="text" name="nama_lengkap" class="form-control" id="nama_lengkap" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="nomor_hp" class="col-sm-2 col-form-label">Nomor HP</label>
                    <div class="col-sm-10">
                        <input type="text" name="nomor_hp" class="form-control" id="nomor_hp" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="nominal" class="col-sm-2 col-form-label">Nominal</label>
                    <div class="col-sm-10">
                        <input type="text" name="nominal" class="form-control" id="nominal" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="status_trans" class="col-sm-2 col-form-label">Status Transaksi</label>
                    <div class="col-sm-10">
                        <input type="text" name="status_trans" class="form-control" id="status_trans" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-10">
                        <button type="button" class="btn btn-success mt-3" onclick="updateOTP()" id="tombol_tambah">Proses</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Modal Overlay -->
    <div class="modal-overlay" id="otpModal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Input OTP</h5>
                <button class="close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="otpForm" method="POST" action="#">
                    <!-- Hidden input for ID Transaction -->
                    <input type="hidden" id="idTransInput" name="id_trans" value="">

                    <div class="form-group">
                        <label for="otpInput">Enter OTP</label>
                        <input type="text" class="form-control" id="otpInput" name="otp" maxlength="6" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" onclick="submitOTP()">Submit OTP</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>