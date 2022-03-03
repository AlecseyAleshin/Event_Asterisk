<?php

/*!
@file blindtransfer.php
@brief Класс
@details Обработка события безусловной переадресаций звонка.
*/

/*!
@author Алексей Алешин
@date 18.02.2022
@version 1.00
@brief Класс
@details Обработка события безусловной переадресаций звонка.
*/

//Получение данных
class BlindTransfer {
    function start($data){
        try{
            $db = new Conn_db;
            $db = $db->connectDB();
        }catch (PDOException $e){
            print "Error";			
        }
        $data=(object)$data;
        $transfer_from = $data->TransfererChannel;
        preg_match('/SIP\/(.*?)-/', $data->TransfererChannel,$id);

//Забпись данных в БД

        try{
        $q="UPDATE Event_call SET transfer_from = '".$id[1]."', transfer_to= '".$data->Extension."' WHERE Linkedid = '".$data->TransfererLinkedid."'";
        $stm=$db->query($q);
        //$res=$stm->fetchAll();
        }catch(PDOException $e){
            echo "Bad blind -- " .  $e->getMessage();
        }
       // print_r($res);

    }
}
?>
