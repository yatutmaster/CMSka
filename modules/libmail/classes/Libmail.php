<?php defined('SYSPATH') or die('No direct script access.');



class Libmail extends Kohana_Mail {

 

    public function  __construct ($charset = "", $ctencoding = '') {
	
	        return new Kohana_Mail($charset , $ctencoding);
	
	}

	
	public static function send_html ($body, $from, $to, $subject, $charset = 'utf-8') {
	
	     
	
	       $body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">'
                 . '<html><head>'
                 . '<meta http-equiv="content-type" content="text/html; charset='.$charset.'">'
                 . '<title></title>'
                 . '</head><body>' . $body
                 . '</body></html>';

           $m= new self($charset);
           $m->From($from);
           $m->To($to);
           $m->Subject($subject);
           $m->Body($body, 'html');
           $m->log_on(true);
           $m->Send();

		   return $m->status_mail;
	
	
	}



}