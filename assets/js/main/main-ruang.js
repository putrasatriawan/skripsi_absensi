require(["../common" ], function (common) {  
    require(["main-function","../app/app-ruang"], function (func,application) { 
    App = $.extend(application,func);
        App.init();  
    }); 
});
