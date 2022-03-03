<?php

/*!
@file hangup.php
@brief Класс
@details Обработка события при окончании звонка.
*/

/*!
@author Алексей Алешин
@date 18.02.2022
@version 1.00
@brief Класс
@details Обработка события при окончании звонка.
*/


class Hangup {
	
	 function start($data){
		 // Подключаемся к базе данных
		try{
				$db = new Conn_db;
				$db = $db->connectDB();
			}catch (PDOException $e){
				print "Error";			
			}

			if($data->Context == "macro-dial-one" OR $data->Context == "from-queue" OR $data->Context == "from-internal"){
				if(stripos($data->Channel,"@") == false){
					$idNum=$data->Channel;
					preg_match("/\/(.*?)-/", $idNum,$id);

				}else{
					$idNum=$data->Channel;
					preg_match("/\/(.*?)@/", $idNum,$id);
				}
				$hangup= PhoneNumTransfer::phoneTransfer($id[1]);
				try{
					$q = "UPDATE Event_call SET hangup_number=$hangup, hangup_date=getdate() WHERE Linkedid = '".$data->Linkedid."'";
					$stm=$db->query($q);
					//print "qqqq 111 --- $hangup\n";
					}catch(PDOException $e){
						echo "Bad hangup -- " .  $e->getMessage();
					}
			}else{
				$hangup= PhoneNumTransfer::phoneTransfer($data->CallerIDNum);
				try{
					$q = "UPDATE Event_call SET hangup_number=$hangup, hangup_date=getdate() WHERE Linkedid = '".$data->Linkedid."'";
					$stm=$db->query($q);
					//print "qqqq 222 --- $hangup\n";
					}catch(PDOException $e){
						echo "Bad hangup -- " .  $e->getMessage();
					}
			}


			$db=NULL;
		}
}		
		
?>
