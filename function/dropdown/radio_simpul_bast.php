<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include "../../config/database.php";
open_connection();
$kode=$_GET['kode_for_js'];//untuk append child

$tambahan_field=explode("_",$_GET['kode']);
$tambahan=$tambahan_field[1];
$prefix=$tambahan_field[0];

$tmp=explode("|",$tambahan);
$parent_node=$tmp[0];

$hasil=explode("|",$kode);
$level=$hasil[2];

$kode_sistem=$tambahan;
$tmpall=  explode("|", $kode_sistem);

$tmp=  explode(".", $tmpall[0]);

echo $tmpall[1]."~";
$tahun=$tmp[0];

$paramater_sql=" ";


if($tahun!="")
{
     $query_golongan=" TglBAST like '$tahun%'";
}


     
 if($tahun!=""){
     $paramater_sql=$query_golongan;
}
 


$level=$level+1;
//$level=count($tmp)+1;
switch ($level) {
    
     
     case "2": //Bidang
          $spasi="&nbsp;&nbsp;&nbsp;&nbsp;";
          //$tambahan_query=" and Bidang is not NULL and Kelompok is NULL and Sub is NULL and SubSub is NULL";
		  $last="stop";
          break;
     

     default:
          break;
}


if($kode!=""&&$paramater_sql!=""){
     $query="Select * FROm BAST where $paramater_sql order by TglBAST asc";
      //echo "$query  <br/>";
     $result=  mysql_query($query) or die(mysql_error());
 
  //  echo "<table width=\"100%\" align=\"left\" border=\"0\" class=\"tabel\">";
//$no=1;
     while($row=  mysql_fetch_object($result)){
          $bast_id=$row->BAST_ID;
          $kode=$row->TglBAST;
          $uraian=$row->NoBAST;
          $kode_for_js="$prefix-bast_row_1|$kode|$level";
          //$sign='1';
        //initinya
                   echo "$kode_for_js]$spasi]";
                    echo "$kode]";
                    echo "add_bast$prefix('$kode_for_js','$prefix"."_$kode')]$uraian]$parent_node]$bast_id]$level]$prefix"."_$bast_id]$last+";
          //intinya   
            //$no++;          
          
//          
//          echo "<tr>";
//                echo "<td> $spasi<input type=\"checkbox\" name=\"kelompok[]\" value=\"$kode\"> $level
//          </td>";
//               echo "<td>$kode</td>";
//          echo "<td><a href=\"javascript:void(0)\" onClick=\"add_kelompok$prefix('$kode_for_js','$kode')\">$uraian </a></td>";
//           
  
          
            
     }
//   echo "</table>";
     
}






?>
