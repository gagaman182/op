<?php
	 header('Access-Control-Allow-Origin: *');
   
	 include 'conn.php';


 $sql = "SELECT
 *, concat(
   DATE_FORMAT(DATE_VISIT, '%d'),
   '-',
   DATE_FORMAT(DATE_VISIT, '%m'),
   '-',
   DATE_FORMAT(DATE_VISIT, '%YYYY')+ 543
 )AS DATE_VISIT_THAI,  CONCAT(temp_opd.HOSPCODE,'-',chospital.hosname) AS HOSPCODE_FULL
FROM
 temp_opd
INNER JOIN chospital on temp_opd.HOSPCODE = chospital.hoscode ";


$return_arr = array();

if ($result = mysqli_query( $conn, $sql )){
    while ($row = mysqli_fetch_assoc($result)) {
	
     array_push($return_arr,$row);
   }
 }

mysqli_close($conn);

echo json_encode($return_arr);








?>
