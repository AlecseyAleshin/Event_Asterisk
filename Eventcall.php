<?php

/*!
@file Eventcall.php
@brief Исполняемый файл
@details Слушает порт Астериск, получает от него События
*/

/*!
@author Алексей Алешин
@date 18.02.2022
@version 1.00
@brief Класс
@details Слушает порт Астериск, получает от него События
*/

//Подключение ббиблиотек и классов

require_once '/var/lib/asterisk/agi-bin/callcenter/vendor/autoload.php';
require_once '/var/lib/asterisk/agi-bin/callcenter/config.php';
include "/var/lib/asterisk/agi-bin/phpagi.php";
require_once 'classes/Conn_db.php';
require_once 'classes/hangup.php';
require_once 'classes/newchannel.php';
require_once 'classes/newexten.php';
require_once 'classes/dialend.php';
require_once 'classes/blindtransfer.php';
require_once 'classes/attendedtransfer.php';
require_once 'classes/phonenumtransfer.php';

// Создаем слушателя Asterisk
$as = new AGI_AsteriskManager();

$res=$as->connect('127.0.0.1','admin', 'b5fc7dacefb0a217754ba396cd471847');
if($res == FALSE) {echo "Connection failed.\n";}elseif($res == TRUE){echo "Connection established.\n";} 

//Слушаем все события
$as->add_event_handler('*','dump_events');
//$as->add_event_handler('newchannel','dump_events');
//$as->add_event_handler('newexten','dump_events');
//$as->add_event_handler('dialend','dump_events');
//$as->add_event_handler('dialstate','dump_events');
//$as->add_event_handler('NewConnectedLine','dump_events');
//$as->add_event_handler('hangup', 'dump_events'); 
//$as->add_event_handler('*', 'dump_events'); 


$as->wait_response();  
$as->disconnect(); 

//Обработка Event поступающие от Asterisk
function dump_events($ecode,$data,$server,$port) {
		
	$data = (object)$data;
	//Обрабатываем события и если есть нужный класс, передаем управление
	if(class_exists($data->Event)){
		$data->Event::start($data);
	}
}

?>
