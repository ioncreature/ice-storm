<?php
/**
	Отправка почты с помощью очередей сообщений
*/

require_once 'PHPMailer.class.php';


class QMailer {
	
	protected $mqcon = null;
	protected $queue = null;
	protected $exchange = null;

	public function __construct( ){
		try{		
			$this->mqcon = new AMQPConnection(array(
				'host' => AMQP_HOST,
				'vhost' => '/',
				'port' => AMQP_PORT,
				'login' => AMQP_USER,
				'password' => AMQP_PASS
			));
				
			
			$this->connect();
			
			// Declare a new exchange
			$this->exchange = new AMQPExchange( $this->mqcon );
			$this->exchange->declare( AMQP_EXCHANGE, AMQP_EX_TYPE_FANOUT );
			
			
			// Open queue
			$this->queue = new AMQPQueue( $this->mqcon );
			$this->queue->declare(AMQP_MAIL_QUEUE, AMQP_DURABLE);
			
			
			// Bind it on the exchange to routing.key
			$this->exchange->bind( AMQP_MAIL_QUEUE, AMQP_ROUTING_KEY );
		}
		catch( Exception $e ){
			die( $e->getMessage() );
		}
	}
	
	
	public function connect(){
		if ( ! $this->mqcon->isConnected() )
			$this->mqcon->connect();
	}

	
	public function __destruct(){
		if ( $this->mqcon )
			$this->mqcon->disconnect();
	}
	
	
	public function add( $from, $to, $message, $subject = "" ){
		$this->connect();
		$mess = array(
			'from' => $from,
			'to' => $to,
			'message' => $message,
			'subject' => $subject
		);
		// Publish a message to the exchange with a routing key
		$p = $this->exchange->publish( json_encode( $mess ), AMQP_ROUTING_KEY );
		
		return $p;
	}
	
	
	public function get(){
		$this->connect();
		
		$frame = $this->queue->get( AMQP_NOACK );
		
		// нет сообщений в очереди
		if ( $frame['count'] === -1 )
			return false;
		else
			return $frame;
	}
	
	
	public function send( $frame = false ){
		if ( !$frame )
			return false;
		else {
			try {
				$mes = json_decode( $frame['msg'], true );
				
				// отправка email
				$mail = new PHPMailer;
				$mail->From = $mes['from'];
				$mail->FromName = $mes['from'];
				$mail->CharSet = 'utf-8';
				$mail->Mailer = 'mail';
				$mail->AddAddress( $mes['to'] );
				$mail->Subject = $mes['subject'];
				$mail->Body = $mes['message'];
				$mail->Send();
				// Удаляем из очереди
				$this->mq->ack( $frame['delivery_tag'] );
				unset( $mail );
			}
			catch ( Exception $e ){
				// bla bla
			}
		}
	}
}