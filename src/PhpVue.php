<?php
declare(strict_types=1);

namespace wheakerd\PhpVue;

/**
 * node、es文件免编译解析
 * 编译型模板引擎 支持动态缓存
 */
class PhpVue
{
    protected array $config = [
        // 解析形式支持 project single
        'engine' => 'project',
        // 定义npm包位置，即node_modules文件夹下文件
        'npm' => '',
        // 默认对npm做解析
        'importmap' => false,
        // 对此类模板后缀文件进行解析
        'view_suffix' => 'vue|js',
    ];


    /**
     * 架构函数
     * @access public
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = array_merge($this->config, $config);
    }

    public function dump (): int
    {
        return 111234555888999;
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
    

}
