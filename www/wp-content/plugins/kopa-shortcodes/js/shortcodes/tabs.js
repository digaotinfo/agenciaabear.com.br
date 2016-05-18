(function() {  
    tinymce.create('tinymce.plugins.tabs', {  
        init : function(ed, url) {  
            ed.addButton('tabs', {  
                title : 'Add tabs',  
                image : url+'/icons/tabs.png',  
                onclick : function() {  
                    ed.selection.setContent('[tabs tab1="Tab 1" tab2="Tab 2" tab3="Tab 3"][tab id="tab1"]Tab content 1[/tab][tab id="tab2"]Tab content 2[/tab][tab id="tab3"]Tab content 3[/tab][/tabs]');
                }  
            });  
        },  
        createControl : function(n, cm) {  
            return null;  
        }
    });  
    tinymce.PluginManager.add('tabs', tinymce.plugins.tabs);  
})();

