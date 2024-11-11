<?php
namespace App\Contracts;


interface HasRepresentationRoute
{

    public function representation(string $id);
}