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
   - 1.学习了display(),assign()方法
   - 2.自动判断请求数据传输类型是否为IS_POST或者IS_AJAX
   - 3.学习公共概念以及建立公共目录，将框架应用程序中所有公共的文件，放到Common文件夹中对应的目录中去。
* 9-19 坚持、坚持、再坚持
   - 1.学习添加框架第三方应用扩展
   - 2.学习自动加载第三方应用扩展目录中的工具类文件
   - 3.为框架增加一个EmptyController类，用于处理用户访问的不存在的控制器时，自动调用Empty控制器。
   - 4.为EmptyController类添加一个__empty()方法，当用户访问不存在的方法时，自动调用__empty()方法。