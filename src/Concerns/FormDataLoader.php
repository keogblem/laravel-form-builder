<?php

namespace KeoGblem\FormTools\Concerns;

use Str;

trait FormDataLoader
{
    protected $source_filename;
    protected $source_type;
    protected $db_table;

    public function setSource($source_filename, $source_type = 'file', $metas = []): static
    {
        $this->source_filename = $source_filename;
        $this->source_type     = $source_type;

        $this->db_table = ! empty($metas['table']) ? $metas['table'] : 'json_forms';

        return $this;
    }

    public function loadData()
    {
        if (empty($this->source_filename)) {
            return null;
        }

        return match ($this->source_type) {
            'db', 'database' => $this->loadDataFromDB(),
            default          => $this->loadDataFromFile(),
        };
    }

    public function loadDataFromFile()
    {
        $path = Str::finish($this->source_filename, '.json');

        try {
            return json_decode(file_get_contents($path), true);
        } catch (\Throwable $e) {
            report('No source file found at "' . $path . '" : ' . $e->getMessage());
            return null;
        }
    }

    public function loadDataFromDB()
    {
        // TODO : loading form database
        return null;
    }
}
