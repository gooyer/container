<?php
/**
 * PSR-11 Container functions helpers
 */
if (!function_exists("container_make")) {

    /**
     * @param string $abstract
     * @throws \Gooyer\Container\Exceptions\NotFoundException
     * @throws \Gooyer\Container\Exceptions\ContainerException
     *
     * @return mixed
     */
    function container_make(string $abstract)
    {
        return \Gooyer\Container\Container::instance()->make($abstract);
    }
}
