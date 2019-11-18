<?php

namespace KeoGblem\FormTools\Traits;

use Exception;

trait FormDataLoader
{
  protected $source_name;
  protected $source_type;
  protected $base_path;
  protected $db_table;

  public function setSource($source_name, $source_type = 'file', $metas = [])
  {
    $this->source_name = $source_name;
    $this->source_type = $source_type;
    $this->base_path = !empty($metas['path']) ? $metas['path'] : resource_path('forms/');
    $this->db_table = !empty($metas['table']) ? $metas['table'] : 'json_forms';
    return $this;
  }

  public function loadData()
  {
    if (empty($this->source_name)) {
      return null;
    }

    switch ($this->source_type) {
      case 'file':
        return $this->loadDataFromFile();
        break;
      case 'db':
      case 'database':
        return $this->loadDataFromDB();
        break;
      default:
        return $this->loadDataFromFile();
    }
  }

  public function loadDataFromFile()
  {
    $path = $this->base_path . $this->source_name . '.json';
    try {
      return json_decode(file_get_contents($path), true);
    } catch (Exception $e) {
      logger('No source file found at "' . $path . '" : '.$e->getMessage());
      // if (app()->environment() == 'local') {
      //   throw $e;
      // }
      return null;
    }
  }

  public function loadDataFromDB()
  {
    // TODO : loading form database
    return null;
  }
}
