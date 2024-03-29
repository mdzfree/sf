<?php
//是否开启调试模式
debug=3
//针对开发模式的IP地址，多个英文逗号分割
dev_ip=0
//是否开启开发者模式
dev_mode=1
//控制台是否输出日志信息，0为关闭，1为优先针对浏览器组件输出，2为原始输出：
console=2
//模块缓存列表过期秒数
module_cache=3600

//定时任务配置    ::开始
//检测介质
async_type=io
//执行线程数
async_max=1
//过期秒数
async_exp=180
//停留时间，一般是挂起任务防止执行频繁
async_sleep=10
//定时任务配置    ::结束