<style>
   /* Styling for modal overlay */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5); /* Semi-transparent black background */
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 1050;  /* Ensure the modal is above all other elements */
}

/* To dim the background when modal is active */
.container-fluid {
    transition: opacity 0.3s ease;
}

.modal-content {
    background: white;
    padding: 20px;
    border-radius: 10px;
    width: 400px;
    box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
}

.close {
    cursor: pointer;
    font-size: 1.5rem;
}

/* Apply opacity to background when modal is visible */
.modal-overlay.active + .container-fluid {
    opacity: 0.3;  /* Dim the background */
}

</style>
<script>
 // Function to display the OTP modal
function updateOTP() {
    // Set the transaction ID (if needed)
    var transactionId = document.getElementById("id_trans").value;
    document.getElementById("idTransInput").value = transactionId;
    
    // Show the OTP modal and dim the background
    document.getElementById("otpModal").style.display = "flex";
    document.querySelector('.modal-overlay').classList.add('active');
}

// Function to close the OTP modal
function closeModal() {
    document.getElementById("otpModal").style.display = "none";
    document.querySelector('.modal-overlay').classList.remove('active');
}

// Function to handle OTP form submission
function submitOTP() {
    var otp = document.getElementById("otpInput").value;
    var transactionId = document.getElementById("idTransInput").value;

    // Validate OTP (simple example, you can add your own validation)
    if (otp.length === 6) {
        // Submit OTP to the server or perform further actions
        alert("OTP submitted for transaction ID: " + transactionId);
        closeModal(); // Close the modal after submission
    } else {
        alert("Please enter a valid 6-digit OTP.");
    }
}

</script>
<div class="container-fluid">
    <h2 class="mt-4">Transaksi Tabungan via QRCode</h2>

    <form id="transactionForm" action="" method="POST">
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-user mr-1"></i> Detail
        </div>
        <div class="card-body">
            <!-- Video Preview -->
            <div class="form-group row">
    <label class="col-sm-2 col-form-label"></label>
    <div class="col-sm-10" style="position: relative; overflow: hidden;">
        <!-- Video element -->
        <video id="preview" style="width: 100%; max-width: 300px; border: 1px solid #ddd; margin-top: 20px; display: none;"></video>
        
        <!-- Scanning effect overlay -->
        <div class="scanning-overlay"></div>
    </div>
</div>

<style>
    .scanning-overlay {
        position: absolute;
        top: 20px;
        left: 10px;
        width: 23%;
        height: 4px;
        background-color: rgba(0, 255, 0, 0.7); 
        animation: scanning 2s infinite linear, fadeOut 10s forwards;
    }

    @keyframes scanning {
        0% {
            top: -8px;
        }
        100% {
            top: 100%; 
        }
    }
    @keyframes fadeOut {
        100% {
            opacity: 0; 
        }
    }
    #preview {
        animation: fadeOutVideo 30s forwards; 
    }

    @keyframes fadeOutVideo {
        100% {
            opacity: 0;
            display: none;
        }
    }
</style>




            <!-- ID Transaksi -->
            <div class="form-group row">
                <label for="id_trans" class="col-sm-2 col-form-label">ID Transaksi</label>
                <div class="col-sm-10">
                    <input type="text" name="id_trans" class="form-control" id="id_trans" readonly>
                </div>
            </div>

            <!-- Tanggal Transaksi -->
            <div class="form-group row">
                <label for="tgl_trans" class="col-sm-2 col-form-label">Tanggal Transaksi</label>
                <div class="col-sm-10">
                    <input type="text" name="tgl_trans" class="form-control" id="tgl_trans" readonly>
                </div>
            </div>

            <!-- Jam Transaksi -->
            <div class="form-group row">
                <label for="jam_trans" class="col-sm-2 col-form-label">Jam Transaksi</label>
                <div class="col-sm-10">
                    <input type="text" name="jam_trans" class="form-control" id="jam_trans" readonly>
                </div>
            </div>

            <!-- Jenis Transaksi -->
            <div class="form-group row">
                <label for="jenis_trans" class="col-sm-2 col-form-label">Jenis Transaksi</label>
                <div class="col-sm-10">
                    <input type="text" name="jenis_trans" class="form-control" id="jenis_trans" readonly>
                </div>
            </div>

            <!-- Nomor Rekening -->
            <div class="form-group row">
                <label for="no_rekening" class="col-sm-2 col-form-label">Nomor Rekening</label>
                <div class="col-sm-10">
                    <input type="text" name="no_rekening" class="form-control" id="no_rekening" readonly>
                </div>
            </div>

            <!-- Nama Lengkap -->
            <div class="form-group row">
                <label for="nama_lengkap" class="col-sm-2 col-form-label">Nama Lengkap</label>
                <div class="col-sm-10">
                    <input type="text" name="nama_lengkap" class="form-control" id="nama_lengkap" readonly>
                </div>
            </div>

            <!-- Nomor HP -->
            <div class="form-group row">
                <label for="nomor_hp" class="col-sm-2 col-form-label">Nomor HP</label>
                <div class="col-sm-10">
                    <input type="text" name="nomor_hp" class="form-control" id="nomor_hp" readonly>
                </div>
            </div>

            <!-- Nominal -->
            <div class="form-group row">
                <label for="nominal" class="col-sm-2 col-form-label">Nominal</label>
                <div class="col-sm-10">
                    <input type="text" name="nominal" class="form-control" id="nominal" readonly>
                </div>
            </div>

            <!-- Status Transaksi -->
            <div class="form-group row">
                <label for="status_trans" class="col-sm-2 col-form-label">Status Transaksi</label>
                <div class="col-sm-10">
                    <input type="text" name="status_trans" class="form-control" id="status_trans" readonly>
                </div>
            </div>

            <!-- Proses Button -->
            <div class="form-group row">
                <label class="col-sm-2 col-form-label"></label>
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