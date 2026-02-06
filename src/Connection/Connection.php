<?php

namespace App\ControleFinanceiro\Connection;

// Conexão com DB utilizando já a VM da Oracle como base

return [
    'host' => '144.22.147.184', // O IP Público da sua VM Oracle
    'port' => '3306',          // Porta padrão do MySQL
    'db' => 'appCashControl',
    'user' => 'facilitte', // Não use o 'root' para conexões externas!
    'pass' => '+9;b|Y2\-<DNBC/0+£m9',
    'charset' => 'utf8mb4',
];