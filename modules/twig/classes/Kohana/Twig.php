<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kohana_Twig extends Kohana_View {

// Twig instance
	protected $_twig;
	// Default template filename extension
	protected $_template_ext = 'html';
	
	// Paths for template loading
	private static $_paths;
	
	
	/**
	 * Constructor. Overriding
	 * 
	 * @param string $file
	 * @param array $data
	 */
public function __construct($file = NULL, array $data = NULL)
	{
		if(Kohana_Twig::$_paths === NULL) 
		{
			Kohana_Twig::get_paths();
		}
		
		$params = Kohana::$config->load('twigconf')->as_array();
		$this->_template_ext = Arr::get($params, 'template_ext', $this->_template_ext);
		
		$loader = new Twig_Loader_Filesystem(Kohana_Twig::$_paths);
		$this->_twig = new Twig_Environment($loader, $params);
		
		parent::__construct($file, $data);
	}
	
	/**
	 * Factory method. Return instance. Overriding
	 * 
	 * @method factory
	 * @param string $file
	 * @param array $data
	 */
	public static function factory($file = NULL, array $data = NULL)
	{
		return new Twig($file, $data);
	}
	
	/**
	 * Set filename. Overriding
	 * 
	 * @method set_filename
	 * @param string $file
	 */
	public function set_filename($file)
	{
		$this->_file = $file;
		return $this;
	}
	
	/**
	 * Return view paths of application and all modules
	 * 
	 * @method get_paths
	 */
	public static function get_paths()
	{
		Kohana_Twig::$_paths = array(APPPATH . 'views');
		
		foreach (Kohana::modules() as $path)
		{
			$path = $path . 'views';
			if (is_dir($path))
			{
				Kohana_Twig::$_paths[] = $path;
			}
		}
	}

	/**
	 * Overriding render method
	 * 
	 * @method render
	 * @param string $file
	 */
	public function render($file = NULL)
	{
		if ($file !== NULL)
		{
			$this->set_filename($file);
		}

		if (empty($this->_file))
		{
			throw new View_Exception('You must set the file to use within your view before rendering');
		}
		
		// Combine local and global data
		return $this->_twig
					->loadTemplate($this->_file . '.' . $this->_template_ext)
					->render(Arr::merge(Kohana_Twig::$_global_data, $this->_data));
	}
}
