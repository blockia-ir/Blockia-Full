tinymce.init({
    content_css : "../tinymce/mycontent.css",
    selector: ".tinymce",
    language : 'fa',
    theme: "modern",
    width: 700,
    height: 350,
	plugins: ["paste image hr directionality  textcolor  charmap nonbreaking  jbimages "],
	toolbar1: " jbimages   | nonbreaking  ltr rtl hr strikethrough underline italic bold forecolor alignleft aligncenter alignright subscript superscript charmap image ",
	menubar: false,
    statusbar: false,
	image_advtab: true,
    toolbar_items_size: 'normal',
    relative_urls: false,
	paste_as_text:true,
	//external_filemanager_path:"/filemanager/",
	//filemanager_title:"مدیریت فایل ها" ,
	//external_plugins: { "filemanager" : "/filemanager/plugin.min.js"}
	
});
	tinymce.init({
    content_css : "../tinymce/mycontent.css",
    selector: ".tinymceImage",
    language : 'fa',
    theme: "modern",
    width: "100%",
    height: 150,
	plugins: ["image   jbimages"],
	toolbar1: " jbimages image",
	menubar: false,
    statusbar: false,
	image_advtab: true,
    toolbar_items_size: 'normal',
	paste_as_text:true,
    relative_urls: false,
	//external_filemanager_path:"/filemanager/",
	//filemanager_title:"مدیریت فایل ها" ,
	//external_plugins: { "filemanager" : "/filemanager/plugin.min.js"}
});

tinymce.init({
    content_css : "../tinymce/mycontent.css",
    selector: ".tinymceQuestion",
    language : 'fa',
    theme: "modern",
    width: "100%",
    height: 150,
	plugins: ["paste image hr directionality  textcolor jbimages code "],
	toolbar1: "  ltr rtl jbimages | underline italic bold forecolor ",
	menubar: false,
    statusbar: false,
	image_advtab: true,
    toolbar_items_size: 'small',
	paste_as_text:false,
    relative_urls: false,
	//external_filemanager_path:"/filemanager/",
	//filemanager_title:"مدیریت فایل ها" ,
	//external_plugins: { "filemanager" : "/filemanager/plugin.min.js"}
});

tinymce.init({
    content_css : "../tinymce/mycontent.css",
    selector: ".tinymceAnswer",
    language : 'fa',
    theme: "modern",
    width: "100%",
    height: 50,
	plugins: ["paste image hr directionality  textcolor jbimages  "],
	toolbar1: "   ltr rtl jbimages",
	menubar: false,
    statusbar: false,
	image_advtab: true,
    toolbar_items_size: 'small',
	paste_as_text:true,
    relative_urls: false,
	//external_filemanager_path:"/filemanager/",
	//filemanager_title:"مدیریت فایل ها" ,
	//external_plugins: { "filemanager" : "/filemanager/plugin.min.js"}
});
