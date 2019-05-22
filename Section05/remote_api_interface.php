<?php

interface RemoteAPI {
    public function connect($connectionDetails);
    public function fetch($path, $callback);
    public function close();
}
