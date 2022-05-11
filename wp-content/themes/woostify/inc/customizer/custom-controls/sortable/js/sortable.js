/**
 * Sortable
 *
 * @package woostify
 */

'use strict';

(function(name, factory) {
	if (typeof window === "object") {
		// add to window.
		window[name] = factory();

		// add jquery plugin, if available.
		if (typeof jQuery === "object") {
			jQuery.fn[name] = function(options) {
				return this.each(
					function() {
						new window[name]( this, options );
					}
				);
			};
		}
	}
})(
	"Sortable",
	function() {
		var _w = window,
		_b     = document.body,
		_d     = document.documentElement;

		// get position of mouse/touch in relation to viewport.
		var getPoint = function(e) {
			var scrollX =
				Math.max(
					0,
					_w.pageXOffset || _d.scrollLeft || _b.scrollLeft || 0
				) - (_d.clientLeft || 0),
			scrollY     =
				Math.max(
					0,
					_w.pageYOffset || _d.scrollTop || _b.scrollTop || 0
				) - (_d.clientTop || 0),
			pointX      = e ? Math.max( 0, e.pageX || e.clientX || 0 ) - scrollX : 0,
			pointY      = e ? Math.max( 0, e.pageY || e.clientY || 0 ) - scrollY : 0;

			return { x: pointX, y: pointY };
		};

		// class constructor.
		var Factory = function(container, options) {
			if (container && container instanceof Element) {
				var t        = this;
				t._container = container;
				t._options   = options || {};
				t._clickItem = null;
				t._dragItem  = null;
				t._hovItem   = null;
				t._sortLists = [];
				t._click     = {};
				t._dragging  = false;

				t._container.setAttribute( 'data-is-sortable' , 1 );
				t._container.style["position"] = 'static';

				window.addEventListener(
					'mousedown',
					t._onPress.bind( t ),
					true
				);
				window.addEventListener(
					'touchstart',
					t._onPress.bind( t ),
					true
				);
				window.addEventListener(
					'mouseup',
					t._onRelease.bind( t ),
					true
				);
				window.addEventListener(
					'touchend',
					t._onRelease.bind( t ),
					true
				);
				window.addEventListener( 'mousemove', t._onMove.bind( t ), true );
				window.addEventListener( 'touchmove', t._onMove.bind( t ), true );
			}
		};

		// class prototype.
		Factory.prototype = {
			constructor: Factory,

			// serialize order into array list.
			toArray: function(attr) {
				attr = attr || "id";

				var data = [],
				item     = null,
				uniq     = "";

				var container_children_length = this._container.children.length;
				for (var i = 0; i < container_children_length; ++i) {
					item = this._container.children[i];
					uniq = item.getAttribute( attr ) || "";
					data.push( uniq );
				}
				return data;
			},

			// serialize order array into a string.
			toString: function(attr, delimiter) {
				delimiter = delimiter || ":";
				return this.toArray( attr ).join( delimiter );
			},

			// checks if mouse x/y is on top of an item.
			_isOnTop: function( item, x, y ) {
				var box = item.getBoundingClientRect(),
				isx     = x > box.left && x < box.left + box.width,
				isy     = y > box.top && y < box.top + box.height;

				return isx && isy;
			},

			// manipulate the className of an item (for browsers that lack classList support).
			_itemClass: function(item, task, cls) {
				var list = item.className.split( /\s+/ ),
				index    = list.indexOf( cls );

				if (task === "add" && index == -1) {
					list.push( cls );
					item.className = list.join( " " );
				} else if (task === "remove" && index != -1) {
					list.splice( index, 1 );
					item.className = list.join( " " );
				}
			},

			// swap position of two item in sortable list container.
			_swapItems: function(item1, item2) {
				var parent1 = item1.parentNode,
				parent2     = item2.parentNode;

				if ( parent1 != parent2 ) {
					return;
				}

				var temp = document.createElement( 'div' );
				parent1.insertBefore( temp, item1 );
				parent1.insertBefore( item1, item2 );
				parent1.insertBefore( item2, temp );
				parent1.removeChild( temp );
			},

			// update item position.
			_moveItem: function(item, x, y) {
				item.style["-webkit-transform"] = "translateX( " + x + "px ) translateY( " + y + "px )";
				item.style["transform"]         = "translateX( " + x + "px ) translateY( " + y + "px )";
			},

			// make a temp fake item for dragging and add to container.
			_makeDragItem: function(item) {
				this._trashDragItem();
				this._sortLists = document.querySelectorAll( '[data-is-sortable]' );

				this._clickItem    = item;
				this._getItemClass = item.getAttribute( 'class' );
				this._itemClass( this._clickItem, 'add', 'active' );

				this._dragItem                = document.createElement( item.tagName );
				this._dragItem.className      = this._getItemClass + ' dragging';
				this._dragItem.innerHTML      = item.innerHTML;
				this._dragItem.style['left']  = (item.offsetLeft || 0) + 'px';
				this._dragItem.style['top']   = (item.offsetTop || 0) + 'px';
				this._dragItem.style['width'] = (item.offsetWidth || 0) + 'px';

				this._container.appendChild( this._dragItem );
			},

			// remove drag item that was added to container.
			_trashDragItem: function() {

				if ( this._dragItem && this._clickItem ) {
					this._itemClass( this._clickItem, 'remove', 'active' );
					this._clickItem = null;

					this._container.removeChild( this._dragItem );
					this._dragItem = null;
				}
			},

			// on item press/drag.
			_onPress: function(e) {
				if ( e && e.target && e.target.parentNode === this._container && 0 == e.button ) {
					e.preventDefault();

					this._dragging = true;
					this._click    = getPoint( e );
					this._makeDragItem( e.target );
					this._onMove( e );
				}
			},

			// Get value to array.
			_getValue: function() {
				var _item  = this._container.querySelectorAll( '.woostify-sortable-list-item' ),
				_dataArray = [];

				if ( _item.length ) {
					for ( var s = 0, t = _item.length; s < t; s++ ) {
						if ( ! _item[s].classList.contains( 'checked' ) ) {
							continue;
						}

						var _dataValue = _item[s].getAttribute( 'data-value' );
						_dataArray.push( _dataValue );
					};
				}

				return _dataArray;
			},

			// on item release/drop.
			_onRelease: function( e ) {
				if ( -1 === e.target.className.indexOf( 'woostify-sortable-list-item' ) ) {
					return;
				}

				this._dragging = false;
				this._trashDragItem();

				var _event = new Event( 'click' ),
				_input     = this._container.nextElementSibling;

				_input.setAttribute( 'value', this._getValue().join( ':' ) );
				_input.dispatchEvent( _event );
			},

			// on item drag/move.
			_onMove: function(e) {
				if (this._dragItem && this._dragging) {
					e.preventDefault();

					var point     = getPoint( e );
					var container = this._container;

					// drag fake item.
					this._moveItem(
						this._dragItem,
						point.x - this._click.x,
						point.y - this._click.y
					);

					// keep an eye for other sortable lists and switch over to it on hover.
					var sortListLength = this._sortLists.length;
					for (var a = 0; a < sortListLength; ++a) {
						var subContainer = this._sortLists[a];

						if (this._isOnTop( subContainer, point.x, point.y )) {
							container = subContainer;
						}
					}

					// container is empty, move clicked item over to it on hover.
					if (
					this._isOnTop( container, point.x, point.y ) &&
					container.children.length === 0
					) {
						container.appendChild( this._clickItem );
						return;
					}

					// check if current drag item is over another item and swap places.
					var container_children_length = container.children.length
					for (var b = 0; b < container_children_length; ++b) {
						var subItem = container.children[b];

						if (
						subItem === this._clickItem ||
						subItem === this._dragItem
						) {
							continue;
						}
						if (this._isOnTop( subItem, point.x, point.y )) {
							this._hovItem = subItem;
							this._swapItems( this._clickItem, subItem );
						}
					}
				}
			}
		};

		// export.
		return Factory;
	}
);
