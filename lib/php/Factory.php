<?php
require_once dirname(__FILE__) . '/FactoryInjector.php';

class Factory
{
    public function __call($name, $args)
    {
        if (preg_match('/^get([a-zA-Z][a-zA-Z0-9_]*)$/', $name, $matches) > 0) {
            $class_name = $matches[1];
            if (class_exists($class_name)) {
                $reflection_class = new ReflectionClass($class_name);
                if ($reflection_class->isInstantiable()) {
                    $instance = count($args) === 0 ? $reflection_class->newInstance()
                                                   : $reflection_class->newInstanceArgs($args);
                    if ($reflection_class->implementsInterface('FactoryInjector')) {
                        $instance->setFactory($this);
                    }
                    return $instance;
                }
            }
        }
    }
}
