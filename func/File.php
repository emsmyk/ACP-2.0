<?php
class File
{
  public static function is_dir($folder_name='', $location = '', $mkdir=false)
  {
    $path = $location.$folder_name;
    if(!file_exists("$path")) {
      if($mkdir === true){
        mkdir($path, 0777, true);
        return $path;
      }

      return $path;
    }

    return false;
  }

  public static function delete_old_files($folderName)
  {
    if (file_exists($folderName)) {
      foreach (new DirectoryIterator($folderName) as $fileInfo) {
        if ($fileInfo->isDot()) {
          continue;
        }
        if ($fileInfo->isFile() && time() - $fileInfo->getCTime() >= 1) {
            unlink($fileInfo->getRealPath());
        }
      }
    }
  }
}
 ?>
