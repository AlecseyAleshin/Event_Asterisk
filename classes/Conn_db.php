<?php
/*!
@file Eventcall.php
@brief Класс
@details Подключение к базе данных
*/

/*!
@author Алексей Алешин
@date 18.02.2022
@version 1.00
@brief Класс
@details Подключение к базе данных
*/


class Conn_db
{
       public function connectDB(){

            require_once '/var/lib/asterisk/agi-bin/callcenter/config.php';
            global $dataBaseConnection;

            //Коннектор для работы с MSSQL сервером.
            $server = $dataBaseConnection->server;
            $userName = $dataBaseConnection->userName;
            $password = $dataBaseConnection->password;
            $queryTimeout = $dataBaseConnection->queryTimeout;

            $options = array(PDO::SQLSRV_ATTR_ENCODING=>PDO::SQLSRV_ENCODING_UTF8, "CharacterSet" => "UTF-8", PDO::ATTR_TIMEOUT => $queryTimeout);
            $conn = new PDO($server, $userName, $password, $options);
            $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // $conn = new PDO("sqlsrv:Server=192.168.255.185;Database=СontactСenter_old", 'sa', 'Rbgcthdbc098', $options); //Подключаемся к базе
            
            
            return $conn;
        }
}
