<?php

//fetch_comment.php

$connect = new PDO('mysql:host=localhost;dbname=testing', 'root', '');

$query = "
SELECT * FROM tbl_comment 
WHERE parent_comment_id = '0' 
ORDER BY comment_id DESC
";

$statement = $connect->prepare($query);

$statement->execute();

$result = $statement->fetchAll();
$output = '';
foreach($result as $row)
{
 $output .= '
 <div class="panel panel-default" style="font-size:22px; text-align:left; background: #222; border: 1px solid lightgrey; border-radius: 10px; padding-left: 6px; margin-top: 30px;">
  <div class="panel-heading">By <b  style="color:mediumspringgreen">'.$row["comment_sender_name"].'</b> on <i style="font-size:18px">'.$row["date"].'</i></div>
  <div class="panel-body" style="margin-left:30px;">'.$row["comment"].'</div>
  <div class="panel-footer" align="right" style="margin-top: -50px;"><button type="button" class="btn btn-default reply" id="'.$row["comment_id"].'">Reply</button></div>
 </div>
 ';
 $output .= get_reply_comment($connect, $row["comment_id"]);
}

echo $output;

function get_reply_comment($connect, $parent_id = 0, $marginleft = 0)
{
 $query = "
 SELECT * FROM tbl_comment WHERE parent_comment_id = '".$parent_id."'
 ";
 $output = '';
 $statement = $connect->prepare($query);
 $statement->execute();
 $result = $statement->fetchAll();
 $count = $statement->rowCount();
 if($parent_id == 0)
 {
  $marginleft = 0;
 }
 else
 {
  $marginleft = $marginleft + 48;
 }
 if($count > 0)
 {
  foreach($result as $row)
  {
   $output .= '
   <div class="panel panel-default" style="margin-left:'.$marginleft.'px; font-size:22px; text-align:left; background: #222; border: 1px solid lightgrey; border-radius: 10px; padding-left: 6px; margin-top: 30px;">
    <div class="panel-heading">By <b style="color:mediumspringgreen">'.$row["comment_sender_name"].'</b> on <i style="font-size:18px">'.$row["date"].'</i></div>
    <div class="panel-body"  style="margin-left:30px;">'.$row["comment"].'</div>
    <div class="panel-footer" align="right" style="margin-top: -50px;"><button type="button" class="btn btn-default reply" id="'.$row["comment_id"].'">Reply</button></div>
   </div>
   ';
   $output .= get_reply_comment($connect, $row["comment_id"], $marginleft);
  }
 }
 return $output;
}

?>
