requirejs.config({
    paths: {
		// libs and extensions
		jqueryLib: './external/jquery-1.10.2',
		backboneLib: './external/backbone-normal',
		backboneSync: './external/backbone-sync',
		backboneAjax: './external/backbone-ajax',
		underscoreLib: './external/underscore-min',
		bootstrapLib: './external/bootstrap-min',
		// plugins
		pleaseWaitPlug: './external/plugins/please-wait'
    },
	shim: {
		'backboneLib' : {
			deps: ['underscoreLib', 'jqueryLib'], // backbone deps
            exports: 'Backbone'
		},
		'bootstrapLib' : {
			deps: ['jqueryLib']
		},
		'pleaseWaitPlug' : {
			deps: ['jqueryLib'],
			exports: 'PleaseWait'
		},
		'backboneSync' : {
			deps: ['backboneLib'] // sync module deps - backbone itself
		},
		'backboneAjax' : {
			deps: ['backboneLib', 'pleaseWaitPlug'] // sync module deps - backbone itself
		},
        'core': {
			deps: ['backboneSync', 'backboneAjax', 'bootstrapLib'] // core to load everything
        }
	},
	urlArgs: "bust=" +  (new Date()).getTime()
});

// Start the main app logic.
requirejs([
	'model/mdlInitProt',
	'view/viewInitProt',
	
	'library/interaction/mouse',
	'model/manipulation/mdlElement',
	'view/collection/viewControl',
	'view/viewWorkArea',
	'view/collection/viewMenu',
	'collection/clnControl',
	'core'
], function (mdlInitProt, viewInitProt, mouse, mdlManipElement, viewControl, viewWorkArea, viewMenu, clnControl) {
	
	// global config
	var conf = Backbone.Config = {};
	// initalize
	var mdlInitObj = new mdlInitProt();
	mdlInitObj.fetch({
		success: function() {
			console.log(mdlInitObj);
			conf.struct = mdlInitObj;
			conf.view = new viewInitProt();
			conf.view.render();
		}
	});
	return;
	
	
	
	return;
	var viewMenuObject = new viewMenu();
	viewMenuObject.render().append();
	
	var viewWorkAreaObject = new viewWorkArea();
	viewWorkAreaObject.append();
	
	var clnControlObject = new clnControl;
	var viewControlsObject = null;
	clnControlObject.fetch({
		success: function(){
			// create
			viewControlsObject = new viewControl({
				collection: clnControlObject, 
				area: viewWorkAreaObject,
				menu: viewMenuObject,
				mdlElement: new mdlManipElement()
			});
			
			// render
			viewControlsObject.render().append();
			viewControlsObject.toggle();
		}
	});
	
	mouse.addRightClick(function(event){
		viewControlsObject.area.setTarget(event.target);
		viewControlsObject.area.isElementInside() ? viewControlsObject.adjust('usual') : viewControlsObject.adjust('immortal');
		viewControlsObject.open().position(event.pageX, event.pageY);
		return false;
	});
});