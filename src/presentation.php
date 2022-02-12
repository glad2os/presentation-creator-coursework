<?php
// TODO: валидация $_GET
$file_get_contents = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/templates/presentation.html");
$file_get_contents = str_replace("{{PRESENTATION_ID}}", $_GET['id'], $file_get_contents);
print $file_get_contents;
