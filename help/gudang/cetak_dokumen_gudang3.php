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
                    <div id='topright'><label>Help &raquo;</label><label>Gudang &raquo; Distribusi Barang <label></div>;
                        <div id='bottomright' style='border:0px'>
						
						<ol type="a" start="4" style="padding-left:20px;">
						
							<li>Persediaan PH, alur pengoperasiannya adalah sebagai berikut :</li><br>
							
							<center><img src ="g37.png"></center><br><br>
							
							<img src ="g23.png"><br><br>
								
								<ul type="disc" style="padding-left:20px;">
								
									<li>Klik Tab Persediaan PH untuk mencetak Persediaan PH</li><br>
								
									<li>Pilih Tahun yang diinginkan dari menu drop down.</li><br>
									
									<li>Pilih SKPD untuk memilih SKPD atau unit SKPD.</li><br>
									
									<li>Pilih Jenis Barang untuk memilih jenis barang dari table kelompok barang.</li><br>
									
									<li>Isi Tanggal Cetak Report untuk memberikan informasi kapan Persediaan PH itu dicetak.</li><br>
									
								</ul>
								
						</ol>
						
						<ol type="a" start="5" style="padding-left:20px;">
							
							<li>Penerimaan I, alur pengoperasiannya adalah sebagai berikut :</li><br>
							
							<center><img src ="g38.png"></center><br><br>
							
							<img src="g24.png"><br><br>
							
								<ul type="disc" style="padding-left:20px;">
								
									<li>Klik Tab Penerimaan I untuk mencetak Penerimaan I</li><br>
									
									<li>Pilih SKPD untuk memilih SKPD atau unit SKPD.</li><br>
									
									<li>Pilih Jenis Barang untuk memilih jenis barang dari table kelompok barang.</li><br>
									
									<li>Isi Tanggal Awal dan Tanggal Akhir untuk mencetak diantara tanggal awal dan tanggal akhir.</li><br>
									
									<li>Isi Tanggal Cetak Report untuk memberikan informasi kapan Penerimaan I itu dicetak.</li><br>
								</ul>
						</ol>
				  <table width="100%">
					<tr>
						<td width="30%"> <a href="cetak_dokumen_gudang2.php" style="color:#0000ff;font-size:20px">Prev</a></td>
						<td style="text-align:center"> <a href="index.php" style="color:#0000ff;font-size:20px">TOC</a></td>
						<td style="text-align:right"> <a href="cetak_dokumen_gudang4.php" style="color:#0000ff;font-size:20px">Next</a></td>
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
