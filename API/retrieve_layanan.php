<?php
class RETRIEVE_LAYANAN extends RETRIEVE{
	var $db = "";
	public function __construct()
	{
		parent::__construct();
		$this->db = new DB;

	}
	

	public function retrieve_layanan_aset_daftar($data,$debug=false)
    {

        // pr($data);
       	$kd_idaset = $data['kd_idaset'];
       	$kd_namaaset = $data['kd_namaaset'];
       	$kd_nokontrak = $data['kd_nokontrak'];
       	$kd_tahun = $data['kd_tahun'];
       	$lokasi_id = $data['lokasi_id'];
       	$kelompok_id = $data['kelompok_id5'];
       	$satker = $data['kodeSatker'];
       	$jenisaset = $data['jenisaset'];
       	$statusaset = $data['statusaset'];
       	
       	// pr($data);
       	$getTable = $this->getTableKibAlias($jenisaset);
        $listTable = $getTable['listTable'];
        $listTableAlias = $getTable['listTableAlias'];
        
        $filter = "";
        //if ($kd_idaset) $filter .= " AND a.kodeSatker = '{$kd_idaset}' ";
        //if ($kd_namaaset) $filter .= " AND a.kodeSatker = '{$kd_namaaset}' ";
        if ($kd_nokontrak) $filter .= " AND a.noKontrak = '{$kd_nokontrak}' ";
        if ($kd_tahun) $filter .= " AND {$listTableAlias}.Tahun = '{$kd_tahun}' ";
        if ($kelompok_id) $filter .= " AND {$listTableAlias}.kodeKelompok = '{$kelompok_id}' ";
        if ($satker) $filter .= " AND {$listTableAlias}.kodeSatker = '{$satker}' ";
        
        if ($statusaset==0) $filter .= " AND ({$listTableAlias}.Status_Validasi_Barang = {$statusaset} OR {$listTableAlias}.Status_Validasi_Barang IS NULL )";
        if ($statusaset==1) $filter .= " AND {$listTableAlias}.Status_Validasi_Barang = '{$statusaset}' ";
        if ($statusaset==22) $filter .= " AND {$listTableAlias}.Status_Validasi_Barang = '{$statusaset}' AND a.fixPenggunaan = 1";
        if ($statusaset==23) $filter .= " AND {$listTableAlias}.Status_Validasi_Barang = '{$statusaset}' AND a.statusPemanfaatan = 1";


        // $tabeltmp = $_SESSION['penggunaan_validasi']['jenisaset'];
        // $getTable = $this->getTableKibAlias($tabeltmp);
        // $tabel = $getTable['listTableAbjad'];

        // pr($data);exit;
        $TipeAset = 
        $sql = array(
                'table'=>"{$listTable}, aset AS a, kelompok AS k, satker AS s",
                'field'=>'a.*, k.Uraian, s.NamaSatker',
                'condition' => "{$listTableAlias}.StatusTampil = 1  {$filter} GROUP BY {$listTableAlias}.Aset_ID",
                'limit' => '100',
                'joinmethod' => 'LEFT JOIN',
                'join' => "{$listTableAlias}.Aset_ID = a.Aset_ID, {$listTableAlias}.kodeKelompok = k.Kode, {$listTableAlias}.kodeSatker = s.Kode"
                );

        $res = $this->db->lazyQuery($sql,$debug);
        if ($res) return $res;
        return false;
    }

    function retrieve_history_aset($data,$debug=false)
    {

        $jenisaset = $data['jenisaset'];
        $Aset_ID = $data['id'];
        
        $listTableAbjad = array('A'=>1,'B'=>2,'C'=>3,'D'=>4,'E'=>5,'F'=>6);

        // pr($data);
        $filter = "";
        

        $getTable = $this->getTableKibAlias($listTableAbjad[$jenisaset]);
        $listTable = $getTable['listTable'];
        $listTableAlias = $getTable['listTableAlias'];

        if ($Aset_ID) $filter .= " AND {$listTableAlias}.Aset_ID = '{$Aset_ID}' ";

        $sql = array(
                'table'=>"log_{$listTable}, aset AS a, kelompok AS k, ref_riwayat AS r",
                'field'=>"{$listTableAlias}.*, a.noRegister, k.Uraian, r.Nm_Riwayat",
                'condition' => "{$listTableAlias}.StatusTampil = 1  {$filter} ",
                'limit' => '100',
                'joinmethod' => 'LEFT JOIN',
                'join' => "{$listTableAlias}.Aset_ID = a.Aset_ID, {$listTableAlias}.kodeKelompok = k.Kode, {$listTableAlias}.action=r.Kd_Riwayat"
                );

        $res = $this->db->lazyQuery($sql,$debug);
        if ($res){

            foreach ($res as $key => $value) {

                $sql = array(
                        'table'=>"satker AS s",
                        'field'=>"s.NamaSatker",
                        'condition' => "s.kode = '{$value['kodeSatker']}' AND s.Kd_Ruang IS NULL",
                        'limit' => '100',
                        );

                $res[$key]['NamaSatker'] = $this->db->lazyQuery($sql,$debug);
            }

            return $res;
        } 
        return false;
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
        $listTable2 = array(
                        1=>'tanah',
                        2=>'mesin',
                        3=>'bangunan',
                        4=>'jaringan',
                        5=>'asetlain',
                        6=>'kdp');

        $data['listTable'] = $listTable[$type];
        $data['listTableAlias'] = $listTableAlias[$type];
        $data['listTableReal'] = $listTable2[$type];
        $data['listTableAbjad'] = $listTableAbjad[$type];


        return $data;
    }
}
?>