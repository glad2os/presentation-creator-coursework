<?php

class search implements request
{

    /**
     * @var mixed|null
     */
    private $request;

    public function __construct($request = null)
    {
        $this->request = $request;
    }

    public function invoke()
    {
        $sql = new sql();
        switch ($this->request['action']) {
            case "searchPresentationByKeyWord":
                print json_encode($sql->searchPresentationByKeyWord((string)$this->request['string']));
                break;
            case "searchPresentationByAuthorName":
                print json_encode($sql->searchPresentationByAuthorName((string)$this->request['string']));
                break;
            case "searchPresentationByPresentationId":
                print json_encode($sql->searchPresentationByPresentationId($this->request['string']));
                break;
            default:
                break;
        }
    }
}