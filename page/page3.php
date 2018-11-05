<!doctype html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
        <title>IM Quality Report</title>
        <script>
        Ext.Loader.setConfig({enabled: true});
		Ext.Loader.setPath('Ext.ux', '../extjs-4.2.2/examples/ux/');
        
        Ext.onReady( function() {
            Ext.QuickTips.init();
		var itemperpage = 25;
		var itemprcode 	= 5;
		// store data
		Ext.define('disp_po',{
				extend	: 'Ext.data.Model',
				fields	: ['posys', 'costdept', 'costid', 'category', 'descid', 'supplierid', 'quono', 'poid', 'genpo', 'ponumber', 'podesc', 'podate', 'exid', 'bcno', 'delvdate', 'statusid', 'statapr', 'currid', 'currrate', 'prid', 'discount', 'condiscount', 'pototal', 'conamount', 'spdisc', 'vat', 'wht', 'pic', 'picno', 'chk1no', 'chk2no', 'apr1no', 'apr2no', 'backgrnd', 'rdesc', 'chk1', 'chk2', 'apr1', 'apr2', 'nextval', 'userlevel', 'pocat', 'costcenter', 'podiscount', 'poamount', 'suppliername', 'addressname', 'city', 'telp', 'faxno', 'person', 'country', 'descname', 'costcenter1', 'currname', 'deliver', 'invamount', 'payment', 'exedetail', 'fymonth', 'amount', 'cek', 'emailsend', 'chk1periode', 'chk2periode', 'apr1periode', 'apr2periode']
		});
		Ext.define('detail_po',{
			extend	: 'Ext.data.Model',
			fields	: ['chk1periode','chk2periode', 'apr1periode', 'apr2periode']
		});
		Ext.define('cbx_prcode',{
			extend	: 'Ext.data.Model',
			fields	: ['problemno', 'problemname']
		});
		Ext.define('cbx_apr',{
			extend	: 'Ext.data.Model',
			fields	: ['userno', 'pic']
		});
		Ext.define('prepareuser',{
			extend	: 'Ext.data.Model',
			fields	: [ 'chk1', 'chk1no', 'chk2', 'chk2no', 'apr1', 'apr1no', 'apr2', 'apr2no' ]
		});
		var data_store = Ext.create('Ext.data.Store',{
				model	: 'disp_po',
				autoLoad: true,
				pageSize: itemperpage,
				proxy	: {
					type	: 'ajax',
					//url	: 'json/json_displaypo.php',
					url		: ' ',
					reader	: {
						type			: 'json',
						root			: 'rows',
						totalProperty	: 'totalCount'
					}
				}
			});
		var data_store_detail = Ext.create('Ext.data.Store',{
			model	: 'detail_po',
			autoLoad: true,
			pageSize: 1,
			proxy	: {
				type	: 'ajax',
				//url	: 'json/json_displaypo.php',
				url		: ' ',
				reader	: {
					type			: 'json',
					root			: 'rows',
					totalProperty	: 'totalCount'
				}
			}
		});
		var cbx_prcode = Ext.create('Ext.data.Store',{
			model	: 'cbx_prcode',
			autoLoad: true,
			pageSize: itemprcode,
			proxy	: {
				type	: 'ajax',
				url		: 'json/json_prcode.php',
				reader	: {
					type			: 'json',
					root			: 'rows',
					totalProperty	: 'totalCount'
				}
			}
		});
		var cbx_apr_store = Ext.create('Ext.data.Store',{
				model	: 'cbx_apr',
				autoload: true,
				pageSize: itemperpage,
				proxy	: {
					type	: 'ajax',
					url		: ' ',
					reader	: {
						type			: 'json',
						root			: 'rows',
						totalProperty	: 'totalCount'
					}
				}
			});
			//	All about function
				// ***
						//	function untuk fontsize grid
								function upsize(val) {
									return '<font style="white-space:normal;">' + val + '</font>';
								}
								function content(val) {
									return '<font size="2" style="font-family:sans-serif; white-space:normal;">' + Ext.String.ellipsis(val, 250, false) + '</font>';
								}
						//	end function untuk column bigsize

						//	function required
								var required = '<span style="color:red;font-weight:bold" data-qtip="Required">*</span>';
						//	end of function required
						
						//	function status [investment]
								function status(val) {
									if (val == 0) {
										return 'Waiting';
									}else if (val == 1) {
										return 'Checked';
									}else if (val == 2) {
										return 'Approved';
									}else if (val == 3) {
										return 'Closed';
									}else if (val == 4) {
										return 'Cancelled';
									}else if (val == 5) {
										return 'Delivered';
									}
								}
						//	end of function
						
						// function status [approval]
								function apr(value,meta,record) {
									var txt = value;
									list = txt.split(",");
									
									//return 'yang: '+list[0]+',yang: '+list[1];
									
									if (list[0] == 4) {
										switch (list[1]){
											case "0" :
												return 'Cancelled';
											break;
											case "1" :
												var pic1 = record.data.chk1;
												return 'Cancelled by ' + pic1;
											break;
											case "2" :
												var pic2 = record.data.chk2;
												return 'Cancelled by ' + pic2;
											break;
											case "3" :
												var pic3 = record.data.apr1;
												return 'Cancelled by ' + pic3;
											break;
											case "4" :
												var pic4 = record.data.apr2;
												return 'Cancelled by ' + pic4;
											break;
										}
									}
									else{
										var skip = 'Waiting';
										var cek  = 'Checked By ';
										switch (list[1]){
											case "0" :
												return 'Open';
											break;
											case "1" :
												var pic1name = record.data.chk1;
												if (pic1name == ''){
													return skip;
												}else{return cek + pic1name;}
											break;
											case "2" :
												var pic2name = record.data.chk2;
												if (pic2name == ''){
													return skip;
												}else{return cek + pic2name;}
											break;
											case "3" :
												var pic3name = record.data.apr1;
												if (pic3name == ''){
													return skip;
												}else{return cek + pic3name;}
											break;
											case "4" :
												return 'Approved';
											break;
										}
									}
								}
						// end  function
						
						// 	function [currency]
								function cur(val) {
									if (val === 'US dollar'){
										return 'US&#36;';
									}else if (val === 'IDR'){
										return 'IDR';
									}else if (val === 'Yen Japan'){
										return '&#165;';
									}else if (val === 'Ringgit Malaysia'){
										return 'RM';
									}else if (val === 'Singapore dollar'){
										return 'SGD';
									}else if (val === 'Euro'){
										return '&#8364;';
									}else if (val === 'Cent') {
										return '&#162;';
									}else if (val === 'Poundsterling'){
										return '&#163;';
									}
								}
						//	end of function
			// ----***----  //
			
			//	Grid data
				//	***
					//	grid panel
						var clock = Ext.create('Ext.toolbar.TextItem', {text: Ext.Date.format(new Date(), 'g:i:s A')});
						
						
								var grid_data = Ext.create('Ext.grid.Panel', {
								title		: 'LINE REJECTION',
								layout      : 'fit',
								id			: 'tr_data',
								iconCls		: 'grid',
								renderTo	: 'section',
								//store		: ,
								width		: '100%',
								height		: 490,
								x			: 0,
								y			: 0,
								columnLines	: true,
								multiSelect	: true,
								viewConfig	: {
										stripeRows          : true,
										enableTextSelection : true 
								},
								//----------------------------COLUMN---------------------------//
								columns		: [ // Ext.util.Format.numberRenderer('0,000.00') --> untuk merubah format rupiah
									{ header: 'Date', 	 			dataIndex: '', 	width:100 },
									{ header: 'MGZ No', 	 		dataIndex: '', 	width:100 },
									{ header: 'NG Found', 	 		dataIndex: '', 	width:114 },
									{ header: 'LINE REJECTION',		columns: [
										{ header: 'Board No', 		dataIndex: '', 	flex:1 },
										{ header: 'Qty Board', 		dataIndex: '', 	flex:1 },
										{ header: 'Problem Code', 	dataIndex: '', 	flex:1 },
										{ header: 'Position', 		dataIndex: '', 	flex:1 },
										{ header: 'Qty Point', 		dataIndex: '', 	flex:1 }
										]
									},
									{ header: 'ACTION TO PRODUCT',	columns: [
										{ header: 'SELECTION PRODUCT', columns: [
											{ header: 'Qty Select', dataIndex: '', flex:1},
											{ header: 'Qty NG', 	dataIndex: '', flex:1}
											]
										},
										{ header: 'REPAIRING', 		columns: [
											{ header: 'Part No', 	dataIndex: '', flex:1},
											{ header: 'Repaired By',dataIndex: '', flex:1},
											{ header: 'Checked By',	dataIndex: '', flex:1},
											{ header: 'Result',		dataIndex: '', flex:1}
											]
										}
										]
									},
									{ header: 'ACTION TO ROCESS',	columns: [
										{ header: 'description', 	dataIndex: '', 	width:100 },
										{ header: 'PIC', 	 		dataIndex: '', 	width:100 }
										]
									}
								],
								//	end columns
								listeners: {
									render: {
										fn: function(){
												Ext.fly(clock.getEl().parent()).addCls('x-status-text-panel').createChild({cls:'spacer'});

										 Ext.TaskManager.start({
												 run: function(){
														 Ext.fly(clock.getEl()).update(Ext.Date.format(new Date(), 'g:i:s A'));
												 },
												 interval: 1000
										 });
										},
										delay: 100
									},
									select: function(selModel, record, index, options){
										//alert(record.get('chk1periode'));
										data_store_detail.proxy.setExtraParam('chk1periode',record.get('chk1periode'));
										data_store_detail.proxy.setExtraParam('chk2periode',record.get('chk2periode'));
										data_store_detail.proxy.setExtraParam('apr1periode',record.get('apr1periode'));
										data_store_detail.proxy.setExtraParam('apr2periode',record.get('apr2periode'));
										data_store_detail.loadPage(1);
									}
								},

								tbar	: [{xtype:'tbspacer',width:10},
										{	xtype	: 'button',
											id		: 'btn_refresh',
											iconCls	: 'refresh',
											text 	: 'Refresh',
											tooltip	: 'Refresh',
											scale	: 'medium',
											handler : function (){
													data_store.loadPage(1);
											}
										},{	xtype	: 'button',
											id		: 'btn_input',
											iconCls	: 'input',
											text	: 'Input Data',
											scale	: 'medium',
											handler	: input
										},{	xtype	: 'button',
											id		: 'btn_src',
											iconCls	: 'search',
											text	: 'Search Data',
											scale	: 'medium',
											handler	: search
										},
										'->',
										{	text  	: 'Settings',
											id		: 'btn_settings',
											iconCls	: 'setting',
											scale	: 'medium',
											menu	: [
												{	text	: 'Problem Code',
													iconCls	: 'code',
													scale	: 'medium',
													handler	: cp
												}
											]
											
										},
										{ 	xtype		: 'label',
											text		: Ext.Date.format(new Date(), 'l, d F Y'),
											margins		: '15 5 0 5'
										}, 
										'-',
										clock,
										{xtype:'tbspacer',width:10}
								],
								bbar		: Ext.create('Ext.PagingToolbar', {
									pageSize	: itemperpage,
									store		: data_store,
									displayInfo	: true,
									plugins		: Ext.create('Ext.ux.ProgressBarPager', {}),
									listeners	: {
											afterrender: function(cmp) {
												cmp.getComponent("refresh").hide();
											}
									}
								})
							});
					//	end of grid panel
				//	----***----  //
				
				// Panel
					Ext.create('Ext.panel.Panel', {
						renderTo	: 'section',
						style		: { background :'rgba(0, 0, 0, 0)',border:'0'},
						bodyStyle	: { background :'rgba(0, 0, 0, 0)',border:'0'},
						width		: '100%',
						height		: 500,
						layout		: 'fit',
						items		: [grid_data]
					});
				// End of panel
			//-----------------------------------------------------------------------------//
					
			function input(){
				var win_input;
					
				if(!win_input) {
					var form_input = Ext.create('Ext.form.Panel',{
						layout			: {
							type			: 'hbox',
							align			: 'stretch'
						},
						layout			: 'column',
						border			: false,
						bodyPadding		: 20,
						bodyStyle		: 'background:url(img/banner.jpg) no-repeat top left',
						fieldDefaults	: {
							labelWidth		: 125,
							labelStyle		: 'font-weight:bold',
							msgTarget		: 'side'
						},
						defaults		: {
							anchor			: '100%'
						},
						//defaultType		: 'textfield',
						items			: [{
							xtype			: 'container',
							defaultType		: 'textfield',
							width			: 300,
							padding			: '0 10 0 0',
							items			: [
								{	fieldLabel			: 'Date/Shift',
									id					: 'fld_date',
									name				: 'fld_date',
									afterLabelTextTpl	: required,
									allowBlank			: false,
									labelSeparator		: ' '
								},{	fieldLabel			: 'MGZ No',
									id					: 'fld_mgz',
									name				: 'fld_mgz',
									afterLabelTextTpl	: required,
									allowBlank			: false,
									labelSeparator		: ' '
								},{ xtype				: 'fieldcontainer',
									id					: 'fld_ng',
									name				: 'fld_ng',
									afterLabelTextTpl	: required,
									allowBlank			: false,
									labelSeparator		: ' ',
									fieldLabel			: 'NG found',
									defaultType			: 'radiofield',
									defaults			: {
										flex 		: 1
									},
									layout				: 'vbox',
									items				: [
										{ 	boxLabel  : 'Sampling',
											name      : 'ngfnd',
											id        : 'ng_1',
											inputValue: 'SAMPLING',
											checked	  : true
										}, {
											boxLabel  : 'Machine',
											name      : 'ngfnd',
											id        : 'ng_2',
											inputValue: 'MACHINE',
										}, {
											boxLabel  : 'Cek 100% Critical Point',
											name      : 'ngfnd',
											id        : 'ng_3',
											inputValue: 'CEK 100% CRITICAL POINT',
											padding	  : '0 0 10 0'
										}
									],
									listeners			: {
										
									}
								},{ fieldLabel			: 'Board No',
									id					: 'fld_boardno',
									name				: 'fld_boardno',
									afterLabelTextTpl	: required,
									allowBlank			: false,
									labelSeparator		: ' ',
									maskRe				: /[.,0-9]/
								},{ fieldLabel			: 'QTY Board',
									id					: 'fld_qtyboard',
									name				: 'fld_qtyboard',
									afterLabelTextTpl	: required,
									allowBlank			: false,
									labelSeparator		: ' '
								},{	xtype				: 'combo',
									fieldLabel			: 'Problem Code',
									id					: 'fld_problem',
									name				: 'fld_problem',
									afterLabelTextTpl	: required,
									allowBlank			: false,
									labelSeparator		: ' '
								},{	fieldLabel			: 'Position',
									id					: 'fld_position',
									name				: 'fld_position',
									afterLabelTextTpl	: required,
									allowBlank			: false,
									labelSeparator		: ' '
								},{	fieldLabel			: 'Qty Point',
									id					: 'fld_qtypoint',
									name				: 'fld_qtypoint',
									afterLabelTextTpl	: required,
									allowBlank			: false,
									labelSeparator		: ' '
								}
							]
						},{
							xtype			: 'container',
							defaultType		: 'textfield',
							padding			: '0 0 0 10',
							width			: 300,
							items			: [
								{	fieldLabel			: 'Qty Select',
									id					: 'fld_qtyselect',
									name				: 'fld_qtyselect',
									afterLabelTextTpl	: required,
									allowBlank			: false,
									labelSeparator		: ' '
								},{ fieldLabel			: 'Qty NG',
									id					: 'fld_qtyng',
									name				: 'fld_qtyng',
									afterLabelTextTpl	: required,
									allowBlank			: false,
									labelSeparator		: ' '
								},{	fieldLabel			: 'Part No',
									id					: 'fld_partno',
									name				: 'fld_partno',
									afterLabelTextTpl	: required,
									allowBlank			: false,
									labelSeparator		: ' '
								},{ fieldLabel			: 'Repaired By',
									id					: 'fld_repair',
									name				: 'fld_repair',
									afterLabelTextTpl	: required,
									allowBlank			: false,
									labelSeparator		: ' '
								},{ fieldLabel			: 'Checked By',
									id					: 'fld_check',
									name				: 'fld_check',
									afterLabelTextTpl	: required,
									allowBlank			: false,
									labelSeparator		: ' '
								},{ fieldLabel			: 'Result',
									id					: 'fld_result',
									name				: 'fld_result',
									afterLabelTextTpl	: required,
									allowBlank			: false,
									labelSeparator		: ' '
								},{ xtype				: 'textareafield',
									fieldLabel			: 'Description',
									id					: 'fld_desc',
									name				: 'fld_desc',
									afterLabelTextTpl	: required,
									allowBlank			: false,
									labelSeparator		: ' '
								},{ fieldLabel			: 'PIC',
									id					: 'fld_pic',
									name				: 'fld_pic',
									afterLabelTextTpl	: required,
									allowBlank			: false,
									labelSeparator		: ' '
								}
							]
						}],
						buttons			: [
							{
								text		: 'Submit',
								iconCls		: 'submit',
								scale		: 'medium',
								formBind	: true,
								handler		: function() {
									var form = this.up('form').getForm();
									var popwindow = this.up('window');
									if (form.isValid()) {
										form.submit({
											//url		: 'resp/resp_sendmailnew.php',
											waitMsg	: 'sending data',
											success	: function(form, action) {
												data_store.loadPage(1);
												popwindow.close();
											},
											failure	: function(form, action) {
												Ext.Msg.show({
													title		:'Failure - Send Notification',
													icon		: Ext.Msg.ERROR,
													msg			: action.result.msg,
													buttons		: Ext.Msg.OK
												});
											}
										});
									}
								}
							}
						]
					});
					win_input = Ext.widget('window',{
						title			: '<p style="color:#000">Form Input',
						width			: 650,
						minWidth		: 650,
						height			: 400,
						minHeight		: 400,
						modal			: false,
						constrain		: true,
						layout			: 'fit',
						border			: false,
						bodyBorder		: false,
						animateTarget	: 'btn_input',
						items			: form_input,
						bodyStyle		: 'background:#008080',
						listeners		:{
							activate:function(){
								Ext.getCmp('btn_input').disable();
								Ext.getCmp('btn_src').disable();
								Ext.getCmp('btn_settings').disable();
							},
							close:function(){
								Ext.getCmp('btn_input').enable();
								Ext.getCmp('btn_src').enable();
								Ext.getCmp('btn_settings').enable();
							}
						}
					});
				}
				win_input.show();
			}
			
			function search(){
				var win_search;
					
				if(!win_search) {
					var form_search = Ext.create('Ext.form.Panel',{
						layout			: {
							type			: 'vbox',
							align			: 'stretch'
						},
						border			: false,
						bodyPadding		: 20,
						bodyStyle		: 'background:url(img/banner.jpg) no-repeat top left',
						fieldDefaults	: {
							labelWidth		: 125,
							labelStyle		: 'font-weight:bold',
							msgTarget		: 'side'
						},
						defaults		: {
							anchor			: '100%'
						},
						items			: [
							{ 	xtype		: 'textfield',
								id			: 'src_model',
								name		: 'src_model',
								fieldLabel	: 'Model Name',
								//allowBlank	: false
							},
							{ 	xtype		: 'textfield',
								id			: 'src_line',
								name		: 'src_line',
								fieldLabel	: 'Line',
								//allowBlank	: false
							},
							{ 	xtype		: 'textfield',
								id			: 'src_lotno',
								name		: 'src_lotno',
								fieldLabel	: 'Lot No'
							}
						],
						buttons			: [
							{
								text		: 'Search',
								iconCls		: 'search',
								scale		: 'medium',
								formBind	: true,
								handler		: function() {
									var form = this.up('form').getForm();
									var popwindow = this.up('window');
									if (form.isValid()) {
										form.submit({
											//url		: 'resp/resp_sendmailnew.php',
											waitMsg	: 'sending data',
											success	: function(form, action) {
												data_store.loadPage(1);
												popwindow.close();
											},
											failure	: function(form, action) {
												Ext.Msg.show({
													title		:'Failure - Send Notification',
													icon		: Ext.Msg.ERROR,
													msg			: action.result.msg,
													buttons		: Ext.Msg.OK
												});
											}
										});
									}
								}
							}
						]
					});
					win_search = Ext.widget('window',{
						title			: '<p style="color:#000">Form Input',
						width			: 400,
						minWidth		: 400,
						height			: 200,
						minHeight		: 200,
						modal			: false,
						constrain		: true,
						layout			: 'fit',
						border			: false,
						bodyBorder		: false,
						animateTarget	: 'btn_src',
						items			: form_search,
						bodyStyle		: 'background:#008080',
						listeners		:{
							activate:function(){
								Ext.getCmp('btn_input').disable();
								Ext.getCmp('btn_src').disable();
								Ext.getCmp('btn_settings').disable();
							},
							close:function(){
								Ext.getCmp('btn_input').enable();
								Ext.getCmp('btn_src').enable();
								Ext.getCmp('btn_settings').enable();
							}
						}
					});
				}
				win_search.show();
			}
			
			function cp(){
				var win_cp;
				
				if (!win_cp){
					var form_cp = Ext.create('Ext.grid.Panel',{
						layout      : 'fit',
						id			: 'cp_data',
						name		: 'cp_data',
						renderTo	: 'section',
						frame		: false,
						//iconCls		: 'grid',
						store		: cbx_prcode,
						width		: '100%',
						//height		: 300,
						x			: 0,
						y			: 0,
						columnLines	: true,
						multiSelect	: true,
						viewConfig	: {
								stripeRows          : true,
								enableTextSelection : true 
						},
						columns		: [
							{header: 'Problem No', 		dataIndex: 'problemno', width:80},
							{header: 'Problem Name', 	dataIndex: 'problemname', flex:1}
						],
						tbar	: [{xtype:'tbspacer',width:10},
								{	xtype	: 'button',
									id		: 'btn_cp_refresh',
									iconCls	: 'refresh',
									text 	: 'Refresh',
									tooltip	: 'Refresh',
									scale	: 'medium',
									handler : function (){
											data_store.loadPage(1);
									}
								},{	xtype	: 'button',
									id		: 'btn_cp_add',
									iconCls	: 'add',
									text	: 'Add Problem',
									scale	: 'medium',
									//handler	: input
								},{	xtype	: 'button',
									id		: 'btn_cp_del',
									iconCls	: 'delete',
									text	: 'Delete Problem',
									scale	: 'medium',
									//handler	: search
								}
						],
						bbar		: Ext.create('Ext.PagingToolbar', {
							pageSize	: itemprcode,
							store		: cbx_prcode,
							displayInfo	: true,
							plugins		: Ext.create('Ext.ux.ProgressBarPager', {}),
							listeners	: {
									afterrender: function(cmp) {
										cmp.getComponent("refresh").hide();
									}
							}
						})
					});
					win_cp = Ext.widget('window',{
						title			: '<p style="color:#000">Form Input',
						width			: 500,
						minWidth		: 500,
						height			: 230,
						minHeight		: 230,
						modal			: false,
						constrain		: true,
						layout			: 'fit',
						border			: false,
						bodyBorder		: false,
						animateTarget	: 'btn_settings',
						items			: form_cp,
						bodyStyle		: 'background:#008080',
						listeners		:{
							activate:function(){
								Ext.getCmp('btn_input').disable();
								Ext.getCmp('btn_src').disable();
								Ext.getCmp('btn_settings').disable();
							},
							close:function(){
								Ext.getCmp('btn_input').enable();
								Ext.getCmp('btn_src').enable();
								Ext.getCmp('btn_settings').enable();
							}
						}
					});
				}
				win_cp.show();
			}
		});
        </script>
    </head>
    <body>
        <div id="section">
            
        </div>
    </body>
</html>