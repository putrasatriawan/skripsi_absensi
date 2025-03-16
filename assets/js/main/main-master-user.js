require(["../common" ], function (common) {  
    require(["main-function","../app/app-master-user"], function (func,application) { 
    App = $.extend(application,func);
        App.init();  
    }); 
});
