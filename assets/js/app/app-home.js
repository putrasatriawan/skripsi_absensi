define([   
    "bootstrap", 
    ], function (  
    bootstrap 
    ) {
    return {  
        table:null,
        init: function () { 
            App.initFunc(); 
            App.initEvent();  
            
         
            $(".loadingpage").hide();
        }, 
        initEvent : function(){   
            
        } 
    }
});