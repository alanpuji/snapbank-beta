<?php
error_reporting(0);
$page = $_GET['page'];
switch ($page) {
	case "home";
		include "home.php";
		break;
	case "pengguna";
		include "pengguna.php";
		break;
	case "pengguna_add";
		include "pengguna_add.php";
		break;
	case "pengguna_edit";
		include "pengguna_edit.php";
		break;
	case "generate_qrcode";
		include "generate_qrcode.php";
		break;
	case "customer";
		include "customer.php";
		break;
	case "customer_add";
		include "customer_add.php";
		break;
	case "customer_edit";
		include "customer_edit.php";
		break;
	case "customer_generate";
		include "customer_generate.php";
		break;
	case "customer_qrcode";
		include "customer_qrcode.php";
		break;
	case "customer_detail";
		include "customer_detail.php";
		break;
	case "transaksi_tab_qrcode";
		include "transaksi_tab_qrcode.php";
		break;
	case "sys_parameter";
		include "sys_parameter.php";
		break;
	case "sys_parameter_add";
		include "sys_parameter_add.php";
		break;
	case "sys_parameter_edit";
		include "sys_parameter_edit.php";
		break;
		case "sys_lobby";
		include "sys_lobby.php";
		break;

	case "page":
	default:
		include "home.php";
}
