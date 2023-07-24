<?php

foreach (glob(__DIR__ . "/env/*.php") as $envFile)
{
    include $envFile;
}
