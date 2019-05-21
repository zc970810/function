<?php

/**
 * [setSession description] 写入session
 * @param [type] $key    [key]
 * @param [type] $data   [value]
 * @param string $prefix [大的key]
 */
function setSession($key, $data, $prefix = '')
{
    session_id() || @session_start();
    if (!empty($prefix)) {
        $_SESSION[$prefix][$key] = $data;
    } else {
        $_SESSION[$key] = $data;
    }
}

/**
 * [getSession description]  获取session
 * @param  [type] $key    [key]
 * @param  string $prefix [大的key]
 * @return [type]         [数组]
 */
function getSession($key, $prefix = '')
{
    session_id() || @session_start();
    if (!empty($prefix)) {
        return isset($_SESSION[$prefix][$key]) ? $_SESSION[$prefix][$key] : [];
    } else {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : [];
    }
}

// 删除session
function deleteSession($key, $prefix = '')
{
    session_id() || @session_start();
    if (!empty($prefix)) {
        $_SESSION[$prefix][$key] = null;
    } else {
        $_SESSION[$key] = null;
    }
}


// 临时存储session，用于提示框内容
function setInfo($info)
{
    setSession('info', $info, 'system');
}

// 读取临时存储session，用于提示框内容
function getInfo()
{
   $info = getSession('info', 'system');
   deleteSession('info', 'system');
   return $info;
}

// 删除临时存储session，用于提示框内容
function hasInfo()
{
   return !empty(getSession('info', 'system'));
}
