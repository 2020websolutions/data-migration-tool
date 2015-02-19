<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Migration\Resource\Adapter;

/**
 * Mysql adapter
 */
class Mysql implements \Migration\Resource\AdapterInterface
{
    /**
     * @var \Magento\Framework\DB\Adapter\AdapterInterface
     */
    protected $resourceAdapter;

    /**
     * @param \Magento\Framework\DB\Adapter\Pdo\MysqlFactory $adapterFactory
     * @param array $config
     */
    public function __construct(
        \Magento\Framework\DB\Adapter\Pdo\MysqlFactory $adapterFactory,
        array $config
    ) {
        $configData['config'] = $config;
        $this->resourceAdapter = $adapterFactory->create($configData);
        $this->resourceAdapter->query('SET FOREIGN_KEY_CHECKS=0;');
    }

    /**
     * @inheritdoc
     */
    public function getDocumentStructure($documentName)
    {
        return $this->resourceAdapter->describeTable($documentName);
    }

    /**
     * @inheritdoc
     */
    public function getDocumentList()
    {
        return $this->resourceAdapter->listTables();
    }

    /**
     * @inheritdoc
     */
    public function getRecordsCount($documentName)
    {
        $select = $this->resourceAdapter->select();
        $select->from($documentName, 'COUNT(*)');
        $result = $this->resourceAdapter->fetchOne($select);
        return $result;
    }

    /**
     * @inheritdoc
     */
    public function loadPage($documentName, $pageNumber, $pageSize)
    {
        $select = $this->resourceAdapter->select();
        $select->from($documentName, '*')
            ->limit($pageSize, $pageNumber * $pageSize);
        $result = $this->resourceAdapter->fetchAll($select);
        return $result;
    }

    /**
     * @inheritdoc
     */
    public function insertRecords($documentName, $records)
    {
        $this->resourceAdapter->rawQuery("SET @OLD_INSERT_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO'");
        $result = $this->resourceAdapter->insertMultiple($documentName, $records);
        $this->resourceAdapter->rawQuery("SET SQL_MODE=IFNULL(@OLD_INSERT_SQL_MODE,'')");
        return $result;
    }

    /**
     * @inheritdoc
     */
    public function deleteAllRecords($documentName)
    {
        $this->resourceAdapter->truncateTable($documentName);
    }
}
