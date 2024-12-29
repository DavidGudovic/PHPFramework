<?php

return new class
{
    public function up(): void
    {
        echo get_class($this) . ' "up" method called' . PHP_EOL;
    }

    public function down(): void
    {
        echo get_class($this) . ' "down" method called' . PHP_EOL;
    }
};