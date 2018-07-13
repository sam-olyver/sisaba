<?php
/**
 * Configuração geral
 */

 /*
 * Version
 * versao, sub-versao, alteracao, mes, ano
 */
define('VERSION', '1.0.6.18');
 
 //link github
define('GIT_URL', 'https://github.com/sam-olyver');
 
// Caminho para a raiz
define( 'ABSPATH', dirname( __FILE__ ) );

// Caminho para a pasta de uploads
define( 'UP_ABSPATH', ABSPATH . '/assets/_uploads/' );

// URL da home
define( 'HOME_URI',	'/sisaba/' );

define( 'VIEW_DIR', HOME_URI . 'views/dashboard/');

//freenom (gmail) - ip

//Nome do HOST do 
define( 'HOSTNAME', '10.110.103.20');

// Nome do DB
define( 'DB_NAME', 'sisaba' );

// Usuário do DB
define( 'DB_USER', 'samuel' );

// Senha do DB
define( 'DB_PASSWORD', 'b0168@ijb#7' );

// Charset da conexão PDO
define( 'DB_CHARSET', 'utf8' );

// Se você estiver desenvolvendo, modifique o valor para true
define( 'DEBUG', false );

/**
 * Não edite daqui em diante
 */

// Carrega o loader, que vai carregar a aplicação inteira
require_once ABSPATH . '/loader.php';
?>