<?php
include "../../config/config.php";

$PENGHAPUSAN = new RETRIEVE_PENGHAPUSAN;

$menu_id = 10;
            $SessionUser = $SESSION->get_session_user();
            ($SessionUser['ses_uid']!='') ? $Session = $SessionUser : $Session = $SESSION->get_session(array('title'=>'GuestMenu', 'ses_name'=>'menu_without_login')); 
            $USERAUTH->FrontEnd_check_akses_menu($menu_id, $Session);

// $get_data_filter = $RETRIEVE->retrieve_kontrak();
// ////pr($get_data_filter);
?>

<?php
	include"$path/meta.php";
	include"$path/header.php";
	include"$path/menu.php";
	
?>
	<!-- SQL Sementara -->
	<?php
	// pr($_POST);
	// pr($_SESSION);
	// $data = $PENGHAPUSAN->retrieve_penetapan_penghapusan_filter_pmd($_POST);
	////pr($data);

		$POST['page'] = intval($_GET['pid']);
	// pr($POST);
	    $par_data_table="bup_pp_sp_tglusul={$_POST['bup_pp_sp_tglusul']}&bup_pp_sp_nousulan={$_POST['bup_pp_sp_nousulan']}&kodeSatker={$_POST['kodeSatker']}&page={$POST['page']}";
// pr($par_data_table);
	if($_POST['kodeSatker']){
		$_SESSION['kdSatkerFilterPMDp']=$_POST['kodeSatker'];
		$kdSatkerFilter=$_SESSION['kdSatkerFilterPMDp'];
	}else{
		$kdSatkerFilter=$_SESSION['kdSatkerFilterPMDp'];
	}
		 $sql = mysql_query("SELECT * FROM kontrak ORDER BY id ");
        while ($dataKontrak = mysql_fetch_assoc($sql)){
                $kontrak[] = $dataKontrak;
            }
	?>
	<!-- End Sql -->
	<script>
		function AreAnyCheckboxesChecked () 
		{
			setTimeout(function() {
		  if ($("#Form2 input:checkbox:checked").length > 0)
			{
			    $("#submit").removeAttr("disabled");
			    updDataCheckbox('RVWPTUSPMD');
			}
			else
			{
			   $('#submit').attr("disabled","disabled");
			    updDataCheckbox('RVWPTUSPMD');
			}}, 100);
		}
		</script>
		<script>
    $(document).ready(function() {
          $('#usulan_aset_pmd_table').dataTable(
                   {
                    "aoColumnDefs": [
                         { "aTargets": [2] }
                    ],
                    "aoColumns":[
                         {"bSortable": false},
                         {"bSortable": false,"sClass": "checkbox-column" },
                         {"bSortable": true},
                         {"bSortable": false},
                         // {"bSortable": false},
                         {"bSortable": false},
                         {"bSortable": false},
                         {"bSortable": false},
                         // {"bSortable": false},
                         {"bSortable": false}],
                    "sPaginationType": "full_numbers",

                    "bProcessing": true,
                    "bServerSide": true,
                    "sAjaxSource": "<?=$url_rewrite?>/api_list/api_usulan_aset_pmd.php?<?php echo $par_data_table?>"
               }
                  );
      });
    </script>
	<section id="main">
		<ul class="breadcrumb">
			  <li><a href="#"><i class="fa fa-home fa-2x"></i>  Home</a> <span class="divider"><b>&raquo;</b></span></li>
			  <li><a href="#">Penghapusan</a><span class="divider"><b>&raquo;</b></span></li>
			  <li class="active">Daftar Penetapan Penghapusan Pemindahtanganan</li>
			  <?php SignInOut();?>
			</ul>
			<div class="breadcrumb">
				<div class="title">Penetapan Penghapusan Pemindahtanganan</div>
				<div class="subtitle">Daftar Penetapan Penghapusan Pemindahtanganan</div>
			</div>	

		<div class="grey-container shortcut-wrapper">
				<a class="shortcut-link" href="<?=$url_rewrite?>/module/penghapusan/dftr_usulan_pmd.php">
					<span class="fa-stack fa-lg">
				      <i class="fa fa-circle fa-stack-2x"></i>
				      <i class="fa fa-inverse fa-stack-1x">1</i>
				    </span>
					<span class="text">Usulan Penghapusan</span>
				</a>
				<a class="shortcut-link active" href="<?=$url_rewrite?>/module/penghapusan/dftr_penetapan_pmd.php">
					<span class="fa-stack fa-lg">
				      <i class="fa fa-circle fa-stack-2x"></i>
				      <i class="fa fa-inverse fa-stack-1x">2</i>
				    </span>
					<span class="text">Penetapan Penghapusan</span>
				</a>
				<a class="shortcut-link" href="<?=$url_rewrite?>/module/penghapusan/dftr_validasi_pmd.php">
					<span class="fa-stack fa-lg">
				      <i class="fa fa-circle fa-stack-2x"></i>
				      <i class="fa fa-inverse fa-stack-1x">3</i>
				    </span>
					<span class="text">Validasi Penghapusan</span>
				</a>
			</div>		

		<section class="formLegend">
		
			<div id="demo">
			<form name="myform" method="POST" ID="Form2" action="<?php echo "$url_rewrite/module/penghapusan/"; ?>dftr_review_penetapan_usulan_pmd.php">
			<input type="hidden" name="kdSatkerFilter" value="<?=$kdSatkerFilter?>" />
					
			<table cellpadding="0" cellspacing="0" border="0" class="display table-checkable" id="usulan_aset_pmd_table">
				<thead>
					<tr>
						
						<td align="left" colspan="6">
								<span><button type="submit" name="submit"  class="btn btn-info " id="submit" disabled/><i class="icon-plus-sign icon-white"></i>&nbsp;&nbsp;Penetapan Penghapusan</button></span>
						</td>
						<td colspan="2">
							<a href="<?php echo "$url_rewrite/module/penghapusan/"; ?>filter_usulan_pmd.php" class="btn">Kembali Ke Pencarian</a>
						</td>
					</tr>
					<tr>
						<th>No</th>
						<th class="checkbox-column"><input type="checkbox" class="icheck-input" onchange="return AreAnyCheckboxesChecked();"></th>
						<th>Nomor Usulan</th>
						<th>Satker</th>
						<th>Jumlah Aset</th>
						<th>Tgl Usulan</th>
						<th>Nilai</th>
						<th>Keterangan</th>
					</tr>
				</thead>
				<tbody>			
					 <tr>
                        <td colspan="8">Data Tidak di temukkan</td>
                     </tr>
				</tbody>
				<tfoot>
					<tr>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
					</tr>
				</tfoot>
			</table>

			</form>
			</div>
			<div class="spacer"></div>
			    
		</section> 
		      
	</section>
	
<?php
	include"$path/footer.php";
?>