<?

/**
 * The Config Class (Registry Pattern)
 * Author: Dom1n1k
 * Licence: GNU
 */

final class config implements ArrayAccess {
	
	private 
			$aConfig;
			
	private static
			$oInstance;
	
	public function __construct()
	{
		$this -> aConfig = parse_ini_file('config.ini', true);
	}
	
	public function singleton()
	{
		if (self::$oInstance == null)
			self::$oInstance = new self;
			
		return self::$oInstance;
	}
	
	public function offsetset($sName, $sValue)
	{
		return $this -> aConfig [$sName] = $sValue;
	}
	
	public function offsetexists($sName)
	{
		return array_key_exists($sName, $this->aConfig);
	}
	
	public function offsetget($sName)
	{
		return $this -> aConfig [$sName];
	}
	
	public function offsetunset($sName)
	{
		if ($this -> aConfig [$sName] != '')
			unset($this -> aConfig[$sName]);
	}
}

/**
 * The Basic Class of Matix Framework
 * Author: Dom1n1k
 * Licence: GNU
 */
 
final class basic {

	private static
					$iStartTime,
					$iExTime;
					
	public function __construct()
	{
		self::$iStartTime = $this -> _time();
	}
	
	public function setController($oRouter)
	{
		$oController = new frontcontroller($oRouter);
	}
	
	public function getRouter()
	{
		return new url;
	}
	
	public function __destruct()
	{
		$oConfig = config::singleton();
		$oConfig['time'] = $this -> _time() - self::$iStartTime;
	}
	
	private function _time()
	{
		$aTime = explode(' ', microtime());
		return (float) $aTime[0] + (float) $aTime[1];
	}
}

/**
 * The Professional Autoloader of Class
 */

class core {
        const 
              temp = 'temp.tmp';
			  
		private static
			  $oInstance;
			  
			  
		public function singleton()
		{
			if (self::$oInstance == '')
				self::$oInstance = new self;
			
			return self::$oInstance;
		}

        public function scan($sWhat)
        {
        
		$aDir = scandir($sWhat, 0);
		$aFiles = array();

		foreach ($aDir as $sFile)
		{
			if ($sFile != '.') {
				if ($sFile != '..')
				{

			if (is_dir($sWhat.$sFile))
			   $aFiles [$sFile] = $this->scan($sWhat.$sFile.'/');
   			else {
			   preg_match('#(.+).php#imU', $sFile, $sFileName);
			   $aFiles [$sFileName[1]] = $sWhat.$sFile;
                        }
                        
			    }
			   }
		}
		
		foreach ($aFiles as $sName => $aDirs)
		{
		 	if (is_array($aDirs))
			{
				foreach ($aDirs as $sName => $sFile)
				$_aFile [$sName] = $sFile;

			}else{
		 	      $_aFile [$sName] = $aDirs;
		 	}
		}


		return $_aFile;
        }

        public function set()
        {
			$oConfig = config::singleton();
			
			$aFiles = serialize($this->scan($oConfig[dirs][core]));

			file_put_contents($oConfig[dirs][core].self::temp, $aFiles);
        }
        
        public function get($sName)
        {
		   $oConfig = config::singleton();
			
	       if(is_file($oConfig[dirs][core].self::temp))
	       {
	       	
	       	$aFiles = unserialize(file_get_contents($oConfig[dirs][core].self::temp));
			
			if ($aFiles[$sName] == '')
		{
  		        $this->set();
  		        
  		        $aFiles = unserialize(file_get_contents($oConfig[dirs][core].self::temp));

  		        if ($aFiles[$sName] == '')
  		           throw new exception ('There is no '.$sName.' File in Our Repository!');
  		        else
			   $this->get($sName);

		}else{
			include($aFiles[$sName]);
			
			return true;
		}

	       }else{
			$this->set();
               }

               return false;
        }
}


/**
 * The Autoloader of Dom1n1k Framework
 * Author: Dom1n1k
 * Licence: GNU
 */

function __autoload($oClass) {
        $oCore = core::singleton();
        $oCore->get($oClass);
}
 
?>