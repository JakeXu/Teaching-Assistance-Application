<?php
/**
 * Created by PhpStorm.
 * User: Wu
 * Date: 2016/11/28
 * Time: 08:59
 */
header('Content-type: application/json');
session_start();
// Connect database
include '../login/_include.php';
global $conn;
connectDB();
//Verify token
loginCheck($_SERVER['HTTP_X_ACCESS_TOKEN']);
//Get information
$title = test_input(mysqli_escape_string($conn, $_POST['title']));
$content = test_input(mysqli_escape_string($conn, $_POST['content']));
$author = test_input(mysqli_escape_string($conn, $_POST['author']));
$authority = test_input(mysqli_escape_string($conn, $_POST['authority']));
$time = date('y-m-d H:i:s',time());
$article_id = test_input(mysqli_escape_string($conn, $_POST['article_id']));
$teacher_id = $_SESSION['teacher_id'];
if($_SESSION['type']!=2){
    $result = array(
        "code" => 403,
        "msg" => "无效用户尝试操作",
        "res" => null
    );
    echo json_encode($result);
    exit;
}
$check =
//select *, count(distinct name) from table group by name
$query_result = mysqli_query($conn, "update article
                                     set title = '$title', content = '$content' , author = '$author' , authority = '$authority', time = '$time'
                                     WHERE art_id = '$article_id' and teacher_id = '$teacher_id';");
if($query_result){
    $result = array(
        "code" => 0,
        "msg" => "修改成功",
        "res" => array(
            'article_id' => $article_id,
            'title' => $title,
            'time' => $time,
            'content' => htmlspecialchars_decode($content),
            'author' => $author,
            'authority' => $authority,
            'token' => $_SESSION['token']
        )
    );
    echo json_encode($result);
}
else{
    $result = array(
        "code" => -1,
        "msg" => "修改失败",
        "res" => array()
    );
    echo json_encode($result);
}
mysqli_close($conn);
?>