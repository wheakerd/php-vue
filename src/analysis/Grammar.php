<?php
declare(strict_types=1);

namespace wheakerd\phpvue\analysis;

/**
 * node、es文件免编译解析
 * 编译型模板引擎 支持动态缓存
 */
class Grammar
{
    /**
     * 配置信息，保留
     * @var array|string[]
     */
    protected array $config = [
        // 解析形式支持 project single
        'engine' => 'project',
        // 定义npm包位置，即node_modules文件夹下文件
        'npm' => '',
        // 对此类模板后缀文件进行解析
        'view_suffix' => 'vue|js',
    ];

    /**
     * 模板数据
     * @var string
     */
    protected string $template = '';

    /**
     * @var array
     */
    protected array $convert = [];

    /**
     * 架构函数
     * @access public
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = array_merge($this->config, $config);
    }

    
    /**
     * 模板引擎配置
     * @access public
     * @param array $config
     * @return $this
     */
    public function config(array $config = []): static
    {
        $this->config = array_merge($this->config, $config);
        return $this;
    }

    /**
     * @param string $template
     * @return $this
     */
    public function handle (string $template = "")
    {
        $this->template = $template;
        $this->import()->script()->template()->style();
        return $this;
    }

    /**
     * @return string
     */
    public function unpack(): string
    {
        return rtrim(ltrim(implode(PHP_EOL, $this->convert)));
    }

    protected function import()
    {
        preg_match_all('/<script[\s\S]*>\r*\n*([\s\S]*?\s*\n*)export\s+default\s+{/i', $this->template, $import);
        $this->convert[] = $this->endArray($import);
        return $this;
    }

    protected function script()
    {
        preg_match_all('/(export\s+default\s+[a-zA-Z\s]*?{\n*\s+[\s\S]*?)}*;?\s*\r*\n*<\/script>/i', $this->template, $script);
        $this->convert[] = end($script[1]) ?? 'export default {';
        return $this;
    }

    protected function template()
    {
        preg_match_all('/\s*\r*\n*<template>([\s\S]*)<\/template>\r*\n*\s*<script[\s\S]*>/i', $this->template, $template);
        if (null != $this->endArray($template)) $this->convert[] = $this->pattern('template:`', $this->endArray($template), '`,');
        return $this;
    }

    protected function style()
    {
        preg_match_all('/<style[\s\S]*>\r*\n*\s*([\s\S]*?)\r*\n*\s*<\/style>/i', $this->template, $style);
        if (null != $this->endArray($style)) $this->convert[] = $this->pattern('style:`', $this->endArray($style), '`,');
        $this->convert[] = '};';
        return $this;
    }


    public function endArray (array $array): string|null
    {
        return end($array[1]) ? end($array[1]) : null;
    }

    /**
     * @param string $l_str
     * @param string $info
     * @param string $r_str
     * @return string
     */
    protected function pattern(string $l_str, string $info, string $r_str): string
    {
        return $l_str . PHP_EOL . $info . PHP_EOL . $r_str;
    }

}