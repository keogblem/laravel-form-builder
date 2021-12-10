<?php

namespace KeoGblem\FormBuilder;

use Arr;
use KeoGblem\FormBuilder\Concerns\FormDataLoader;
use KeoGblem\FormBuilder\Concerns\Generator;
use KeoGblem\FormBuilder\Concerns\Validator;

class FormBuilder
{
    use FormDataLoader;
    use Generator;
    use Validator;

    protected $form_data = [];
    protected $id_stamp = 1;
    protected $model;
    protected $verbosity = 0;

    public function __construct($source_name = null, $source_type = 'file', array $metas = [])
    {
        if ($source_name) {
            $this->form_data = $this->setSource($source_name, $source_type, $metas)->loadData();
        }

        $this->verbosity = (bool) Arr::get($metas, 'verbosity');
    }

    /**
     * Sets the JSON Source, and loads Form Data
     * @param        $source_name
     * @param  string  $source_type
     * @param  null  $metas
     * @return \KeoGblem\FormBuilder\FormBuilder
     */
    public function source($source_name, string $source_type = 'file', $metas = null): static
    {
        $this->form_data = $this->setSource($source_name, $source_type, $metas)->loadData();
        return $this;
    }

    /**
     * returns a generated form from the data loaded
     * @param  string  $type
     * @param  array  $data
     * @return string|null
     */
    public function generate(string $type = 'create', array $data = []): ?string
    {
        $this->writeLog($data);

        if (empty($data)) {
            return null;
        }
        if (empty($this->form_data)) {
            return null;
        }

        $this->form_data['type'] = $type;
        $this->normalizeOverrideFormdata($data);

        if (! empty($data['model'])) {
            $this->model = $data['model'];

            if (! is_array($this->model)) {
                $this->model->toArray();
            }

            $this->writeLog($this->model);
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->id_stamp = random_int(111, 999);

        return $this->createFromData();
    }

    public function validate(array $inputs): \Illuminate\Validation\Validator
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

    protected function writeLog($message, int $level = 2)
    {
        if ($level >= $this->verbosity) {
            return;
        }

        if (is_array($message)) {
            logger($message);
            return;
        }

        $message = (is_string($message) || is_numeric($message))
            ? $message
            : json_encode($message);

        logger('FORM BUILDER :: ' . $message);
    }
}
