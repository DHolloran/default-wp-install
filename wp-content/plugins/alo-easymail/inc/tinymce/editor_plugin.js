(function(){tinymce.create("tinymce.plugins.easymail",{init:function(a,b){the_editor=a},createControl:function(e,b){switch(e){case"easymail":var a=b.createListBox("easymail",{title:"EasyMail",onselect:function(f){if(f!=""){tinyMCE.activeEditor.selection.setContent(f)}}});if(alo_em_tinymce_labels){for(var c in alo_em_tinymce_labels){a.add(alo_em_tinymce_labels[c],"");if(alo_em_tinymce_tags[c]){for(var d in alo_em_tinymce_tags[c]){a.add(alo_em_tinymce_tags[c][d],alo_em_tinymce_tags[c][d])}}}}return a}return null}});tinymce.PluginManager.add("easymail",tinymce.plugins.easymail)})();