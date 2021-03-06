<?php

include "../config/config.php";


class UpdateAset extends DB{

	var $db = "";
	public function __construct()
	{
		parent::__construct();

        $this->db = new DB;
	}


	function getAset()
	{

		$tabel = array('A'=>'tanah','B'=>'mesin','C'=>'bangunan','D'=>'jaringan', 'E'=>'asetlain','F'=>'kdp');
		$sql = array(
	            'table'=>"mutasi AS m ",
	            'field'=>"m.Mutasi_ID, m.TglSKKDH",
	            'condition'=>"m.fixMutasi = 1 AND m.Mutasi_ID >= 404 ",
	            );

	    $res = $this->db->lazyQuery($sql,$debug);
		if ($res){

			foreach ($res as $key => $value) {

				$sql = array(
			            'table'=>"mutasiaset AS ma, aset AS a",
			            'field'=>"ma.Aset_ID, a.TglPembukuan, a.TipeAset",
			            'condition'=>"ma.Mutasi_ID = {$value['Mutasi_ID']} AND ma.Status = 1 AND a.TipeAset !=''",
			            'joinmethod'=>'LEFT JOIN',
			            'join'=>'ma.Aset_ID = a.Aset_ID'
			            );

			    $res[$key]['aset'] = $this->db->lazyQuery($sql,$debug);
			}
			// pr($res);exit;
			if ($res){
				foreach ($res as $key => $value) {
					

					foreach ($value['aset'] as  $val) {
						// echo $val['TipeAset'];exit;
						
						$tabelKib = $tabel[$val['TipeAset']];
						$sql = array(
					            'table'=>"{$tabelKib} AS o, log_{$tabelKib} AS a",
					            'field'=>"o.TglPembukuan AS TglKib, a.TglPembukuan AS TglLog",
					            'condition'=>"o.Aset_ID = {$val['Aset_ID']} AND a.Kd_Riwayat = 3 ",
					            'joinmethod'=>'LEFT JOIN',
					            'join'=>'o.Aset_ID = a.Aset_ID'
					            );

					    $tmp = $this->db->lazyQuery($sql,$debug);
					    if ($tmp){
					    	$res[$key]['log'] = $tmp;
					    }
					     
					}
				}
			}

			// pr($res);
			return $res;
		}
		
		return false;
	}

	function updateTglDokumen($data=array(), $debug=false)
	{

		if ($data){

			$tabel = array('A'=>'tanah','B'=>'mesin','C'=>'bangunan','D'=>'jaringan', 'E'=>'asetlain','F'=>'kdp');
		

			foreach ($data['Aset_ID'] as $key => $value) {
				
				$tmp = explode('|', $value);
				$Aset_ID = $tmp[0];
				$TglPembukuan = $tmp[1];
				$tabelKib = $tabel[$tmp[2]];

				// pr($tmp);
				$sql2 = array(
	                    'table'=>"Aset",
	                    'field'=>"TglPembukuan = '{$TglPembukuan}'",
	                    'condition'=>"Aset_ID='$Aset_ID'",
	                    'limit'=>1
	                    );

	            $res2 = $this->db->lazyQuery($sql2,$debug,2); 
	            

            	$sql2 = array(
	                    'table'=>"{$tabelKib}",
	                    'field'=>"TglPembukuan = '{$TglPembukuan}'",
	                    'condition'=>"Aset_ID='$Aset_ID'",
	                    'limit'=>1
	                    );

	            $res2 = $this->db->lazyQuery($sql2,$debug,2); 

	            $sql2 = array(
	                    'table'=>"log_{$tabelKib}",
	                    'field'=>"TglPembukuan = '{$TglPembukuan}'",
	                    'condition'=>"Aset_ID='$Aset_ID' AND Kd_Riwayat = 3",
	                    );

	            $res2 = $this->db->lazyQuery($sql2,$debug,2); 

            	echo 'updated tgl pembukuan Aset_ID : '.$Aset_ID.'<br>';
	            
			}
		}
	}
}


$update = new UpdateAset;
$data = $update->getAset();

if (isset($_POST)){

	if ($_POST['submit']){

		// pr($_POST);
		$run = $update->updateTglDokumen($_POST);

	}
}
?>

<form method="post" action="">
<table border="1">
	<tr>
		<td>No</td>
		<td>Mutasi ID</td>
		<td>Tgl SKKDH</td>
		<td>Aset ID</td>
		<td>Tgl Pembukuan</td>
		<td>Tipe Aset</td>
		<td>Update ?</td>
	</tr>


	
	<?php

	if ($data){
		$no = 1;
		foreach ($data as $key => $value) {
			
			foreach ($value['aset'] as $val) {
				echo "<tr>";
				?>
				<td><?=$no?></td>
				<td><?=$value['Mutasi_ID']?></td>
				<td><?=$value['TglSKKDH']?></td>
				<td><?=$val['Aset_ID']?></td>
				<td><?=$val['TglPembukuan']?></td>
				<td><?=$val['TipeAset']?></td>
				<td><input type="checkbox" name="Aset_ID[]" value="<?=$val['Aset_ID']?>|<?=$value['TglSKKDH']?>|<?=$val['TipeAset']?>"></td>
				<?php
				echo "</tr>";
				$no++;
			}

			
		}

	}
	
	?>
	
	<tr>
		<td><input type="submit" name="submit" value="submit"></td>
		
	</tr>
</table>

</form>