/* global tinymce - Use this after WordPress 3.9 when they mess everything up.

/* global tinymce - P.S. TinyMCE is by far the WORST part of WordPress. Possibly the worst part of the internet as a whole. Maybe the entire universe. So now that you know that, you get to look at crappy code related to it:
tinymce.PluginManager.add('mpstacks', function( editor ) {
	
	function replaceMPStackShortcodes( content ) {
		return content.replace( /\[mp_stack([^\]]*)\]/g, function( match ) {
			return html( 'mp-stack', match );
		});
	}

	function html( cls, data ) {
		data = window.encodeURIComponent( data );
		return '<img src="' + tinymce.Env.transparentSrc + '" class="mceItem ' + cls + '" ' +
			'data-wp-media="' + data + '" data-mce-resize="false" data-mce-placeholder="1" />';
	}


	// Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('...');
	editor.addCommand('MP_Stacks', function() {
							
		jQuery(document).ready(function($){
			
			//Set the content of the active editor
			tinyMCE.activeEditor.setContent(
				//To be the replaced content from the _do_stack function in this class
				t._do_stack(tinyMCE.activeEditor.getContent())
			);
							 
		});
		
	});
	
	// Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('...');
	editor.addCommand('MP_Stacks_View_Page', function() {
		
		jQuery(document).ready(function($){		
			window.location.href = $( '#view-post-btn a' ).attr('href');
		});
		
	});
	

	editor.on( 'BeforeSetContent', function( event ) {
			
		event.content = replaceMPStackShortcodes( event.content );		
		
	});
});

*/
(function() {
	tinymce.create('tinymce.plugins.mpStacks', {

		init : function(ed, url) {
			var t = this;

			t.url = url;
			t.editor = ed;
			t._createButtons();

			// Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('...');
			ed.addCommand('MP_Stacks', function() {
									
				jQuery(document).ready(function($){
					
					//Set the content of the active editor
					tinyMCE.activeEditor.setContent(
						//To be the replaced content from the _do_stack function in this class
						t._do_stack(tinyMCE.activeEditor.getContent())
					);
									 
				});
				
			});
			
			// Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('...');
			ed.addCommand('MP_Stacks_View_Page', function() {
				
				jQuery(document).ready(function($){		
					window.location.href = $( '#view-post-btn a' ).attr('href');
				});
				
			});
			
			ed.onInit.add(function(ed) {
				
				//hide popup buttons on scroll or drag
				tinymce.dom.Event.add(ed.getWin(), 'scroll', function() {
					t._hideButtons();
				});
				tinymce.dom.Event.add(ed.getBody(), 'dragstart', function() {
					t._hideButtons();
				});
				
				// iOS6 doesn't show the buttons properly on click, show them on 'touchstart'
				if ( 'ontouchstart' in window ) {
					ed.dom.events.add(ed.getBody(), 'touchstart', function(e){
						var target = e.target;

						if ( target.nodeName == 'IMG' && ed.dom.hasClass(target, 'mp-stack') ) {
							ed.selection.select(target);
							ed.dom.events.cancel(e);
							t._hideButtons();
							t._showButtons(target, 'mp_stack_btns');
						}
					});
				}
			});
						
			ed.onBeforeExecCommand.add( function( ed ) {
				t._hideButtons();
			});

			ed.onSaveContent.add( function( ed ) {
				t._hideButtons();
			});

			ed.onKeyDown.add(function(ed, e){
				if ( e.which == tinymce.VK.DELETE || e.which == tinymce.VK.BACKSPACE )
					t._hideButtons();
			});

			ed.onMouseDown.add(function(ed, e) {
				if ( e.target.nodeName == 'IMG' && ed.dom.hasClass(e.target, 'mp-stack') ) {
					t._hideButtons();
					t._showButtons(e.target, 'mp_stack_btns');
				}
			});

			ed.onBeforeSetContent.add(function(ed, o) {
				o.content = t._do_stack(o.content);
			});
			
			ed.onChange.add(function(ed, o) {
				o.content = t._do_stack(o.content);
			});
			
			ed.onPostProcess.add(function(ed, o) {
				if (o.get)
					o.content = t._get_stack(o.content);
			});
		},

		_do_stack : function(co) {
			return co.replace(/\[mp_stack([^\]]*)\]/g, function(a,b){
				return '<img src="'+tinymce.baseURL+'/plugins/wpgallery/img/t.gif" class="mp-stack mceItem" title="mp_stack'+tinymce.DOM.encode(b)+'" />';
			});
		},

		_get_stack : function(co) {

			function getAttr(s, n) {
				n = new RegExp(n + '=\"([^\"]+)\"', 'g').exec(s);
				return n ? tinymce.DOM.decode(n[1]) : '';
			}

			return co.replace(/(?:<p[^>]*>)*(<img[^>]+>)(?:<\/p>)*/g, function(a,im) {
				var cls = getAttr(im, 'class');

				if ( cls.indexOf('mp-stack') != -1 )
					return '<p>['+tinymce.trim(getAttr(im, 'title'))+']</p>';

				return a;
			});
		},

		_createButtons : function() {
			var t = this, ed = tinymce.activeEditor, DOM = tinymce.DOM, editButton, dellButton, isRetina;
			
			var mp_stacks_plugin_url = t.url.replace(/\\/g, '/').replace(/\/[^\/]*\/?$/, '').replace(/\\/g, '/').replace(/\/[^\/]*\/?$/, '');
			
			if ( DOM.get('mp_stack_btns') )
				return;

			isRetina = ( window.devicePixelRatio && window.devicePixelRatio > 1 ) || // WebKit, Opera
				( window.matchMedia && window.matchMedia('(min-resolution:130dpi)').matches ); // Firefox, IE10, Opera

			DOM.add(document.body, 'div', {
				id : 'mp_stack_btns',
			});

			dellButton = DOM.add('mp_stack_btns', 'img', {
				src : isRetina ? mp_stacks_plugin_url+'/assets/images/delete-2x.png' : mp_stacks_plugin_url+'/assets/images/delete-2x.png',
				id : 'wp_delstack',
				width : '100',
				height : '36.5',
				style : 'margin-right:5px;',
				title : ed.getLang('wordpress.delstack')
			});

			tinymce.dom.Event.add(dellButton, 'mousedown', function(e) {
				var ed = tinymce.activeEditor, el = ed.selection.getNode();

				if ( el.nodeName == 'IMG' && ed.dom.hasClass(el, 'mp-stack') ) {
					ed.dom.remove(el);

					ed.execCommand('mceRepaint');
					ed.dom.events.cancel(e);
				}

				t._hideButtons();
			});
			
			editButton = DOM.add('mp_stack_btns', 'img', {
				src : isRetina ? mp_stacks_plugin_url+'/assets/images/edit-2x.png' : mp_stacks_plugin_url+'/assets/images/edit-2x.png',
				id : 'mp_stack_edit',
				width : '100',
				height : '36.5',
				title : ed.getLang('wordpress.editstack')
			});

			tinymce.dom.Event.add( editButton, 'mousedown', function() {
				var ed = tinymce.activeEditor;
				ed.mpStacksBookmark = ed.selection.getBookmark('simple');
				ed.execCommand('MP_Stacks_View_Page');
				t._hideButtons();
			});
		},
		
		_showButtons : function(n, id) {
			
			var ed = tinymce.activeEditor, p1, p2, vp, DOM = tinymce.DOM, X, Y;

			vp = ed.dom.getViewPort(ed.getWin());
			p1 = DOM.getPos(ed.getContentAreaContainer());
			p2 = ed.dom.getPos(n);
			
			X = Math.max(p2.x - vp.x, 0) + p1.x;
			Y = Math.max(p2.y - vp.y, 0) + p1.y;

			DOM.setStyles(id, {
				'top' : Y+5+'px',
				'left' : X+5+'px',
				'display' : 'block'
			});
		},

		_hideButtons : function() {
			var DOM = tinymce.DOM;
			DOM.hide( DOM.select('#mp_stack_btns') );
		},

		getInfo : function() {
			return {
				longname : 'MP Stacks Shortcode Settings',
				author : 'Move Plugins',
				authorurl : 'http://moveplugins.org',
				infourl : '',
				version : '1.0'
			};
		}
	});

	tinymce.PluginManager.add('mpstacks', tinymce.plugins.mpStacks);
})();
