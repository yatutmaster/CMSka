<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Main extends Controller_Template {

  public $template = 'main/template';

  public function before()
  {
    parent::before();
	
		
	
	    $this->template->pid = '';
	    $this->template->title = '';
	    $this->template->pkeys = '';
	    $this->template->pdesc = '';
	    $this->template->urlsite = URL::site();
		$this->template->crumbs = [];
	    $this->template->content = '';

 
  }

 
  
  
  
	
  public function action_index()
  {
     /////////////////////////////redirect
      // $rfrom =  $this->request->uri();
    
	  // $redir = DB::select('to')->from('redirects')->where('visible','=',1)->where('from','=',$rfrom)->execute()->as_array();
	
	  // if(isset($redir[0]))
              // $this->redirect($redir[0]['to']);
    /////////////////////////////////redirect
    
      $urlsite = URL::site();
	  $this->template->pid = 1;
	  $this->template->title = '';
	  $this->template->pkeys = '';
	  $this->template->pdesc = '';
	  

      $this->template->content = Twig::factory('main/content')
	                                                   ->set('urlsite',$urlsite);
  
  }
  
  
  
  ///////////////////////////////////////////////qapcha
  public function action_set_qap()
  {
	  

       $aResponse['error'] = false;
       if(isset($_POST['action']) && isset($_POST['qaptcha_key']))
        {
            $qap = false;
					   
            if(htmlentities($_POST['action'], ENT_QUOTES, 'UTF-8') == 'qaptcha')
           {
                       $qap = $_POST['qaptcha_key'];
                       echo json_encode($aResponse);
           }         
           else
           {
                       $aResponse['error'] = true;
                       echo json_encode($aResponse);
            }
        }
        else
        {
             $aResponse['error'] = true;
            echo json_encode($aResponse);
         }
		 
	    Session::instance()->set('qaptcha_key',$qap);
	    exit;
	  
  }
  ///////////////////////////////////////////////qapcha
   
   
	/**
	 * Not Found.
	 */
	public function action_404()
	{

	   $this->template->content = Twig::factory('error_404');
	
	}
  
  	/**
	 * Not authorized
	 *
	 * You have to authenticate to access this resource.
	 */
	public function action_401()
	{

	}

	/**
	 * Forbidden
	 *
	 * Authentication will not change anything.
	 */
	public function action_403()
	{

	}


	/**
	 * Internal Server Error.
	 */
	public function action_500()
	{

	}

	/**
	 * Service Unavailable.
	 */
	public function action_503()
	{

	}

	public function after()
	{
		

		parent::after();
	}

  
 
  
  
} 
