<?php
	
/*!
@file newexten.php
@brief Класс
@details Обработка события при смене статуса канала. Получаем данные по файлу аудиозаписи (MixMonitor),путь и название
*/

/*!
@author Алексей Алешин
@date 18.02.2022
@version 1.00
@brief Класс
@details Обработка события при смене статуса канала. Получаем данные по файлу аудиозаписи (MixMonitor),путь и название
*/


	class NewExten {
			function start($data){
				If($data->Application == "MixMonitor"){
						
								//Подучаем путь и название файла аудиозаписи
								$soundFile = explode(",", $data->AppData);
					
									$sound=$soundFile[0];
									try{
										$db = new Conn_db;
										$db = $db->connectDB();
										
									}catch (PDOException $e){
										print "Error ";			
									}
									$link="'".$data->Linkedid."'";
									try{
										$query="UPDATE Event_call SET sound_file = '".$sound."' WHERE Linkedid = $link";
										$stm=$db->query($query);
									}catch(PDOException $e){
										print "Error -  ".$e->getMessage();
									}
				
						}
			}
	}
	
	?>
