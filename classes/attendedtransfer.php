<?php
/*!
@file attendedtransfer.php
@brief Класс
@details Обработка события перевода звонка с консультацией кому переведен звонок.
*/

/*!
@author Алексей Алешин
@date 18.02.2022
@version 1.00
@brief Класс
@details Обработка события перевода звонка с консультацией кому переведен звонок.
*/

//Получение данных
class AttendedTransfer {
    function start($data){
        try{
            $db = new Conn_db;
            $db = $db->connectDB();
        }catch (PDOException $e){
            print "Error";			
        }
        $data=(object)$data;
        
//Запись данных в БД

        try{
            $transfer_from = $data->SecondTransfererCallerIDNum;
            $transfer_to= $data->SecondTransfererConnectedLineNum;
        $q = "UPDATE Event_call SET transfer_from = $transfer_from, transfer_to=$transfer_to WHERE Linkedid = '".$data->OrigTransfererLinkedid."'";
        $res=$db->prepare($q);
        $res->execute();
       $q="DELETE FROM Event_call WHERE Linkedid = '".$data->TransferTargetLinkedid."'";
       $res=$db->prepare($q);
       $res->execute();
       //print_r($res);
        }catch(PDOException $e){
            echo "Atten  -". $e->getMessage();
        }
    
    }
}

?>
