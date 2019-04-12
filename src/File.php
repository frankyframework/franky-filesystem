<?php
namespace  Franky\Filesystem;

class File{

  public function mkdir($path = ''){
      if(empty($path))
      {
          return false;
      }

      $part = explode("/",$path);
      $new_part = array();
      foreach ($part as $p) {
        if(!empty($p))
        {
          $new_part[] = $p;

          $dir = "/".implode("/",$new_part);
          //echo $dir."<br />";
          if(!is_dir($dir))
          {
              if(!mkdir($dir,0777))
              {
                return false;
              }
          }
        }

      }

      return true;

  }

  public function PermisosArchivo($file)
  {
  	$perms = fileperms($file);

  	if (($perms & 0xC000) == 0xC000)
  	{
  		// Socket
  		 $info = 's';
  	}
  	elseif (($perms & 0xA000) == 0xA000)
  	{
  		// Enlace Simb�lico
  		$info = 'l';
  	}
  	elseif (($perms & 0x8000) == 0x8000)
  	{
  		// Regular
  		$info = '-';
  	}
  	elseif (($perms & 0x6000) == 0x6000)
  	{
  		// Bloque especial
  		$info = 'b';
  	}
  	elseif (($perms & 0x4000) == 0x4000)
  	{
      	// Directorio
      	$info = 'd';
  	}
  	elseif (($perms & 0x2000) == 0x2000)
  	{
  		// Caracter especial
  		$info = 'c';
  	}
  	elseif (($perms & 0x1000) == 0x1000)
  	{
      	// Pipe FIFO
      	$info = 'p';
  	}
  	else
  	{
      	// Desconocido
      	$info = 'u';
  	}
  	// Dueño
  	$info .= (($perms & 0x0100) ? 'r' : '-');
  	$info .= (($perms & 0x0080) ? 'w' : '-');
  	$info .= (($perms & 0x0040) ? (($perms & 0x0800) ? 's' : 'x' ) : (($perms & 0x0800) ? 'S' : '-'));
  	// Grupo
  	$info .= (($perms & 0x0020) ? 'r' : '-');
  	$info .= (($perms & 0x0010) ? 'w' : '-');
  	$info .= (($perms & 0x0008) ? (($perms & 0x0400) ? 's' : 'x' ) : (($perms & 0x0400) ? 'S' : '-'));
  	// Mundo
  	$info .= (($perms & 0x0004) ? 'r' : '-');
  	$info .= (($perms & 0x0002) ? 'w' : '-');
  	$info .= (($perms & 0x0001) ? (($perms & 0x0200) ? 't' : 'x' ) : (($perms & 0x0200) ? 'T' : '-'));
  	return $info;

  }

  public function getFiles($path,$type="file")
  {
      $files = array();
      $file = array();
      if (is_dir($path)) {
                  if ($dh = opendir($path)) {
                  while (($files = readdir($dh)) !== false) {
                      if($type == "dir")
                      {
                          if (is_dir($path ."/". $files) && $files != ".." && $files != ".")
                          {
                               $file[] = $files;
                          }
                      }
                      else
                      {
                          if (!is_dir($path ."/". $files) && $files != ".." && $files != ".")
                          {
                               $file[] = $files;
                          }
                      }

                  }
                  closedir($dh);
                  }
      }
       natcasesort($file);
      return $file;
  }


  public function getAllFiles($path)
  {
      $files = array('dir' => array(),'file' => array());

      $files['dir'][] = $path."/";

      $contenido = array();

      $result = $this->getFiles($path,'dir');

      foreach($result as $file)
      {
          $contenido[] = array($file,'directory');
      }
      $result = $this->getFiles($path);
      foreach($result as $file)
      {
          $contenido[] = array($file,'file');
      }


      if(!empty($contenido))
      {
          foreach($contenido as $nodo)
          {
              if($nodo[1] == "file")
              {
                  $files['file'][] = $path."/".$name."/".$nodo[0];
              }
              else
              {
                  $_files = $this->getAllFiles($path."/".$nodo[0]);
                  $files['dir'] = array_merge($files['dir'], $_files['dir']);
                  $files['file'] = array_merge($files['file'], $_files['file']);
              }
          }
      }

      return $files;

  }

  public function rm($path)
  {
      if(is_dir($path))
      {
        foreach(glob($path . "/*") as $archivos_carpeta)
        {
            if (is_dir($archivos_carpeta))
            {
                $this->rm($archivos_carpeta);
            }
            else
            {
                unlink($archivos_carpeta);
            }
        }
        rmdir($path);
      }
      else {
        unlink($path);
      }
  }

}
