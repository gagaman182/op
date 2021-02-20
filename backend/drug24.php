<?php
 header('Content-Type:application/json');
 header('Access-Control-Allow-Origin: *');
 header('Access-Control-Allow-Methods: POST');
 header('Access-Control-Allow-Headers: Acess-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization');
 $data = json_decode(file_get_contents('php://input'),true);
 $monthstartstore = $data['monthstartstore'];
 $monthendstore = $data['monthendstore'];

   
	 include 'conn.php';




 $sql = "SELECT
x.drugcode,
 x.drugname,
 x.drugcode24,
 sum(x.unit) as unit 
 FROM
 (
   SELECT
     visit.visitno,
     visit.visitdate,
     visit.username,
     USER .officertype,
     cright.rightcode,
     cright.rightname,
     cright.rightgroup,
     crightgroup.rightgroupname,
     visitdrug.drugcode,
     cdrug.drugname,
     visitdrug.unit,
     cdrug.drugcode24,
     cdrug.drugtype,
     cdrug.drugflag,
     visit.flagservice
   FROM
     visit
   LEFT JOIN USER ON visit.username = USER .username
   LEFT JOIN cright ON visit.rightcode = cright.rightcode
   LEFT JOIN crightgroup ON cright.rightgroup = crightgroup.rightgroupcode
   LEFT JOIN visitdrug ON visit.visitno = visitdrug.visitno
   INNER JOIN cdrug ON visitdrug.drugcode = cdrug.drugcode
   WHERE
   visitdate between  '".$monthstartstore."' and  '".$monthendstore."'
   AND visit.flagservice < '4'
   AND cdrug.drugflag = '1'
   AND cdrug.drugtype = '01'
   AND rightgroupcode IN ('3', '4')
 ) x
 WHERE
 (	x.drugcode24 LIKE '203030150018341120381169'
     OR x.drugcode24 LIKE '124813000003620120381421'
     OR x.drugcode24 LIKE '124813000003521120381421'
     OR x.drugcode24 LIKE '100736000004320120481602'
     OR x.drugcode24 LIKE '100736000004000120481602'
     OR x.drugcode24 LIKE '100439000004021220381421'
     OR x.drugcode24 LIKE '101705000003040270281057'
     OR x.drugcode24 LIKE '100618000003750120381168'
     OR x.drugcode24 LIKE '100571000003122120381551'
     OR x.drugcode24 LIKE '100605000003361120381562'
     OR x.drugcode24 LIKE '100619000003521120381421'
     OR x.drugcode24 LIKE '101070000003841120381247'
     OR x.drugcode24 LIKE '100590000004511220381421'
     OR x.drugcode24 LIKE '101444000003521120381040'
     OR x.drugcode24 LIKE '100653133003850120381081'
     OR x.drugcode24 LIKE '100653133003620120381169'
     OR x.drugcode24 LIKE '100653133003750120381169'
     OR x.drugcode24 LIKE '101063000003850120381506'
     OR x.drugcode24 LIKE '101445149134990210181184'
     OR x.drugcode24 LIKE '101445149174990210181184'
     OR x.drugcode24 LIKE '201100110017657110181415'
     OR x.drugcode24 LIKE '101445149174990210181415'
     OR x.drugcode24 LIKE '100658000003620120381506'
     OR x.drugcode24 LIKE '100658000003521120681247'
     OR x.drugcode24 LIKE '100658000003721120281421'
     OR x.drugcode24 LIKE '124889000003850121781258'
     OR x.drugcode24 LIKE '101434000004590120381445'
     OR x.drugcode24 LIKE '101434000004493121781506'
     OR x.drugcode24 LIKE '100440000004021220381169'
     OR x.drugcode24 LIKE '100667000003721120581685'
     OR x.drugcode24 LIKE '140539000003771120381421'
     OR x.drugcode24 LIKE '100443000003620120381506'
     OR x.drugcode24 LIKE '100443000003841120381506'
     OR x.drugcode24 LIKE '105573000003620121781258'
     OR x.drugcode24 LIKE '105573000003841121781258'
 )
 GROUP BY
 x.drugcode,
 x.drugname ";


$return_arr = array();

if ($result = mysqli_query( $conn, $sql )){
    while ($row = mysqli_fetch_assoc($result)) {
	
     array_push($return_arr,$row);
   }
 }

mysqli_close($conn);

echo json_encode($return_arr);








?>
