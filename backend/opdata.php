<?php
	 header('Access-Control-Allow-Origin: *');
   
	 include 'conn.php';


 $sql = "SELECT
 CONCAT(temp_opd.HOSPCODE,'-',chospital.hosname) AS HOSPCODE,
DATE_FORMAT(month1.date_visit, '%M') as  MONTHS_1,
DATE_FORMAT(month1.date_visit, '%YYYY')+543 as  YEARS_1,
DATE_FORMAT(month2.date_visit, '%M') as  MONTHS_2,
DATE_FORMAT(month2.date_visit, '%YYYY')+543 as  YEARS_2,
 count(DISTINCT pid)AS PERSON,
  count(pid)AS VISIT,
 sum(IF(DRUG_PRICE_ALL IS NULL,0,DRUG_PRICE_ALL))+
sum(IF(PROCED_PRICE_ALL IS NULL,0,PROCED_PRICE_ALL))+
sum(IF(SUPPLIES_PRICE_ALL IS NULL,0,SUPPLIES_PRICE_ALL)) AS MONEYS,
count(case when temp_opd.PROVIDER_CODE_ALL LIKE '%1%' THEN pid end) as DOCTORS,

count(case when temp_opd.PROVIDER_CODE_ALL LIKE '%3%' OR temp_opd.PROVIDER_CODE_ALL LIKE '%4%' OR temp_opd.PROVIDER_CODE_ALL LIKE '%5%' THEN pid end) as NURSES,
count(case when temp_opd.PROVIDER_CODE_ALL LIKE '%1%' or temp_opd.PROVIDER_CODE_ALL LIKE '%3%' OR temp_opd.PROVIDER_CODE_ALL LIKE '%4%' 
OR temp_opd.PROVIDER_CODE_ALL LIKE '%5%' THEN pid end) as DOCTORANDNURSE,
count(case when (temp_opd.DIAG_CODE_ALL NOT LIKE 'E1%' and temp_opd.DIAG_CODE_ALL NOT LIKE 'E7%' and temp_opd.DIAG_CODE_ALL NOT LIKE 'I1%') 
and (temp_opd.PROVIDER_CODE_ALL LIKE '%1%')  THEN pid end) as GENERAL_DOCTOR,
count(case when (temp_opd.DIAG_CODE_ALL not LIKE 'E1%' and temp_opd.DIAG_CODE_ALL not LIKE 'E7%' and temp_opd.DIAG_CODE_ALL not LIKE 'I1%') 
and temp_opd.PROVIDER_CODE_ALL in ('3','4','5')  THEN pid end) as GENERAL_NURSE,
count(case when (temp_opd.DIAG_CODE_ALL not LIKE 'E1%' and temp_opd.DIAG_CODE_ALL not LIKE 'E7%' and temp_opd.DIAG_CODE_ALL not LIKE 'I1%') 
and (temp_opd.PROVIDER_CODE_ALL LIKE '%1%' OR temp_opd.PROVIDER_CODE_ALL LIKE '%3%' OR temp_opd.PROVIDER_CODE_ALL LIKE '%4%' 
OR temp_opd.PROVIDER_CODE_ALL LIKE '%5%')  THEN pid end) as GENERAL_DOCTOR_NURSE,
count(case when (temp_opd.DIAG_CODE_ALL LIKE 'E1%' OR temp_opd.DIAG_CODE_ALL LIKE 'E7%' OR temp_opd.DIAG_CODE_ALL LIKE 'I1%') 
and temp_opd.PROVIDER_CODE_ALL LIKE '%1%'  THEN pid end) as CHRONIC_DOCTOR,
count(case when (temp_opd.DIAG_CODE_ALL LIKE 'E1%' OR temp_opd.DIAG_CODE_ALL LIKE 'E7%' OR temp_opd.DIAG_CODE_ALL LIKE 'I1%') 
and (temp_opd.PROVIDER_CODE_ALL LIKE '%3%' OR temp_opd.PROVIDER_CODE_ALL LIKE '%4%' OR temp_opd.PROVIDER_CODE_ALL LIKE '%5%') THEN pid end) as CHRONIC_NURSE,
count(case when (temp_opd.DIAG_CODE_ALL LIKE 'E1%' OR temp_opd.DIAG_CODE_ALL LIKE 'E7%' OR temp_opd.DIAG_CODE_ALL LIKE 'I1%') 
and (temp_opd.PROVIDER_CODE_ALL LIKE '%1%' OR temp_opd.PROVIDER_CODE_ALL LIKE '%3%' OR temp_opd.PROVIDER_CODE_ALL LIKE '%4%' OR temp_opd.PROVIDER_CODE_ALL LIKE '%5%')  THEN pid end) as CHRONIC_DOCTOR_NURSE,
count(case when temp_opd.PROVIDER_CODE_ALL LIKE '%2%' THEN pid end) as DENT_DOCTORS,
count(case when temp_opd.PROVIDER_CODE_ALL LIKE '%6%' OR temp_opd.PROVIDER_CODE_ALL LIKE '%7%'  THEN pid end) as DENT_NURSES,
count(case when temp_opd.PROVIDER_CODE_ALL LIKE '%2%' or temp_opd.PROVIDER_CODE_ALL LIKE '%6%' OR temp_opd.PROVIDER_CODE_ALL LIKE '%7%'  THEN pid end) as DENT_DOCTORANDNURSE


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
