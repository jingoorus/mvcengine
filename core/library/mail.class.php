<?php
final class Mail
{
	protected static $from = "";

	protected static $to = "";

	protected static $subject = "";

	protected static $message = "";

	protected static $mail_headers = "";

	public static $site_name = null;

	public static $additional_parameters = null;

	public static $bcc = array ();

	public static $html_mail = true;

	public static $charset = 'UTF-8';

	public static $send_error = FALSE;

	public static $eol = PHP_EOL;

	private final function __construct() {}

	protected static function compile_headers(){

		self::$subject = "=?" . self::$charset . "?b?" . base64_encode( self::$subject ) . "?=";
		if( self::$site_name ) {
			$from = "=?" . self::$charset . "?b?" . base64_encode( self::$site_name ) . "?=";
		} else $from = "";
		if( self::$html_mail ) {
			self::$mail_headers .= "MIME-Version: 1.0" . self::$eol;
			self::$mail_headers .= "Content-type: text/html; charset=\"" . self::$charset . "\"" . self::$eol;
		} else {
			self::$mail_headers .= "MIME-Version: 1.0" . self::$eol;
			self::$mail_headers .= "Content-type: text/plain; charset=\"" . self::$charset . "\"" . self::$eol;
		}
		if( count( self::$bcc ) ) {
			self::$mail_headers .= "Bcc: " . implode( ",", self::$bcc ) . self::$eol;
		}
		self::$mail_headers .= "From: \"" . $from . "\" <" . self::$from . ">" . self::$eol;
		self::$mail_headers .= "Return-Path: <" . self::$from . ">" . self::$eol;
		self::$mail_headers .= "X-Priority: 3" . self::$eol;
		self::$mail_headers .= "X-Mailer: PHP" . self::$eol;
	}

	protected static function clear()
	{
		self::$mail_headers = "";
		self::$subject = "";
		self::$message = "";
		self::$from = "";
		self::$to = "";
		self::$html_mail = true;
	}

	public static function send($subject, $message, $from, $to)
	{
		self::$subject = $subject;

		self::$message = $message;

		self::$from = $from;

		self::$to = $to;

		self::compile_headers();

		if( (self::$to) && (self::$from) && (self::$subject) ) {

				if( !@mail( self::$to, self::$subject, self::$message, self::$mail_headers, self::$additional_parameters )  ) {

					if( !@mail( self::$to, self::$subject, self::$message, self::$mail_headers)  ) {

						self::$send_error = true;

					}

				}
		}
		self::clear();
	}
}
?>
