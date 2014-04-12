<?php

include_once __DIR__ . '/../lib/mysql/DataBase.class.php';

DataBase::addConnexion('root', '456456lol', 'otougo', 'otougo');
DataBase::setNomConnexionDefaut('otougo');