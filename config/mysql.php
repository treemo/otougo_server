<?php

include_once __DIR__ . '/../lib/mysql/DataBase.class.php';

DataBase::addConnexion('login', 'pass', 'table', 'otougo');
DataBase::setNomConnexionDefaut('otougo');