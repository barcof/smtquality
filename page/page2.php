<!-- <!doctype html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
        <title>IM Quality Report</title> -->
        <script>
        Ext.Loader.setConfig({enabled: true});
		Ext.Loader.setPath('Ext.ux', '../framework/extjs-4.2.2/examples/ux/');
        
        Ext.onReady( function() {
            Ext.QuickTips.init();
		var itemperpage = 25;
		// store data
		Ext.define('disp_po',{
				extend	: 'Ext.data.Model',
				fields	: ['posys', 'costdept', 'costid', 'category', 'descid', 'supplierid', 'quono', 'poid', 'genpo', 'ponumber', 'podesc', 'podate', 'exid', 'bcno', 'delvdate', 'statusid', 'statapr', 'currid', 'currrate', 'prid', 'discount', 'condiscount', 'pototal', 'conamount', 'spdisc', 'vat', 'wht', 'pic', 'picno', 'chk1no', 'chk2no', 'apr1no', 'apr2no', 'backgrnd', 'rdesc', 'chk1', 'chk2', 'apr1', 'apr2', 'nextval', 'userlevel', 'pocat', 'costcenter', 'podiscount', 'poamount', 'suppliername', 'addressname', 'city', 'telp', 'faxno', 'person', 'country', 'descname', 'costcenter1', 'currname', 'deliver', 'invamount', 'payment', 'exedetail', 'fymonth', 'amount', 'cek', 'emailsend', 'chk1periode', 'chk2periode', 'apr1periode', 'apr2periode']
		});
		Ext.define('detail_po',{
			extend	: 'Ext.data.Model',
			fields	: ['chk1periode','chk2periode', 'apr1periode', 'apr2periode']
		});
		Ext.define('cbx_user',{
			extend	: 'Ext.data.Model',
			fields	: ['userno', 'pic']
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
		var cbx_store = Ext.create('Ext.data.Store',{
			model	: 'cbx_user',
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
								title		: 'OUTPUT CONTROL',
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
										enableTextSelection : true,
										getRowClass: function(record) {
											var stat = record.get('statusid');
											if (stat == 2) {
												return 'approved'; 
											}else if (stat == 3) {
												return 'closed'; 
											}else if (stat == 4) {
												return 'cancelled'; 
											}else if (stat == 5) {
												return 'delivered'; 
											}
										} 
								},
								//----------------------------COLUMN---------------------------//
								columns		: [ // Ext.util.Format.numberRenderer('0,000.00') --> untuk merubah format rupiah
									{ header: 'MGZ NO.', 	 	dataIndex: '', 	width:134, align: 'center' },
									//{ header: 'Model Name', 	dataIndex: '', 	flex:1 },
									{ header: 'OUTPUT TIME',		columns: [
										{ header: 'Date', 	 		dataIndex: '', 	width:200 },
										{ header: 'Shift', 	 		dataIndex: '', 	width:200 },
										{ header: 'Time', 	 		dataIndex: '', 	width:200 }
										]
									},
									{
										header: 'OUTPUT QTY',		columns: [
										{ header: 'Qty', 	 		dataIndex: '', 	width:200 },
										{ header: 'Shift Total', 	dataIndex: '', 	width:200 },
										{ header: 'Grand Total', 	dataIndex: '', 	width:200 }
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
										{
											xtype		: 'label',
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
							{ 	xtype				: 'textfield',
								id					: 'fld_mgz',
								name				: 'fld_mgz',
								fieldLabel			: 'MGZ no.',
								afterLabelTextTpl	: required,
								allowBlank			: false,
								labelSeparator		: ' ',
								maskRe				: /[.,0-9]/
							},
							{ 	xtype				: 'datefield',
								id					: 'fld_date',
								name				: 'fld_date',
								fieldLabel			: 'Date',
								afterLabelTextTpl	: required,
								allowBlank			: false,
								labelSeparator		: ' '
							},
							{ 	xtype				: 'fieldcontainer',
								id					: 'oc_rd',
								name				: 'oc_rd',
								afterLabelTextTpl	: required,
								allowBlank			: false,
								labelSeparator		: ' ',
								fieldLabel			: 'Shift',
								defaultType			: 'radiofield',
								defaults			: {
									flex 		: 1
								},
								layout				: 'hbox',
								items				: [
									{ 	boxLabel  : 'A',
										name      : 'shift',
										inputValue: 'A',
										id        : 'shift_a',
										checked	  : true
									}, {
										boxLabel  : 'B',
										name      : 'shift',
										inputValue: 'B',
										id        : 'shift_b'
									}, {
										boxLabel  : 'C',
										name      : 'shift',
										inputValue: 'C',
										id        : 'shift_c'
									}
								],
								listeners			: {
									
								}
							},
							{ 	xtype				: 'timefield',
								id					: 'waktu_awal',
								name				: 'waktu_awal',
								fieldLabel			: 'Time',
								afterLabelTextTpl	: required,
								allowBlank			: false,
								labelSeparator		: ' ',
								increment			: 30,
								format				: 'H:i'
							},
							{	xtype				: 'textfield',
								afterLabelTextTpl	: required,
								allowBlank			: false,
								id					: 'fld_qty',
								name				: 'fld_qty',
								fieldLabel			: 'Qty',
								labelSeparator		: ' '
							},{ xtype				: 'textfield',
								afterLabelTextTpl	: required,
								allowBlank			: false,
								id					: 'fld_shiftot',
								name				: 'fld_shiftot',
								fieldLabel			: 'Shift Total',
								labelSeparator		: ' '
							},{ xtype				: 'textfield',
								afterLabelTextTpl	: required,
								allowBlank			: false,
								id					: 'fld_gratot',
								name				: 'fld_gratot',
								fieldLabel			: 'Grand Total',
								labelSeparator		: ' '
							}
						],
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
						width			: 400,
						minWidth		: 400,
						height			: 300,
						minHeight		: 300,
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
							},
							close:function(){
								Ext.getCmp('btn_input').enable();
								Ext.getCmp('btn_src').enable();
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
							},
							close:function(){
								Ext.getCmp('btn_input').enable();
								Ext.getCmp('btn_src').enable();
							}
						}
					});
				}
				win_search.show();
			}
		});
        </script>
<!--     </head>
    <body>
        <div id="section">
            
        </div>
    </body>
</html> -->