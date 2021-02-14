<?php
	 header('Access-Control-Allow-Origin: *');
   
	 include 'conn.php';


 $sql = "SELECT
 CONCAT(HOSPCODE,'-',chospital.hosname) AS HOSPCODE,
 count(DISTINCT pid)AS PERSON,
	count(pid)AS VISIT,
 sum(IF(DRUG_PRICE_ALL IS NULL,0,DRUG_PRICE_ALL))+
sum(IF(PROCED_PRICE_ALL IS NULL,0,PROCED_PRICE_ALL))+
sum(IF(DENTAL_PRICE_ALL IS NULL,0,DENTAL_PRICE_ALL))+
sum(IF(SUPPLIES_PRICE_ALL IS NULL,0,SUPPLIES_PRICE_ALL))+
sum(IF(DOCTOR_FEE_PRICE IS NULL,0,DOCTOR_FEE_PRICE))+
sum(IF(PAN_THAI_PRICE IS NULL,0,PAN_THAI_PRICE))+
sum(IF(DRUG_HERB_PRICE IS NULL,0,DRUG_HERB_PRICE))AS MONEYS


FROM
 temp_opd
 INNER JOIN chospital on temp_opd.HOSPCODE = chospital.hoscode
GROUP BY
 HOSPCODE ";


$return_arr = array();

if ($result = mysqli_query( $conn, $sql )){
    while ($row = mysqli_fetch_assoc($result)) {
	
     array_push($return_arr,$row);
   }
 }

mysqli_close($conn);

echo json_encode($return_arr);








?>
