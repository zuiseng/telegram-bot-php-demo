<?php
/**
 * @author      Akasha
 * @copyright   2020 (https://zuiseng.com)
 */
//获取sum
function Get_sum(){
  $db_connect= new mysqli('127.0.0.1','avbook','SjP5xDJCnrm22KCP','avbook');
  $sql="select count(*) from AV_VIDEO";//SQL查询语句
  $result=$db_connect->query($sql);
  while(@$row=mysqli_fetch_array($result)){
        $rows[]=$row;
     }
  return $rows[0][0];
}
function Get_today(){
  $today_aa = date("Y-m-d");
  $db_connect= new mysqli('127.0.0.1','avbook','SjP5xDJCnrm22KCP','avbook');
  $sql="select count(*) from AV_VIDEO where py_date = '".$today_aa."'";//SQL查询语句
  $result=$db_connect->query($sql);
  while(@$row=mysqli_fetch_array($result)){
        $rows[]=$row;
     }
  return $rows[0][0];
}
function v_list($wd){
  	$url = 'https://cj.okzy.tv/inc/api1s_subname.php?ac=list&pg=1&wd='.$wd;
	$xmls = file_get_contents($url);
	$xmls = str_replace('<![CDATA[', "", $xmls);
	$xmls = str_replace(']]>', "", $xmls);
    $xml =simplexml_load_string($xmls);
    $xmljson= json_encode($xml);
    $xml=json_decode($xmljson,true);
  	$video_list = $xml['list']['video'];
 	return $video_list;
}
function v_movie($id){
  	$url = 'https://cj.okzy.tv/inc/api1s_subname.php?ac=videolist&pg=&ids='.$id;
	$xmls = file_get_contents($url);
	$xmls = str_replace('<![CDATA[', "", $xmls);
	$xmls = str_replace(']]>', "", $xmls);
    $xml =simplexml_load_string($xmls);
    $xmljson= json_encode($xml);
    $xml=json_decode($xmljson,true);
  	$movie_list = $xml['list']['video'];
 	return $movie_list;
}
