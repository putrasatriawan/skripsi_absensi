require(["../common" ], function (common) {  
    require(["main-function","../app/app-config"], function (func,application) { 
    App = $.extend(application,func);
        App.init();  
    }); 
});
