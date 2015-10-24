<?php
	require_once dirname(__FILE__) . '/_Loader.php';

	class FileManager
	{
		private $Directory = null;

		private $JsonOptions = 0;

		public function __construct($Directory)
		{
			$this->Directory = $Directory;

			$this->CreateDirectory();
		}

		public function CreateDirectory($Directory = null, $ParseRecursively = true)
		{
			if(is_array($Directory))
				return $this->CreateDirectoriesRecursively($Directory);
			elseif((strpos($Directory, '/') !== false || strpos($Directory, '\\') !== false) && $ParseRecursively)
				return $this->CreateDirectoriesRecursively(explode('/', str_replace('\\', '/', $Directory)));
			elseif(empty($Directory))
				$Path = $this->Directory;
			else
				$Path = $this->GetPath($Directory);

			if(!file_exists($Path))
				return mkdir($Path);

			return false;
		}

		private function CreateDirectoriesRecursively(Array $Directories)
		{
			$Directory = '';

			foreach($Directories as $CurrentDirectory)
			{
				$Directory .= $CurrentDirectory . DIRECTORY_SEPARATOR;

				$this->CreateDirectory($Directory, false);
			}

			return $this->IsDirectory(implode(DIRECTORY_SEPARATOR, $Directories));
		}

		public function Get($Filename, Array $Directories = array())
		{
			if(!empty($Filename))
			{
				$Path = $this->GetPath($Filename, $Directories);

				if($this->IsFile($Filename, $Directories))
				{
					if($this->IsReadable($Filename, $Directories))
					{
						return file_get_contents($Path);
					}
					else
						Std::Out("[Warning] [FileManager :: {$this->Directory}] {$Path} isn't readable");
				}
				else
					Std::Out("[Warning] [FileManager :: {$this->Directory}] {$Path} doesn't exist");
			}
			else
				Std::Out("[Warning] [FileManager :: {$this->Directory}] You can't use an empty filename");
			
			return false;
		}

		public function Set($Filename, $Data = null, $Append = false, Array $Directories = array())
		{
			if(!empty($Filename))
			{
				$Path = $this->GetPath($Filename, $Directories);

				if($Append)
				{
					$AppendData = $this->Get($Filename, $Directories);

					if($AppendData !== false)
						$Data = $AppendData . $Data;
					else
						Std::Out("[Warning] [FileManager :: {$this->Directory}] Can't append {$Path} data");
				}

				$Dirname = $this->GetDirname($Path);
				$Basename = $this->GetBasename($Filename);

				if($this->CreateDirectory($Dirname))
				{
					$ToWrite = strlen($Data);
					$Written = file_put_contents($Path, $Data);

					if($Written !== false)
					{
						if($Written === $ToWrite)
							return true;
						else
							Std::Out("[Warning] [FileManager :: {$this->Directory}] {$Written}/{$ToWrite} bytes written to {$Path}");
					}
					else
						Std::Out("[Warning] [FileManager :: {$this->Directory}] Can't create file {$Path}");
				}
				else
					Std::Out("[Warning] [FileManager :: {$this->Directory}] Can't create directory {$this->Directory}/{$Dirname} to save {$Basename}");
			}
			else
				Std::Out("[Warning] [FileManager :: {$this->Directory}] You can't use an empty filename");

			return false;
		}

		public function GetJson($Filename, Array $Directories = array())
		{
			$Data = $this->Get($Filename, $Directories);

			if($Data !== false)
			{
				$Data = json_decode($Data, true);

				if($Data !== null)
					return $Data;
				else
					Std::Out("[Warning] [FileManager :: {$this->Directory}} {$Path} isn't decodable");
			}

			return false;
		}

		public function SetJson($Filename, $Data, Array $Directories = array())
		{
			$Path = $this->GetPath($Filename, $Directories);

			$Data = json_encode($Data);

			if($Data !== false)
				return $this->Set($Filename, $Data, false, $Directories);
			else
			{
				$LogFilename = time() . '_warning_json';

				$this->Set($LogFilename, sprintf("Can't encode %s: %s", $Filename, var_export($Data, true)), false, array('data', 'log'));

				Std::Out("[Warning] [FileManager :: {$this->Directory}} Can't encode {$Path} (see data/{$LogFilename})");
			}

			return false;
		}

		# Path

		protected function GetPath($Filename, Array $Directories = array())
		{
			$Path = $this->Directory . DIRECTORY_SEPARATOR;

			if(!empty($Directories))
				$Path .= implode(DIRECTORY_SEPARATOR, $Directories) . DIRECTORY_SEPARATOR;

			if(!empty($Filename))
				$Path .= $Filename;

			return $Path;
		}

		# PathInfo

		public function GetDirname($Path)
		{ return pathinfo($Path, PATHINFO_DIRNAME); }

		public function GetBasename($Path)
		{ return pathinfo($Path, PATHINFO_BASENAME); }

		public function GetFilename($Path)
		{ return pathinfo($Path, PATHINFO_FILENAME); }

		public function GetExtension($Path)
		{ return pathinfo($Path, PATHINFO_EXTENSION); }

		# Exists

		public function Exists($Path, Array $Directories = array())
		{ return file_exists($this->GetPath($Path, $Directories)); }

		public function IsFile($Path, Array $Directories = array())
		{ return is_file($this->GetPath($Path, $Directories)); }

		public function IsDirectory($Path, Array $Directories = array())
		{ return is_dir($this->GetPath($Path, $Directories)); }

		# (Reada|Writa)bility

		public function IsReadable($Path, Array $Directories = array())
		{ return is_readable($this->GetPath($Path, $Directories)); }

		public function IsWritable($Path, Array $Directories = array())
		{ return is_writable($this->GetPath($Path, $Directories)); }

		# Scan

		public function Scan(Array $Directories = array(), $SortingOrder = SCANDIR_SORT_ASCENDING)
		{ return scandir($this->GetPath(null, $Directories), $SortingOrder); }

		# Glob

		public function Glob($Pattern, Array $Directories = array(), $Flags = 0)
		{ return glob($this->GetPath($Pattern, $Directories), $Flags); }

		# Directory

		public function GetDirectory()
		{ return $this->Directory; }

		# Json Options

		public function GetJsonOptions()
		{ return $this->JsonOptions; }

		public function SetJsonOptions($JsonOptions)
		{ return $this->JsonOptions = $JsonOptions; }
	}