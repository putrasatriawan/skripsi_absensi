require(["../common" ], function (common) {  
    require(["main-function","../app/app-report"], function (func,application) { 
    App = $.extend(application,func);
        App.init();  
    }); 
});