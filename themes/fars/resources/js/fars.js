fars = {
	common: {
		init: function() {},
		menu: function() {}
	},
	home: {
		init: function() {}
	},
	members: {
		init: function() {
		}	
	},
	login: {
			
	},
	product: {
		init: function() {}
	},
	checkout: {
		init: function() {}		
	},
	pay: {
		init: function() {}	
	},
	about_us: {
		init: function() {}
	},
	contact_us: {
		init: function() {}
	},
	order: {
		init: function() {}
	}
};


util = {
	fire: function(func, funcname, args) {
		var namespace = fars;
		funcname = (funcname === undefined) ? 'init' : funcname;
		if (func !== '' && namespace[func] && typeof namespace[func][funcname] == 'function') {
			namespace[func][funcname](args);
		}
	},
	load_events: function() {
		var page = $('body').data('page');
		util.fire('common', 'init');
		util.fire(page, 'init');
		util.fire('common', 'finalize');
	}
};

$(document).ready(util.load_events);
$(window).load(function() {
	$('html').removeClass('no-js');
});

window.Phpr.options.loadIndicator.html = '<span>Just a tick&hellip;</span>';