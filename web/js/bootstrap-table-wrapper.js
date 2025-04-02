(function($){

	/**
		Version : 1.0.2

		Version info :
			x.y.z

			x : major update, possibly does not support backward compability
			y : minor update, adding new feature, changing feature behavior
			z : revision, bug fixing

		Changelog : 

			1.0.0 : Adding capability to changing from client side data source to server side data source
			1.0.1 : Adding capability for creating pagination
			1.0.2 : Fixing paging / refresh / page count issues

		Dependency :
		- jQuery
		- bootstrap table wenzhixin
		- $.makePagination from freescript.js
	*/

	if( $.fn.bootstrapTableWrapper != undefined )
	{
		throw "Library bootstrapTableWrapper is conflicting with other library or loaded twice";
	}

	$.fn.bootstrapTableWrapper = function( options ){

		if( typeof options != 'object' )
		{
			throw "Options must be an object";
		}

		if( !("data" in options)  )
		{
			throw "data must be included in options!";
		}

		if( !("totalRows" in options)  )
		{
			throw "totalRows must be included in options!";
		}

		if( !("url" in options) )
		{
			throw "url for server side rendering must be included in options!";
		}

		var _this = this;

		/**
			These are default configuration, which will be overridden when the user passes the options

			makePagination is used to create a pagination
		*/
		var _settings = $.extend({
			makePagination : false,
			singleSelect   : true,
			clickToSelect  : true,
			showRefresh		: true,
			pagination		: true,
			sidePagination : 'server',
			pageNumber		: 1,
			pageSize			: 10,
			method			: 'POST',
			contentType    : 'application/x-www-form-urlencoded',
			queryParams		: function(p)
			{
				return p;
			},
			responseHandler	: function(res)
			{
				return res;
			}
		}, options );

		var _clientRenderSetting = $.extend({}, _settings);
		var _serverRenderSetting = $.extend({}, _settings);

		var changeToServerSidePaging = function(additionalOptions){

			if( additionalOptions )
			{
				_serverRenderSetting = $.extend(_serverRenderSetting, additionalOptions);
			}

			_this.bootstrapTable('destroy');
			_this.bootstrapTable(_serverRenderSetting);

			if( _settings.makePagination )
			{
				$.makePagination( '#' + $(_this).prop('id') );
			}

		};

		_clientRenderSetting.onRefresh = function()
			{
				changeToServerSidePaging();
			};

		_clientRenderSetting.onPageChange = function(page, size)
			{
				var additionalOptions = {
					pageNumber: page,
					pageSize: size
				};

				changeToServerSidePaging(additionalOptions);
			};

		_clientRenderSetting.onSort = function()
			{
				var additionalOptions = {
					sortName: this.sortName
				};

				changeToServerSidePaging(additionalOptions);
			};

		_clientRenderSetting.method = null;
		_clientRenderSetting.contentType = null;
		_clientRenderSetting.url = null;

		_serverRenderSetting.data = {};

		var bootstrapTableInstance = null;

		if( _settings.forceServer == 1 )
		{
			bootstrapTableInstance = _this.bootstrapTable(_serverRenderSetting);
		}
		else
		{
			bootstrapTableInstance = _this.bootstrapTable(_clientRenderSetting);
		}

		if( _settings.makePagination )
		{
			var tableId = '#' + $(_this).prop('id');

			$.makePagination( tableId );
			$(tableId).trigger('post-body.bs.table');
		}
		
		return bootstrapTableInstance;

	};

})(jQuery);