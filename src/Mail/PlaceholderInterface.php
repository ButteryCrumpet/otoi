<?php

namespace Otoi\Mail;


interface PlaceholderInterface
{
    public function resolve($data);
}