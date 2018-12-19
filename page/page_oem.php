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
		Ext.define('get_field_oem',{
			extend	: 'Ext.data.Model',
			fields	: ['model_name', 'start_serial', 'prod_no', 'lot_size', 'pcb_name', 'pwb_no', 'process']
		});
		Ext.define('rejection_store',{
			extend	: 'Ext.data.Model',
			fields	: ['rejectid', 'inputid', 'partno', 'qtyselect', 'qtyng', 'repairedby', 'howtorepair', 'checkedby', 'fld_result', 'fld_desc', 'pic', 'file_name', 'reelno', 'inputdate','mdcode']
		});
		Ext.define('get_partno_repair',{
			extend	: 'Ext.data.Model',
			fields 	: ['partno','model','pcbname','location']
		});
		Ext.define('get_oem',{
			extend	: 'Ext.data.Model',
			fields 	: ['inputid','ngloc','symptom']
		});
		Ext.define('get_partaddress',{
			extend: 'Ext.data.Model',
			fields: ['partno','partaddress']
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
		var get_field_oem = Ext.create('Ext.data.Store',{
			model	: 'get_field_oem',
			autoLoad: false,
			proxy	: {
				type	: 'ajax',
				url		: 'json/json_get_field_oem.php',
				reader	: {
					type			: 'json',
					root			: 'rows',
					totalProperty	: 'totalCount'
				}
			},
			listeners: {
				load: function(store, records) {
					if (records != '') {
						var model = store.getAt(0).get('model_name');
						var stserial = store.getAt(0).get('start_serial');
						var prodno = store.getAt(0).get('prod_no');
						var lotsize = store.getAt(0).get('lot_size');
						var pcbname = store.getAt(0).get('pcb_name');
						var pwb_no = store.getAt(0).get('pwb_no');
						var process = store.getAt(0).get('process');
						Ext.getCmp('cbx_model').setValue(model);
						Ext.getCmp('fld_model').setValue(model);
						Ext.getCmp('fld_stserial').setValue(stserial);
						Ext.getCmp('fld_lotno').setValue(prodno);
						Ext.getCmp('fld_lotqty').setValue(lotsize);
						Ext.getCmp('fld_pcb').setValue(pcbname);
						Ext.getCmp('fld_pwb').setValue(pwb_no);
						Ext.getCmp('fld_proc').setValue(process);
						Ext.getCmp('hid_proc').setValue(process);
					} else {
						console.log(records);
					}
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
				url		: 'json/json_oem_inqual.php',
				reader	: {
					type			: 'json',
					root			: 'rows',
					totalProperty	: 'totalCount'
				}
			}
		});
		var cbx_howtorepair = Ext.create('Ext.data.Store', {
			fields: ['catval'],
			data : [
				{ "catval":"Touch Up" },
				{ "catval":"Change Part" }
				// { "catval":"Touch Up + Change Part" }
			]
		});
		var get_partno = Ext.create('Ext.data.Store',{
			model : 'get_partno_repair',
			autoLoad: true,
			proxy : {
				type: 'ajax',
				url: 'json/json_get_partno.php',
				reader: {
					type : 'json',
					root : 'rows'
				}
			},
			listeners: {
				load: function(store, records) {
					if (records != '') {
						var partno = store.getAt(0).get('partno');
						Ext.getCmp('fld_part_oem').setValue(partno);
						Ext.getCmp('fld_partno').setValue(partno);
					} else {
						console.log(records);
					}
				}
			}
		});
		var get_oem = Ext.create('Ext.data.Store',{
			model : 'get_oem',
			autoLoad: false,
			proxy : {
				type: 'ajax',
				url: 'json/json_get_oem.php',
				reader: {
					type : 'json',
					root : 'rows'
				}
			},
			listeners: {
				load: function(store, records) {
					if (records != '') {
						var inputid = store.getAt(0).get('inputid');
						var ngloc = store.getAt(0).get('ngloc');
						var symptom = store.getAt(0).get('symptom');
						Ext.getCmp('fld_inputid').setValue(inputid);
						Ext.getCmp('cbx_inputid').setValue(inputid);
						Ext.getCmp('label_ngloc').setValue(ngloc);
						Ext.getCmp('label_symptom').setValue(symptom);
					} else {
						console.log(records);
					}
				}
			}
		});
		var get_partaddress = Ext.create('Ext.data.Store',{
			model : 'get_partaddress',
			// autoLoad: true,
			proxy : {
				type: 'ajax',
				url: 'json/json_get_partaddress.php',
				reader: {
					type : 'json',
					root : 'rows'
				}
			},
			listeners: {
				load: function(store, records) {
					// console.log(records.value);
					if(records != '') {
						var partno = store.getAt(0).get('partno');
						var partaddress = store.getAt(0).get('partaddress');
						Ext.getCmp('label_partno').setText(partno);
						Ext.getCmp('fld_address').setValue(partaddress);
					} else {
						Ext.getCmp('label_partno').setText('Part Tidak Ada');
						Ext.getCmp('fld_address').setValue(null);
					}
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
			selModel: { selType: 'cellmodel' },
			plugins: [cellEditing],
			tbar	: [{xtype:'tbspacer',width:10},
				{	xtype	: 'button',
					id		: 'btn_refresh',
					iconCls	: 'refresh',
					iconAlign: 'top',
					text 	: 'Refresh',
					tooltip	: 'Refresh',
					scale	: 'medium',
					handler : function (){
						data_store.proxy.setExtraParam('src_mch', '');
						data_store.proxy.setExtraParam('src_model', '');
						data_store.proxy.setExtraParam('src_stserial', '');
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
						rejection_store.proxy.setExtraParam('inputid', '');
						rejection_store.loadPage(1);
					}
				},
				{	xtype	: 'button',
					id		: 'btn_input',
					iconCls	: 'input',
					iconAlign: 'top',
					text	: 'Input Data',
					scale	: 'medium',
					handler	: input
				},
				{ 	xtype	: 'button',
					id		: 'btn_update',
					iconCls	: 'update',
					iconAlign: 'top',
					text	: 'Update Data',
					scale	: 'medium',
					// handler	: update
				},
				{ 	xtype	: 'button',
					id		: 'btn_del',
					iconCls	: 'delete',
					iconAlign: 'top',
					text	: 'Delete Data',
					scale	: 'medium',
					// handler	: del
				},
				{	xtype	: 'button',
					id		: 'btn_src',
					iconCls	: 'search',
					iconAlign: 'top',
					text	: 'Search Data',
					scale	: 'medium',
					// handler	: search
					//hidden	: true // remove this to show search button
				},
				{	xtype	: 'button',
					id		: 'btn_input_serialno',
					iconCls	: 'input',
					iconAlign: 'top',
					text	: 'Input Serial No',
					scale	: 'medium',
					// handler	: input_serialno
				},
				{	xtype	: 'button',
					id		: 'btn_src_serialno',
					iconCls	: 'search',
					iconAlign: 'top',
					text	: 'Search Serial',
					scale	: 'medium',
					// handler	: search_serialno
				},
				{ 	xtype	: 'button',
					id		: 'btn_download',
					iconCls	: 'download',
					iconAlign: 'top',
					text	: 'Download',
					scale	: 'medium',
					// handler	: download
				},
				{ 	xtype: 'textfield',
					id: 'oemfollowup',
					name: 'oemfollowup',
					fieldLabel: 'Follow Up',
					emptyText: 'SCAN BOARD ID HERE ...',
					labelWidth: 55,
					listeners: {
						specialkey: function(field, e) {
							if(e.getKey() == e.ENTER) {
								var val = field.getValue();
								var len = val.length;
								if(len >= 24) {
									rejection();
									Ext.getCmp('fld_res').setValue(val);
								} else {
									Ext.MessageBox.alert('WARNING','<h1 style="color:red">PLEASE SCAN BOARD ID FIRST</h1>');
								}
								// console.log(len);
							} else { return false; }
						}
					}
				},
				'->',
				{	text  	: 'Settings',
					id		: 'btn_settings',
					iconCls	: 'setting',
					iconAlign: 'top',
					scale	: 'medium',
					menu	: [
						{	text	: 'Problem Code',
							iconCls	: 'machine-16',
							id		: 'btn_cp',
							// handler	: cp
						},
						{	text	: 'Machine Category',
							iconCls	: 'machine-16',
							id		: 'btn_mch',
							// handler	: mch
						},
						{	text	: 'PCB Category',
							iconCls	: 'machine-16',
							id		: 'btn_pcb',
							// handler	: pcb
						},
						{	text	: 'AI Category',
							iconCls	: 'machine-16',
							id		: 'btn_ai',
							// handler	: ai
						},
						{	text	: 'NG Category',
							iconCls	: 'machine-16',
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
			bbar: Ext.create('Ext.PagingToolbar', {
				pageSize: itemperpage,
				store: data_store,
				displayInfo: true,
				plugins: Ext.create('Ext.ux.ProgressBarPager', {}),
				listeners: {
					afterrender: function(cmp) {
						cmp.getComponent("refresh").hide();
					}
				}
			})
		});
		
		//-----------------------------------------------------[-Input Rejection-]
		function rejection(){
			var win_rejection;

			var boardid = Ext.getCmp('oemfollowup').getValue(); // get board id
			get_partno.proxy.setExtraParam('boardid',boardid); // set parameter to get partnumber based on boardid
			get_oem.proxy.setExtraParam('boardid',boardid); // set parameter to get inputid, symptom & location
			get_partno.loadPage(0);
			get_oem.loadPage(0);

			if(!win_rejection){
				var panel_rejection = Ext.create('Ext.panel.Panel',{
					layout		: 'border',
					frame		: false,
					bodyBorder	: false,
					defaults	: {
						collapsible	: true,
						split		: true
					},
					items: [{
						title		: 'Input Product',
						region		: 'north',
						items		: [{
							xtype		: 'form',
							id			: 'form_rejection',
							name		: 'form_rejection',
							width		: '100%',
							//height		: 245,
							bodyStyle	: {
								background	: 'url(img/banner.jpg) no-repeat top left',
								backgroundSize: 'cover'
							},
							style		: {
								background	: '#008080'
							},
							layout		: {
								type : 'hbox',
								pack : 'center',
								align: 'stretch'
							},
							items		: [
							{ 	xtype: 'container',
								layout:	'vbox',
								items: [{
									xtype : 'combo',
									id : 'cbx_inputid',
									name : 'cbx_inputid',
									fieldLabel : 'Input ID',
									afterLabelTextTpl : required,
									allowBlank : false,
									labelSeparator : ' ',
									margin : '5 10 5 10',
									width: 360,
									queryMode : 'local',
									store : get_oem,
									displayField : 'inputid',
									valueField : 'inputid',
									listeners: {
										select: function(combo, records, eOpts) {
											var boardid = this.getValue();
											var symptom = records[0].get('symptom');
											var nglocation = records[0].get('ngloc');
											// Ext.MessageBox.alert('INFO',symptom);
											Ext.getCmp('fld_inputid').setValue(boardid);
											Ext.getCmp('label_symptom').setValue(symptom);
											Ext.getCmp('label_ngloc').setValue(nglocation);
										}
									}
								},
								{	xtype			: 'textfield',
									margin			: '0 10 5 10',
									fieldLabel		: 'Symptom',
									id				: 'label_symptom',
									name			: 'label_symptom',
									labelSeparator	: ' ',
									readOnly		: true,
									width			: 360
								},
								{
									xtype			: 'fieldset',
									title			: '<b style="color:black;font-size:14px;">ACTION TO PRODUCT</b>',
									width			: 390,
									defaultType		: 'textfield',
									defaults		: {
										width	: 360
									},
									items			: [
									{ 	fieldLabel			: 'Part No',
										id					: 'fld_part_oem',
										name				: 'fld_part_oem',
										labelSeparator		: ' ',
										listeners: {
											specialkey: function(field, e) {
												if (e.getKey() == e.ENTER) {
													var reel = field.getValue().substr(0,15);
													field.setValue(reel);
													// var part = Ext.getCmp('fld_part').getValue();
													// if (reel != part) {
													// 	Ext.Msg.alert('WARNING','<span style="color:red;font-size:32px">Wrong Part !</span>');
													// 	field.reset();
													// } else {
													// 	Ext.Msg.alert('INFORMATION','<span style="color:green;font-size:32px">Part OK !</span>');
													// }
												}
											},
											change:function(field){
												field.setValue(field.getValue().toUpperCase());
											}
										}
									},{ fieldLabel			: 'Repaired By',
										id					: 'fld_repby',
										name				: 'fld_repby',
										labelSeparator		: ' '
									},{ xtype				: 'combo',
										fieldLabel			: 'How to Repair',
										id					: 'fld_howto',
										name				: 'fld_howto',
									   	queryMode			: 'local',
										store				: cbx_howtorepair,
										displayField		: 'catval',
										valueField			: 'catval',
										labelSeparator		: ' ',
										listeners			: {
											change: function() {
												var repair_val = this.getRawValue();
												if (repair_val.match('Change Part')) {
													Ext.getCmp('fld_reel').focus(false, 1);
												} else {
													Ext.getCmp('fld_checkby').focus(false, 1);
												}
											}
										}
									},{ fieldLabel			: 'Checked By',
										id					: 'fld_checkby',
										name				: 'fld_checkby',
										labelSeparator		: ' '
									},{ fieldLabel			: 'Board ID',
										id					: 'fld_res',
										name				: 'fld_res',
										labelSeparator		: ' '
									}]
								}]
							},{xtype:'tbspacer',width:10},
							{	xtype: 'container',
								layout:	'vbox',
								// width: 430,
								items: [{xtype:'tbspacer',height: 35},
									{	xtype			: 'textfield',
										margin			: '0 10 5 10',
										fieldLabel		: 'NG Location',
										id				: 'label_ngloc',
										name			: 'label_ngloc',
										labelSeparator	: ' ',
										readOnly		: true,
										width			: 360,
										// value			: location
									},
									{ 	xtype			: 'fieldset',
										title			: '<b style="color:black;font-size:14px;">ACTION TO PROCESS</b>',
										width			: 390,
										defaultType		: 'textfield',
										defaults		: {
											width	: 360,
											padding	: '4 0 4 0'
										},
										items			: [
											{	xtype				: 'textareafield',
												fieldLabel			: 'Description',
												id					: 'fld_desc',
												name				: 'fld_desc',
												labelSeparator		: ' '
											},{	fieldLabel			: 'PIC',
												id					: 'fld_pic',
												name				: 'fld_pic',
												labelSeparator		: ' '
											},{	xtype				: 'filefield',
												id					: 'fld_photo',
												name				: 'fld_photo',
												fieldLabel			: 'Upload Image',
												buttonText			: '•••',
												msgTarget			: 'side',
												labelSeparator		: ' '
											},{	xtype				: 'hiddenfield',
												id					: 'fld_inputid',
												name				: 'fld_inputid',
												labelSeparator		: ' ',
												listeners: {
													change: function(field) {
														console.log(field.getValue());
														var inputid = field.getValue();
														rejection_store.proxy.setExtraParam('inputid', inputid);
														rejection_store.loadPage(1);
													}
												}
											}
										]
									}, {
										xtype			: 'textfield',
										fieldLabel		: 'Reel Number',
										id				: 'fld_reel',
										name			: 'fld_reel',
										width			: 370,
										labelWidth		: 110,
										maxLength		: 30,
										emptyText		: 'Input If Repaired by Change Part',
										labelSeparator	: ' ',
										listeners 		: {
											specialkey: function(field, e) {
												if (e.getKey() == e.ENTER) {
													var reel = field.getValue().substr(0,15);
													var part = Ext.getCmp('fld_part').getValue();
													if (reel != part) {
														Ext.Msg.alert('WARNING','<span style="color:red;font-size:32px;text-align:center">Wrong Part !</span>');
														field.reset();
													} else {
														if (reel == '' || part == '') {
															Ext.Msg.alert('WARNING','<span style="color:red;font-size:32px;text-align:center">Part not yet input</span>');
														} else {
															Ext.Msg.alert('INFORMATION','<span style="color:green;font-size:32px;text-align:center">Part OK !</span>');
														}
													}
												}
							                },
							                change:function(field){
								                field.setValue(field.getValue().toUpperCase());
								            }
										}
									}
								]
							}],
							buttons		: [
								{
									text		: 'New',
									id			: 'add_rejection',
									iconCls		: 'add',
									scale		: 'medium',
									handler		: function() {
										this.up('form').getForm().reset();
									}
								},{
									text		: 'Submit',
									id			: 'submit_rejection',
									iconCls		: 'submit',
									scale		: 'medium',
									formBind	: true,
									handler		: function() {
										var form = this.up('form').getForm();
										var popwindow = this.up('window');
										if (form.isValid()) {
											form.submit({
												url				: 'resp/resp_input_rejection.php',
												waitMsg			: 'sending data',
												submitEmptyText	: false,

												success	: function(form, action) {
													Ext.Msg.show({
														title		:'Success - Input Data',
														icon		: Ext.Msg.SUCCESS,
														msg			: action.result.msg,
														buttons		: Ext.Msg.OK
													});
													form.reset();
													rejection_store.loadPage(0);
													data_store.loadPage(1);
												},

												failure	: function(form, action) {
													Ext.Msg.show({
														title		:'Failed - Input Data',
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
						}]
					},{
						title		: 'Product',
						region		: 'center',
						layout		: 'fit',
						items		: [{
							xtype		: 'grid',
							id			: 'grid_rejection',
							name		: 'grid_rejection',
							store		: rejection_store,
							columnLines	: true,
							multiSelect	: true,
							viewConfig	: {
									stripeRows          : true,
									enableTextSelection : true
							},
							columns		: [
								{header: 'No', xtype: 'rownumberer', width: 50, align: 'center'},
								{header: 'Reject ID', 	dataIndex: 'rejectid',		flex: 1,	hidden: true},
								{header: 'Input ID', 	dataIndex: 'inputid',		flex: 1,	hidden: true},
								{header: 'ACTION TO PRODUCT', 	columns: [
									{header: 'SELECTION PRODUCT', columns: [
										{header		: 'Qty Selection',
										 dataIndex	: 'qtyselect',
										 width		: 80,
										 editor		: {xtype:'textfield',maskRe: /[0-9.,]/,allowBlank:false}
										},
										{header		: 'Qty NG',
										 dataIndex	: 'qtyng',
										 width		: 60,
										 editor		: {xtype:'textfield',maskRe: /[0-9.,]/,allowBlank:false}
										}
									]},
									{header: 'REPAIRING', 		  columns: [
										{header		: 'Part No',
										 dataIndex	: 'partno',
										 width		: 80,
										 editor		: {xtype:'textfield',allowBlank:false}
										},
										{header		: 'Repaired By',
										 dataIndex	: 'repairedby',
										 width		: 70,
										 editor		: {xtype:'textfield',allowBlank:false}
										},
										{header		: 'How to Repair',
										 dataIndex	: 'howtorepair',
										 width		: 150,
										 editor		: {xtype: 'combo',queryMode: 'local',store: cbx_howtorepair,displayField: 'catval',valueField: 'catval'}
										}, 
										{header		: 'Reel Number',
										 dataIndex	: 'reelno',
										 width		: 150,
										 editor		: {xtype:'textfield',allowBlank:false}
										},
										{header		: 'Checked By',
										 dataIndex	: 'checkedby',
										 width		: 70,
										 editor		: {xtype:'textfield',allowBlank:false}
										},
										{header		: 'Board ID',
										 dataIndex	: 'fld_result',
										 width		: 150,
										 editor		: {xtype:'textfield',allowBlank:false}
										},
										{header		: 'Mother Code',
										 dataIndex	: 'mdcode',
										 width		: 150,
										 hidden 	: true,
										 editor		: {xtype:'textfield',allowBlank:false}
										}
									]}
								]},
								{header: 'ACTION TO PROCESS', 	columns: [
									{header		: 'Description',
									 dataIndex	: 'fld_desc',
									 width		: 100,
									 editor		: {xtype:'textfield',allowBlank:false}
									},
									{header		: 'PIC',
									 dataIndex	: 'pic',
									 width		: 100,
									 editor		: {xtype:'textfield',allowBlank:false}
									}
								]},
								{header: 'Rejection<br>(click to zoom)', 		dataIndex: 'file_name', 	width		: 100,		renderer: image, 	align: 'center'}
							],
							selModel	: {
								selType: 'cellmodel'
							},
							plugins		: [cellEditing],
							bbar		: Ext.create('Ext.PagingToolbar', {
								pageSize	: itemperpage,
								store		: rejection_store,
								displayInfo	: true,
								plugins		: Ext.create('Ext.ux.ProgressBarPager', {}),
								items: [
									{ xtype: 'button', text: 'Update', iconCls	: 'edit', scale: 'medium', iconAlign: 'left'/*, handler: update_rejection*/  }
								],
								// listeners	: {
								// 	afterrender: function(cmp) {
								// 		cmp.getComponent("refresh").hide();
								// 	}
								// }
							})
						}]
					}]
				});

				win_rejection = Ext.create('Ext.window.Window',{
					title			: '<p style="color:#000">Form Follow Up',
					width			: 966,
					minWidth		: 966,
					height			: 600,
					minHeight		: 600,
					layout			: 'fit',
					animateTarget	: 'oemfollowup',
					items			: panel_rejection,
					bodyStyle		: 'background:#008080',
					bodyBorder		: false,
					autoScroll		: true,
					modal			: false,
					constrain		: true,
					border			: false,
					listeners	: {
						activate:function(){
							Ext.ComponentQuery.query('textfield[name=oemfollowup]')[0].disable();
						},
						close:function(){
							Ext.ComponentQuery.query('textfield[name=oemfollowup]')[0].enable();
						}
					}
				});
			}
			win_rejection.show();
		}

		//-----------------------------------------------------[-Input Quality-]
		function input(){
			var win_input;

			if(!win_input) {
				var form_input = Ext.create('Ext.form.Panel',{
					layout			: {
						type			: 'hbox',
						align			: 'stretch'
					},
					border			: false,
					bodyPadding		: 20,
					bodyStyle		: 'background:url(img/banner.jpg) no-repeat top left',
					fieldDefaults	: {
						labelWidth		: 120,
						labelStyle		: 'font-weight:bold',
						msgTarget		: 'side',
						// width			: 300
					},
					// defaults		: {
					// 	anchor			: '100%'
					// },
					items			: [{
						xtype: 'hiddenfield',
						id: 'fld_inputstatus',
						name: 'fld_inputstatus',
						value: 1
					},
					{ 	xtype			: 'container',
						defaultType		: 'textfield',
						width			: 320,
						//padding			: '0 10 0 0',
						items			: [
						{
							xtype				: 'hiddenfield',
							id					: 'userlevel',
							name				: 'userlevel',
							value				: <?=$_SESSION['iqrs_userlevel']?>
						},
						{	xtype				: 'datefield',
							id					: 'fld_date',
							name				: 'fld_date',
							fieldLabel			: 'Date',
							labelSeparator		: ' ',
							hidden: true, // temporary hide
							format				: 'Y-m-d',
							listeners			: {
								select	: function(){
									Ext.getCmp('fld_mch').reset();
									Ext.getCmp('fld_model').reset();
								}
							}
						},{
							xtype: 'textfield',
							id: 'fld_nik',
							name: 'fld_nik',
							fieldLabel: 'PIC Input',
							afterLabelTextTpl: required,
							allowBlank: false,
							labelSeparator: ' ',
							emptyText: 'SCAN NIK HERE . . .'
						},
						{ 	xtype: 'textfield',
							id: 'fld_boardid',
							name: 'fld_boardid',
							fieldLabel: 'Board ID',
							afterLabelTextTpl: required,
							allowBlank: false,
							labelSeparator: ' ',
							listeners: {
								specialkey: function(field, e) {
									if(e.getKey() == e.ENTER) {
										var val = field.getValue();
										var len = val.length;
										if(len >= 24) {
											get_field_oem.proxy.setExtraParam('fld_boardid',val);
											get_field_oem.loadPage(0);
											Ext.getCmp('fld_stserial').enable();
											Ext.getCmp('fld_lotno').enable();
											Ext.getCmp('fld_lotqty').enable();
											Ext.getCmp('fld_pcb').enable();
											Ext.getCmp('fld_pwb').enable();
										} else {
											Ext.MessageBox.alert('WARNING','<h1 style="color:red">PLEASE SCAN BOARD ID FIRST</h1>');
										}
										// console.log(len);
									} else { return false; }
								}
							}
						},
						{	xtype				: 'combobox',
							id					: 'fld_mch',
							name				: 'fld_mch',
							fieldLabel			: 'Machine Name',
							afterLabelTextTpl	: required,
							allowBlank			: false,
							labelSeparator		: ' ',
							queryMode			: 'local',
							store				: cbx_mch,
							displayField		: 'mchname',
							valueField			: 'mchno'
						},
						{ 	xtype				: 'textfield',
							id					: 'cbx_model',
							name				: 'cbx_model',
							fieldLabel			: 'Model Name',
							afterLabelTextTpl	: required,
							allowBlank			: false,
							labelSeparator		: ' '
						},{	xtype      			: 'radiogroup',
							fieldLabel 			: 'Group',
							id					: 'fld_group',
							name				: 'fld_group',
							afterLabelTextTpl	: required,
							allowBlank			: false,
							labelSeparator		: ' ',
							items				: [
								{ boxLabel: '1', name: 'fld_group', inputValue: '1',checked: true, width: 50 },
								{ boxLabel: '2', name: 'fld_group', inputValue: '2', width: 50 },
								{ boxLabel: '3', name: 'fld_group', inputValue: '3', width: 50 }
							]
						},{	xtype				: 'radiogroup',
							fieldLabel			: 'Shift',
							id					: 'fld_shift',
							name				: 'fld_shift',
							afterLabelTextTpl	: required,
							allowBlank			: false,
							labelSeparator		: ' ',
							items				: [
								{ boxLabel: 'A', name: 'fld_shift', inputValue: 'A', checked: true, width: 50 },
								{ boxLabel: 'B', name: 'fld_shift', inputValue: 'B', width: 50 },
								{ boxLabel: 'C', name: 'fld_shift', inputValue: 'C', width: 50 }
							],
						},{	xtype				: 'hiddenfield',
							fieldLabel			: 'Model',
							id					: 'fld_model',
							name				: 'fld_model',
						},{	xtype				: 'textfield',
							fieldLabel			: 'Start Serial',
							id					: 'fld_stserial',
							name				: 'fld_stserial',
							afterLabelTextTpl	: required,
							allowBlank			: false,
							labelSeparator		: ' ',
							disabled			: true
						},{	xtype				: 'textfield',
							fieldLabel			: 'Lot No',
							id					: 'fld_lotno',
							name				: 'fld_lotno',
							afterLabelTextTpl	: required,
							allowBlank			: false,
							labelSeparator		: ' ',
							disabled			: true
						},{	xtype				: 'textfield',
							fieldLabel			: 'Lot Qty',
							id					: 'fld_lotqty',
							name				: 'fld_lotqty',
							afterLabelTextTpl	: required,
							allowBlank			: false,
							labelSeparator		: ' ',
							disabled			: true
						},{xtype				: 'textfield',
							fieldLabel			: 'PCB Name',
							id					: 'fld_pcb',
							name				: 'fld_pcb',
							afterLabelTextTpl	: required,
							allowBlank			: false,
							labelSeparator		: ' ',
							disabled			: true
						},{	xtype				: 'textfield',
							fieldLabel			: 'PWB No',
							id					: 'fld_pwb',
							name				: 'fld_pwb',
							afterLabelTextTpl	: required,
							allowBlank			: false,
							labelSeparator		: ' ',
							disabled			: true
						},{	xtype				: 'hiddenfield',
							fieldLabel			: 'Process',
							id					: 'hid_proc',
							name				: 'hid_proc',
							listeners: {
								change: function(field) {
									var x = field.getValue();
									var dm = Ext.getCmp('dm');
									var dm1 = Ext.getCmp('dm1');
									var dm2 = Ext.getCmp('dm2');
									var cm = Ext.getCmp('cm');
									switch (x) {
										case 'DM' :
											dm.setValue(true);
											break;
										case 'DM1' :
											dm1.setValue(true);
											break;
										case 'DM2' :
											dm2.setValue(true);
											break;
										case 'CM' :
											cm.setValue(true);
											break;
									}
								}
							}
						},{	xtype				: 'radiogroup',
							fieldLabel			: 'Process',
							id					: 'fld_proc',
							name				: 'fld_proc',
							afterLabelTextTpl	: required,
							allowBlank			: false,
							labelSeparator		: ' ',
							columns				: 2,
							items				: [
								{ id: 'dm', 	boxLabel: 'DM', 	name: 'fld_proc', 	inputValue: 'DM', 		width: 100, checked: true},
								{ id: 'dm1', 	boxLabel: 'DM 1', 	name: 'fld_proc', 	inputValue: 'DM 1', 	width: 50 	},
								{ id: 'dm2', 	boxLabel: 'DM 2', 	name: 'fld_proc', 	inputValue: 'DM 2', 	width: 100 	},
								{ id: 'cm',		boxLabel: 'CM', 	name: 'fld_proc', 	inputValue: 'CM', 		width: 50 	}
							]
						},{	xtype				: 'combobox',
							fieldLabel			: 'AI',
							id					: 'fld_ai',
							name				: 'fld_ai',
							//afterLabelTextTpl	: required,
							//allowBlank		: false,
							labelSeparator		: ' ',
							queryMode			: 'local',
							store				: cbx_ai,
							displayField		: 'ainame',
							valueField			: 'aino',
							hidden				: true
						}]
					},{ xtype			: 'container',
						// width			: 350,
						items			: [
						//field LINE REJECTION
						{ 	xtype			: 'fieldset',
							title			: 'LINE REJECTION',
							anchor			: '100%',
							defaultType		: 'textfield',
							defaults		: {
								padding: '10 0 10 0'
							},
							items			: [
								{	xtype				: 'combobox',
									fieldLabel			: 'Problem Code /<br>Symptom',
									id					: 'fld_prcode',
									name				: 'fld_prcode',
									afterLabelTextTpl	: required,
									allowBlank			: false,
									labelSeparator		: ' ',
									queryMode			: 'local',
									store				: cbx_prcode,
									displayField		: 'problemname',
									valueField			: 'problemno',
									editable			: false,
									listConfig			: {
										getInnerTpl	: function() {
											return '<div style="border:1px solid #fff"><b>{problemno} - </b>{problemname}</div>';
										}
									}
								},{ xtype				: 'textfield',
									fieldLabel			: 'Location',
									id					: 'fld_loc',
									name				: 'fld_loc',
									afterLabelTextTpl	: required,
									allowBlank			: false,
									labelSeparator		: ' ',
									listeners: {
										blur: function() {
											var model = Ext.getCmp('cbx_model').getValue();
											var pcb_name = Ext.getCmp('fld_pcb').getValue();
											get_partaddress.proxy.setExtraParam('model_name',model);
											get_partaddress.proxy.setExtraParam('pcb_name',pcb_name);
											get_partaddress.proxy.setExtraParam('location',this.getValue());
											get_partaddress.loadPage(0);
										}
									}
								},{ xtype: 'fieldcontainer',
									fieldLabel: 'Part Number',
									id: 'container_partno',
									name: 'container_partno',
									afterLabelTextTpl: required,
									labelSeparator: ' ',
									layout: 'vbox',
									anchor: '100%',
									items: [{
										xtype: 'textfield',
										id: 'fld_partno',
										name: 'fld_partno',
										allowBlank: false,
										listeners: {
											specialkey: function(field, e) {
												if(e.getKey() == e.ENTER) {
													// console.log(get_partaddress.getAt(0).get('partno'));
													// console.log(get_partaddress.getAt(0));
													var checkitem = get_partaddress.getAt(0);
													console.log(checkitem);
													// Ext.MessageBox.alert('INFO',get_partaddress.getAt(0));
													if (checkitem == null){
														var audio = document.getElementById('nopart');
														audio.autoplay = true;
														audio.load();
														Ext.Msg.show({
															title:'WARNING MESSAGE',
															msg: '<h1 style="color:#e53935;padding-top:5px;font-size:40px">PART TIDAK ADA !!!</h1>',
															buttons: Ext.Msg.OK,
															icon: Ext.Msg.ERROR
														});
													} else {
														var partnumber = checkitem.get('partno');
														if(partnumber != field) {
															var audio = document.getElementById('wrongpart');
															audio.autoplay = true;
															audio.load();
															Ext.Msg.show({
																title:'WARNING MESSAGE',
																msg: '<h1 style="color:#e53935;padding-top:5px;font-size:40px">PART TIDAK SAMA !!!</h1>',
																buttons: Ext.Msg.OK,
																icon: Ext.Msg.ERROR
															});
														} else {
															Ext.MessageBox.alert('INFO',field.getValue());
														}
													}
												}
											}
										}
									},{
										xtype: 'label',
										margin: '5 0 0 0',
										id: 'label_partno',
										name: 'label_partno'
									}]
								},{
									xtype: 'textfield',
									fieldLabel: 'Address Part',
									id: 'fld_address',
									name: 'fld_address',
									afterLabelTextTpl: required,
									allowBlank: false,
									labelSeparator: ' '
								},{ fieldLabel			: 'Magazine No',
									id					: 'fld_mag',
									name				: 'fld_mag',
									maskRe				: /[0-9,.]/,
									labelSeparator		: ' '
								},{	xtype				: 'combobox',
									fieldLabel			: 'Detection',
									id					: 'fld_ng',
									name				: 'fld_ng',
									afterLabelTextTpl	: required,
									allowBlank			: false,
									labelSeparator		: ' ',
									queryMode			: 'local',
									store				: cbx_ng,
									displayField		: 'ngname',
									valueField			: 'ngno',
									editable			: false
								}
							]
						}]
					}],
					buttons			: [
						{ 	text		: 'New',
							iconCls		: 'add',
							scale		: 'medium',
							handler		: function() {
								var form = this.up('form').getForm();
								form.reset();
								Ext.getCmp('label_partno').setText('');
							}
						},
						{ 	text		: 'Submit',
							iconCls		: 'submit',
							scale		: 'medium',
							formBind	: true,
							handler		: function() {
								var form = this.up('form').getForm();
								var popwindow = this.up('window');
								if (form.isValid()) {
									form.submit({
										url		: 'resp/resp_input_oem.php',
										waitMsg	: 'sending data',
										success	: function(form, action) {
											Ext.Msg.show({
												title		:'Success - Input Data',
												icon		: Ext.Msg.SUCCESS,
												msg			: action.result.msg,
												buttons		: Ext.Msg.OK
											});
											data_store.loadPage(1);
											Ext.getCmp('fld_prcode').reset();
											Ext.getCmp('fld_loc').reset();
											Ext.getCmp('fld_mag').reset();
											Ext.getCmp('fld_ng').reset();
											Ext.getCmp('fld_boardke').reset();
											Ext.getCmp('fld_boardqty').reset();
											Ext.getCmp('fld_pointqty').reset();
											//popwindow.close();
										},
										failure	: function(form, action) {
											Ext.Msg.show({
												title		:'Failure - Input Data',
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
					minWidth		: 670,
					height			: 450,
					minHeight		: 450,
					layout			: 'fit',
					animateTarget	: 'btn_input',
					items			: form_input,
					bodyStyle		: 'background:#008080',
					bodyBorder		: false,
					autoScroll		: true,
					modal			: false,
					constrain		: true,
					border			: false,
					listeners		:{
						activate:function(){
							Ext.getCmp('btn_input').disable();
						},
						close:function(){
							Ext.getCmp('btn_input').enable();
						}
					}
				});
			}
			win_input.show();
		}
	});
</script>