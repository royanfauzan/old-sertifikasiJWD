<?php

require 'core/koneksi.php';
$error = false;
if (isset($_POST["daftar"])) {

	$username = $_POST["username"];
	$password = $_POST["password"];
    $email = $_POST["email"];
	$nohp = $_POST["nohp"];
    $role = 'user';

	$data = mysqli_query($koneksi, "select * from user where username='$username'");
	$cek = mysqli_num_rows($data);
    // print_r($data);
    // die("test");

	if ($cek < 1) {
        $sql = "INSERT INTO `user` (`username`, `password`, email, no_hp, `role`) VALUES('" . $username . "','" . $password . "','" . $email . "','" . $nohp . "','" . $role ."')";
        // echo $sql;
        // die($sql);
        $simpan = mysqli_query($koneksi, $sql);
        if ($simpan) {
            echo '<script>
            window.alert("Registrasi berhasil, silahkan login");
            window.location.replace("login.php");</script>';
        }
	}
	$error = true;
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
	<div class="container">
		<div class="row justify-content-center mt-5">
			<div class="col-md-4">
				<div class="card">
					<div class="card-header bg-transparent mb-0">
						<h5 class="text-center">Form <span class="font-weight-bold text-primary">REGISTRASI</span>
						</h5>
					</div>
					<div class="card-body">
						<?php
						if ($error == true)
							echo "<div class='alert alert-warning'> Username sudah ada! </div>";
						?>
						<form action="" method="post">
							<div class="form-group">
								<input type="text" name="username" class="form-control" placeholder="username">
							</div>

							<div class="form-group">
								<input type="password" name="password" class="form-control" placeholder="password">
							</div>

                            <div class="form-group">
								<input type="email" name="email" class="form-control" placeholder="email">
							</div>

                            <div class="form-group">
								<input type="number" name="nohp" class="form-control" placeholder="nomor HP">
							</div>

							<div class="form-group">
								<input type="submit" name="daftar" value="DAFTAR" class="btn btn-primary btn-block">
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>

<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>

</html>