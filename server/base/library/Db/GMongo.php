<?php

namespace Gear\Db;

use Gear\Db\Connecting;

class GMongo
{
	static $mongo;

	/**
	 * Establece la conexión a Mongo 
	 */
	static function checkConnection()
	{
		//si no hay una conexion mongo, lo crea
		if ( !isset( self::$mongo ) ) 
		{
			self::$mongo = Connecting::startConnection();
		} // end if
	}//end checkMysqli

	/**
	 * Obtiene un solo documento
	 *
	 * Collection del cual extraer
	 * @var collection
	 *
	 * Campos a recuperar
	 * @var field
	 *
	 * Condicional de la peticion
	 * @var conditional
	 */
	static function getRegister( $collection, $fields = array(), $conditional = array() )
	{
		self::checkConnection();

		return self::$mongo->selectCollection( $collection )->findOne( $conditional, $fields );
	} // end getRegister

	/**
	 * Obtiene la cantidad de documentos de acuerdo a la condición dada
	 *
	 * Collection del cual extraer
	 * @var collection
	 *
	 * Campos a recuperar
	 * @var field
	 *
	 * Condicional de la peticion
	 * @var conditional
	 */
	static function getRegisters( $collection, $fields = array(), $conditional = array(), $order_by = array() )
	{
		self::checkConnection();

		return self::$mongo->selectCollection( $collection )->find( $conditional, $fields )->sort(  $order_by );
	} // end getRegisters

	/**
	 * Actualiza un documento
	 *
	 * Collection del cual extraer
	 * @var collection
	 *
	 * Datos a actualizar
	 * @var update
	 *
	 * Condicional de la peticion
	 * @var conditional
	 *
	 * Permitir los upserts
	 * @var upsert
	 */
	static function updateRegister( $collection, $update = array(), $conditional = array(), $options = array() )
	{
		self::checkConnection();

		return self::$mongo->selectCollection( $collection )->update( $conditional, $update, $options );
	} // end updateRegister

} // end GMongo