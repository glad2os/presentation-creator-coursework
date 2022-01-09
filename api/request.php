<?php
/*
 * Интерфейс RESTApi
 */
interface request
{
    public function __construct();

    public function invoke();
}