<script type="text/javascript">
	Ext.Loader.setConfig({enabled: true});
	Ext.Loader.setPath('Ext.ux', '../framework/extjs-4.2.2/examples/ux/');
	Ext.require([
		'Ext.ux.grid.FiltersFeature'
	]);

	Ext.override(Ext.form.TextField, {
		enableKeyEvents:true,
		onKeyUp: function (e,o){
			var getval = this.getValue();
			if (getval == '') {
				var value = getval;
			} else {
				var value = getval.toUpperCase();
			}
			// var value = getval.toUpperCase();
			this.setValue(value);
			this.fireEvent('keyup', this, e);
		}
	});

	Ext.onReady(function() {
		Ext.QuickTips.init();

		// custom function
		var cellEditing = Ext.create('Ext.grid.plugin.CellEditing', {
			clicksToEdit: 2
		});

		var itemperpage = 25;
		var itemprcode 	= 5;

		var required = '<span style="color:red;font-weight:bold" data-qtip="Required">*</span>';

		function image(val) {
			if (val == "" || val == null){
				return '';
			}else{

			return '<a href="uploaded/'+ val + '" target="_blank"> <img style="max-width:120px; max-height:120px;" src="uploaded/' + val + '" /> </a>';
			}
		}

		var filtersCfg = {
			ftype: 'filters',
			autoReload: true, //don't reload automatically
			local: true,
			filters: [
				{ type: 'date', dataIndex: 'dateid' },
				{ type: 'string', dataIndex: 'group' },
				{ type: 'string', dataIndex: 'shift' },
				{ type: 'string', dataIndex: 'mch' },
				{ type: 'string', dataIndex: 'model_name' },
				{ type: 'string', dataIndex: 'start_serial' },
				{ type: 'string', dataIndex: 'lot_no' },
				{ type: 'string', dataIndex: 'lot_qty' },
				{ type: 'string', dataIndex: 'pcb_name' },
				{ type: 'string', dataIndex: 'pwb_no' },
				{ type: 'string', dataIndex: 'process' },
				{ type: 'string', dataIndex: 'smt' },
				{ type: 'string', dataIndex: 'loc' },
				{ type: 'string', dataIndex: 'magazineno' },
				{ type: 'string', dataIndex: 'ng' },
				{ type: 'string', dataIndex: 'boardke' },
				{ type: 'string', dataIndex: 'boardqty' },
				{ type: 'string', dataIndex: 'pointqty' },
				{ type: 'string', dataIndex: 'inputdate' }
			]
		};

		var groupingFeature = Ext.create('Ext.grid.feature.GroupingSummary',{
			id: 'group',
			ftype: 'groupingsummary',
			enableGroupingMenu: true
		});

		Ext.define('disp_inqual',{
			extend	: 'Ext.data.Model',
			fields	: ['inputid','dateid','group','shift','mch','model_name','start_serial','serial_no','lot_no','lot_qty','pcb_name','pwb_no','process','ai','smt','loc','magazineno','ng','boardid','boardke','boardqty','pointqty','inputdate']
		});
		Ext.define('prcode_store',{
			extend	: 'Ext.data.Model',
			fields	: ['problemno', 'problemname']
		});
		Ext.define('mch_store',{
			extend	: 'Ext.data.Model',
			fields	: ['mchno', 'mchname']
		});
		Ext.define('pcb_store',{
			extend	: 'Ext.data.Model',
			fields	: ['pcbno', 'pcbname']
		});
		Ext.define('ai_store',{
			extend	: 'Ext.data.Model',
			fields	: ['aino', 'ainame']
		});
		Ext.define('ng_store',{
			extend	: 'Ext.data.Model',
			fields	: ['ngno', 'ngname']
		});
		Ext.define('fld_store',{
			extend	: 'Ext.data.Model',
			fields	: ['item_id', 'model_name', 'start_serial', 'prod_no', 'lot_size', 'pcb_name', 'pwb_no', 'process']
		});
		Ext.define('rejection_store',{
			extend	: 'Ext.data.Model',
			fields	: ['rejectid', 'inputid', 'partno', 'qtyselect', 'qtyng', 'repairedby', 'howtorepair', 'checkedby', 'fld_result', 'fld_desc', 'pic', 'file_name', 'reelno', 'inputdate','mdcode']
		});
		var prcode_store = Ext.create('Ext.data.Store',{
			model	: 'prcode_store',
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
		var mch_store = Ext.create('Ext.data.Store',{
			model	: 'mch_store',
			autoLoad: true,
			pageSize: 10,
			proxy	: {
				type	: 'ajax',
				url		: 'json/json_mch.php',
				reader	: {
					type			: 'json',
					root			: 'rows',
					totalProperty	: 'totalCount'
				}
			}
		});
		var pcb_store = Ext.create('Ext.data.Store',{
			model	: 'pcb_store',
			autoLoad: true,
			pageSize: 10,
			proxy	: {
				type	: 'ajax',
				url		: 'json/json_pcb.php',
				reader	: {
					type			: 'json',
					root			: 'rows',
					totalProperty	: 'totalCount'
				}
			}
		});
		var ai_store = Ext.create('Ext.data.Store',{
			model	: 'ai_store',
			autoLoad: true,
			pageSize: 10,
			proxy	: {
				type	: 'ajax',
				url		: 'json/json_ai.php',
				reader	: {
					type			: 'json',
					root			: 'rows',
					totalProperty	: 'totalCount'
				}
			}
		});
		var ng_store = Ext.create('Ext.data.Store',{
			model	: 'ng_store',
			autoLoad: true,
			pageSize: 10,
			proxy	: {
				type	: 'ajax',
				url		: 'json/json_ng.php',
				reader	: {
					type			: 'json',
					root			: 'rows',
					totalProperty	: 'totalCount'
				}
			}
		});
		var cbx_prcode = Ext.create('Ext.data.Store',{
			model	: 'prcode_store',
			autoLoad: true,
			proxy	: {
				type	: 'ajax',
				url		: 'json/json_cbx_prcode.php',
				reader	: {
					type			: 'json',
					root			: 'rows',
					totalProperty	: 'totalCount'
				}
			}
		});
		var cbx_mch = Ext.create('Ext.data.Store',{
			model	: 'mch_store',
			autoLoad: true,
			proxy	: {
				type	: 'ajax',
				url		: 'json/json_cbx_mch.php',
				reader	: {
					type			: 'json',
					root			: 'rows',
					totalProperty	: 'totalCount'
				}
			}
		});
		var cbx_pcb = Ext.create('Ext.data.Store',{
			model	: 'pcb_store',
			autoLoad: true,
			proxy	: {
				type	: 'ajax',
				url		: 'json/json_cbx_pcb.php',
				reader	: {
					type			: 'json',
					root			: 'rows',
					totalProperty	: 'totalCount'
				}
			}
		});
		var cbx_ai = Ext.create('Ext.data.Store',{
			model	: 'ai_store',
			autoLoad: true,
			proxy	: {
				type	: 'ajax',
				url		: 'json/json_cbx_ai.php',
				reader	: {
					type			: 'json',
					root			: 'rows',
					totalProperty	: 'totalCount'
				}
			}
		});
		var cbx_ng = Ext.create('Ext.data.Store',{
			model	: 'ng_store',
			autoLoad: true,
			proxy	: {
				type	: 'ajax',
				url		: 'json/json_cbx_ng.php',
				reader	: {
					type			: 'json',
					root			: 'rows',
					totalProperty	: 'totalCount'
				}
			}
		});
		var fld_store = Ext.create('Ext.data.Store',{
			model	: 'fld_store',
			autoLoad: true,
			proxy	: {
				type	: 'ajax',
				url		: 'json/json_get_field.php',
				reader	: {
					type			: 'json',
					root			: 'rows',
					totalProperty	: 'totalCount'
				}
			}
		});
		var rejection_store = Ext.create('Ext.data.Store',{
			model	: 'rejection_store',
			autoLoad: true,
			proxy	: {
				type	: 'ajax',
				url		: 'json/json_rejection.php',
				reader	: {
					type			: 'json',
					root			: 'rows',
					totalProperty	: 'totalCount'
				}
			}
		});
		var data_store = Ext.create('Ext.data.Store',{
			model	: 'disp_inqual',
			autoLoad: true,
			pageSize: itemperpage,
			proxy	: {
				type	: 'ajax',
				url		: 'json/json_inqual.php',
				reader	: {
					type			: 'json',
					root			: 'rows',
					totalProperty	: 'totalCount'
				}
			}
		});

		//	grid panel
		var clock = Ext.create('Ext.toolbar.TextItem', {text: Ext.Date.format(new Date(), 'g:i:s A')});
		var grid_data = Ext.create('Ext.grid.Panel', {
			title       : 'QUALITY INPUT FOR ADMIN',
			autoScroll	: true,
			layout      : 'fit',
			id			: 'qu_data',
			name	    : 'qu_data',
			iconCls		: 'grid',
			renderTo	: 'section',
			store       : data_store,
			width       : '100%',
			height		: 490,
			x			: 0,
			y			: 0,
			columnLines	: true,
			multiSelect	: true,
			viewConfig	: {
					stripeRows          : true,
					enableTextSelection : true
			},
			features: [groupingFeature,filtersCfg],
			//----------------------------COLUMN---------------------------//
			columns		: [ // Ext.util.Format.numberRenderer('0,000.00') --> untuk merubah format rupiah
				{ 	header		: 'NO.',		xtype: 'rownumberer', 	width:30, 	align: 'center' },
				{ 	header		: 'InputID',	dataIndex: 'inputid', 	flex:1, 	locked: false, hidden:true },
				{ 	header		: 'Date',	//Date
					dataIndex	: 'dateid',
					width		: 100,
					locked		: false,
					//renderer	: Ext.util.Format.dateRenderer('Y-m-d'),
					editor		: {xtype:'datefield',format:'Y-m-d'}
				},{ header		: 'Group',	//Group
					dataIndex	: 'group',
					width		: 50,
					locked		: false,
					editor		: {xtype:'textfield',allowBlank:false,maskRe:/[1-3]/,maxLength:1}
				},{ header		: 'Shift',	//Shift
					dataIndex	: 'shift',
					width		: 50,
					locked		: false,
					editor		: {xtype:'textfield',allowBlank:false,maskRe:/[a-c]/,maxLength:1}
				},{ header		: 'Machine Name',	//Machine Name
					dataIndex	: 'mch',
					width		: 80,
					locked		: false,
					editor		: {xtype:'combobox',queryMode: 'local',store: cbx_mch,displayField: 'mchname',valueField: 'mchname',editable: false}
				},{ header		: 'Model Name',	//Model Name
					dataIndex	: 'model_name',
					width		: 100,
					locked		: false
					//editor		: {xtype:'textfield',allowBlank:false}
				},{ header		: 'Start Serial',	//Start Serial
					dataIndex	: 'start_serial',
					width		: 100,
					locked		: false,
					editor		: {xtype:'textfield',allowBlank:false,maskRe:/[0-9]/}
				},{ header		: 'Serial No',	//Serial Number
					dataIndex	: 'serial_no',
					width		: 100,
					locked		: false
				},{ header		: 'Board ID',
					dataIndex	: 'boardid',
					width		: 70,
					editor		: {xtype:'textfield',allowBlank:false}
				},{ header		: 'Lot No',
					dataIndex	: 'lot_no',
					width		: 50,
					locked		: false,
					editor		: {xtype:'textfield',allowBlank:false}
				},{ header		: 'Lot Qty',
					dataIndex	: 'lot_qty',
					width		: 60,
					locked		: false,
					editor		: {xtype:'textfield',allowBlank:false}
				},{ header		: 'PCB Name',
					dataIndex	: 'pcb_name',
					width		: 100,
					locked		: false,
					summaryType	: 'count',
					editor		: {xtype:'combobox',queryMode: 'local',store: cbx_pcb,displayField: 'pcbname',valueField: 'pcbname',editable: false}
				},{ header		: 'PWB No',
					dataIndex	: 'pwb_no',
					width		: 100,
					locked		: false,
					editor		: {xtype:'textfield',allowBlank:false}
				},{ header		: 'Process',
					dataIndex	: 'process',
					width		: 60,
					locked		: false,
					editor		: {xtype:'textfield',allowBlank:false}
				},{ header		: 'AI',
					dataIndex	: 'ai',
					width		: 100,
					locked		: false,
					hidden		: true,
					editor		: {xtype:'combobox',queryMode: 'local',store: cbx_ai,displayField: 'ainame',valueField: 'ainame'}
				},{ header		: 'Problem/Symptom',
					dataIndex	: 'smt',
					width		:150,
					locked		: false,
					editor		: {xtype:'combobox',queryMode: 'local',store: cbx_prcode,displayField: 'problemname',valueField: 'problemname',editable: false,listConfig: {getInnerTpl	: function() {return '<div style="border:1px solid #fff"><b>{problemno} - </b>{problemname}</div>';}}}
				},{ header		: 'Location',
					dataIndex	: 'loc',
					width		:70,
					editor		: {xtype:'textfield',allowBlank:false}
				},{ header		: 'Magazine No',
					dataIndex	: 'magazineno',
					width		: 100,
					editor		: {xtype:'textfield',allowBlank:true}
				},{ header		: 'NG Found By',
					dataIndex	: 'ng',
					width		: 100,
					editor		: {xtype:'combobox',queryMode: 'local',store: cbx_ng,displayField: 'ngname',valueField: 'ngname',editable:false}
				},{ header		: 'Board No',
					dataIndex	: 'boardke',
					width		: 70,
					editor		: {xtype:'textfield',allowBlank:false}
				},{ header		: 'Board NG Qty',
					dataIndex	: 'boardqty',
					width		: 100,
					summaryType	: 'sum',
					editor		: {xtype:'textfield',allowBlank:false,maskRe:/[0-9]/}
				},{ header		: 'Point NG Qty',
					dataIndex	: 'pointqty',
					width		: 100,
					summaryType	: 'sum',
					editor		: {xtype:'textfield',allowBlank:false,maskRe:/[0-9]/}
				},{ header		: 'Input Date',
					dataIndex	: 'inputdate',
					width		: 130
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
				afterrender: function() {
					if (<?=$_SESSION['iqrs_userlevel']?>>0) {
						if (<?=$_SESSION['iqrs_userlevel']?>==5) {
							Ext.getCmp('btn_input').hide();
							Ext.getCmp('btn_update').hide();
							Ext.getCmp('btn_del').hide();
							Ext.getCmp('btn_input_serialno').hide();
							Ext.getCmp('btn_src_serialno').hide();
							Ext.getCmp('btn_add_reject').hide();
							Ext.getCmp('btn_settings').hide();
						} else if (<?=$_SESSION['iqrs_userlevel']?>==3) {
							Ext.getCmp('btn_input').hide();
							Ext.getCmp('btn_update').hide();
							Ext.getCmp('btn_del').hide();
							Ext.getCmp('btn_add_reject').hide();
							Ext.getCmp('btn_settings').hide();
							Ext.getCmp('btn_src').hide();
						} else if (<?=$_SESSION['iqrs_userlevel']?>==1) {
							Ext.getCmp('btn_pcb').hide();
							Ext.getCmp('btn_mch').hide();
						} else {
							Ext.getCmp('btn_settings').hide();
							Ext.getCmp('btn_input_serialno').hide();
							Ext.getCmp('btn_src_serialno').hide();
						}
					}  else {

					}
					
				}
			},
			selModel: {
				selType: 'cellmodel'
			},
			plugins: [cellEditing],
			tbar	: [{xtype:'tbspacer',width:10},
				{	xtype	: 'button',
					id		: 'btn_refresh',
					iconCls	: 'refresh',
					text 	: 'Refresh',
					tooltip	: 'Refresh',
					scale	: 'medium',
					handler : function (){
						data_store.proxy.setExtraParam('src_mch', '');
						data_store.proxy.setExtraParam('src_model', '');
						data_store.proxy.setExtraParam('src_stserial', 0);
						data_store.proxy.setExtraParam('src_lotno', '');
						data_store.proxy.setExtraParam('src_pcbname', '');
						data_store.proxy.setExtraParam('src_pwbno', '');
						data_store.proxy.setExtraParam('src_proc', '');
						data_store.loadPage(1);
						cbx_prcode.loadPage(1);
						cbx_mch.loadPage(1);
						cbx_pcb.loadPage(1);
						cbx_ai.loadPage(1);
						cbx_ng.loadPage(1);
						rejection_store.loadPage(1);
					}
				},
				{	xtype	: 'button',
					id		: 'btn_input',
					iconCls	: 'input',
					text	: 'Input Data',
					scale	: 'medium',
					// handler	: input
				},
				{ 	xtype	: 'button',
					id		: 'btn_update',
					iconCls	: 'update',
					text	: 'Update Data',
					scale	: 'medium',
					// handler	: update
				},
				{ 	xtype	: 'button',
					id		: 'btn_del',
					iconCls	: 'delete',
					text	: 'Delete Data',
					scale	: 'medium',
					// handler	: del
				},
				{	xtype	: 'button',
					id		: 'btn_src',
					iconCls	: 'search',
					text	: 'Search Data',
					scale	: 'medium',
					// handler	: search
					//hidden	: true // remove this to show search button
				},
				{ 	xtype	: 'button',
					id		: 'btn_add_reject',
					iconCls	: 'reject',
					text	: 'Follow Up',
					scale	: 'medium',
					// handler	: rejection
				},
				{	xtype	: 'button',
					id		: 'btn_input_serialno',
					iconCls	: 'input',
					text	: 'Input Serial No',
					scale	: 'medium',
					// handler	: input_serialno
				},
				{	xtype	: 'button',
					id		: 'btn_src_serialno',
					iconCls	: 'search',
					text	: 'Search Serial',
					scale	: 'medium',
					// handler	: search_serialno
				},
				{
					xtype	: 'button',
					id		: 'btn_download',
					iconCls	: 'download',
					text	: 'Download',
					scale	: 'medium',
					// handler	: download
				},
				'->',
				{	text  	: 'Settings',
					id		: 'btn_settings',
					iconCls	: 'setting',
					scale	: 'medium',
					menu	: [
						{	text	: 'Problem Code',
							iconCls	: 'machine-16',
							scale	: 'medium',
							id		: 'btn_cp',
							// handler	: cp
						},
						{	text	: 'Machine Category',
							iconCls	: 'machine-16',
							scale	: 'medium',
							id		: 'btn_mch',
							// handler	: mch
						},
						{	text	: 'PCB Category',
							iconCls	: 'machine-16',
							scale	: 'medium',
							id		: 'btn_pcb',
							// handler	: pcb
						},
						{	text	: 'AI Category',
							iconCls	: 'machine-16',
							scale	: 'medium',
							id		: 'btn_ai',
							// handler	: ai
						},
						{	text	: 'NG Category',
							iconCls	: 'machine-16',
							scale	: 'medium',
							id		: 'btn_ng',
							// handler	: ng
						}
					]

				},
				'-',
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

		// Ext.create('Ext.container.Viewport',{
		// 	layout: 'border',
		// 	renderTo: 'section',
		// 	border: false,
		// 	items: [{
		// 		region: 'north',
		// 		layout: 'fit',
		// 		height: 500,
		// 		// bodyStyle: 'background: rgba(255,255,255,0) !important'
		// 		// bodyStyle: 'background: grey !important',
		// 		html: '<?php=include_once("../menu.php;"); ?>'
		// 	}]
		// });
	});
</script>