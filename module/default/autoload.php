<?php
    sfregister_event('async', new Method('consumeMessage', Default_CommonBean::instance()));//消费消息
    /*
    //测试etc/config.php  async_max=10 性能
    sfregister_event('async', new Method('addLog', Default_CommonBean::instance(), 1));
    sfregister_event('async', new Method('addLog', Default_CommonBean::instance(), 2));
    sfregister_event('async', new Method('addLog', Default_CommonBean::instance(), 3));
    sfregister_event('async', new Method('addLog', Default_CommonBean::instance(), 4));
    sfregister_event('async', new Method('addLog', Default_CommonBean::instance(), 5));
    sfregister_event('async', new Method('addLog', Default_CommonBean::instance(), 6));
    sfregister_event('async', new Method('addLog', Default_CommonBean::instance(), 7));
    sfregister_event('async', new Method('addLog', Default_CommonBean::instance(), 8));
    sfregister_event('async', new Method('addLog', Default_CommonBean::instance(), 9));
    sfregister_event('async', new Method('addLog', Default_CommonBean::instance(), 10));
    */