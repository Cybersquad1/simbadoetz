<?php

include "../config/config.php";


class MUTASI extends DB{

	var $db = "";
	public function __construct()
	{
		parent::__construct();

        $this->db = new DB;
	}

	function usulan($idpenggunaan, $nodok, $debug=false)
	{

		// get list aset

		$getPenggunaan = $this->getPenggunaan($idpenggunaan);

		$getKodeSatker = $this->getGUID($getPenggunaan);

		// pr($getKodeSatker);
		// exit;
		$jenisaset = $data['jenisaset'];

        $olah_tgl= date('Y-m-d H:i:s');
        $alasan="Mutasi [importing]";
        $pemakai="dinas pendidikan";
        $kodeKelompok = $data['kodeKelompok'];

        

        $UserNm=$_SESSION['ses_uoperatorid'];// usernm akan diganti jika session di implementasikan
        
        // $nmaset=$getPenggunaan;
        
        $asset_id=Array();
        $no_reg=Array();
        $nm_barang=Array();
        // $asetKapitalisasi = array_keys($_POST['asetKapitalisasi']);
        // $asetKapitalisasiOri = $_POST['asetKapitalisasi'];

        $mutasi_id=get_auto_increment("Mutasi");
        
        logFile('Start transaksi mutasi');
        // pr($jenisaset);

        $listTable = array(
                'A'=>'tanah',
                'B'=>'mesin',
                'C'=>'bangunan',
                'D'=>'jaringan',
                'E'=>'asetlain',
                'F'=>'kdp');

        foreach ($getKodeSatker['satker'] as $satker => $value) {
       		
       		$sql = array(
	                'table'=>"Mutasi",
	                'field'=>"NoSKKDH , TglSKKDH, Keterangan, SatkerTujuan, NotUse, TglUpdate, UserNm, FixMutasi, Pemakai",
	                'value'=>"'$nodok','$olah_tgl', '$alasan','$satker',0,'$olah_tgl','$UserNm','0','$pemakai'",
	                );

	        $res = $this->db->lazyQuery($sql,$debug,1);
	        $mutasiIDReturn = $this->db->insert_id();

	        $nmaset = $value;
	        $panjang=count($nmaset);

	        $sleep = 1;
	        for($i=0;$i<$panjang;$i++){
            
	            $getJenisAset = $this->getJenisAset($nmaset);

	            $getKIB = $this->getTableKibAlias($getJenisAset[$i]);

	            $asset_id = $nmaset[$i];

	            $satkerAwal =$getKodeSatker['aset'][$asset_id]['kodeSatker'];
		        $kelompokAwal =$getKodeSatker['aset'][$asset_id]['kodeKelompok'];
		        $lokasiAwal =$getKodeSatker['aset'][$asset_id]['kodeLokasi'];
		        $registerAwal =$getKodeSatker['aset'][$asset_id]['noRegister'];
		        $namaSatkerAwal =$getKodeSatker['aset'][$asset_id]['NamaSatker'];

	           	$lokasiBaru = ubahLokasi($lokasiAwal,$satker);
	            // pr($lokasiBaru);
	            // exit;
	            $sqlSelect = array(
	                    'table'=>"Aset",
	                    'field'=>"MAX(noRegister) AS noRegister",
	                    'condition'=>"kodeKelompok = '{$kelompokAwal}' AND kodeSatker = '{$satkerAwal}' AND kodeLokasi = '{$lokasiAwal}'",
	                    );

	            $result = $this->db->lazyQuery($sqlSelect,$debug);
	            
	            $gabung_nomor_reg_tujuan=intval(($result[0]['noRegister'])+1);

	            // if (!in_array($asset_id, $asetKapitalisasi)){
	                $sql1 = array(
	                        'table'=>"MutasiAset",
	                        'field'=>"Mutasi_ID,Aset_ID,NamaSatkerAwal, NomorRegAwal,NomorRegBaru,SatkerAwal,SatkerTujuan",
	                        'value'=>"'$mutasi_id','$asset_id','$namaSatkerAwal','$registerAwal','$gabung_nomor_reg_tujuan','$satkerAwal','$satker'",
	                        );

	                $res1 = $this->db->lazyQuery($sql1,$debug,1);
	                if (!$res1){
	                    logFile('rollback 3');
	                    
	                    return false;   
	                }   
	            // }else{

	            //      $sql1 = array(
	            //             'table'=>"MutasiAset",
	            //             'field'=>"Mutasi_ID,Aset_ID,NamaSatkerAwal, NomorRegAwal,NomorRegBaru,SatkerAwal,SatkerTujuan, Aset_ID_Tujuan",
	            //             'value'=>"'$mutasi_id','$asset_id','$namaSatkerAwal','$registerAwal','$gabung_nomor_reg_tujuan','$satkerAwal','$satker', {$asetKapitalisasiOri[$asset_id]}",
	            //             );

	            //     $res1 = $this->db->lazyQuery($sql1,$debug,1);
	            //     if (!$res1){
	            //         logFile('rollback 4');
	                    
	            //         return false;   
	            //     }
	            // }

	            $sql2 = array(
	                    'table'=>"Aset",
	                    'field'=>"StatusValidasi = 3, Status_Validasi_Barang = 3",
	                    'condition'=>"Aset_ID='$asset_id'",
	                    );

	            $res2 = $this->db->lazyQuery($sql2,$debug,2); 
	            if (!$res2){
	                logFile('rollback 5');
	                
	                return false;   
	            }
	            // pr($getKIB);
	            $sqlKib = array(
	                    'table'=>"{$getKIB['listTableOri']}",
	                    'field'=>"StatusValidasi = 3, Status_Validasi_Barang = 3, StatusTampil = 3",
	                    'condition'=>"Aset_ID='$asset_id'",
	                    );

	            $resKib = $this->db->lazyQuery($sqlKib,$debug,2);
	            if (!$resKib){
	                logFile('rollback 5');
	                
	                return false;   
	            }

	            $sql3 = array(
	                    'table'=>"PenggunaanAset",
	                    'field'=>"StatusMutasi=1, Mutasi_ID='$mutasi_id'",
	                    'condition'=>"Aset_ID='$asset_id'",
	                    );

	            $res3 = $this->db->lazyQuery($sql3,$debug,2);
	            if (!$res3){
	                logFile('rollback 6');
	                
	                return false;   
	            }
	            
	            $sql = array(
	                    'table'=>'aset',
	                    'field'=>"TipeAset",
	                    'condition' => "Aset_ID={$asset_id}",
	                    );
	            $result = $this->db->lazyQuery($sql,$debug);
	            $asetid[$asset_id] = $listTable[implode(',', $result[0])];
	        	
	        	$sleep++;
	        	if ($sleep == 200){
	        		sleep(1);
	        		$sleep = 1;
	        	}

	        	$returnAsetID[] = $value[$i];
	        }
       	}
        
        

        if ($result){
            
            $noDokumen = "Mutasi [importing]";

            $sleep = 1;
            foreach ($asetid as $key => $value) {
                // if (!in_array($key, $asetKapitalisasi)){
                    $this->db->logIt($tabel=array($value), $Aset_ID=$key, $kd_riwayat=3, $noDokumen=$nodok, $tglProses =$olah_tgl, $text="Usulan Mutasi");
                // }else{
                //     $this->db->logIt($tabel=array($value), $Aset_ID=$key, $kd_riwayat=28, $noDokumen=$nodok, $tglProses =$olah_tgl, $text="Usulan Mutasi dengan mode kapitalisasi", $tmpSatker=$asetKapitalisasiOri[$key]);
                // }
                
                $sleep++;
                if ($sleep == 200){
                	sleep(1);
                	$sleep = 1;
                }
            }

            logFile('commit transaksi mutasi');
           
            return array('asetid'=>$returnAsetID, 'mutasiid'=>$mutasiIDReturn);
        } 

        logFile('Rollback transaksi mutasi');
        
        return false;
	}


	function getJenisAset($data, $debug=false)
    {
        // pr($data);

        
        foreach ($data as $key => $value) {

            
            $sqlSelect = array(
                    'table'=>"Aset",
                    'field'=>"TipeAset",
                    'condition'=>"Aset_ID = '{$value}'",
                    );

            $result[] = $this->db->lazyQuery($sqlSelect,$debug);
        }
        
        // pr($result);

        $revert = array('A'=>1, 'B'=>2, 'C'=>3, 'D'=>4, 'E'=>5, 'F'=>6);
        if ($result){
            foreach ($result as $key => $value) {
                $dataType[] = $revert[$value[0]['TipeAset']];
            }
        }
        // pr($dataType);

        return $dataType;

    }

	
	function getPenggunaan($penggunaanID)
	{
		$sql = array(
                'table'=>"penggunaanaset",
                'field'=>"Aset_ID",
                'condition' => "Penggunaan_ID = {$penggunaanID}",
                );

        $res = $this->db->lazyQuery($sql,$debug);
        if ($res){
        	foreach ($res as $key => $value) {
        		$newData[] = $value['Aset_ID'];
        	}
        	return $newData;	
        } 
        return false;
	}

	

	function validasi($data, $debug=false)
	{


        $jenisaset = $this->getJenisAset($data['aset_id']);

        // pr($data);
        if ($jenisaset){
            foreach ($jenisaset as $key => $value) {
            
                $table = $this->getTableKibAlias($value);

                // cek dulu jika kapitalisasi atau aset baru
                $sql = array(
                        'table'=>"mutasiaset",
                        'field'=>"Aset_ID_Tujuan",
                        'condition'=>"Status = 0 AND Mutasi_ID = {$data[Mutasi_ID]} AND Aset_ID = {$data['aset_id'][$key]}",
                        );

                $res = $this->db->lazyQuery($sql,$debug);

                // ambil data aset asal
                $sql = array(
                        'table'=>"{$table['listTableOri']}",
                        'field'=>"*",
                        'condition'=>"Aset_ID={$data[aset_id][$key]}",
                        );

                $resultAwal = $this->db->lazyQuery($sql,$debug);


                $getLokasiTujuan = $this->get_satker_tujuan($data['Mutasi_ID'], $data['aset_id'][$key]);

                // pr($resultAwal);
                // pr($getLokasiTujuan);
                
                logFile(serialize($res));
                if ($res){

                    if ($res[0]['Aset_ID_Tujuan']>0){
                        // kapitalisasi data
                        
                        // $this->db->logIt(
                        //                 $tabel=array($table['listTableOri']), 
                        //                 $Aset_ID=$data['aset_id'][$key], 
                        //                 $kd_riwayat=28);
                        
                        // exit;
                        // ambil nilai perolehan aset tujuan
                        $sql = array(
                                'table'=>"{$table['listTableOri']}",
                                'field'=>"NilaiPerolehan",
                                'condition'=>"Aset_ID={$res[0]['Aset_ID_Tujuan']}",
                                );

                        $result2 = $this->db->lazyQuery($sql,$debug);

                        // echo '1';
                        $NilaiPerolehan = ($resultAwal[0]['NilaiPerolehan'] + $result2[0]['NilaiPerolehan']);

                        $this->db->logIt($tabel=array($table['listTableOri']), $Aset_ID=$res[0]['Aset_ID_Tujuan'], $kd_riwayat=28, $noDokumen=$nodok, $tglProses =$olah_tgl, $text="Aset Penambahan kapitalisasi Mutasi",0);

                        logFile('Nilai Perolehan awal : '.serialize($resultAwal));
                        logFile('Nilai Perolehan tujuan : '.serialize($result2));
                        logFile('Nilai Perolehan gabungan : '.$NilaiPerolehan);
                        if ($NilaiPerolehan > 0){
                            $sqlKib = array(
                                    'table'=>"{$table['listTableOri']}",
                                    'field'=>"NilaiPerolehan='{$NilaiPerolehan}'",
                                    'condition'=>"Aset_ID='{$res[0]['Aset_ID_Tujuan']}'",
                                    );

                            $resKib = $this->db->lazyQuery($sqlKib,$debug,2);
                            if (!$resKib) {$this->db->rollback(); return false;}
                            
                            $sql2 = array(
                                    'table'=>"Aset",
                                    'field'=>"NilaiPerolehan = '{$NilaiPerolehan}'",
                                    'condition'=>"Aset_ID='{$res[0]['Aset_ID_Tujuan']}'",
                                    );

                            $res2 = $this->db->lazyQuery($sql2,$debug,2); 
                            if (!$res2) {$this->db->rollback(); return false;}

                            $sql3 = array(
                                    'table'=>"Aset",
                                    'field'=>"StatusValidasi = 2, Status_Validasi_Barang = 2, NotUse = 2",
                                    'condition'=>"Aset_ID='{$data[aset_id][$key]}'",
                                    );

                            $res3 = $this->db->lazyQuery($sql3,$debug,2); 
                            if (!$res3) {$this->db->rollback(); return false;}

                            $nodok = $_POST['noDokumen'];
                            $olah_tgl =  $_POST['TglSKKDH'];
                            
                            $this->db->logIt($tabel=array($table['listTableOri']), $Aset_ID=$data['aset_id'][$key], $kd_riwayat=28, $noDokumen=$nodok, $tglProses =$olah_tgl, $text="Sukses kapitalisasi Mutasi",$res[0]['Aset_ID_Tujuan']);
                        
                        }else{

                            $this->db->rollback();
                            logFile('Nilai Perolehan kosong ketika kapitalisasi aset mutasi');
                            return false;
                        }
                        
                        

                    }else{
                        // ubah data baru


                        $tmpKodeLokasi = explode('.', $resultAwal[0]['kodeLokasi']);
                        $tmpKodeSatker = explode('.', $getLokasiTujuan[0]['SatkerTujuan']);

                        $prefix = $tmpKodeLokasi[0].'.'.$tmpKodeLokasi[1].'.'.$tmpKodeLokasi[2];
                        $prefixkodesatker = $tmpKodeSatker[0].'.'.$tmpKodeSatker[1];
                        $prefixTahun = substr($resultAwal[0]['Tahun'], 2,2);
                        $postfixkodeSatker = $tmpKodeSatker[2].'.'.$tmpKodeSatker[3];

                        $implLokasi = $prefix.'.'.$prefixkodesatker.'.'.$prefixTahun.'.'.$postfixkodeSatker;
                        // pr($implLokasi);

                        // $lokasiBaru = ubahLokasi("12.11.33",$getLokasiTujuan[0]['SatkerTujuan']);
                    
                        //buat gabung nomor registrasi akhir
                        // $array=array($pemilik,$provinsi,$kabupaten,$row_kode_satker,$tahun,$row_kode_unit);
                        
                        $sql = array(
                                'table'=>"{$table['listTableOri']}",
                                'field'=>"MAX(noRegister) AS noRegister",
                                'condition'=>"kodeKelompok = '{$resultAwal[0][kodeKelompok]}' AND kodeSatker = '{$getLokasiTujuan[0][SatkerTujuan]}' AND kodeLokasi = '{$implLokasi}'",
                                );
                        $result = $this->db->lazyQuery($sql,$debug);

                        // $sqlSelect = array(
                        //         'table'=>"Aset",
                        //         'field'=>"MAX(noRegister) AS noRegister",
                        //         'condition'=>"kodeKelompok = '{$resultAwal[0][kodeKelompok]}' AND kodeSatker = '{$resultAwal[0][kodeSatker]}' AND kodeLokasi = '{$resultAwal[0][kodeLokasi]}'",
                        //         );

                        // $result = $this->db->lazyQuery($sqlSelect,$debug);
                        // pr($result);

                        $gabung_nomor_reg_tujuan=intval(($result[0]['noRegister'])+1);

                        $sqlKib = array(
                                'table'=>"{$table['listTableOri']}",
                                'field'=>"kodeSatker='{$getLokasiTujuan[0]['SatkerTujuan']}', kodeLokasi = '{$implLokasi}', noRegister='$gabung_nomor_reg_tujuan', StatusValidasi = 1, Status_Validasi_Barang = 1, StatusTampil = 1",
                                'condition'=>"Aset_ID='{$data[aset_id][$key]}'",
                                );

                        $resKib = $this->db->lazyQuery($sqlKib,$debug,2);

                        $sql2 = array(
                                'table'=>"Aset",
                                'field'=>"kodeSatker='{$getLokasiTujuan[0][SatkerTujuan]}', kodeLokasi = '{$implLokasi}', noRegister='$gabung_nomor_reg_tujuan',StatusValidasi = 1, Status_Validasi_Barang = 1, NotUse = 0, fixPenggunaan = 0, statusPemanfaatan = 0",
                                'condition'=>"Aset_ID='{$data[aset_id][$key]}'",
                                );

                        $resAset = $this->db->lazyQuery($sql2,$debug,2); 

                        if ($resKib){
                            logFile('Data baru berhasil diubah kode satkernya di validasi mutasi');
                        }else{
                            logFile('Nilai Perolehan kosong ketika kapitalisasi aset mutasi');
                            $this->db->rollback();
                            return false;
                        }

                        if ($resAset){

                            $noDok = array('penggu_penet_eks_nopenet','mutasi_trans_eks_nodok');

                            $nodok = $_POST['noDokumen'];
                            $olah_tgl =  $_POST['TglSKKDH'];
                            
                            $this->db->logIt($tabel=array($table['listTableOri']), $Aset_ID=$data['aset_id'][$key], $kd_riwayat=3, $noDokumen=$nodok, $tglProses =$olah_tgl, $text="Sukses Mutasi");
                            
                             
                        }else{

                            $this->db->rollback();
                            logFile('gagal log data mutasi aset '.$data['aset_id'][$key]);
                        } 

                    }  
                }
                

                
                $sql = array(
                        'table'=>"mutasiaset",
                        'field'=>"Status=1",
                        'condition'=>"Aset_ID = '{$data[aset_id][$key]}' AND Mutasi_ID = '$data[Mutasi_ID]'",
                        );
                $res1 = $this->db->lazyQuery($sql,$debug,2); 

                
            
            } 
            

            $sql = array(
                    'table'=>"mutasiaset",
                    'field'=>"COUNT(*) AS total",
                    'condition'=>"Status = 0 AND Mutasi_ID = {$data[Mutasi_ID]}",
                    );

            $res = $this->db->lazyQuery($sql,$debug);
            
            if ($res[0]['total']==0){
                $sql = array(
                            'table'=>"Mutasi",
                            'field'=>"FixMutasi=1",
                            'condition'=>"Mutasi_ID = '{$data[Mutasi_ID]}'",
                            );
                $res1 = $this->db->lazyQuery($sql,$debug,2); 
            }
            
            logFile('Commit transaction mutasi');
            $this->db->commit();
            return true;   
        }
        
        $this->db->rollback();
        return false;

        
	}

	function get_satker_tujuan($mutasiid=1, $Aset_ID=false)
        {   


            $table = $this->getTableKibAlias($jenis);

            $sql = array(
                    'table'=>"mutasiaset",
                    'field'=>"*",
                    'condition'=>" Aset_ID = {$Aset_ID} AND Mutasi_ID = {$mutasiid} AND Status = 0",
                    );

            $res = $this->db->lazyQuery($sql,$debug);
            if ($res) return $res;
            return false;
        }

	function getGUID($aset)
	{	
		$listTable = array(
                        'A'=>'tanah',
                        'B'=>'mesin',
                        'C'=>'bangunan',
                        'D'=>'jaringan',
                        'E'=>'asetlain',
                        'F'=>'kdp');

		foreach ($listTable as $key => $value) {

			$asetidimplode = implode(',', $aset);
			$sql = array(
	            'table'=>"{$value}, satker AS s",
	            'field'=>"{$value}.kodeSatker, {$value}.GUID AS kodeSatkerTujuan, {$value}.Aset_ID, {$value}.kodeKelompok, {$value}.kodeLokasi, {$value}.noRegister, s.NamaSatker",
	            'condition' => "{$value}.Aset_ID IN ({$asetidimplode}) GROUP BY {$value}.Aset_ID",
	            'joinmethod' => 'LEFT JOIN',
                'join' => "{$value}.kodeSatker = s.kode"
                );
	        $res = $this->db->lazyQuery($sql,$debug);
	        if ($res){
	        	foreach ($res as $key => $value) {
	        		$newData['satker'][$value['kodeSatkerTujuan']][] = $value['Aset_ID'];
	        		$newData['aset'][$value['Aset_ID']] = $value;
	        	}

	        	
	        }

		}
		return $newData;
	}


	function getTableKibAlias($type=1)
    {
	    $listTableAlias = array(1=>'t',2=>'m',3=>'b',4=>'j',5=>'al',6=>'k');
	    $listTableAbjad = array(1=>'A',2=>'B',3=>'C',4=>'D',5=>'E',6=>'F');

	    $listTable = array(
	                    1=>'tanah AS t',
	                    2=>'mesin AS m',
	                    3=>'bangunan AS b',
	                    4=>'jaringan AS j',
	                    5=>'asetlain AS al',
	                    6=>'kdp AS k');

	    $listTableOri = array(
	                    1=>'tanah',
	                    2=>'mesin',
	                    3=>'bangunan',
	                    4=>'jaringan',
	                    5=>'asetlain',
	                    6=>'kdp');

	    $data['listTable'] = $listTable[$type];
	    $data['listTableAlias'] = $listTableAlias[$type];
	    $data['listTableAbjad'] = $listTableAbjad[$type];
	    $data['listTableOri'] = $listTableOri[$type];

	    return $data;
    }
}


$run = new MUTASI;
$getUsulan = $run->usulan(4, 'no-dok');

logFile('==================== Usulan Mutasi DONE =====================');
if ($getUsulan){
	
	$unique['aset_id'] = array_unique($getUsulan['asetid']);
	$unique['Mutasi_ID'] = $getUsulan['mutasiid'];
	// pr($unique);
	
	$validasi = $run->validasi($unique);
	logFile('==================== Validasi Mutasi DONE =====================');
		
	
}


?>