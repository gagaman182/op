<?php
	 header('Access-Control-Allow-Origin: *');
   
	 include 'conn.php';


 $sql = "SELECT
 CONCAT(temp_opd.HOSPCODE,'-',chospital.hosname) AS 'สถานบริการ',
 temp_opd.PID AS 'HN',
 temp_opd.visitno AS 'ลำดับการรับบริการ',
 
   concat(
    DATE_FORMAT(DATE_VISIT, '%d'),
    '-',
    DATE_FORMAT(DATE_VISIT, '%m'),
    '-',
    DATE_FORMAT(DATE_VISIT, '%YYYY')+ 543
  )AS DATE_VISIT_THAI,
 temp_opd.INSTYPE AS 'รหัสสิทธิ',
 temp_opd.RIGHT_SERV AS 'สิทธิ',
 temp_opd.RIGHT_GROUP AS 'กลุ่มสิทธิ',
 temp_opd.symptoms AS 'อาการสำคัญ',
 temp_opd.DIAG_CODE_ALL AS 'รหัสโรค',
 temp_opd.DIAG_NAME_ALL AS 'โรค',
 temp_opd.PROVIDER_CODE_ALL AS 'กลุ่มผู้ให้บริการ',
 temp_opd.DRUG_ALL AS 'ยา',
 temp_opd.DRUG_PRICE_ALL AS 'ราคายา',
 temp_opd.DRUG_CODE24_ALL AS 'ยา24หลัก',
 temp_opd.PROCED_ALL AS 'หัตถการ',
 temp_opd.PROCED_PRICE_ALL AS 'ค่าหัตถการ',
 temp_opd.PROCED_CODE_ALL AS 'รหัสหัตถการ',
 temp_opd.DENTAL_ALL AS 'ฟัน',
 temp_opd.DENTAL_PRICE_ALL AS 'ค่าทำฟัน',
 temp_opd.DENTAL_CODE_ALL AS 'รหัสการทำฟัน',
 temp_opd.SUPPLIES_ALL AS 'วัสดุสิ้นเปลือง',
 temp_opd.SUPPLIES_PRICE_ALL AS 'ค่าวัสดุสิ้นเปลือง' ,
 temp_opd.SUPPLIES_CODE_ALL AS 'รหัสวัสดุสิ้นเปลือง',
 temp_opd.DOCTOR_FEE AS 'ราการที่ไม่คิดค่าใช้จ่าย',
 temp_opd.DOCTOR_FEE_PRICE AS 'จำนวนเงินราการที่ไม่คิดค่าใช้จ่าย' ,
 temp_opd.DOCTOR_FEE_CODE as 'รหัสราการที่ไม่คิดค่าใช้จ่าย',
 temp_opd.PAN_THAI_ALL as 'แผนไทย',
 temp_opd.PAN_THAI_PRICE as 'ค่าแผนไทย',
 temp_opd.PAN_THAI_CODE as 'รหัสแผนไทย',
 temp_opd.DRUG_HERB_ALL as 'สมุนไพร',
 temp_opd.DRUG_HERB_PRICE as 'ค่าสมุนไพร',
 temp_opd.DRUG_HERB_CODE as 'รหัสสมุนไพร',
 temp_opd.PAN_THAI_VISIT as 'จำนวนผู้รับบริการแผนไทย',
 temp_opd.PAN_THAI_NAME as 'แผนไทย',
 temp_opd.PROVIDER_PANTHAI as 'แผนไทยผู้ให้บริการ'
 
 FROM
  temp_opd
 INNER JOIN chospital on temp_opd.HOSPCODE = chospital.hoscode
 WHERE temp_opd.DRUG_ALL IS NOT NULL OR 
 temp_opd.PROCED_ALL IS NOT NULL OR 
 temp_opd.SUPPLIES_ALL IS NOT NULL
 AND symptoms NOT LIKE '%เยี่ยม%'  ";


$return_arr = array();

if ($result = mysqli_query( $conn, $sql )){
    while ($row = mysqli_fetch_assoc($result)) {
	
     array_push($return_arr,$row);
   }
 }

mysqli_close($conn);

echo json_encode($return_arr);








?>
