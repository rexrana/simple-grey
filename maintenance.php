<?php
/**
 * The template for site maintenance mode.
 *
 @package Simple Grey
 */

$protocol = $_SERVER["SERVER_PROTOCOL"];
if ( 'HTTP/1.1' != $protocol && 'HTTP/1.0' != $protocol )
$protocol = 'HTTP/1.0';
header( "$protocol 503 Service Unavailable", true, 503 );
header( 'Content-Type: text/html; charset=utf-8' );

$context = Timber::get_context();

Timber::render( array( 'maintenance.twig' ), $context );
