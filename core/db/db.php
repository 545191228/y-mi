<?php
/**
 * 数据层接口
 */
namespace DB;

interface Db {

    /**
     * 数据库连接
     */
    public function connect($db_config);

    /**
     * select查询多条记录
     */
    public function query($sql);

    /**
     * 除 select 外的sql
     */
    public function excute($sql);

    /**
     * 查询单条记录
     */
    public function getOne($sql);

}