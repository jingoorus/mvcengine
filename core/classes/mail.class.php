<?php
class Mailer {

	var $site_name = "Нескучный газ";
	var $from = "info@neskuchniy-gas.ru";
	var $to = "vlaskinstanislav@yandex.ru"/*"nethuntermail@gmail.com"*/;
	var $subject = "";
	var $message = "";
	var $header = "";
	var $additional_parameters = null;
	var $error = "";
	var $bcc = array ();
	var $mail_headers = "";
	var $html_mail = true;
	var $charset = 'UTF-8';
	var $send_error = FALSE;

	var $eol = "\n";

	var $mail_method = 'php';

	protected function compile_headers(){

		$this->subject = "=?" . $this->charset . "?b?" . base64_encode( $this->subject ) . "?=";
		if( $this->site_name ) {
			$from = "=?" . $this->charset . "?b?" . base64_encode( $this->site_name ) . "?=";
		} else $from = "";
		if( $this->html_mail ) {
			$this->mail_headers .= "MIME-Version: 1.0" . $this->eol;
			$this->mail_headers .= "Content-type: text/html; charset=\"" . $this->charset . "\"" . $this->eol;
		} else {
			$this->mail_headers .= "MIME-Version: 1.0" . $this->eol;
			$this->mail_headers .= "Content-type: text/plain; charset=\"" . $this->charset . "\"" . $this->eol;
		}
		if( count( $this->bcc ) ) {
			$this->mail_headers .= "Bcc: " . implode( ",", $this->bcc ) . $this->eol;
		}
		$this->mail_headers .= "From: \"" . $from . "\" <" . $this->from . ">" . $this->eol;
		$this->mail_headers .= "Return-Path: <" . $this->from . ">" . $this->eol;
		$this->mail_headers .= "X-Priority: 3" . $this->eol;
		$this->mail_headers .= "X-Mailer: PHP" . $this->eol;
	}

	public function send($subject, $message) {
		$this->subject = $subject;
		$this->message = $message;
		$this->message = str_replace( "\r", "", $this->message );
		$this->compile_headers();
		if( ($this->to) && ($this->from) && ($this->subject) ) {

				if( !@mail( $this->to, $this->subject, $this->message, $this->mail_headers, $this->additional_parameters )  ) {

					if( !@mail( $this->to, $this->subject, $this->message, $this->mail_headers)  ) {

						$this->smtp_msg = "PHP Mail Error.";
						$this->send_error = true;

					}

				} else echo 'success';
		}
		$this->mail_headers = "";
	}
}
?>
