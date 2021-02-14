<?php
 header('Content-Type:application/json');
 header('Access-Control-Allow-Origin: *');
 header('Access-Control-Allow-Methods: PUT');
 header('Access-Control-Allow-Headers: Acess-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization');
 $data = json_decode(file_get_contents('php://input'),true);

  $monthstart = $data['monthstart'];
  $monthend = $data['monthend'];

 


include 'conn.php';



 
   $sql = "drop table if EXISTS temp_opd";
  $sql2 = "CREATE TABLE temp_opd  (
    `HOSPCODE` varchar(5) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `PID` int NOT NULL,
    `IDCARD` varchar(13) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
    `visitno` int NOT NULL,
    `DATE_VISIT` date NULL DEFAULT NULL,
    `INSTYPE` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
    `RIGHT_SERV` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
    `RIGHT_GROUP` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
    `symptoms` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
    `DIAG_CODE_ALL` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
    `DIAG_NAME_ALL` varchar(2000) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
    `PROVIDER_CODE_ALL` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
    `DRUG_ALL` varchar(1000) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
    `DRUG_PRICE_ALL` decimal(20, 2) NULL DEFAULT NULL,
    `DRUG_CODE24_ALL` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
    `PROCED_ALL` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
    `PROCED_PRICE_ALL` decimal(20, 2) NULL DEFAULT NULL,
    `PROCED_CODE_ALL` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
    `DENTAL_ALL` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
    `DENTAL_PRICE_ALL` decimal(20, 2) NULL DEFAULT NULL,
    `DENTAL_CODE_ALL` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
    `SUPPLIES_ALL` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
    `SUPPLIES_PRICE_ALL` decimal(20, 2) NULL DEFAULT NULL,
    `SUPPLIES_CODE_ALL` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
    `DOCTOR_FEE` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
    `DOCTOR_FEE_PRICE` decimal(20, 2) NULL DEFAULT NULL,
    `DOCTOR_FEE_CODE` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
    `PAN_THAI_ALL` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
    `PAN_THAI_PRICE` decimal(20, 2) NULL DEFAULT NULL,
    `PAN_THAI_CODE` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
    `DRUG_HERB_ALL` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
    `DRUG_HERB_PRICE` decimal(20, 2) NULL DEFAULT NULL,
    `DRUG_HERB_CODE` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
    `PAN_THAI_VISIT` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
    `PAN_THAI_NAME` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
    `PROVIDER_PANTHAI` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
    PRIMARY KEY (`HOSPCODE`, `PID`, `visitno`) ,
    INDEX `HOSPCODE`(`HOSPCODE`, `PID`) ,
    INDEX `PID`(`PID`) ,
    INDEX `HOSPCODE_2`(`HOSPCODE`, `visitno`) ,
    INDEX `visitno`(`visitno`) 
  ) ";
        
        $sql3 = "TRUNCATE TABLE temp_opd";
          
        $sql4 = "INSERT INTO temp_opd (HOSPCODE,pid,visitno,DATE_VISIT,INSTYPE,RIGHT_SERV,RIGHT_GROUP,symptoms)
        SELECT pcucode AS HOSPCODE,pid,visitno,visitdate AS DATE_VISIT,visit.rightcode AS INSTYPE,rightname AS RIGHT_SERV,rightgroup AS RIGHT_GROUP,symptoms
        FROM visit 
        INNER JOIN cright ON visit.rightcode=cright.rightcode
        WHERE LEFT(visitdate, 7) BETWEEN '".$monthstart."' AND '".$monthend."' AND flagservice='03' AND hosmain='10682'
        AND rightgroup IN ('3','4')";
          
        $sql5 = "UPDATE temp_opd INNER JOIN
        (SELECT visitno,GROUP_CONCAT(diagcode) AS DIAG_CODE_ALL,GROUP_CONCAT(officertype) AS PROVIDER_CODE_ALL,GROUP_CONCAT(diseasenamethai) AS DIAG_NAME_ALL FROM visitdiag 
        INNER JOIN `user` ON visitdiag.doctordiag=`user`.username
        INNER JOIN cdisease ON visitdiag.diagcode=cdisease.diseasecode
        WHERE (LEFT(diagcode,3) NOT BETWEEN 'U50' AND 'U52') AND 
        (LEFT(diagcode,3) NOT BETWEEN 'U54' AND 'U55') AND 
        (LEFT(diagcode,3) NOT BETWEEN 'U56' AND 'U60') AND 
        (LEFT(diagcode,3) NOT BETWEEN 'U61' AND 'U72') AND 
        (LEFT(diagcode,3) NOT BETWEEN 'U74' AND 'U75') AND 
        LEFT(diagcode,3) <> 'U77' AND 
        (LEFT(diagcode,1) <> 'Z' OR  diagcode IN ('Z48.0','Z51.8','Z51.5'))
        GROUP BY visitno) aa
        ON temp_opd.VISITNO=aa.visitno
        SET temp_opd.DIAG_CODE_ALL=aa.DIAG_CODE_ALL,temp_opd.PROVIDER_CODE_ALL=aa.PROVIDER_CODE_ALL,temp_opd.DIAG_NAME_ALL=aa.DIAG_NAME_ALL";
          
        $sql6 = "UPDATE temp_opd INNER JOIN
        (SELECT visitno,GROUP_CONCAT(diagcode) AS PAN_THAI_VISIT,GROUP_CONCAT(officertype) AS PROVIDER_PANTHAI,GROUP_CONCAT(diseasenamethai) AS PAN_THAI_NAME FROM visitdiag 
        INNER JOIN `user` ON visitdiag.doctordiag=`user`.username
        INNER JOIN cdisease ON visitdiag.diagcode=cdisease.diseasecode
        WHERE (LEFT(diagcode,3) BETWEEN 'U50' AND 'U52') OR 
        (LEFT(diagcode,3) BETWEEN 'U54' AND 'U55') OR 
        (LEFT(diagcode,3) BETWEEN 'U56' AND 'U60') OR 
        (LEFT(diagcode,3) BETWEEN 'U61' AND 'U72') OR 
        (LEFT(diagcode,3) BETWEEN 'U74' AND 'U75') OR 
        LEFT(diagcode,3)='U77'
        GROUP BY visitno) aa
        ON temp_opd.VISITNO=aa.visitno
        SET temp_opd.PAN_THAI_VISIT=aa.PAN_THAI_VISIT,temp_opd.PROVIDER_PANTHAI=aa.PROVIDER_PANTHAI,temp_opd.PAN_THAI_NAME=aa.PAN_THAI_NAME";
          
        $sql7 = "UPDATE temp_opd INNER JOIN
        (SELECT visitno,GROUP_CONCAT(drugname) AS DRUG_ALL,ROUND(SUM(unit*costprice),2) AS DRUG_PRICE_ALL,GROUP_CONCAT(drugcode24) AS DRUG_CODE24_ALL FROM visitdrug INNER JOIN cdrug ON visitdrug.drugcode=cdrug.drugcode WHERE drugtype IN ('01','04','05','10') AND cdrug.drugcode24 NOT IN (
        '410000000100000020110665',
        '420000002370000020111411',
        '410000000230000020111411',
        '420000002870000020111411',
        '410000000320000020111460',
        '420000003480000020111411',
        '420000003520000020311411',
        '420000003950000020111411',
        '410000000449125020111411',
        '410000000470000020110665',
        '420000004230000020111411',
        '420000004910000020111411',
        '420000005170000020311411',
        '420000005280000020111295'
        )
        GROUP BY visitno) bb
        ON temp_opd.VISITNO=bb.visitno
        SET temp_opd.DRUG_ALL=bb.DRUG_ALL,temp_opd.DRUG_PRICE_ALL=bb.DRUG_PRICE_ALL,temp_opd.DRUG_CODE24_ALL=bb.DRUG_CODE24_ALL";
          
        $sql8 = "UPDATE temp_opd INNER JOIN
        (SELECT visitno,GROUP_CONCAT(drugname) AS DRUG_HERB_ALL,ROUND(SUM(unit*costprice),2) AS DRUG_HERB_PRICE,GROUP_CONCAT(drugcode24) AS DRUG_HERB_CODE FROM visitdrug INNER JOIN cdrug ON visitdrug.drugcode=cdrug.drugcode WHERE drugtype IN ('01','04','05','10') AND cdrug.drugcode24 IN (
        '410000000100000020110665',
        '420000002370000020111411',
        '410000000230000020111411',
        '420000002870000020111411',
        '410000000320000020111460',
        '420000003480000020111411',
        '420000003520000020311411',
        '420000003950000020111411',
        '410000000449125020111411',
        '410000000470000020110665',
        '420000004230000020111411',
        '420000004910000020111411',
        '420000005170000020311411',
        '420000005280000020111295'
        )
        GROUP BY visitno) bb
        ON temp_opd.VISITNO=bb.visitno
        SET temp_opd.DRUG_HERB_ALL=bb.DRUG_HERB_ALL,temp_opd.DRUG_HERB_PRICE=bb.DRUG_HERB_PRICE,temp_opd.DRUG_HERB_CODE=bb.DRUG_HERB_CODE";
          
        $sql9 = "UPDATE temp_opd INNER JOIN
        (SELECT visitno,GROUP_CONCAT(drugname) AS PROCED_ALL,ROUND(SUM(unit*costprice),2) AS PROCED__PRICE_ALL,GROUP_CONCAT(visitdrug.drugcode) AS PROCED_CODE_ALL FROM visitdrug INNER JOIN cdrug ON visitdrug.drugcode=cdrug.drugcode WHERE drugtype IN ('02') AND visitdrug.drugcode NOT IN 
        ('2387030',
        '238703A',
        '238703B',
        '238703C',
        '238703D',
        '238703E',
        '238703F',
        '238703G',
        '238703H',
        '2377020',
        '2377021',
        '2277310',
        '2287310',
        '2277320',
        '2287320',
        '9007769',
        '9007800',
        '9007801',
        '9007802',
        '9007803',
        '9007808',
        '9007809',
        '9007810',
        '9007811',
        '9007812',
        '9007818',
        '9007819',
        '9007820',
        '9007821',
        '9007822',
        '9007830',
        '9007833',
        '9007834',
        '9007835',
        '9007836',
        '9007837',
        '9007838',
        '9007839',
        '9007900',
        '9007901',
        '9007902',
        '9007903',
        '9007904',
        '9007905',
        '9007906',
        '9007998',
        '9007999',
        '2330011',
        '0130000'
        )
        GROUP BY visitno) bb
        ON temp_opd.VISITNO=bb.visitno
        SET temp_opd.PROCED_ALL=bb.PROCED_ALL,temp_opd.PROCED_PRICE_ALL=bb.PROCED__PRICE_ALL,temp_opd.PROCED_CODE_ALL=bb.PROCED_CODE_ALL";
          
        $sql10 = "UPDATE temp_opd INNER JOIN
        (SELECT visitno,GROUP_CONCAT(drugname) AS DENTAL_ALL,ROUND(SUM(unit*costprice),2) AS DENTAL_PRICE_ALL,GROUP_CONCAT(visitdrug.drugcode) AS DENTAL_CODE_ALL FROM visitdrug INNER JOIN cdrug ON visitdrug.drugcode=cdrug.drugcode WHERE drugtype IN ('02') AND visitdrug.drugcode IN 
        ('2387030',
        '238703A',
        '238703B',
        '238703C',
        '238703D',
        '238703E',
        '238703F',
        '238703G',
        '238703H',
        '2377020',
        '2377021',
        '2277310',
        '2287310',
        '2277320',
        '2287320')
        GROUP BY visitno) bb
        ON temp_opd.VISITNO=bb.visitno
        SET temp_opd.DENTAL_ALL=bb.DENTAL_ALL,temp_opd.DENTAL_PRICE_ALL=bb.DENTAL_PRICE_ALL,temp_opd.DENTAL_CODE_ALL=bb.DENTAL_CODE_ALL";
          
        $sql11 = "UPDATE temp_opd INNER JOIN
        (SELECT visitno,GROUP_CONCAT(drugname) AS SUPPLIES_ALL,ROUND(SUM(unit*costprice),2) AS SUPPLIES_PRICE_ALL,GROUP_CONCAT(visitdrug.drugcode) AS SUPPLIES_CODE_ALL FROM visitdrug INNER JOIN cdrug ON visitdrug.drugcode=cdrug.drugcode WHERE drugtype IN ('03')
        GROUP BY visitno) bb
        ON temp_opd.VISITNO=bb.visitno
        SET temp_opd.SUPPLIES_ALL=bb.SUPPLIES_ALL,temp_opd.SUPPLIES_PRICE_ALL=bb.SUPPLIES_PRICE_ALL,temp_opd.SUPPLIES_CODE_ALL=bb.SUPPLIES_CODE_ALL";
          
        $sql12 = "UPDATE temp_opd INNER JOIN
        (SELECT visitno,GROUP_CONCAT(drugname) AS DOCTOR_FEE,ROUND(SUM(unit*costprice),2) AS DOCTOR_FEE_PRICE,GROUP_CONCAT(visitdrug.drugcode) AS DOCTOR_FEE_CODE FROM visitdrug INNER JOIN cdrug ON visitdrug.drugcode=cdrug.drugcode WHERE drugtype IN ('06','07')
        GROUP BY visitno) bb
        ON temp_opd.VISITNO=bb.visitno
        SET temp_opd.DOCTOR_FEE=bb.DOCTOR_FEE,temp_opd.DOCTOR_FEE_PRICE=bb.DOCTOR_FEE_PRICE,temp_opd.DOCTOR_FEE_CODE=bb.DOCTOR_FEE_CODE";
          
        $sql13 = "UPDATE temp_opd INNER JOIN
        (SELECT visitno,GROUP_CONCAT(drugname) AS PAN_THAI_ALL,ROUND(SUM(unit*costprice),2) AS PAN_THAI_PRICE,GROUP_CONCAT(visitdrug.drugcode) AS PAN_THAI_CODE FROM visitdrug INNER JOIN cdrug ON visitdrug.drugcode=cdrug.drugcode WHERE drugtype IN ('02') AND visitdrug.drugcode IN 
        ('9007769',
        '9007800',
        '9007801',
        '9007802',
        '9007803',
        '9007808',
        '9007809',
        '9007810',
        '9007811',
        '9007812',
        '9007818',
        '9007819',
        '9007820',
        '9007821',
        '9007822',
        '9007830',
        '9007833',
        '9007834',
        '9007835',
        '9007836',
        '9007837',
        '9007838',
        '9007839',
        '9007900',
        '9007901',
        '9007902',
        '9007903',
        '9007904',
        '9007905',
        '9007906',
        '9007998',
        '9007999')
        GROUP BY visitno) bb
        ON temp_opd.VISITNO=bb.visitno
        SET temp_opd.PAN_THAI_ALL=bb.PAN_THAI_ALL,temp_opd.PAN_THAI_PRICE=bb.PAN_THAI_PRICE,temp_opd.PAN_THAI_CODE=bb.PAN_THAI_CODE";
          
      

       
      
        
        if ($conn->query($sql) && $conn->query($sql2) && $conn->query($sql3) && $conn->query($sql4) && $conn->query($sql5)
        && $conn->query($sql6) && $conn->query($sql7) && $conn->query($sql8) && $conn->query($sql9) && $conn->query($sql10)
        && $conn->query($sql11) && $conn->query($sql12) && $conn->query($sql13) === TRUE) {
          
            
            $return_message = array();
            $row_array['message'] = "เพิ่มข้อมูลสำเร็จ";
            array_push($return_message,$row_array);
        
        
        }
       else {
            // echo "Error: " . $sql . "<br>" . $conn->error;
            $return_message = array();
            $row_array['message'] = "เพิ่มข้อมูลไม่สำเร็จ";
            array_push($return_message,$row_array);
            
        }


    
      
mysqli_close($conn);
	
echo json_encode($return_message);

?>