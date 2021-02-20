<?php
	 header('Access-Control-Allow-Origin: *');
   
	 include 'conn.php';


 $sql = "SELECT
 CONCAT(temp_opd.HOSPCODE,'-',chospital.hosname) AS 'สถานบริการ',

 count(DISTINCT pid)AS 'ค่าใช้จ่ายจริง คน',
  count(pid)AS 'ค่าใช้จ่ายจริง ครั้ง',
 sum(IF(DRUG_PRICE_ALL IS NULL,0,DRUG_PRICE_ALL))+
sum(IF(PROCED_PRICE_ALL IS NULL,0,PROCED_PRICE_ALL))+
sum(IF(SUPPLIES_PRICE_ALL IS NULL,0,SUPPLIES_PRICE_ALL)) AS 'ค่าใช้จ่ายจริง จำนวนเงิน',
count(case when temp_opd.PROVIDER_CODE_ALL LIKE '%1%' THEN pid end) as 'ตรวจโดย หมอ',

count(case when temp_opd.PROVIDER_CODE_ALL LIKE '%3%' OR temp_opd.PROVIDER_CODE_ALL LIKE '%4%' OR temp_opd.PROVIDER_CODE_ALL LIKE '%5%' THEN pid end) as 'ตรวจโดย  พยาบาล',
count(case when temp_opd.PROVIDER_CODE_ALL LIKE '%1%' or temp_opd.PROVIDER_CODE_ALL LIKE '%3%' OR temp_opd.PROVIDER_CODE_ALL LIKE '%4%' 
OR temp_opd.PROVIDER_CODE_ALL LIKE '%5%' THEN pid end) as 'ตรวจโดย หมอ พยาบาล',
count(case when (temp_opd.DIAG_CODE_ALL NOT LIKE 'E1%' and temp_opd.DIAG_CODE_ALL NOT LIKE 'E7%' and temp_opd.DIAG_CODE_ALL NOT LIKE 'I1%') 
and (temp_opd.PROVIDER_CODE_ALL LIKE '%1%')  THEN pid end) as 'โรคทั่วไป หมอตรวจ' ,
count(case when (temp_opd.DIAG_CODE_ALL not LIKE 'E1%' and temp_opd.DIAG_CODE_ALL not LIKE 'E7%' and temp_opd.DIAG_CODE_ALL not LIKE 'I1%') 
and temp_opd.PROVIDER_CODE_ALL in ('3','4','5')  THEN pid end) as 'โรคทั่วไป พยาบาลตรวจ',
count(case when (temp_opd.DIAG_CODE_ALL not LIKE 'E1%' and temp_opd.DIAG_CODE_ALL not LIKE 'E7%' and temp_opd.DIAG_CODE_ALL not LIKE 'I1%') 
and (temp_opd.PROVIDER_CODE_ALL LIKE '%1%' OR temp_opd.PROVIDER_CODE_ALL LIKE '%3%' OR temp_opd.PROVIDER_CODE_ALL LIKE '%4%' 
OR temp_opd.PROVIDER_CODE_ALL LIKE '%5%')  THEN pid end) as 'โรคทั่วไป หมอและพยาบาลตรวจ',
count(case when (temp_opd.DIAG_CODE_ALL LIKE 'E1%' OR temp_opd.DIAG_CODE_ALL LIKE 'E7%' OR temp_opd.DIAG_CODE_ALL LIKE 'I1%') 
and temp_opd.PROVIDER_CODE_ALL LIKE '%1%'  THEN pid end) as 'โรคเรื้อรัง หมอตรวจ',
count(case when (temp_opd.DIAG_CODE_ALL LIKE 'E1%' OR temp_opd.DIAG_CODE_ALL LIKE 'E7%' OR temp_opd.DIAG_CODE_ALL LIKE 'I1%') 
and (temp_opd.PROVIDER_CODE_ALL LIKE '%3%' OR temp_opd.PROVIDER_CODE_ALL LIKE '%4%' OR temp_opd.PROVIDER_CODE_ALL LIKE '%5%') THEN pid end) as 'โรคเรื้อรัง พยาบาลตรวจ',
count(case when (temp_opd.DIAG_CODE_ALL LIKE 'E1%' OR temp_opd.DIAG_CODE_ALL LIKE 'E7%' OR temp_opd.DIAG_CODE_ALL LIKE 'I1%') 
and (temp_opd.PROVIDER_CODE_ALL LIKE '%1%' OR temp_opd.PROVIDER_CODE_ALL LIKE '%3%' OR temp_opd.PROVIDER_CODE_ALL LIKE '%4%' OR temp_opd.PROVIDER_CODE_ALL LIKE '%5%')  THEN pid end) as 'โรคเรื้อรัง  หมอและพยาบาลตรวจ',
count(case when temp_opd.PROVIDER_CODE_ALL LIKE '%2%' THEN pid end) as 'ฟัน หมอตรวจ ',
count(case when temp_opd.PROVIDER_CODE_ALL LIKE '%6%' OR temp_opd.PROVIDER_CODE_ALL LIKE '%7%'  THEN pid end) as 'ฟัน พยาบาลตรวจ ',
count(case when temp_opd.PROVIDER_CODE_ALL LIKE '%2%' or temp_opd.PROVIDER_CODE_ALL LIKE '%6%' OR temp_opd.PROVIDER_CODE_ALL LIKE '%7%'  THEN pid end) as 'ฟัน หมอและพยาบาลตรวจ '


FROM
 temp_opd
 INNER JOIN chospital on temp_opd.HOSPCODE = chospital.hoscode
INNER JOIN (SELECT HOSPCODE,DATE_VISIT FROM temp_opd ORDER BY DATE_VISIT asc LIMIT 1)month1 on temp_opd.HOSPCODE = month1.HOSPCODE
INNER JOIN (SELECT HOSPCODE,DATE_VISIT FROM temp_opd ORDER BY DATE_VISIT desc LIMIT 1)month2 on temp_opd.HOSPCODE = month2.HOSPCODE
 WHERE temp_opd.DRUG_ALL IS NOT NULL OR 
temp_opd.PROCED_ALL IS NOT NULL OR 
temp_opd.SUPPLIES_ALL IS NOT NULL
AND symptoms NOT LIKE '%เยี่ยม%'
GROUP BY
 temp_opd.HOSPCODE ";


$return_arr = array();

if ($result = mysqli_query( $conn, $sql )){
    while ($row = mysqli_fetch_assoc($result)) {
	
     array_push($return_arr,$row);
   }
 }

mysqli_close($conn);

echo json_encode($return_arr);








?>
