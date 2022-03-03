<?php

/*!
@file phonenumtransfer.php
@brief Класс
@details Нормализациыя номера телефона
*/

/*!
@author Алексей Алешин
@date 18.02.2022
@version 1.00
@brief Класс
@details Нормализациыя номера телефона
*/


class PhoneNumTransfer {
    function phoneTransfer($data){
    $telNum=$data;
	$telNum=preg_replace('/[^\p{L}\p{N}\s]/u', '', $telNum);
	if(strlen($telNum)==11 AND substr($telNum,0,-10)==8){
	$telNum=substr_replace($telNum,'7',0,1);
	//print "\n$tel ---- $tel\n";
	}
    return $telNum;
}
}
?>
