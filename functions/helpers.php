<?php

    function svg($name)
    {
        return file_get_contents("./resources/svg/{$name}.svg");
    }

    function registerExceptionHandler()
    {
        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        $whoops->register();
    }

    function getLine(array $todo): string
    {
        return $todo['done'] ? ' line-through' : '';
    }
