<?php

$db = new SQLite3('db');

$db->exec('CREATE TABLE node (code CHAR NOT NULL UNIQUE, title TEXT NOT NULL, desc TEXT)');
$db->exec('CREATE TABLE row (code CHAR NOT NULL UNIQUE, title TEXT NOT NULL, desc TEXT)');
$db->exec('CREATE TABLE column (code CHAR NOT NULL UNIQUE, title TEXT NOT NULL, desc TEXT)');
$db->exec('CREATE TABLE nodes_version (matrix TEXT)');