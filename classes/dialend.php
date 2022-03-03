<?php

/*!
@file dialend.php
@brief Класс
@details Обработка события при поднятии трубки - занято\отказано
*/

/*!
@author Алексей Алешин
@date 18.02.2022
@version 1.00
@brief Класс
@details Обработка события при поднятии трубки - занято\отказано
*/

//Получение данных и запись в БД
class DialEnd {

	
	
	 function start($data){
		try{
				$db = new Conn_db;
				$db = $db->connectDB();
			}catch (PDOException $e){
				print "Error";			
			}
			
		if($data->DialStatus=="ANSWER"){
				$link="'".$data->Linkedid."'";
				//print "$link\n";
				if($data->DestContext=="from-queue"){
							//Входящий внешний звонок
							$idNom=$data->DestChannel;
							preg_match('/Local\/(.*?)@/', $idNom,$id);
							$q="SELECT * FROM Operators WHERE GeographicNumber=" . $id[1];
							$res=$db->prepare($q);
							$res->execute();
							$resultat=$res->fetchAll();
							$src_num = PhoneNumTransfer::phoneTransfer($data->CallerIDNum);
							$src_num = empty($src_num) ? '0' : $src_num;
							$dest_num = PhoneNumTransfer::phoneTransfer($id[1]);
							$dest_num=empty($dest_num) ? '0' : $dest_num;
							$id_dest=empty($resultat[0]['ID_Operatora']) ? '0' : $resultat[0]['ID_Operatora'];	
							$query="UPDATE Event_call SET up_date=getdate(), SRC_NUM=$src_num, DEST_NUM=$dest_num, ID_DEST=$id_dest WHERE Linkedid= $link";
				}elseif($data->DestContext=="from-internal"){
							//Входящий внутренний звонок
							$idNum=$data->ConnectedLineNum;
							//Ищем данные по номеру
							$q="SELECT * FROM Operators WHERE GeographicNumber=" . $idNum;
							$res=$db->prepare($q);
							$res->execute();
							$resultat=$res->fetchAll();
							$src_num = PhoneNumTransfer::phoneTransfer($data->CallerIDNum);
							$src_num = empty($src_num) ? "0" : $src_num;
							$id_dest = empty($resultat[0]['ID_Operatora']) ? "0" : $resultat[0]['ID_Operatora'];	
							$query="UPDATE Event_call SET up_date=getdate(), SRC_NUM=$src_num, DEST_NUM=$idNum, ID_DEST=$id_dest WHERE Linkedid = $link";
				}elseif($data->Context == "macro-dialout-trunk"){
							//Исходящий внешний вызов
							$idNom=$data->ConnectedLineNum;
							$s=$data->Channel;
							preg_match('/\/(.*?)-/', $s,$id);
							$q="SELECT * FROM Operators WHERE GeographicNumber=" . $id[1];
							$res=$db->prepare($q);
							$res->execute();
							$resultat=$res->fetchAll();
							$src_num = PhoneNumTransfer::phoneTransfer($id[1]); 
							$dest_num = PhoneNumTransfer::phoneTransfer($data->ConnectedLineNum);
							$dest_num = empty($dest_num) ? "0" : $dest_num;
							$id_src = empty($resultat[0]['ID_Operatora']) ? "0" : $resultat[0]['ID_Operatora'];	
							$query="UPDATE Event_call SET up_date=getdate(), SRC_NUM= $src_num, DEST_NUM=$dest_num, ID_SRC=$id_src WHERE Linkedid = $link";
								

				}
				
				if($data->Linkedid == $data->Uniqueid){
					
					try{
						$stm1=$db->query($query);
						//$stm1->execute();
						//print_r($stm1);
					}catch(PDOException $e){
						echo "Bad dial -- " .  $e->getMessage();
					}
				}
			}	

		}
}		
		
?>
