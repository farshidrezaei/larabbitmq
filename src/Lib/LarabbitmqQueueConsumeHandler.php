<?php

namespace FarshidRezaei\Larabbitmq\Lib;

interface LarabbitmqQueueConsumeHandler
{
    public function handle($data): void;
}
