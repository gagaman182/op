<?php
	 header('Access-Control-Allow-Origin: *');
   
	 include 'conn.php';


 $sql = "SELECT
 'ค่ายา' as ประเภท,
 count(case when temp_opd.DRUG_ALL IS NOT NULL and temp_opd.PROVIDER_CODE_ALL LIKE '%1%' THEN pid end ) as ตรวจโดยแพทย์,
 count(case when temp_opd.DRUG_ALL IS NOT NULL and temp_opd.PROVIDER_CODE_ALL LIKE '%3%' OR temp_opd.PROVIDER_CODE_ALL LIKE '%4%' 
 OR temp_opd.PROVIDER_CODE_ALL LIKE '%5%' THEN pid end ) as ตรวจโดยพยาบาล,
 count(case when temp_opd.DRUG_ALL IS NOT NULL and temp_opd.PROVIDER_CODE_ALL is null THEN pid end ) as ว่าง,
 
 sum(case when temp_opd.DRUG_ALL IS NOT NULL then DRUG_PRICE_ALL end) as ค่าใช้จ่าย
 
 FROM
  temp_opd
 INNER JOIN chospital on temp_opd.HOSPCODE = chospital.hoscode
 WHERE temp_opd.DRUG_ALL IS NOT NULL OR 
 temp_opd.PROCED_ALL IS NOT NULL OR 
 temp_opd.SUPPLIES_ALL IS NOT NULL
 AND symptoms NOT LIKE '%เยี่ยม%' 
 
 UNION
 
 SELECT
 'ค่าหัตถการ' as ประเภท,
 count(case when temp_opd.PROCED_ALL IS NOT NULL and temp_opd.PROVIDER_CODE_ALL LIKE '%1%' THEN pid end ) as ตรวจโดยแพทย์,
 count(case when temp_opd.PROCED_ALL IS NOT NULL and temp_opd.PROVIDER_CODE_ALL LIKE '%3%' OR temp_opd.PROVIDER_CODE_ALL LIKE '%4%' 
 OR temp_opd.PROVIDER_CODE_ALL LIKE '%5%' THEN pid end ) as ตรวจโดยพยาบาล,
 count(case when temp_opd.PROCED_ALL IS NOT NULL and temp_opd.PROVIDER_CODE_ALL is null THEN pid end ) as ว่าง,
 
 sum(case when temp_opd.PROCED_ALL IS NOT NULL and temp_opd.PROVIDER_CODE_ALL  not in ('2','6') then PROCED_PRICE_ALL   end) as ค่าใช้จ่าย
 
 
 
 
 FROM
  temp_opd
 INNER JOIN chospital on temp_opd.HOSPCODE = chospital.hoscode
 WHERE temp_opd.DRUG_ALL IS NOT NULL OR 
 temp_opd.PROCED_ALL IS NOT NULL OR 
 temp_opd.SUPPLIES_ALL IS NOT NULL
 AND symptoms NOT LIKE '%เยี่ยม%' 
 
 UNION
 
 SELECT
 'ค่าวัสดุสิ้นเปลือง' as ประเภท,
 
 count(case when temp_opd.SUPPLIES_ALL IS NOT NULL and temp_opd.PROVIDER_CODE_ALL LIKE '%1%' THEN pid end ) as ตรวจโดยแพทย์,
 count(case when temp_opd.SUPPLIES_ALL IS NOT NULL and temp_opd.PROVIDER_CODE_ALL LIKE '%3%' OR temp_opd.PROVIDER_CODE_ALL LIKE '%4%' 
 OR temp_opd.PROVIDER_CODE_ALL LIKE '%5%' THEN pid end ) as ตรวจโดยพยาบาล,
 count(case when temp_opd.SUPPLIES_ALL IS NOT NULL and temp_opd.PROVIDER_CODE_ALL is null THEN pid end ) as ว่าง,
 
 
 sum(case when temp_opd.SUPPLIES_ALL IS NOT NULL then SUPPLIES_PRICE_ALL end) as ค่าใช้จ่าย
 
 
 
 
 FROM
  temp_opd
 INNER JOIN chospital on temp_opd.HOSPCODE = chospital.hoscode
 WHERE temp_opd.DRUG_ALL IS NOT NULL OR 
 temp_opd.PROCED_ALL IS NOT NULL OR 
 temp_opd.SUPPLIES_ALL IS NOT NULL
 AND symptoms NOT LIKE '%เยี่ยม%' 
 
 union
 
 SELECT
 'ฟัน' as ประเภท,
 
 count(case when temp_opd.PROVIDER_CODE_ALL LIKE '%2%' THEN pid end ) as ตรวจโดยแพทย์,
 count(case when  temp_opd.PROVIDER_CODE_ALL LIKE '%6%'  THEN pid end ) as ตรวจโดยพยาบาล,
 count(case when temp_opd.PROCED_ALL IS NOT NULL and temp_opd.PROVIDER_CODE_ALL is null THEN pid end ) as ว่าง,
 sum(case when temp_opd.PROCED_ALL IS NOT NULL and temp_opd.PROVIDER_CODE_ALL  in ('2','6') then PROCED_PRICE_ALL   end) as ค่าใช้จ่าย
 
 
 
 
 FROM
  temp_opd
 INNER JOIN chospital on temp_opd.HOSPCODE = chospital.hoscode
 WHERE temp_opd.DRUG_ALL IS NOT NULL OR 
 temp_opd.PROCED_ALL IS NOT NULL OR 
 temp_opd.SUPPLIES_ALL IS NOT NULL
 AND symptoms NOT LIKE '%เยี่ยม%' 
 union 
 
 SELECT
 'รวม' as ประเภท,
 
 count(case when temp_opd.PROVIDER_CODE_ALL LIKE '%1%' THEN pid end ) as ตรวจโดยแพทย์,
 count(case when  temp_opd.PROVIDER_CODE_ALL LIKE '%3%' OR temp_opd.PROVIDER_CODE_ALL LIKE '%4%' 
 OR temp_opd.PROVIDER_CODE_ALL LIKE '%5%' THEN pid end ) as ตรวจโดยพยาบาล,
 count(case when  temp_opd.PROVIDER_CODE_ALL is null THEN pid end ) as ว่าง,
 
 
  sum(IF(DRUG_PRICE_ALL IS NULL,0,DRUG_PRICE_ALL))+
 sum(IF(PROCED_PRICE_ALL IS NULL,0,PROCED_PRICE_ALL))+
 sum(IF(SUPPLIES_PRICE_ALL IS NULL,0,SUPPLIES_PRICE_ALL))  as ค่าใช้จ่าย
 
 
 
 
 FROM
  temp_opd
 INNER JOIN chospital on temp_opd.HOSPCODE = chospital.hoscode
 WHERE temp_opd.DRUG_ALL IS NOT NULL OR 
 temp_opd.PROCED_ALL IS NOT NULL OR 
 temp_opd.SUPPLIES_ALL IS NOT NULL
 AND symptoms NOT LIKE '%เยี่ยม%' 
 
 
 
 
 
 
 
 
  ";


$return_arr = array();

if ($result = mysqli_query( $conn, $sql )){
    while ($row = mysqli_fetch_assoc($result)) {
	
     array_push($return_arr,$row);
   }
 }

mysqli_close($conn);

echo json_encode($return_arr);








?>
