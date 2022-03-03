<?php

/*!
@file newchannel.php
@brief Класс
@details Обработка события при образовании нового канала при входящем и исходящем звонке 
*/

/*!
@author Алексей Алешин
@date 18.02.2022
@version 1.00
@brief Класс
@details Обработка события при образовании нового канала при входящем и исходящем звонке
*/

	
	class Newchannel {
			function start($data){
			
				//Получаем номер звонящего
			if(stripos($data->Channel) != false){
					preg_match("/\/(.*?)@/",$data->Channel,$id);
					$num = PhoneNumTransfer::phoneTransfer($id[1]);
			}else{
					$num= PhoneNumTransfer::phoneTransfer($data->CallerIDNum);
			}
	
 			try{
				$db = new Conn_db;
				$db = $db->connectDB();
			}catch (PDOException $e){
				print "Error";			
			}
			//Проверяем есть ли запись с таким значением
			$query="SELECT * FROM Event_call WHERE Linkedid = '".$data->Linkedid."'";
			$stm=$db->query($query);
			$stm->execute();
			$row=$stm->rowCount();
			if($data->ChannelState == 4){
				if($row==0){
					//Если записи нет вносим данные
					try{
						$exten=$data->Exten;
						$linkedid=$data->Linkedid;
						$uniqueid=$data->Uniqueid;
						$query="INSERT INTO Event_call (Exten, Linkedid, in_date, Uniqueid, SRC_NUM) VALUES ('".$exten."', $linkedid, getdate(), $uniqueid, $num)";
						$stm1=$db->query($query);
					}catch(PDOException $e){
						echo "Bad  channel -- " .  $e->getMessage();
					} 
				}
			}
			unset($db);
			
		}
	}
	
	?>
