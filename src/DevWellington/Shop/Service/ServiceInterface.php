<?php

namespace DevWellington\Shop\Service;

interface ServiceInterface
{
    public function insert(array $data);
    public function update(array $data);
    public function delete($id);
} 