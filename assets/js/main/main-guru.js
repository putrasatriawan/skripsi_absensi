require(["../common" ], function (common) {  
    require(["main-function","../app/app-guru"], function (func,application) { 
    App = $.extend(application,func);
        App.init();  
    }); 
});
