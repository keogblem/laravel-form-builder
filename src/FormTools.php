<?php

namespace KeoGblem\FormTools;

use KeoGblem\FormTools\Traits\FormDataLoader;
use KeoGblem\FormTools\Traits\Generator;
use KeoGblem\FormTools\Traits\Validator;

class FormTools
{
  use FormDataLoader;
  use Generator;
  use Validator;

  protected $form_data = [];
  protected $id_stamp = 1;
  protected $model;

  /**
   * Form constructor.
   * @param        $source_name
   * @param string $source_type
   * @param null   $metas
   */
  public function __construct($source_name = null, $source_type = 'file', $metas = null)
  {
    if ($source_name) {
      $this->form_data = $this->setSource($source_name, $source_type, $metas)->loadData();
    }
  }

  /**
   * Sets the JSON Source, and loads Form Data
   * @param        $source_name
   * @param string $source_type
   * @param null   $metas
   * @return \KeoGblem\FormTools\FormTools
   */
  public function source($source_name, $source_type = 'file', $metas = null)
  {
    $this->form_data = $this->setSource($source_name, $source_type, $metas)->loadData();
    return $this;
  }

  /**
   * returns a generated form form the data loaded
   * @param string $type
   * @param array  $data
   * @return string|null
   */
  public function generate($type = 'create', array $data = [])
  {
    // $data = [ 'url' , 'model', input-size, ... ];
    logger($data);
    if (empty($data)) {
      return null;
    }
    if (empty($this->form_data)) {
      return null;
    }

    $this->form_data['type'] = $type;
    $this->normalizeOverrideFormdata($data);

    if (!empty($data['model'])) {
      $this->model = $data['model'];
      if (!is_array($this->model)) {
        $this->model->toArray();
      }
      logger($this->model);
    }

    $this->id_stamp = rand(111, 999);

    return $this->createFromData();
  }

  /**
   * Returns the form validator
   * @param array $inputs
   * @return mixed
   */
  public function validate(array $inputs)
  {
    return $this->validateInputs($inputs);
  }

  protected function normalizeOverrideFormdata(array $data)
  {
    $this->form_data['id']         = $data['id'] ?? $this->form_data['id'] ?? null;
    $this->form_data['url']        = $data['url'] ?? $this->form_data['url'] ?? '';
    $this->form_data['class']      = $data['class'] ?? $this->form_data['class'] ?? '';
    $this->form_data['method']     = $data['method'] ?? $this->form_data['method'] ?? 'GET';
    $this->form_data['input-size'] = $data['input-size'] ?? $this->form_data['input-size'] ?? '';
    $this->form_data['columns']    = $data['columns'] ?? $this->form_data['columns'] ?? 1;
    $this->form_data['title']      = $data['title'] ?? $this->form_data['title'] ?? '';
  }
}
