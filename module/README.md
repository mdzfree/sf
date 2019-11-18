######Aa[name]开头目录
    子目录系统，比一般模块多了一层，例如：host/name/module[_controller]/[action]

######Default（模块）目录
    模块目录，默认情况下访问 host 则模块名默认为 Default，控制器默认为DefaultController，方法名默认为 indexAction,格式：host/name[_controller]/[action]
    
######Tpl目录
    用于存放模块模板的文件

######block/&lt;Name&gt;.phtml
    块模板文件名，通过在phtml中调用 $this->getTemplateFile('block/<Name>')来引用
    
####其他
######config.xml
    模块配置文件，一般用于说明用途与负责人

#####CommonModel.php
    公共数据模块文件，基本的增删改查
    
#####&lt;Name&gt;Bean.php
    业务处理类
    
#####&lt;Name&gt;Controller.php
    特定逻辑控制器