<style>
    h1 {
        text-align: center;
        font-family: Arial, Helvetica, sans-serif;
        position: fixed;
        top: 50%;
        left: 50%;
        /* bring your own prefixes */
        transform: translate(-50%, -50%);

        animation-name: loading_login;
        color: black;
        animation-duration: 1.5s;
        animation-iteration-count: 10000;
    }

    @keyframes loading_login {
        from {
            color: black;
        }

        to {
            color: white;
        }
    }
</style>
<?php
/**
 * simpleSQLinjectionDetect Class
 * @link      https://github.com/bs4creations/simpleSQLinjectionDetect 
 * @version   1.1
 */

class simpleSQLinjectionDetect
{
    protected $_method  = array();
    protected $_suspect = null;

    public $_options = array(
        'log'    => true,
        'unset'  => true,
        'exit'   => true,
        'errMsg' => 'Not allowed',
    );

    public function detect()
    {
        self::setMethod();

        if (!empty($this->_method)) {
            $result = self::parseQuery();

            if ($result) {
                if ($this->_options['log']) {
                    self::logQuery();
                }

                if ($this->_options['unset']) {
                    unset($_GET, $_POST);
                }

                if ($this->_options['exit']) {
                    exit($this->_options['errMsg']);
                }
            }
        }
    }

    private function setMethod()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->_method = $_GET;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->_method = $_POST;
        }
    }

    private function parseQuery()
    {
        $operators = array(
            'select * ',
            'select ',
            'union all ',
            'union ',
            ' all ',
            ' where ',
            ' and 1 ',
            ' and ',
            ' or ',
            ' 1=1 ',
            ' 2=2 ',
            ' -- ',
        );

        foreach ($this->_method as $key => $val) {
            $k = urldecode(strtolower($key));
            $v = urldecode(strtolower($val));

            foreach ($operators as $operator) {
                if (preg_match("/" . $operator . "/i", $k)) {
                    $this->_suspect = "operator: '" . $operator . "', key: '" . $k . "'";
                    return true;
                }
                if (preg_match("/" . $operator . "/i", $v)) {
                    $this->_suspect = "operator: '" . $operator . "', val: '" . $v . "'";
                    return true;
                }
            }
        }
    }

    private function logQuery()
    {
        $data  = date('d-m-Y H:i:s') . ' - ';
        $data .= $_SERVER['REMOTE_ADDR'] . ' - ';
        $data .= 'Suspect: [' . $this->_suspect . '] ';
        $data .= json_encode($_SERVER);
        @file_put_contents('./logs/sql.injection.txt', $data . PHP_EOL, FILE_APPEND);
    }
}

/* then call it in your app...
*********************************************/
$inj = new simpleSQLinjectionDetect();
$inj->detect();

function getOS()
{
    global $user_agent;
    $os_platform  = "Unknown OS Platform";
    $os_array     = array(
        '/windows nt 10/i'      =>  'Windows 10',
        '/windows nt 6.3/i'     =>  'Windows 8.1',
        '/windows nt 6.2/i'     =>  'Windows 8',
        '/windows nt 6.1/i'     =>  'Windows 7',
        '/windows nt 6.0/i'     =>  'Windows Vista',
        '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
        '/windows nt 5.1/i'     =>  'Windows XP',
        '/windows xp/i'         =>  'Windows XP',
        '/windows nt 5.0/i'     =>  'Windows 2000',
        '/windows me/i'         =>  'Windows ME',
        '/win98/i'              =>  'Windows 98',
        '/win95/i'              =>  'Windows 95',
        '/win16/i'              =>  'Windows 3.11',
        '/macintosh|mac os x/i' =>  'Mac OS X',
        '/mac_powerpc/i'        =>  'Mac OS 9',
        '/linux/i'              =>  'Linux',
        '/ubuntu/i'             =>  'Ubuntu',
        '/iphone/i'             =>  'iPhone',
        '/ipod/i'               =>  'iPod',
        '/ipad/i'               =>  'iPad',
        '/android/i'            =>  'Android',
        '/blackberry/i'         =>  'BlackBerry',
        '/webos/i'              =>  'Mobile'
    );
    foreach ($os_array as $regex => $value)
        if (preg_match($regex, $user_agent))
            $os_platform = $value;
    return $os_platform;
}
function getBrowser()
{
    global $user_agent;
    $browser        = "Unknown Browser";
    $browser_array = array(
        '/msie/i'      => 'Internet Explorer',
        '/firefox/i'   => 'Firefox',
        '/safari/i'    => 'Safari',
        '/chrome/i'    => 'Chrome',
        '/edge/i'      => 'Edge',
        '/opera/i'     => 'Opera',
        '/netscape/i'  => 'Netscape',
        '/maxthon/i'   => 'Maxthon',
        '/konqueror/i' => 'Konqueror',
        '/mobile/i'    => 'Handheld Browser'
    );
    foreach ($browser_array as $regex => $value)
        if (preg_match($regex, $user_agent))
            $browser = $value;
    return $browser;
}

function login_sukses()
{
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $nama = $_POST["nama_user"];
    date_default_timezone_set('Asia/Jakarta');
    //get ip address
    $ipaddress = gethostbyname(trim(exec("hostname")));

    //get os and browser info
    $user_os        = getOS();
    $user_browser   = getBrowser();

    // open log file
    $filename = "../logs/log_app.log";
    $fh = fopen($filename, "a") or die("Could not open log file.");
    fwrite($fh, date("d-m-Y|H:i:s") . "|IP_ADDRESS: $ipaddress|LOGIN: $nama|DESCRIPTION: $user_agent\n") or die("Could not write file!");
    fclose($fh);
}
?>
<?php
if (isset($_POST['input_captcha'])) {
    session_start();
    if ($_SESSION['kode_captcha'] != $_POST['input_captcha']) {
        echo "<script>
            alert('Security code salah!');
            document.location.href='../index.php';
            </script>";
    } else {
        include_once "../bin/koneksi.php";
        $database = new Connection();
        $db = $database->openConnection();

        $nama_user = htmlentities($_POST['nama_user']);
        $password_user = htmlentities($_POST['password_user']);
        strpos($nama_user, ';') ? die('username tidak boleh mengandung ";"') : '';

        $hari_ini = date("Y-m-d");

        $query = $db->prepare("SELECT * FROM sys_user WHERE nama_user=:nama_user");
        $query->bindParam(":nama_user", $nama_user);
        $query->execute();
        if ($query->rowCount() == 0) {
            echo "<script>
                alert('Maaf, Username tidak ditemukan');
                document.location.href='../index.php';
                </script>";
        } else {
            $data = $query->fetch();
            $id_pengguna = $data['id_pengguna'];
            if (password_verify($password_user, $data['password_user'])) {
                if ($data['last_login'] <> $hari_ini) {
                    $query = $db->prepare("UPDATE sys_user SET flag=0,login_failure=0 WHERE id_pengguna=:id_pengguna");
                    $query->bindParam(":id_pengguna", $id_pengguna);
                    $query->execute();
                    //mengambil ulang record setelah di update
                    $query = $db->prepare("SELECT * FROM sys_user WHERE id_pengguna=:id_pengguna");
                    $query->bindParam(":id_pengguna", $id_pengguna);
                    $query->execute();
                    $data = $query->fetch();
                    $id_pengguna = $data['id_pengguna'];
                }
                if ($data['first_use'] == 0) {
                    echo "<script>
                        alert('Silahkan ganti password default anda.');
                        document.location.href='../renew_user.php?id_pengguna=$id_pengguna&first_use=true';
                        </script>";
                } elseif ($data['status_aktif'] == 0) {
                    echo "<script>
                        alert('Maaf, Username anda diblokir, hubungi ADMINISTRATOR.');
                        document.location.href='../index.php';
                        </script>";
                } elseif ($data['flag'] == 1) {
                    echo "<script>
                        alert('Maaf, Username anda masih aktif di perangkat lain');
                        document.location.href='../unflag_user.php?id_pengguna=$id_pengguna&first_use=false';
                        </script>";
                } elseif ($hari_ini > $data['tgl_expired']) {
                    echo "<script>
                        alert('Maaf, Username anda telah expired');
                        document.location.href='../renew_user.php?id_pengguna=$id_pengguna&first_use=false';
                        </script>";
                } else {
                    $query = $db->prepare("UPDATE sys_user SET flag=1,last_login=:last_login WHERE id_pengguna=:id_pengguna");
                    $query->bindParam(":last_login", $hari_ini);
                    $query->bindParam(":id_pengguna", $id_pengguna);
                    $query->execute();
                    login_sukses();
                    //session_start();
                    $_SESSION['id_pengguna'] = $id_pengguna;
                    $_SESSION['nama_user'] = $data['nama_user'];
                    $_SESSION['latency'] = $data['latency'];
                    $_SESSION["last_login_time"] = time();
                    $_SESSION['id_user_cbs'] = $data['id_user_cbs'];
                    $_SESSION['kode_kantor'] = (string)$data['kode_kantor'];
                    $_SESSION['kode_kas'] = $data['kode_kas'];
                    $_SESSION['username_cbs'] = $data['username_cbs'];

                    include_once "sys_log_user.php";

                    if ($data['latency'] == 3) {
                        echo "<script>
                            document.location.href='../lobby/index.php';
                            </script>";
                    } else {
                        echo "<script>
                            document.location.href='../admin/index.php?page=home';
                            </script>";
                    }
                }
            } else {
                $query = $db->prepare("UPDATE sys_user SET login_failure=login_failure+1 WHERE id_pengguna=:id_pengguna");
                $query->bindParam(":id_pengguna", $id_pengguna);
                $query->execute();
                if ($data['login_failure'] == 2) {
                    echo "<script>
                        alert('Maaf, Password Salah 3 kali, Coba lagi besok.');
                        document.location.href='../index.php';
                        </script>";
                } elseif ($data['login_failure'] > 2) {
                    $query = $db->prepare("UPDATE sys_user SET status_aktif=0 WHERE id_pengguna=:id_pengguna");
                    $query->bindParam(":id_pengguna", $id_pengguna);
                    $query->execute();
                    echo "<script>
                        alert('Maaf, Password Salah lebih dari 3 kali, User anda di blokir oleh sistem.');
                        document.location.href='../index.php';
                        </script>";
                } else {
                    echo "<script>
                        alert('Maaf, password salah.');
                        document.location.href='../index.php';
                        </script>";
                }
            }
        }
    }
} else {
    echo "<script>
        alert('Security code masih kosong!');
        document.location.href='../index.php';
        </script>";
}


?>