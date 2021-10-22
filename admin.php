<?php
session_start();
if (!isset($_SESSION["login"])) {
	header("Location:login.php");
	exit;
}elseif($_SESSION['role']!='admin'){
    header("Location:index.php");
	exit;
}


?>

<!DOCTYPE html>
<html>

<head>
	<title>Pendaftaran</title>
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="assets/css/bootstrap.min.css">

</head>

<body>
	<!-- HEADER -->
	<nav class="navbar navbar-expand-lg navbar-light bg-light">
		<div class="container">
			<a class="navbar-brand" href="admin.php"><img style="max-width: 70%;" src="assets/images/logo.png" alt="home"></a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav mr-auto">
					<li class="nav-item <?= isset($_GET['fungsi'])?($_GET['fungsi']=='read'?'active':''):''; ?>">
						<a class="nav-link" href="admin.php?fungsi=read">Informasi Pendaftaran</a>
					</li>
                    <li class="nav-item <?= isset($_GET['fungsi'])?($_GET['fungsi']=='listAkun'?'active':''):''; ?>">
						<a class="nav-link" href="admin.php?fungsi=listAkun">List Akun</a>
					</li>
                    <li class="nav-item <?= isset($_GET['fungsi'])?($_GET['fungsi']=='listAkun'?'active':''):''; ?>">
						<a class="nav-link" href="pembuat-Laporan.php">Cetak Laporan</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href=""></a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="logout.php">Logout</a>
					</li>
				</ul>
			</div>
		</div>
	</nav>
	<?php
	include('core/koneksi.php');
	if (isset($_GET['fungsi'])) {
		switch ($_GET['fungsi']) {
			case "create":
				create($koneksi);
				break;
			case "create_success":
				create_success();
				break;
			case "read":
				read($koneksi);
				break;
			case "update":
				update($koneksi);
				break;
			case "update_success":
				update_success();
				break;
            case "listAkun":
                listAkun($koneksi);
                break;
            case "profil":
                profil($koneksi);
                break;
			case "delete":
				delete($koneksi);
				break;
            case "deleteAkun":
                deleteAkun($koneksi);
                break;
			case "delete_success":
				delete_success();
				break;
			default:
				read($koneksi);
		}
	} else {
		mainpage();
	}

	function mainpage()
	{
		echo '
	<div class="container" style="margin-top:20px">
	<h3>Pendaftaran Calon Siswa</h3> 
		<hr>

		<p> Silahkan pilih <b> Menu Informasi Pendaftaran</b> untuk melihat data pendaftar </p> 
		</div>';
	}

	function create($koneksi)
	{
		if (isset($_POST['btn_simpan'])) {
			$nama_peserta = $_POST['nama_peserta'];
			$alamat = $_POST['alamat'];
			$jenis_kelamin = $_POST['jenis_kelamin'];
			$agama = $_POST['agama'];
			$sekolah_asal = $_POST['sekolah_asal'];
			if (
				!empty($nama_peserta) && !empty($alamat) && !empty($jenis_kelamin) &&
				!empty($agama) && !empty($sekolah_asal)
			) {
				$sql = "INSERT INTO pendaftaran (id_peserta,nama_peserta, alamat, jenis_kelamin, agama, sekolah_asal) VALUES('','" . $nama_peserta . "','" . $alamat . "','" . $jenis_kelamin . "','" . $agama . "','" . $sekolah_asal . "')";
				//echo $sql;
				// die($sql);
				$simpan = mysqli_query($koneksi, $sql);
				if ($simpan && isset($_GET['fungsi'])) {
					if ($_GET['fungsi'] == 'create') {
						header('location:admin.php?fungsi=create_success');
					}
				}
			} else {
				$pesan = "Tidak dapat menyimpan, data belum lengkap!";
			}
		}
	?>
		<div class="container" style="margin-top:20px">
			<h2>Tambah Data Peserta</h2>
			<form action="admin.php?fungsi=create" method="post">
				<div class="form-group row">
					<label class="col-sm-2 col-form-label">Nama</label>
					<div class="col-sm-10">
						<input type="text" name="nama_peserta" class="form-control" size="4" required>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-2 col-form-label">Alamat</label>
					<div class="col-sm-10">
						<textarea name="alamat" class="form-control" required></textarea>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-2 col-form-label">Jenis Kelamin</label>
					<div class="col-sm-10">
						<div class="form-check">
							<input type="radio" class="form-check-input" name="jenis_kelamin" value="L" required>
							<label class="form-check-label">Laki-laki</label>
						</div>
						<div class="form-check">
							<input type="radio" class="form-check-input" name="jenis_kelamin" value="P" required>
							<label class="form-check-label">Perempuan</label>
						</div>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-2 col-form-label">Agama</label>
					<div class="col-sm-10">
						<select name="agama" class="form-control" required>
							<option value="">Pilih salah satu</option>
							<option value="Islam">Islam</option>
							<option value="Kristen Protestan">Kristen Protestan</option>
							<option value="Katolik">Katolik</option>
							<option value="Hindu">Hindu</option>
							<option value="Budha">Budha</option>
							<option value="Kepercayaan lainnya">Kepercayaan lainnya</option>
						</select>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-2 col-form-label">Sekolah Asal</label>
					<div class="col-sm-10">
						<input type="text" name="sekolah_asal" class="form-control" required>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-2 col-form-label">&nbsp;
					</label>
					<div class="col-sm-10">
						<input type="submit" name="btn_simpan" class="btn btn-primary" value="Simpan">
						<input type="reset" name="btn_reset" class="btn btn-info" value="Reset">
						<a href="admin.php" class="btn btn-success" role="button">Kembali</a>
					</div>
				</div>
			</form>

		</div>
	<?php

	}

	function create_success()
	{
		echo '	
			<div class="container" style="margin-top:20px">	
			<h3>Data Calon Peserta Digital Telent</h3>	
				
			<hr>	
				
			<p> Pendaftaran Berhasil </p>	
				
			</div>';
	}


	// --- Fungsi Baca Data (Read)	
	function read($koneksi)
	{
		echo '	
			<div class="container" style="margin-top:20px">	
			<h2>Pendaftar</h2>	
				
			<hr>	
				
			<table class="table table-striped table-hover	table-sm table-bordered">
			<thead class="thead-dark">	
			<tr>	
			<th>No</th>	
			<th>Nama Peserta</th>	
			<th>Alamat</th>	
			<th>Jenis Kelamin</th>	
			<th>Agama</th>	
			<th>Sekolah Asal</th>
            <th>Status</th>	
			<th>Tindakan</th>	
			</tr>	
			</thead>	
			<tbody>';

		//query ke database SELECT tabel siswa urut berdasarkan id yang paling besar
		$sql = "SELECT * FROM pendaftaran";
		$query = mysqli_query($koneksi, $sql);

		//jika query diatas menghasilkan nilai > 0 maka menjalankan script di bawah if...
		if (mysqli_num_rows($query) > 0) {
			$no = 1;
			//melakukan perulangan while dengan dari dari query $sql
			while ($data = mysqli_fetch_assoc($query)) {
				//menampilkan data perulangan
                $jk = $data['jenis_kelamin']=='L'?'Laki-Laki':'Perempuan';
				echo '
					<tr>
					<td>' . $no . '</td>
					<td>' . $data['nama_peserta'] . '</td>
					<td>' . $data['alamat'] . '</td>
					<td>' . $jk . '</td>
					<td>' . $data['agama'] . '</td>
					<td>' . $data['sekolah_asal'] . '</td>
                    <td>' . $data['status'] . '</td>
					<td>
					<a href="admin.php?fungsi=update&id_peserta=' . $data['id_pendaftar'] . '" class="badge badge-warning">Detil</a>
					<a href="admin.php?fungsi=delete&id_peserta=' . $data['id_pendaftar'] . '" class="badge badge-danger" onclick="return confirm(\'Yakin ingin menghapus data ini?\')">Delete</a>
					</td>
					</tr>';
				$no++;
			}
			//jika query menghasilkan nilai 0
		} else {
			echo '
				<tr>
				<td colspan="6">Tidak ada data.</td>
				</tr>
				';
		}
		echo '
			<tbody>
			</table> 
			</div>';
	}

	function update($koneksi)
	{

		if (isset($_POST['btn_simpan'])) {
			$id_peserta = $_POST['id_peserta'];
			$status = $_POST['status'];

			if (
				!empty($status) 
			) {
				$sql = "UPDATE pendaftaran SET `status`='$status' WHERE id_pendaftar='$id_peserta'";
				// // echo $sql;
				// die("error= ".$koneksi->error);
				$update = mysqli_query($koneksi, $sql);
				if ($update && isset($_GET['fungsi'])) {
					if ($_GET['fungsi'] == 'update') {
						// header('location:admin.php?fungsi=update_success');
                        echo '<script>window.location.replace("admin.php?fungsi=update_success")</script>';
					}
				}
			} else {
				$pesan = "Tidak dapat menyimpan, data belum lengkap!";
			}
		} else {
			$id_peserta = $_GET['id_peserta'];
			$sql_peserta = "SELECT * FROM pendaftaran WHERE id_pendaftar=" . $id_peserta;
			$query_peserta = mysqli_query($koneksi, $sql_peserta);
			$data_peserta = mysqli_fetch_assoc($query_peserta);
		}
	?>
		<div class="container" style="margin-top:20px">
			<h2>Detail Data Pendaftar</h2>
			<form action="admin.php?fungsi=update" method="post">
				<div class="form-group row">
					<label class="col-sm-2 col-form-label">Nama</label>
					<div class="col-sm-10">
						<input type="text" name="nama_peserta" class="form-control-plaintext" size="4" value="<?php echo $data_peserta['nama_peserta']; ?>" readonly>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-2 col-form-label">Alamat</label>
					<div class="col-sm-10">
						<textarea name="alamat" class="form-control-plaintext" readonly><?php echo
																				$data_peserta['alamat']; ?></textarea>
					</div>
				</div>
				<div class="form-group row">
                        <label class="col-sm-2 col-form-label">Tanggal Lahir</label>
                        <div class="col-sm-10">
                            <input type="date" name="tanggalLahir" class="form-control-plaintext" size="4" value="<?= $data_peserta['tanggalLahir'] ?>" readonly>
                        </div>
                </div>
				<div class="form-group row">
					<label class="col-sm-2 col-form-label">Jenis Kelamin</label>
					<div class="col-sm-10">
						<div class="form-check">
							<input type="radio" class="form-check-input" name="jenis_kelamin" value="L" <?php
																												if ($data_peserta['jenis_kelamin'] == 'L') {
																													echo 'checked';
																												}	?> disabled>
							<label class="form-check-label">Laki-laki</label>
						</div>
						<div class="form-check">
							<input type="radio" class="form-check-input" name="jenis_kelamin" value="P" <?php
																												if ($data_peserta['jenis_kelamin'] == 'P') {
																													echo 'checked';
																												}	?> disabled>
							<label class="form-check-label">Perempuan</label>
						</div>
					</div>
				</div>

				<div class="form-group row">
					<label class="col-sm-2 col-form-label">Agama</label>
					<div class="col-sm-10">
						<select name="agama" class="form-control-plaintext" disabled>
							<option value="">Pilih salah satu</option>
							<option value="Islam" <?php if (
														$data_peserta['agama'] ==
														'Islam'
													) {
														echo 'selected';
													} ?>>Islam</option>
							<option value="Kristen Protestan" <?php
																if ($data_peserta['agama'] == 'Kristen Protestan') {
																	echo
																	'selected';
																} ?>>Kristen Protestan</option>
							<option value="Katolik" <?php if (
														$data_peserta['agama'] ==
														'Katolik'
													) {
														echo 'selected';
													} ?>>Katolik</option>
							<option value="Hindu" <?php if (
														$data_peserta['agama'] ==
														'Hindu'
													) {
														echo 'selected';
													} ?>>Hindu</option>
							<option value="Budha" <?php if (
														$data_peserta['agama'] ==
														'Budha'
													) {
														echo 'selected';
													} ?>>Budha</option>
							<option value="Kepercayaan lainnya" <?php
																if ($data_peserta['agama'] == 'Kepercayaan lainnya') {
																	echo
																	'selected';
																} ?>>Kepercayaan lainnya</option>
						</select>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-2 col-form-label">Sekolah Asal</label>

					<div class="col-sm-10">
						<input type="text" name="sekolah_asal" class="form-control-plaintext" value="<?php echo $data_peserta['sekolah_asal']; ?>" readonly>
					</div>
				</div>
                <div class="form-group row">
					<label class="col-sm-2 col-form-label">Status Pendaftaran</label>
					<div class="col-sm-10">
						<div class="form-check">
							<input type="radio" class="form-check-input" name="status" value="menunggu" <?php
																												if ($data_peserta['status'] == 'menunggu') {
																													echo 'checked';
																												}	?> required>
							<label class="form-check-label">Menunggu konfirmasi</label>
						</div>
                        <div class="form-check">
							<input type="radio" class="form-check-input" name="status" value="diterima" <?php
																												if ($data_peserta['status'] == 'diterima') {
																													echo 'checked';
																												}	?> required>
							<label class="form-check-label text-success"><b>Diterima</b></label>
						</div>
                        <div class="form-check">
							<input type="radio" class="form-check-input" name="status" value="cadangan" <?php
																												if ($data_peserta['status'] == 'cadangan') {
																													echo 'checked';
																												}	?> required>
							<label class="form-check-label text-warning"><b>Cadangan</b></label>
						</div>
                        <div class="form-check">
							<input type="radio" class="form-check-input" name="status" value="ditolak" <?php
																												if ($data_peserta['status'] == 'ditolak') {
																													echo 'checked';
																												}	?> required>
							<label class="form-check-label text-danger"><b>Ditolak</b></label>
						</div>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-2 col-form-label">&nbsp;</label>
					<div class="col-sm-10">
						<input type="hidden" name="id_peserta" value="<?php echo
																		$id_peserta; ?>">
						<input type="submit" name="btn_simpan" class="btn btn-primary" value="Update Status">
						<a href="admin.php" class="btn btn-success" role="button">Kembali</a>
					</div>
				</div>
			</form>

		</div>
	<?php
	}

	function update_success()
	{
		echo '
			<div class="container" style="margin-top:20px">
			<h3>Data Calon Siswa</h3>
			<hr>
			<p> Update Data Pendaftar Berhasil </p>
			</div>';
	}

    function listAkun($koneksi)
	{
		echo '	
			<div class="container" style="margin-top:20px">	
			<h2>List Akun</h2>	
				
			<hr>	
				
			<table class="table table-striped table-hover	table-sm table-bordered">
			<thead class="thead-dark">	
			<tr>	
			<th>No</th>	
			<th>username</th>	
			<th>email</th>	
			<th>no HP</th>	
			<th>id Pendaftaran</th>	
            <th>Status</th>	
			<th>Tindakan</th>	
			</tr>	
			</thead>	
			<tbody>';

		//query ke database SELECT tabel siswa urut berdasarkan id yang paling besar
		$sql = "SELECT user.id_user AS idUser,username,email,no_hp,id_pendaftar,`status` FROM user LEFT JOIN pendaftaran ON pendaftaran.id_user = user.id_user";
		$query = mysqli_query($koneksi, $sql);

		//jika query diatas menghasilkan nilai > 0 maka menjalankan script di bawah if...
		if (mysqli_num_rows($query) > 0) {
			$no = 1;
			//melakukan perulangan while dengan dari dari query $sql
			while ($data = mysqli_fetch_assoc($query)) {
				//menampilkan data perulangan
                
				echo '
					<tr>
					<td>' . $no . '</td>
					<td>' . $data['username'] . '</td>
					<td>' . $data['email'] . '</td>
					<td>' . $data['no_hp'] . '</td>
					<td>' . $data['id_pendaftar'] . '</td>
                    <td>' . $data['status'] . '</td>
					<td>
					<a href="admin.php?fungsi=profil&id_peserta=' . $data['idUser'] . '" class="badge badge-warning">Detil</a>
					<a href="admin.php?fungsi=deleteAkun&id_peserta=' . $data['idUser'] . '" class="badge badge-danger" onclick="return confirm(\'Yakin ingin menghapus SERURUH data User ini?\')">Delete</a>
					</td>
					</tr>';
				$no++;
			}
			//jika query menghasilkan nilai 0
		} else {
			echo '
				<tr>
				<td colspan="6">Tidak ada data.</td>
				</tr>
				';
		}
		echo '
			<tbody>
			</table> 
			</div>';
	}

    function profil($koneksi)
	{
		echo '
            <div class="container" style="margin-top:20px">
            <h3>Profil User</h3> 
                <hr>

                <p> Adapun informasi akun adalah sebagai berikut :  </p> 
                ';

        $id_user = $_GET['id_peserta'];

		//query ke database SELECT tabel pendaftaran
		$sql = "SELECT * FROM user LEFT JOIN pendaftaran ON pendaftaran.id_user = user.id_user WHERE user.id_user = $id_user";
		$query = mysqli_query($koneksi, $sql);
        
        // die(var_dump($query));

		//jika query diatas menghasilkan nilai > 0 maka menjalankan script di bawah if...
		if (mysqli_num_rows($query) > 0) {
		
			//melakukan perulangan while dengan dari dari query $sql
			while ($data = mysqli_fetch_assoc($query)) {
				?>

                <div class="container" style="margin-top:20px">
                    <h4>Detail Data Akun</h4>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Username</label>
                        <div class="col-sm-10">
                            <input type="text" name="username" class="form-control-plaintext" size="4" value="<?php echo $data['username']; ?>" readonly>
                        </div>
                    </div>
                
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                        <input type="text" name="email" class="form-control-plaintext" size="4" value="<?php echo $data['email']; ?>" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">No Telpon</label>
                        <div class="col-sm-10">
                        <input type="text" name="nohp" class="form-control-plaintext" size="4" value="<?php echo $data['no_hp']; ?>" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">ID Pendaftaran</label>
                        <div class="col-sm-10">
                        <input type="text" name="nama_peserta" class="form-control-plaintext" size="4" value="<?= !empty($data['id_pendaftar'])?$data['id_pendaftar']:'-'; ?>" readonly>
                        </div>
                    </div>

                </div>

                <?php

                    // if ($data['id_user']==$id_user) {
                    //     echo '<p>
                    //     <a href="index.php?fungsi=update&id_peserta=' . $data['id_pendaftar'] . '" class="badge badge-warning">Edit</a>
                    //     </p>';
                    // }
				
			}
			//jika query menghasilkan nilai 0
		} else {
			echo '
				<p>
				Anda Belum Mendaftar. <a href="index.php?fungsi=create" class="badge badge-success">Daftar Sekarang</a>
				</p>
				';
		}
		echo '
			</div>';
	}

	function delete($koneksi)
	{
		if (isset($_GET['id_peserta']) && isset($_GET['fungsi'])) {
			$id_peserta = $_GET['id_peserta'];
			$sql_hapus = "DELETE FROM pendaftaran WHERE id_pendaftar=" . $id_peserta;
			$hapus = mysqli_query($koneksi, $sql_hapus);
			if ($hapus) {
				if ($_GET['fungsi'] == 'delete') {
					// header('location:admin.php?fungsi=delete_success');
                    echo '<script>window.location.replace("admin.php?fungsi=delete_success")</script>';
				}
			}
		}
	}

    function deleteAkun($koneksi)
	{
		if (isset($_GET['id_peserta']) && isset($_GET['fungsi'])) {
			$id_peserta = $_GET['id_peserta'];
			$sql_hapus = "DELETE FROM pendaftaran WHERE id_user=" . $id_peserta;
            // die($sql_hapus);
			$hapus = mysqli_query($koneksi, $sql_hapus);
            $sql_hapus = "DELETE FROM user WHERE id_user=" . $id_peserta;
			$hapus = mysqli_query($koneksi, $sql_hapus);
			if ($hapus) {
				if ($_GET['fungsi'] == 'delete') {
					// header('location:admin.php?fungsi=delete_success');
                    echo '<script>window.location.replace("admin.php?fungsi=delete_success")</script>';
				}
			}
		}
	}

	function delete_success()
	{
		echo '
			<div class="container" style="margin-top:20px">
			<h3>Data Calon Siswa</h3> 
			<hr>
			<p> Delete Data Pendaftar Berhasil </p>
			</div>';
	}
	?>


	<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="assets/js/bootstrap.min.js"></script>
</body>

</html>
