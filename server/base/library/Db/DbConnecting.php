<?php
//Creado por Valentin Sánchez
//21/07/2013

namespace Gear\Db;

class Connecting
{
	static $dbEngine;
	static $host;
	static $user;
	static $password;
	static $db;
		
	static function startConnection()
	{
		switch ( self::$dbEngine ) 
		{
			case 'mysql':
				if( !isset( self::$port ) )
					$connection = new \mysqli( self::$host, self::$user, self::$password, self::$db );
				else
					$connection = new \mysqli( self::$host, self::$user, self::$password, self::$db, self::$port );
		
				$connection->set_charset( 'utf8' );
				
				if( mysqli_connect_error() )
					echo 'Error in the connection';
				break;
			
			case 'mongo':
				if( !isset( self::$port ) )					
					$connectionString = sprintf('mongodb://%s:%d', self::$host, 27017 );
				else
					$connectionString = sprintf('mongodb://%s:%d', self::$host, self::$port );

				$connection = new \Mongo( $connectionString );

				$connection = $connection->selectDB( self::$db );
				break;
		} // end switch
			
		return $connection;
			
	}//end startConnection


	static function setConnectData( &$data )
	{
		self::$dbEngine = $data[ 'Engine' ];
		self::$host = $data[ 'Host' ];
		self::$user = $data[ 'User' ];
		self::$password = $data[ 'Password' ];
		self::$db = $data[ 'DB' ];

		if( isset( $data[ 'Port' ] ) ) // Si se establece un puerto personalizado
			self::$port = (int) $data[ 'Port' ];
	} // end setConnecData
	
}//end Connecting
