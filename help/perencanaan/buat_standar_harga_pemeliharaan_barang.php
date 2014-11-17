<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<?php
include "../../config/config.php";
?>
<html>
    <head>
        <title>Help SIMBADA</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                <title><?=$title?></title>
                <!-- include css file -->
                
                <link rel="stylesheet" href="../../css/simbada.css" type="text/css" media="screen" />
                <link rel="stylesheet" href="../../css/jquery-ui.css" type="text/css">
                <link rel="stylesheet" href="../../css/example.css" TYPE="text/css" MEDIA="screen">
    </head>
    <body>
        <div>
            <div id="frame_header">
                <div id="header"></div>
            </div>
            <div id="list_header"></div>
            <div id="kiri">
            <?php include '../menu_samping.php';?>
        </div>
        
        <div id="tengah1">	
            <div id="frame_tengah1">
                <div id="frame_gudang">
                    <div id='topright'><label>Help &raquo;</label><label> Perencanaan &raquo; Buat Standar Harga Pemeliharaan Barang <label></div>;
                        <div id='bottomright' style='border:0px'>
							<p>Sub menu Buat Standar Harga Pemeliharaan Barang memiliki 2 alur pengoperasian sebagai berikut :</p><br>
								<img src = "alur_shb.png"><br><br>
							<p>Langkah-langkah pengoperasian Sub menu Buat Standar Harga Pemeliharaan Barang adalah sebagai berikut:</p><br>
								<ol style="Padding-left:20px;">
									<li>Arahkan pointer mouse ke Sub menu Buat Standar Harga Pemeliharaan Barang.</li><br>
									<li>Klik sub menu Buat Standar Harga Pemeliharaan Barang</li><br>
										<img src = "shpb1.png"><br><br>
									<li>Untuk melakukan pencarian pada sub menu Buat Standar Harga Pemeliharaan Barang, pengguna dapat mengisi salah satu atau semua field pada seleksi pencarian, seperti contoh berikut:</li><br>
										<img src = "shpb2.png"><br><br>
									<li>Setelah dilakukan seleksi pada form seleksi pencarian, jika tombol Lanjut diklik dan akan muncul notifikasi lalu klik OK, maka akan muncul halaman hasil seleksi pencarian, seperti di bawah ini:</li>
										<img src = "shpb3.png"><br><br>
									<li>Untuk membuat data Standar Harga Pemeliharaan Barang baru, dapat dilakukan dengan dua cara yaitu dengan Tambah Data:Import dan Tambah Data: Manual</li><br>
									<li>Tambah Data : Import, digunakan untuk menambah data denga cara mengimport file yang berekstensi .XLS.</li>
										<img src = "shb4.png"><br><br>
										<p>Tombol Browse untuk memilih file yang akan di import, jika sudah terpilih file yang diinginkan klik Import untuk melakukan proses import.</p><br>
									<li>Tambah Data : Manual, digunakan untuk menambah data secara manual, caranya yaitu dengan mengklik tombol Tambah Data: Manual kemudian akan tampil halaman seperti dibawah ini:</li>
										<img src = "shpb4.png"><br><br>
										<p>Halaman diatas menampilkan Daftar Standar Harga Barang yang sebelumnya telah diinput pada sub menu Buat Standar Harga Barang, untuk menentukan Harga Pemeliharaan dari salah satu data Standar Harga Barang klik tombol Tentukan Pemeliharaan, kemudian akan tampil halaman seperti dibawah ini:</p><br>
										<img src = "shpb5.png"><br><br>
										<p>Untuk memberikan besarnya dana yang akan digunakan untuk pemeliharaan barang tersebut masukan nilai pada kolom Harga Pemeliharaan, kemudian klik tombol Pelihara untuk melakukan penyimpanan data.</p>
								</ol><br>
								<table width="100%">
									<tr>
										<td> <a href="buat_standar_harga_barang.php" style="color:#0000ff;font-size:20px">Back</a></td>
										<td style="text-align:center"> <a href="../perencanaan/" style="color:#0000ff;font-size:20px">Toc</a></td>
										<td style="text-align:right"> <a href="buat_standar_kebutuhan_barang.php" style="color:#0000ff;font-size:20px">Next</a></td>
									</tr>
								</table>
						</div>
				</div>
            </div>
        </div>
        </div>
</div>
</div>
            <div id="footer">Sistem Informasi Barang Daerah ver. 0.x.x <br />
            Powered by BBSDM Team 2012
            </div>
        </div>
    </body>
</html>
