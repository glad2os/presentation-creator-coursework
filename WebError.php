<?php

class WebError
{
    public $erornumber;

    public function __construct(string $error)
    {
        $this->erornumber = $error;
    }

    /**
     * @return string
     */
    public function printError(): string
    {
        /*
         * Шаблонизатор
         */
        return str_replace("{{ERROR}}", $this->erornumber, file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/templates/error.html"));
    }
}
