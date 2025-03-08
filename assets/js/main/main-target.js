require(["../common" ], function (common) {  
    require(["main-function","../app/app-target"], function (func,application) { 
    App = $.extend(application,func);
        App.init();  
    }); 
});
