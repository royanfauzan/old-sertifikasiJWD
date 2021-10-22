<?php
session_start();
require('assets/library/fpdf183/fpdf.php');
include 'core/koneksi.php';


// Data Dari Form
$role = $_SESSION['role'];

if ($role=='admin') {

        //Fungsi pemotong nama
    function potongNama(string $teksNama){
        $panjangteks = strlen($teksNama);
        if ($panjangteks<20) {
            return $teksNama;
        } else {
            $posisiSpasi = strrpos($teksNama, ' ');
            $teksbuff = substr($teksNama, 0, $posisiSpasi);
            return potongNama($teksbuff);
        }
    }



    //dataDasar
    $tanggalCetak = date("d-m-Y");
    $penghitungPersen = 0;
    $pencetak = 'Admin';

    // Data Laporan
    $queryDaftar = "SELECT COUNT(`status`) AS daftarAll,COUNT(CASE `status` WHEN 'menunggu' THEN 1 ELSE NULL END) AS daftarWait,COUNT(CASE `status` WHEN 'ditolak' THEN 1 ELSE NULL END) AS daftarPerbaikan,COUNT(CASE `status` WHEN 'cadangan' THEN 1 ELSE NULL END) AS daftarProses,COUNT(CASE `status` WHEN 'diterima' THEN 1 ELSE NULL END) AS daftarSelesai FROM pendaftaran";
    $dataDaftar = mysqli_query($koneksi,$queryDaftar);
    $detilDaftar = mysqli_fetch_assoc($dataDaftar);
    $totalDaftar = intval($detilDaftar['daftarAll']);
    $totalDaftarRumus = 1;

    if ($totalDaftar>1) {
        $totalDaftarRumus = $totalDaftar;
    }


    // Data Lampiran
    $queryLmpiran = "SELECT * FROM pendaftaran";
    $dataLampiran = mysqli_query($koneksi,$queryLmpiran);
    $jumlahDataJudul = mysqli_num_rows($dataLampiran);
    $kolom_status_Arr = array("Ditolak", "Menunggu", "Proses", "Selesai");

    $pdf = new FPDF('P','mm','A4');

    $pdf->AddPage();

    $pdf->SetFont('Arial','B',14);

    $pdf->Cell(130,10,'LAPORAN PENDAFTARAN SISWA',0,0);
    $pdf->Cell(59,10,'',0,1);

    $pdf->SetFont('Arial','I',12);

    $pdf->Cell(130,5,$pencetak,0,1);

    $pdf->SetFont('Arial','',12);
    $pdf->Cell(130,5,"",0,0);
    $pdf->Cell(59,5,'Per : '.$tanggalCetak,1,1);

    $pdf->Cell(130,5,'',0,1);
    $pdf->Cell(130,20,'',0,1);
    $pdf->Cell(130,10,'',0,1);

    $pdf->SetFont('Arial','B',13);

    $pdf->Cell(130,10,'Overview Data Pendaftaran Siswa',0,1);

    $pdf->SetFont('Arial','B',12);

    $pdf->Cell(130,6,'Kategori',1,0,'C');
    $pdf->Cell(59,6,'Jumlah',1,1,'C');

    $pdf->SetFont('Arial','',12);

    $pdf->Cell(130,6,'Jumlah Pendaftar',1,0);
    $pdf->Cell(59,6,$totalDaftar,1,1,'R');

    $pdf->Cell(130,6,'Menunggu Konfirmasi',1,0);
    $pdf->Cell(59,6,$detilDaftar['daftarWait'],1,1,'R');

    $pdf->Cell(130,6,'Ditolak',1,0);
    $pdf->Cell(59,6,$detilDaftar['daftarPerbaikan'],1,1,'R');

    $pdf->Cell(130,6,'Cadangan',1,0);
    $pdf->Cell(59,6,$detilDaftar['daftarProses'],1,1,'R');

    $pdf->Cell(130,6,'Diterima',1,0);
    $pdf->Cell(59,6,$detilDaftar['daftarSelesai'],1,1,'R');

    $penghitungPersen = round((intval($detilDaftar['daftarSelesai'])/$totalDaftarRumus)*100,2);

    $pdf->SetFont('Arial','B',12);

    $pdf->Cell(60,5,'',0,0);
    $pdf->Cell(70,7,'Persentase Penerimaan',1,0);
    $pdf->Cell(59,7,strval($penghitungPersen).'%',1,1,'R');

    $pdf->SetFont('Arial','I',12);
    $pdf->Cell(130,5,'',0,1);
    $pdf->Cell(130,5,'',0,1);
    $pdf->Cell(130,5,'* Terlampir data Judul Terdaftar',0,1);


    //Lampiran

    $pdf->AddPage();

    $pdf->SetFont('Arial','B',12);

    $pdf->Cell(130,10,'Lampiran Data Judul Terdaftar',0,1);

    $pdf->SetFont('Arial','B',10);

    $pdf->Cell(30,5,'ID Pendaftar',1,0,'C');
    $pdf->Cell(39,5,'Nama Pendaftar',1,0,'C');
    $pdf->Cell(100,5,'Alamat',1,0,'C');
    $pdf->Cell(20,5,'Status',1,1,'C');

    $pdf->SetFont('Arial','',10);

    $perulangan = 0;

    while ($perinci = mysqli_fetch_array($dataLampiran)) {
        if ($perulangan<50) {
            $panjangKata = strlen($perinci['alamat']);

            if ($panjangKata>54) {
                $tinggi = ceil($panjangKata/54)*5;

                $pdf->Cell(30,$tinggi,$perinci['id_pendaftar'],1,0);
                $pdf->Cell(39,$tinggi,potongNama($perinci['nama_peserta']),1,0);

                $xPos=$pdf->GetX();
                $yPos=$pdf->GetY();

                $pdf->MultiCell(100,5,$perinci['alamat'],1,'L');
                $pdf->SetXY(($xPos+100),$yPos);
                $pdf->Cell(20,$tinggi,$perinci['status'],1,1,'C');
            } else {
                $pdf->Cell(30,5,$perinci['id_pendaftar'],1,0);
                $pdf->Cell(39,5,potongNama($perinci['nama_peserta']),1,0);
                $pdf->Cell(100,5,$perinci['alamat'],1,0);
                $pdf->Cell(20,5,$perinci['status'],1,1,'C');
            }
            
            $perulangan++;
        }else {
            $pdf->AddPage();
            $pdf->SetFont('Arial','B',10);

            $pdf->Cell(30,5,'ID Pendaftaran',1,0,'C');
            $pdf->Cell(39,5,'Nama Pendaftar',1,0,'C');
            $pdf->Cell(100,5,'Alamat',1,0,'C');
            $pdf->Cell(20,5,'Status',1,1,'C');

            $pdf->SetFont('Arial','',10);

            $perulangan = 0;
        }
    }


    $pdf->Output();




} else {
    echo "<script>alert('User Anda tidak dapat mengakses Fitur Laporan');</script>";
    header('location:login.php');

}

