<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<style>
	.forbidden-container {
		text-align: center;
		padding: 30px;
		border-radius: 10px;
		box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
	}

	.forbidden-container h1 {
		font-size: 100px;
		color: #dc3545;
		margin-bottom: 20px;
	}

	.forbidden-container h2 {
		font-size: 32px;
		margin-bottom: 10px;
		color: #333;
	}

	.forbidden-container p {
		color: #777;
		font-size: 16px;
		margin-bottom: 20px;
	}

	.forbidden-container a.btn {
		background-color: #007bff;
		color: white;
		padding: 10px 25px;
		border-radius: 5px;
		text-decoration: none;
	}

	.forbidden-container a.btn:hover {
		background-color: #0056b3;
	}
</style>
<div class="forbidden-container">
	<h1>403</h1>
	<h2>Akses Ditolak</h2>
	<p>Maaf, Anda tidak memiliki izin untuk mengakses halaman ini.<br>Silakan hubungi administrator jika Anda merasa ini adalah kesalahan.</p>
	<a href="<?php base_url(); ?>" class="btn">Kembali ke Beranda</a>
</div>


</div>