<?php
  $conn = mysqli_connect("localhost","root","");
  mysqli_select_db($conn,"todo");
  $action = $_REQUEST['action'];

  switch ($action) {
    case 'get':
      get_all_tasks();
      break;
    case 'add':
      add_task();
      break;
    case 'del':
      del_task();
      break;
    case 'done':
      done_task();
      break;
    case 'undo':
      undo_task();
      break;
    default:
      unknown_action();
  }
  function get_all_tasks(){
    global $conn;
    $result = mysqli_query($conn,"select * from tasks");
    $tasks = array();
    while ($rows = mysqli_fetch_assoc($result)) {
      $tasks[] = $rows;
    }
    echo json_encode($tasks);

  }
  function add_task(){
    global $conn;
    $subject = $_POST['subject'];
    $result = mysqli_query($conn,"insert into tasks(subject,created_date) values('$subject',now())");
    if($result){
      $id = mysql_insert_id();
      echo json_encode(array("err"=>0,"id"=>$id));
    }else{
      echo json_encode(array("err"=>1,"msg"=> "unable to insert task"));
    }
  }
  function del_task(){
    global $conn;
    $id = $_POST['id'];
    $result = mysqli_query($conn,"delete from tasks where id=$id");
    if($result){
      echo json_encode(array("err"=>0));
    }else{
      echo json_encode(array("err"=>1,"msg"=>"unable to delete these task"));
    }
  }
  function done_task(){
    global $conn;
    $id = $_POST['id'];
    $result = mysqli_query($conn,"update tasks set status=1 where id = $id");
    if($result){
      echo json_encode(array("err"=>0));
    }else{
      echo json_encode(array("err"=>1, "msg" => "unable to update task"));
    }
  }
  function undo_task(){
    global $conn;
    $id = $_POST['id'];
    $result = mysqli_query($conn,"update tasks set status=0 where id = $id");
    if($result){
      echo json_encode(array("err"=>0));
    }else{
      echo json_encode(array("err"=>1,"msg"=>"unable to update task"));
    }
  }
  function unknown_action(){
    echo json_encode(array('err' => 1,'msg'=>"unknown action" ));
  }
 ?>
