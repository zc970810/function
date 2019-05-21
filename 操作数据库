<?php
// 连接数据库
function connect()
{
    $config = require dirname(__FILE__) . '/config.php';
    $mysqli = @mysqli_connect(
        $config['DB_HOST'] . ':' . $config['DB_PORT'],
        $config['DB_USER'],
        $config['DB_PASS'],
        $config['DB_NAME']
    ) or die('Connect Error: ' . mysqli_connect_errno() . '-' . mysqli_connect_error());
    mysqli_set_charset($mysqli, $config['DB_CHARSET']);
    return $mysqli;
}

// 查询一行数据
function queryOne($sql)
{
    $mysqli = connect();
    $result = mysqli_query($mysqli, $sql);
    $data = [];
    if ($result && mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
    }
    return $data;
}


// 查询多行数据
function queryAll($sql)
{
    $mysqli = connect();
    $result = mysqli_query($mysqli, $sql);
    $data = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($res = mysqli_fetch_assoc($result)) {
            $data[] = $res;
        }
    }
    return $data;
}


// 获取数据表前缀
function getDBPrefix()
{
    $config = require dirname(__FILE__) . '/config.php';
    return $config['DB_PREFIX'];
}

// 执行sql
function execute($sql)
{
    $mysqli = connect();
    mysqli_query($mysqli, $sql);
    return mysqli_affected_rows($mysqli) > 0;
}
