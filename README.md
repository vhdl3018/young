# young
框架学习
1、为Controller父类添加success,error方法；
2、为Controller父类增加初始化功能
    通过:
    if(method_exists($this, '__init')){
        $this->__init();
    }
    或者
    if(method_exists($this, '__auto')){
        $this->__auto();
    }

    可以在子类中，实现__init()方法，并且对子类的相关动作进行初始化。
    如果是多重继承，则可以定义两个子类初始化的方法，来完成对应的功能。
