<?php
//src/Data/DBConfig.php

namespace Webshop\Data;

class DBConfig
{
    public const DB_NAME = "prulariacom";
    public const DB_USERNAME = "root";
    public const DB_PASSWORD = "";
    public const DB_CONNECTION = "mysql:host=localhost;dbname=" . self::DB_NAME . ";charset=utf8";
}
