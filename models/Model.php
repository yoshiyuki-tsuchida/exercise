<?php
require_once dirname(__FILE__) . '/../lib/php/FactoryInjector.php';

/**
 * Modelクラス
 *
 * @package models
 * @version $Id$
 */
abstract class Model implements FactoryInjector
{
    /**
     * ファクトリ
     * @var
     */
    private $_factory;

    /**
     * ファクトリを設定する
     *
     * @param Factory $factory
     */
    public function setFactory($factory)
    {
        $this->_factory = $factory;
    }

    /**
     * ファクトリを取得する
     *
     * @return Factory ファクトリ
     */
    public function getFactory()
    {
        return $this->_factory;
    }
}

