# young
##框架学习
1.为Controller父类添加success,error方法；
2.为Controller父类增加初始化功能
    通过:
    ```if(method_exists($this, '__init')){
        $this->__init();
    }
    ```
    或者
    ```
    if(method_exists($this, '__auto')){
        $this->__auto();
    }
    ```
> 可以在子类中，实现__init()方法，并且对子类的相关动作进行初始化。
  如果是多重继承，则可以定义两个子类初始化的方法，来完成对应的功能。
* 9-17 学习了框架的Log类编写，引入了DEBUG模式，为P()增加了新功能，增加了页面跳转go()函数，增加了错误日志显示功能函数halt();
* 9-18 勿忘国耻 圆梦中国。
** 学习了display(),assign()方法
** 自动判断请求数据传输类型是否为IS_POST或者IS_AJAX
** 学习公共概念以及建立公共目录，将框架应用程序中所有公共的文件，放到Common文件夹中对应的目录中去。