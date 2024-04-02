<?php
/**
 * Plugin Name: Usul Buku
 * Plugin URI: -
 * Description: Usul Buku
 * Version: 1.0.0
 * Author: Drajat
 * Author URI: https://t.me/drajathasan
 */
use SLiMS\Plugins;
$plugins = Plugins::getInstance();

$plugins->registerMenu('opac', 'Usul Buku', __DIR__ . '/pages/usul_buku.inc.php');
$plugins->registerMenu('reporting', 'Laporan Usul Buku', __DIR__ . '/pages/laporan.inc.php');