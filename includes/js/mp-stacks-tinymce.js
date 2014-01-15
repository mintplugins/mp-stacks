/* global tinymce */
(function() {
	tinymce.create('tinymce.plugins.mpStacks', {

		init : function(ed, url) {
			var t = this;

			t.url = url;
			t.editor = ed;
			t._createButtons();

			// Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('...');
			ed.addCommand('MP_Stacks', function() {
				
				//Our Edit Buttons has been clicked
				
				var el = ed.selection.getNode(),gallery = wp.media.gallery,frame;
					
				var stack_id = ed.dom.getAttrib( el, 'title' ).split('mp_stack stack="');
				stack_id = stack_id[1].split('"');
				stack_id = stack_id[0];
					
					
				jQuery(document).ready(function($){
					
					//Open the lightbox
					
					$.magnificPopup.open({
						  items: {
							src: 'edit.php?post_type=mp_brick&mp_stacks=' + stack_id + '&mp-stacks-minimal-admin=true'
						  },
						  type: 'iframe'
					});
					
				 
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
				style : 'display:block;'
			});

			editButton = DOM.add('mp_stack_btns', 'img', {
				src : isRetina ? mp_stacks_plugin_url+'/assets/images/edit-2x.png' : mp_stacks_plugin_url+'/assets/images/edit.png',
				id : 'mp_stack_edit',
				width : '24',
				height : '24',
				title : ed.getLang('wordpress.editstack')
			});

			tinymce.dom.Event.add( editButton, 'mousedown', function() {
				var ed = tinymce.activeEditor;
				ed.mpStacksBookmark = ed.selection.getBookmark('simple');
				ed.execCommand('MP_Stacks');
				t._hideButtons();
			});

			dellButton = DOM.add('mp_stack_btns', 'img', {
				src : isRetina ? mp_stacks_plugin_url+'/assets/images/delete-2x.png' : mp_stacks_plugin_url+'/assets/images/delete.png',
				id : 'wp_delstack',
				width : '24',
				height : '24',
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
