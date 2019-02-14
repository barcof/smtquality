<!doctype html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
        <title>IM Quality Report</title>
        <script>
        Ext.Loader.setConfig({enabled: true});
		Ext.Loader.setPath('Ext.ux', '../framework/extjs-4.2.2/examples/ux/');
		Ext.require([
			'Ext.ux.grid.FiltersFeature'
		]);
		/*Ext.override(Ext.grid.RowNumberer, {
			renderer: function(value, metaData, record, rowIdx, colIdx, store) {
				var rowspan = this.rowspan;
				if (rowspan) {
					metaData.tdAttr = 'rowspan="' + rowspan + '"';
					alert(store.indexOfTotal ? (store.indexOfTotal(record) + 1) : (rowIdx + 1));
				}
				metaData.tdCls = Ext.baseCSSPrefix + 'x-grid-cell-row-numberer';
				return store.indexOfTotal ? (store.indexOfTotal(record) + 1) : (rowIdx + 1);
				//return store.indexOfTotal(record) + 1;
			}
		});*/
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
		//if (window.location.search.indexOf('scopecss') !== -1) {
		//	// We are using ext-all-scoped.css, so all rendered ExtJS Components must have a
		//	// reset wrapper round them to provide localized CSS resetting.
		//	Ext.scopeResetCSS = true;
		//}
        Ext.onReady( function() {
            Ext.QuickTips.init();

			// Add the additional 'advanced' VTypes
			Ext.apply(Ext.form.field.VTypes, {
				daterange: function(val, field) {
					var date = field.parseDate(val);

					if (!date) {
						return false;
					}
					if (field.startDateField && (!this.dateRangeMax || (date.getTime() != this.dateRangeMax.getTime()))) {
						var start = field.up('form').down('#' + field.startDateField);
						start.setMaxValue(date);
						start.validate();
						this.dateRangeMax = date;
					}
					else if (field.endDateField && (!this.dateRangeMin || (date.getTime() != this.dateRangeMin.getTime()))) {
						var end = field.up('form').down('#' + field.endDateField);
						end.setMinValue(date);
						end.validate();
						this.dateRangeMin = date;
					}
					/*
					 * Always return true since we're only using this vtype to set the
					 * min/max allowed values (these are tested for after the vtype test)
					 */
					return true;
				},

				daterangeText: 'Start date must be less than end date'
			});

			var itemperpage = 25;
			var itemprcode 	= 5;

			var cellEditing = Ext.create('Ext.grid.plugin.CellEditing', {
				clicksToEdit: 2
			});

			//function for rendering image
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
				filters: [{
					type: 'date',
					dataIndex: 'dateid'
				},{
					type: 'string',
					dataIndex: 'group'
				},{
					type: 'string',
					dataIndex: 'shift'
				},{
					type: 'string',
					dataIndex: 'mch'
				},{
					type: 'string',
					dataIndex: 'model_name'
				},{
					type: 'string',
					dataIndex: 'start_serial'
				},{
					type: 'string',
					dataIndex: 'lot_no'
				},{
					type: 'string',
					dataIndex: 'lot_qty'
				},{
					type: 'string',
					dataIndex: 'pcb_name'
				},{
					type: 'string',
					dataIndex: 'pwb_no'
				},{
					type: 'string',
					dataIndex: 'process'
				},{
					type: 'string',
					dataIndex: 'smt'
				},{
					type: 'string',
					dataIndex: 'loc'
				},{
					type: 'string',
					dataIndex: 'magazineno'
				},{
					type: 'string',
					dataIndex: 'ng'
				},{
					type: 'string',
					dataIndex: 'boardke'
				},{
					type: 'string',
					dataIndex: 'boardqty'
				},{
					type: 'string',
					dataIndex: 'pointqty'
				},{
					type: 'string',
					dataIndex: 'inputdate'
				}]
			};

			var groupingFeature = Ext.create('Ext.grid.feature.GroupingSummary',{
				id: 'group',
				ftype: 'groupingsummary',
				enableGroupingMenu: true
			});

			// store data
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
			Ext.define('get_partno_repair',{
				extend	: 'Ext.data.Model',
				fields 	: ['partno']
			});

			var cbx_howtorepair = Ext.create('Ext.data.Store', {
				fields: ['catval'],
				data : [
					{ "catval":"Touch Up" },
					{ "catval":"Change Part" }
					// { "catval":"Touch Up + Change Part" }
				]
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
							console.log(partno);
							Ext.getCmp('fld_part').setValue(partno);
						} else {
							console.log(records);
						}
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
			// ----***----  //

			//	Grid data
				var clock = Ext.create('Ext.toolbar.TextItem', {text: Ext.Date.format(new Date(), 'g:i:s A')});
				var grid_data = Ext.create('Ext.grid.Panel', {
						title       : 'QUALITY INPUT',
						autoScroll	: true,
						layout      : 'fit',
						id			: 'qu_data',
						name	    : 'qu_data',
						iconCls		: 'grid',
						renderTo	: 'section',
						store       : data_store,
						width       : '100%',
						height		: 500,
						columnLines	: true,
						multiSelect	: true,
						viewConfig	: {
								stripeRows          : true,
								enableTextSelection : true
						},
						features: [groupingFeature,filtersCfg],
						//----------------------------COLUMN---------------------------//
						columns		: [ // Ext.util.Format.numberRenderer('0,000.00') --> untuk merubah format rupiah
							{	header		: 'NO.',		xtype: 'rownumberer', 	width:30, 	align: 'center' },
							{	header		: 'InputID',	dataIndex: 'inputid', 	flex:1, 	locked: false, hidden:true },
							{	header		: 'Date',	//Date
								dataIndex	: 'dateid',
								width		: 100,
								locked		: false,
								renderer	: Ext.util.Format.dateRenderer('Y-m-d'),
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
								} else { }
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
									iconAlign: 'top',
									text 	: 'Refresh',
									tooltip	: 'Refresh',
									scale	: 'medium',
									handler : function (){
										data_store.proxy.setExtraParam('src_mch', '');
										data_store.proxy.setExtraParam('src_model', '');
										data_store.proxy.setExtraParam('src_stserial', '');
										data_store.proxy.setExtraParam('src_boardid', '');
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
									handler	: update
								},
								{ 	xtype	: 'button',
									id		: 'btn_del',
									iconCls	: 'delete',
									iconAlign: 'top',
									text	: 'Delete Data',
									scale	: 'medium',
									handler	: del
								},
								{	xtype	: 'button',
									id		: 'btn_src',
									iconCls	: 'search',
									iconAlign: 'top',
									text	: 'Search Data',
									scale	: 'medium',
									handler	: search
									//hidden	: true // remove this to show search button
								},
								{ 	xtype	: 'button',
									id		: 'btn_add_reject',
									iconCls	: 'reject',
									iconAlign: 'top',
									text	: 'Follow Up',
									scale	: 'medium',
									handler	: rejection
								},
								{	xtype	: 'button',
									id		: 'btn_input_serialno',
									iconCls	: 'input',
									iconAlign: 'top',
									text	: 'Input Serial No',
									scale	: 'medium',
									handler	: input_serialno
								},
								{	xtype	: 'button',
									id		: 'btn_src_serialno',
									iconCls	: 'search',
									iconAlign: 'top',
									text	: 'Search Serial',
									scale	: 'medium',
									handler	: search_serialno
								},
								{
									xtype	: 'button',
									id		: 'btn_download',
									iconCls	: 'download',
									iconAlign: 'top',
									text	: 'Download',
									scale	: 'medium',
									handler	: download
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
											handler	: cp
										},
										{	text	: 'Machine Category',
											iconCls	: 'machine-16',
											id		: 'btn_mch',
											handler	: mch
										},
										{	text	: 'PCB Category',
											iconCls	: 'machine-16',
											id		: 'btn_pcb',
											handler	: pcb
										},
										{	text	: 'AI Category',
											iconCls	: 'machine-16',
											id		: 'btn_ai',
											handler	: ai
										},
										{	text	: 'NG Category',
											iconCls	: 'machine-16',
											id		: 'btn_ng',
											handler	: ng
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
			//	----***----  //

			// Panel
				Ext.create('Ext.panel.Panel', {
					renderTo	: 'section',
					style		: { background :'rgba(0, 0, 0, 0)',border:'0'},
					bodyStyle	: { background :'rgba(0, 0, 0, 0)',border:'0'},
					width		: '100%',
					height		: 535,
					layout		: 'fit',
					items		: [grid_data]
				});
			// End of panel
			//-----------------------------------------------------------------------------//

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
						defaults		: {
							anchor			: '100%'
						},
						items			: [{
							xtype: 'hiddenfield',
							id: 'fld_inputstatus',
							name: 'fld_inputstatus',
							value: 0
						},
						{ 	xtype			: 'container',
							defaultType		: 'textfield',
							// width			: 320,
							//padding			: '0 10 0 0',
							items			: [
							{	// USER LEVEL
								xtype				: 'hiddenfield',
								id					: 'userlevel',
								name				: 'userlevel',
								value				: <?=$_SESSION['iqrs_userlevel']?>
							},
							{	xtype				: 'datefield', //DATE
								id					: 'fld_date',
								name				: 'fld_date',
								fieldLabel			: 'Date',
								afterLabelTextTpl	: required,
								allowBlank			: false,
								labelSeparator		: ' ',
								format				: 'Y-m-d',
								listeners			: {
									select	: function(){
										Ext.getCmp('fld_mch').reset();
										Ext.getCmp('fld_model').reset();
										fld_store.proxy.setExtraParam('fld_date',Ext.getCmp('fld_date').getValue());
									}
								}
							},{	xtype				: 'combobox',
								id					: 'fld_mch',
								name				: 'fld_mch',
								fieldLabel			: 'Machine Name',
								afterLabelTextTpl	: required,
								allowBlank			: false,
								labelSeparator		: ' ',
								queryMode			: 'local',
								store				: cbx_mch,
								displayField		: 'mchname',
								valueField			: 'mchno',
								listeners			: {
									select	: function(){
										Ext.getCmp('cbx_model').reset();
										Ext.getCmp('fld_model').reset();
										Ext.getCmp('fld_stserial').reset();
										Ext.getCmp('fld_lotno').reset();
										Ext.getCmp('fld_lotqty').reset();
										Ext.getCmp('fld_pcb').reset();
										Ext.getCmp('fld_pwb').reset();
										Ext.getCmp('fld_proc').reset();
										Ext.getCmp('hid_proc').reset();
										fld_store.proxy.setExtraParam('fld_mch',Ext.getCmp('fld_mch').getValue());
										fld_store.loadPage(1);
									},
									change  	: function(f,new_val) {
										Ext.getCmp('cbx_model').reset();
										Ext.getCmp('fld_model').reset();
									},
									scope		: this,
									specialkey	: function(f, e) {
									  if (e.getKey() === e.ENTER) {
										fld_store.proxy.setExtraParam('fld_mch',Ext.getCmp('fld_mch').getValue());
										fld_store.loadPage(1);
									  }
									}
								}
							},{ xtype				: 'combobox',
								id					: 'cbx_model',
								name				: 'cbx_model',
								fieldLabel			: 'Model Name',
								afterLabelTextTpl	: required,
								allowBlank			: false,
								labelSeparator		: ' ',
								queryMode			: 'local',
								store				: fld_store,
								displayField		: 'model_name',
								valueField			: 'item_id',
								listeners			: {
									select	: function(combo, records, eOpts){
										Ext.getCmp('fld_stserial').reset();
										Ext.getCmp('fld_lotno').reset();
										Ext.getCmp('fld_lotqty').reset();
										Ext.getCmp('fld_pcb').reset();
										Ext.getCmp('fld_pwb').reset();
										//Ext.getCmp('fld_lotno').setValue(fld_store.getAt(0).data.prod_no);
										Ext.getCmp('fld_model').setValue(records[0].get('model_name'));
										Ext.getCmp('fld_stserial').setValue(records[0].get('start_serial'));
										Ext.getCmp('fld_lotno').setValue(records[0].get('prod_no'));
										Ext.getCmp('fld_lotqty').setValue(records[0].get('lot_size'));
										Ext.getCmp('fld_pcb').setValue(records[0].get('pcb_name'));
										Ext.getCmp('fld_pwb').setValue(records[0].get('pwb_no'));
										Ext.getCmp('hid_proc').setValue(records[0].get('process'));
										Ext.getCmp('fld_stserial').enable();
										Ext.getCmp('fld_lotno').enable();
										Ext.getCmp('fld_lotqty').enable();
										Ext.getCmp('fld_pcb').enable();
										Ext.getCmp('fld_pwb').enable();
										fld_store.loadPage(1);

										var x = Ext.getCmp('hid_proc').getValue();
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
									},
									change	: function(){
										Ext.getCmp('fld_model').setValue(this.getValue());
										Ext.getCmp('fld_stserial').enable();
										Ext.getCmp('fld_lotno').enable();
										Ext.getCmp('fld_lotqty').enable();
										Ext.getCmp('fld_pcb').enable();
										Ext.getCmp('fld_pwb').enable();
									}
								},
								listConfig		: {
									getInnerTpl	: function() {
										//return '<table style="border:1px solid #fff"><tr><th style="border:1px solid #999">Model</th><th style="border:1px solid #999">Prod. No</th></tr><tr><td style="border:1px solid #999">{model_name}</td><td style="border:1px solid #999">{prod_no}</td></tr></table>';
										return '<div style="border:1px solid #fff"><b>{model_name} - </b>{prod_no}</div>';
									}
								}
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
											labelSeparator		: ' '
										},{ fieldLabel			: 'Magazine No',
											id					: 'fld_mag',
											name				: 'fld_mag',
											maskRe				: /[0-9,.]/,
											labelSeparator		: ' '
										},{	xtype				: 'combobox',
											fieldLabel			: 'NG Found',
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
								}
								//---------------------------------------------//
							]
						}],
						buttons			: [
							{ 	text		: 'New',
								iconCls		: 'add',
								scale		: 'medium',
								handler		: function() {
									var form = this.up('form').getForm();
									form.reset();
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
											url		: 'resp/resp_input_prodctrl.php',
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
						autoWidth		: '100%',
						minWidth		: 635,
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
								Ext.getCmp('btn_update').disable();
								Ext.getCmp('btn_add_reject').disable();
								Ext.getCmp('btn_settings').disable();
								//Ext.getCmp('btn_src').disable();
							},
							close:function(){
								Ext.getCmp('btn_input').enable();
								Ext.getCmp('btn_update').enable();
								Ext.getCmp('btn_add_reject').enable();
								Ext.getCmp('btn_settings').enable();
								//Ext.getCmp('btn_src').enable();
							}
						}
					});
				}
				win_input.show();
			}

			function input_cp(){
				var win_input_cp;

				if(!win_input_cp){
					var form_input_cp = Ext.create('Ext.form.Panel',{
						layout			: {
							type	: 'vbox',
							align	: 'stretch'
						},
						border			: false,
						bodyPadding		: 20,
						bodyStyle		: 'background:url(img/banner.jpg) no-repeat top left',
						fieldDefaults	: {
							labelWidth		: 120,
							labelStyle		: 'font-weight:bold',
							msgTarget		: 'side',
							width			: 300
						},
						defaults		: {
							anchor	: '100%'
						},
						items			: [
							{	xtype				: 'textfield',
								id					: 'fld_prno',
								name				: 'fld_prno',
								fieldLabel			: 'Problem No',
								afterLabelTextTpl	: required,
								allowBlank			: false,
								labelSeparator		: ' '
							},{	xtype				: 'textfield',
								id					: 'fld_prname',
								name				: 'fld_prname',
								fieldLabel			: 'Problem Name',
								afterLabelTextTpl	: required,
								allowBlank			: false,
								labelSeparator		: ' '
							}
						],
						buttons			: [
							{ 	text		: 'Submit',
								iconCls		: 'submit',
								scale		: 'medium',
								formBind	: true,
								handler		: function() {
									var form = this.up('form').getForm();
									var popwindow = this.up('window');
									if (form.isValid()) {
										form.submit({
											url		: 'resp/resp_input_prcode.php',
											waitMsg	: 'sending data',
											success	: function(form, action) {
												Ext.Msg.show({
													title		:'Success - Input Data',
													icon		: Ext.Msg.SUCCESS,
													msg			: action.result.msg,
													buttons		: Ext.Msg.OK
												});
												popwindow.close();
												prcode_store.loadPage(1);
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
					win_input_cp = Ext.widget('window',{
						title			: '<p style="color:#000">Form Input',
						width			: 350,
						minWidth		: 350,
						height			: 200,
						minHeight		: 200,
						layout			: 'fit',
						animateTarget	: 'btn_cp_add',
						items			: form_input_cp,
						bodyStyle		: 'background:#008080',
						bodyBorder		: false,
						autoScroll		: true,
						modal			: true,
						constrain		: true,
						border			: false,
						listeners		:{
							activate:function(){
								Ext.getCmp('btn_cp_add').disable();
								Ext.getCmp('btn_cp_refresh').disable();
								Ext.getCmp('btn_cp_del').disable();
							},
							close:function(){
								Ext.getCmp('btn_cp_add').enable();
								Ext.getCmp('btn_cp_refresh').enable();
								Ext.getCmp('btn_cp_del').enable();
							}
						}
					});
				}
				win_input_cp.show();
			}

			function input_mch(){
				var win_input_mch;

				if(!win_input_mch){
					var form_input_mch = Ext.create('Ext.form.Panel',{
						layout			: {
							type	: 'vbox',
							align	: 'stretch'
						},
						border			: false,
						bodyPadding		: 20,
						bodyStyle		: 'background:url(img/banner.jpg) no-repeat top left',
						fieldDefaults	: {
							labelWidth		: 120,
							labelStyle		: 'font-weight:bold',
							msgTarget		: 'side',
							width			: 300
						},
						defaults		: {
							anchor	: '100%'
						},
						items			: [
							{	xtype				: 'textfield',
								id					: 'fld_mchno',
								name				: 'fld_mchno',
								fieldLabel			: 'Machine No',
								afterLabelTextTpl	: required,
								allowBlank			: false,
								labelSeparator		: ' '
							},{	xtype				: 'textfield',
								id					: 'fld_mchname',
								name				: 'fld_mchname',
								fieldLabel			: 'Machine Name',
								afterLabelTextTpl	: required,
								allowBlank			: false,
								labelSeparator		: ' '
							}
						],
						buttons			: [
							{ 	text		: 'Submit',
								iconCls		: 'submit',
								scale		: 'medium',
								formBind	: true,
								handler		: function() {
									var form = this.up('form').getForm();
									var popwindow = this.up('window');
									if (form.isValid()) {
										form.submit({
											url		: 'resp/resp_input_mch.php',
											waitMsg	: 'sending data',
											success	: function(form, action) {
												Ext.Msg.show({
													title		:'Success - Input Data',
													icon		: Ext.Msg.SUCCESS,
													msg			: action.result.msg,
													buttons		: Ext.Msg.OK
												});
												popwindow.close();
												mch_store.loadPage(1);
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
					win_input_mch = Ext.widget('window',{
						title			: '<p style="color:#000">Form Input',
						width			: 350,
						minWidth		: 350,
						height			: 200,
						minHeight		: 200,
						layout			: 'fit',
						animateTarget	: 'btn_mch_add',
						items			: form_input_mch,
						bodyStyle		: 'background:#008080',
						bodyBorder		: false,
						autoScroll		: true,
						modal			: true,
						constrain		: true,
						border			: false,
						listeners		:{
							activate:function(){
								Ext.getCmp('btn_mch_add').disable();
								Ext.getCmp('btn_mch_refresh').disable();
								Ext.getCmp('btn_mch_del').disable();
							},
							close:function(){
								Ext.getCmp('btn_mch_add').enable();
								Ext.getCmp('btn_mch_refresh').enable();
								Ext.getCmp('btn_mch_del').enable();
							}
						}
					});
				}
				win_input_mch.show();
			}

			function input_pcb(){
				var win_input_pcb;

				if(!win_input_pcb){
					var form_input_pcb = Ext.create('Ext.form.Panel',{
						layout			: {
							type	: 'vbox',
							align	: 'stretch'
						},
						border			: false,
						bodyPadding		: 20,
						bodyStyle		: 'background:url(img/banner.jpg) no-repeat top left',
						fieldDefaults	: {
							labelWidth		: 120,
							labelStyle		: 'font-weight:bold',
							msgTarget		: 'side',
							width			: 300
						},
						defaults		: {
							anchor	: '100%'
						},
						items			: [
							{	xtype				: 'textfield',
								id					: 'fld_pcbno',
								name				: 'fld_pcbno',
								fieldLabel			: 'PCB No',
								afterLabelTextTpl	: required,
								allowBlank			: false,
								labelSeparator		: ' '
							},{	xtype				: 'textfield',
								id					: 'fld_pcbname',
								name				: 'fld_pcbname',
								fieldLabel			: 'PCB Name',
								afterLabelTextTpl	: required,
								allowBlank			: false,
								labelSeparator		: ' '
							}
						],
						buttons			: [
							{ 	text		: 'Submit',
								iconCls		: 'submit',
								scale		: 'medium',
								formBind	: true,
								handler		: function() {
									var form = this.up('form').getForm();
									var popwindow = this.up('window');
									if (form.isValid()) {
										form.submit({
											url		: 'resp/resp_input_pcb.php',
											waitMsg	: 'sending data',
											success	: function(form, action) {
												Ext.Msg.show({
													title		:'Success - Input Data',
													icon		: Ext.Msg.SUCCESS,
													msg			: action.result.msg,
													buttons		: Ext.Msg.OK
												});
												popwindow.close();
												pcb_store.loadPage(1);
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
					win_input_pcb = Ext.widget('window',{
						title			: '<p style="color:#000">Form Input',
						width			: 350,
						minWidth		: 350,
						height			: 200,
						minHeight		: 200,
						layout			: 'fit',
						animateTarget	: 'btn_pcb_add',
						items			: form_input_pcb,
						bodyStyle		: 'background:#008080',
						bodyBorder		: false,
						autoScroll		: true,
						modal			: true,
						constrain		: true,
						border			: false,
						listeners		:{
							activate:function(){
								Ext.getCmp('btn_pcb_add').disable();
								Ext.getCmp('btn_pcb_refresh').disable();
								Ext.getCmp('btn_pcb_del').disable();
							},
							close:function(){
								Ext.getCmp('btn_pcb_add').enable();
								Ext.getCmp('btn_pcb_refresh').enable();
								Ext.getCmp('btn_pcb_del').enable();
							}
						}
					});
				}
				win_input_pcb.show();
			}

			function input_ai(){
				var win_input_ai;

				if(!win_input_ai){
					var form_input_ai = Ext.create('Ext.form.Panel',{
						layout			: {
							type	: 'vbox',
							align	: 'stretch'
						},
						border			: false,
						bodyPadding		: 20,
						bodyStyle		: 'background:url(img/banner.jpg) no-repeat top left',
						fieldDefaults	: {
							labelWidth		: 120,
							labelStyle		: 'font-weight:bold',
							msgTarget		: 'side',
							width			: 300
						},
						defaults		: {
							anchor	: '100%'
						},
						items			: [
							{	xtype				: 'textfield',
								id					: 'fld_aino',
								name				: 'fld_aino',
								fieldLabel			: 'AI No',
								afterLabelTextTpl	: required,
								allowBlank			: false,
								labelSeparator		: ' '
							},{	xtype				: 'textfield',
								id					: 'fld_ainame',
								name				: 'fld_ainame',
								fieldLabel			: 'AI Name',
								afterLabelTextTpl	: required,
								allowBlank			: false,
								labelSeparator		: ' '
							}
						],
						buttons			: [
							{ 	text		: 'Submit',
								iconCls		: 'submit',
								scale		: 'medium',
								formBind	: true,
								handler		: function() {
									var form = this.up('form').getForm();
									var popwindow = this.up('window');
									if (form.isValid()) {
										form.submit({
											url		: 'resp/resp_input_ai.php',
											waitMsg	: 'sending data',
											success	: function(form, action) {
												Ext.Msg.show({
													title		:'Success - Input Data',
													icon		: Ext.Msg.SUCCESS,
													msg			: action.result.msg,
													buttons		: Ext.Msg.OK
												});
												popwindow.close();
												ai_store.loadPage(1);
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
					win_input_ai = Ext.widget('window',{
						title			: '<p style="color:#000">Form Input',
						width			: 350,
						minWidth		: 350,
						height			: 200,
						minHeight		: 200,
						layout			: 'fit',
						animateTarget	: 'btn_ai_add',
						items			: form_input_ai,
						bodyStyle		: 'background:#008080',
						bodyBorder		: false,
						autoScroll		: true,
						modal			: true,
						constrain		: true,
						border			: false,
						listeners		:{
							activate:function(){
								Ext.getCmp('btn_ai_add').disable();
								Ext.getCmp('btn_ai_refresh').disable();
								Ext.getCmp('btn_ai_del').disable();
							},
							close:function(){
								Ext.getCmp('btn_ai_add').enable();
								Ext.getCmp('btn_ai_refresh').enable();
								Ext.getCmp('btn_ai_del').enable();
							}
						}
					});
				}
				win_input_ai.show();
			}

			function input_ng(){
				var win_input_ng;

				if(!win_input_ng){
					var form_input_ng = Ext.create('Ext.form.Panel',{
						layout			: {
							type	: 'vbox',
							align	: 'stretch'
						},
						border			: false,
						bodyPadding		: 20,
						bodyStyle		: 'background:url(img/banner.jpg) no-repeat top left',
						fieldDefaults	: {
							labelWidth		: 120,
							labelStyle		: 'font-weight:bold',
							msgTarget		: 'side',
							width			: 300
						},
						defaults		: {
							anchor	: '100%'
						},
						items			: [
							{	xtype				: 'textfield',
								id					: 'fld_ngno',
								name				: 'fld_ngno',
								fieldLabel			: 'NG No',
								afterLabelTextTpl	: required,
								allowBlank			: false,
								labelSeparator		: ' '
							},{	xtype				: 'textfield',
								id					: 'fld_ngname',
								name				: 'fld_ngname',
								fieldLabel			: 'NG Name',
								afterLabelTextTpl	: required,
								allowBlank			: false,
								labelSeparator		: ' '
							}
						],
						buttons			: [
							{ 	text		: 'Submit',
								iconCls		: 'submit',
								scale		: 'medium',
								formBind	: true,
								handler		: function() {
									var form = this.up('form').getForm();
									var popwindow = this.up('window');
									if (form.isValid()) {
										form.submit({
											url		: 'resp/resp_input_ng.php',
											waitMsg	: 'sending data',
											success	: function(form, action) {
												Ext.Msg.show({
													title		:'Success - Input Data',
													icon		: Ext.Msg.SUCCESS,
													msg			: action.result.msg,
													buttons		: Ext.Msg.OK
												});
												popwindow.close();
												ng_store.loadPage(1);
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
					win_input_ng = Ext.widget('window',{
						title			: '<p style="color:#000">Form Input',
						width			: 350,
						minWidth		: 350,
						height			: 200,
						minHeight		: 200,
						layout			: 'fit',
						animateTarget	: 'btn_ng_add',
						items			: form_input_ng,
						bodyStyle		: 'background:#008080',
						bodyBorder		: false,
						autoScroll		: true,
						modal			: true,
						constrain		: true,
						border			: false,
						listeners		:{
							activate:function(){
								Ext.getCmp('btn_ng_add').disable();
								Ext.getCmp('btn_ng_refresh').disable();
								Ext.getCmp('btn_ng_del').disable();
							},
							close:function(){
								Ext.getCmp('btn_ng_add').enable();
								Ext.getCmp('btn_ng_refresh').enable();
								Ext.getCmp('btn_ng_del').enable();
							}
						}
					});
				}
				win_input_ng.show();
			}

			function input_serialno(){
				var rec = grid_data.getSelectionModel().getSelection();
				if (rec == 0){
					Ext.Msg.show({
						title	: 'Failure - Select Data',
						icon	: Ext.Msg.ERROR,
						msg		: 'Select field to input serial numer !',
						buttons	: Ext.Msg.OK
					});
				} else {
					var win_serialno;
					var inputid = rec[0].data.inputid;
					var boardid	= rec[0].data.boardid;
					if(!win_serialno){
						var form_serialno = Ext.create('Ext.form.Panel',{
							layout			: {
								type			: 'vbox',
								align			: 'stretch'
							},
							border			: false,
							bodyPadding		: 20,
							bodyStyle		: 'background:url(img/banner.jpg) no-repeat top left',
							fieldDefaults	: {
								labelWidth		: 120,
								labelStyle		: 'font-weight:bold',
								msgTarget		: 'side',
								width			: 300
							},
							defaults		: {
								anchor			: '100%'
							},
							items			: [
								{	xtype				: 'textfield',
									fieldLabel			: 'Serial Number',
									name				: 'fld_serialno',
									id					: 'fld_serialno',
									allowBlank			: false,
									labelSeparator		: ' '
								},{
									xtype				: 'hiddenfield',
									name				: 'inputid',
									id					: 'inputid',
									value				: inputid
								},{
									xtype				: 'hiddenfield',
									name				: 'boardid',
									id					: 'boardid',
									value				: boardid
								}
							],
							buttons			: [
								{	text		: 'Submit',
									iconCls		: 'submit',
								 	formBind	: true,
									//iconAlign	: 'top',
									id			: 'submit',
									name		: 'submit',
									scale		: 'medium',
									handler		: function(field) {
										var form = this.up('form').getForm();
										var popwindow = this.up('window');
										if (form.isValid()) {
											form.submit({
												url		: 'resp/resp_input_serialno.php',
												waitMsg	: 'sending data',
												success	: function(form, action) {
													Ext.Msg.show({
														title		:'Success - Input Data',
														icon		: Ext.Msg.SUCCESS,
														msg			: action.result.msg,
														buttons		: Ext.Msg.OK
													});
													data_store.loadPage(1);
													Ext.getCmp('fld_serialno').reset();
													popwindow.close();
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
						win_serialno = Ext.widget('window',{
							title			: '<p style="color:#000">Form Input',
							width			: 350,
							minWidth		: 350,
							height			: 150,
							minHeight		: 150,
							layout			: 'fit',
							animateTarget	: 'btn_input_serialno',
							items			: form_serialno,
							bodyStyle		: 'background:#008080',
							formBind		: true,
							autoScroll		: true,
							modal			: false,
							constrain		: true,
							border			: false,
							defaultFocus 	: 'fld_serialno',
						});
					}
					win_serialno.show();
				}
			}

			function del_cp(){
				var rec = Ext.getCmp('cp_data').getSelectionModel().getSelection();
				if(rec == 0){
					Ext.Msg.show({
						title	: 'Failure - Select Data',
						icon	: Ext.Msg.ERROR,
						msg		: 'Select field to delete data !',
						buttons	: Ext.Msg.OK
					})
				} else {
					var problemno 	= rec[0].data.problemno;
					var problemname = rec[0].data.problemname;

					var win_del_cp;
					if(!win_del_cp){
						var form_del_cp = Ext.create('Ext.form.Panel',{
							layout		: {
								type		: 'vbox',
								align		: 'stretch'
							},
							border			: false,
							bodyPadding		: 20,
							bodyStyle		: 'background:url(img/banner.jpg) no-repeat top left',
							fieldDefaults	: {
								labelWidth		: 120,
								labelStyle		: 'font-weight:bold',
								msgTarget		: 'side',
								width			: 300
							},
							defaults		: {
								anchor	: '100%'
							},
							items			: [
								{	xtype	: 'label',
									html	: '<p>Delete this data ?<br><br>Problem NO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: '+problemno+'<br>Problem Name : '+problemname+'</p>'
								},{	xtype	: 'hiddenfield',
									id		: 'fld_del_prno',
									name	: 'fld_del_prno',
									value	: problemno
								},{	xtype	: 'hiddenfield',
									id		: 'fld_del_prname',
									name	: 'fld_del_prname',
									value	: problemname
								}
							],
							buttons			: [
								{
									text	: 'DELETE',
									handler		: function() {
										var form = this.up('form').getForm();
										var popwindow = this.up('window');
										if (form.isValid()) {
											form.submit({
												url		: 'resp/resp_del_prcode.php',
												waitMsg	: 'deleting data',
												success	: function(form, action) {
													Ext.Msg.show({
														title		:'Success - Delete Data',
														icon		: Ext.Msg.SUCCESS,
														msg			: action.result.msg,
														buttons		: Ext.Msg.OK
													});
													popwindow.close();
													prcode_store.loadPage(1);
												},
												failure	: function(form, action) {
													Ext.Msg.show({
														title		:'Failure - Delete Data',
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
						win_del_cp = Ext.widget('window', {
							title			: 'DELETE DATA',
							width			: 400,
							minWidth		: 400,
							height			: 200,
							minHeight		: 200,
							modal			: true,
							constrain		: true,
							layout			: 'fit',
							animateTarget	: 'btn_cp_del',
							items			: form_del_cp,
							listeners		:{
								activate:function(){
									Ext.getCmp('btn_cp_add').disable();
									Ext.getCmp('btn_cp_refresh').disable();
									Ext.getCmp('btn_cp_del').disable();
								},
								 close:function(){
									Ext.getCmp('btn_cp_add').enable();
									Ext.getCmp('btn_cp_refresh').enable();
									Ext.getCmp('btn_cp_del').enable();
								}
							}
						});
					}
					win_del_cp.show();
				}
			}

			function del_mch(){
				var rec = Ext.getCmp('mch_data').getSelectionModel().getSelection();
				if(rec == 0){
					Ext.Msg.show({
						title	: 'Failure - Select Data',
						icon	: Ext.Msg.ERROR,
						msg		: 'Select field to delete data !',
						buttons	: Ext.Msg.OK
					})
				} else {
					var mchno 	= rec[0].data.mchno;
					var mchname = rec[0].data.mchname;

					var win_del_mch;
					if(!win_del_mch){
						var form_del_mch = Ext.create('Ext.form.Panel',{
							layout		: {
								type		: 'vbox',
								align		: 'stretch'
							},
							border			: false,
							bodyPadding		: 20,
							bodyStyle		: 'background:url(img/banner.jpg) no-repeat top left',
							fieldDefaults	: {
								labelWidth		: 120,
								labelStyle		: 'font-weight:bold',
								msgTarget		: 'side',
								width			: 300
							},
							defaults		: {
								anchor	: '100%'
							},
							items			: [
								{
									xtype	: 'label',
									html	: '<p>Delete this data ?<br><br>Machine NO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: '+mchno+'<br>Machine Name : '+mchname+'</p>'
								},{	xtype	: 'hiddenfield',
									id		: 'fld_del_mchno',
									name	: 'fld_del_mchno',
									value	: mchno
								},{	xtype	: 'hiddenfield',
									id		: 'fld_del_mchname',
									name	: 'fld_del_mchname',
									value	: mchname
								}
							],
							buttons			: [
								{
									text	: 'DELETE',
									handler	: function(){
										var form = this.up('form').getForm();
										var popwindow = this.up('window');
										if(form.isValid()){
											form.submit({
												url		: 'resp/resp_del_mch.php',
												waitMsg	: 'deleting data',
												success	: function(form,action){
													Ext.Msg.show({
														title		:'Success - Delete Data',
														icon		: Ext.Msg.SUCCESS,
														msg			: action.result.msg,
														buttons		: Ext.Msg.OK
													});
													popwindow.close();
													mch_store.loadPage(1);
												},
												failure	: function(form,action){
													Ext.Msg.show({
														title		: 'Failure - Delete Data',
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
						win_del_mch = Ext.widget('window', {
							title			: 'DELETE DATA',
							width			: 400,
							minWidth		: 400,
							height			: 200,
							minHeight		: 200,
							modal			: true,
							constrain		: true,
							layout			: 'fit',
							animateTarget	: 'btn_mch_del',
							items			: form_del_mch,
							listeners		:{
								activate:function(){
									Ext.getCmp('btn_mch_add').disable();
									Ext.getCmp('btn_mch_refresh').disable();
									Ext.getCmp('btn_mch_del').disable();
								},
								 close:function(){
									Ext.getCmp('btn_mch_add').enable();
									Ext.getCmp('btn_mch_refresh').enable();
									Ext.getCmp('btn_mch_del').enable();
								}
							}
						});
					}
					win_del_mch.show();
				}
			}

			function del_pcb(){
				var rec = Ext.getCmp('pcb_data').getSelectionModel().getSelection();
				if(rec == 0){
					Ext.Msg.show({
						title	: 'Failure - Select Data',
						icon	: Ext.Msg.ERROR,
						msg		: 'Select field to delete data !',
						buttons	: Ext.Msg.OK
					})
				} else {
					var pcbno 	= rec[0].data.pcbno;
					var pcbname = rec[0].data.pcbname;

					var win_del_pcb;
					if(!win_del_pcb){
						var form_del_pcb = Ext.create('Ext.form.Panel',{
							layout		: {
								type		: 'vbox',
								align		: 'stretch'
							},
							border			: false,
							bodyPadding		: 20,
							bodyStyle		: 'background:url(img/banner.jpg) no-repeat top left',
							fieldDefaults	: {
								labelWidth		: 120,
								labelStyle		: 'font-weight:bold',
								msgTarget		: 'side',
								width			: 300
							},
							defaults		: {
								anchor	: '100%'
							},
							items			: [
								{
									xtype	: 'label',
									html	: '<p>Delete this data ?<br><br>PCB NO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: '+pcbno+'<br>PCB Name : '+pcbname+'</p>'
								},{	xtype	: 'hiddenfield',
									id		: 'fld_del_pcbno',
									name	: 'fld_del_pcbno',
									value	: pcbno
								},{	xtype	: 'hiddenfield',
									id		: 'fld_del_pcbname',
									name	: 'fld_del_pcbname',
									value	: pcbname
								}
							],
							buttons			: [
								{
									text	: 'DELETE',
									handler	: function(){
										var form = this.up('form').getForm();
										var popwindow = this.up('window');
										if(form.isValid()){
											form.submit({
												url		: 'resp/resp_del_pcb.php',
												waitMsg	: 'deleting data',
												success	: function(form,action){
													Ext.Msg.show({
														title		:'Success - Delete Data',
														icon		: Ext.Msg.SUCCESS,
														msg			: action.result.msg,
														buttons		: Ext.Msg.OK
													});
													popwindow.close();
													pcb_store.loadPage(1);
												},
												failure	: function(form,action){
													Ext.Msg.show({
														title		: 'Failure - Delete Data',
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
						win_del_pcb = Ext.widget('window', {
							title			: 'DELETE DATA',
							width			: 400,
							minWidth		: 400,
							height			: 200,
							minHeight		: 200,
							modal			: true,
							constrain		: true,
							layout			: 'fit',
							animateTarget	: 'btn_mch_del',
							items			: form_del_pcb,
							listeners		:{
								activate:function(){
									Ext.getCmp('btn_pcb_add').disable();
									Ext.getCmp('btn_pcb_refresh').disable();
									Ext.getCmp('btn_pcb_del').disable();
								},
								 close:function(){
									Ext.getCmp('btn_pcb_add').enable();
									Ext.getCmp('btn_pcb_refresh').enable();
									Ext.getCmp('btn_pcb_del').enable();
								}
							}
						});
					}
					win_del_pcb.show();
				}
			}

			function del_ai(){
				var rec = Ext.getCmp('ai_data').getSelectionModel().getSelection();
				if(rec == 0){
					Ext.Msg.show({
						title	: 'Failure - Select Data',
						icon	: Ext.Msg.ERROR,
						msg		: 'Select field to delete data !',
						buttons	: Ext.Msg.OK
					})
				} else {
					var aino 	= rec[0].data.aino;
					var ainame 	= rec[0].data.ainame;

					var win_del_ai;
					if(!win_del_ai){
						var form_del_ai = Ext.create('Ext.form.Panel',{
							layout		: {
								type		: 'vbox',
								align		: 'stretch'
							},
							border			: false,
							bodyPadding		: 20,
							bodyStyle		: 'background:url(img/banner.jpg) no-repeat top left',
							fieldDefaults	: {
								labelWidth		: 120,
								labelStyle		: 'font-weight:bold',
								msgTarget		: 'side',
								width			: 300
							},
							defaults		: {
								anchor	: '100%'
							},
							items			: [
								{
									xtype	: 'label',
									html	: '<p>Delete this data ?<br><br>AI NO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: '+aino+'<br>AI Name : '+ainame+'</p>'
								},{	xtype	: 'hiddenfield',
									id		: 'fld_del_aino',
									name	: 'fld_del_aino',
									value	: aino
								},{	xtype	: 'hiddenfield',
									id		: 'fld_del_ainame',
									name	: 'fld_del_ainame',
									value	: ainame
								}
							],
							buttons			: [
								{
									text	: 'DELETE',
									handler	: function(){
										var form = this.up('form').getForm();
										var popwindow = this.up('window');
										if(form.isValid()){
											form.submit({
												url		: 'resp/resp_del_ai.php',
												waitMsg	: 'deleting data',
												success	: function(form,action){
													Ext.Msg.show({
														title		:'Success - Delete Data',
														icon		: Ext.Msg.SUCCESS,
														msg			: action.result.msg,
														buttons		: Ext.Msg.OK
													});
													popwindow.close();
													ai_store.loadPage(1);
												},
												failure	: function(form,action){
													Ext.Msg.show({
														title		: 'Failure - Delete Data',
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
						win_del_ai = Ext.widget('window', {
							title			: 'DELETE DATA',
							width			: 400,
							minWidth		: 400,
							height			: 200,
							minHeight		: 200,
							modal			: true,
							constrain		: true,
							layout			: 'fit',
							animateTarget	: 'btn_ai_del',
							items			: form_del_ai,
							listeners		:{
								activate:function(){
									Ext.getCmp('btn_ai_add').disable();
									Ext.getCmp('btn_ai_refresh').disable();
									Ext.getCmp('btn_ai_del').disable();
								},
								 close:function(){
									Ext.getCmp('btn_ai_add').enable();
									Ext.getCmp('btn_ai_refresh').enable();
									Ext.getCmp('btn_ai_del').enable();
								}
							}
						});
					}
					win_del_ai.show();
				}
			}

			function del_ng(){
				var rec = Ext.getCmp('ng_data').getSelectionModel().getSelection();
				if(rec == 0){
					Ext.Msg.show({
						title	: 'Failure - Select Data',
						icon	: Ext.Msg.ERROR,
						msg		: 'Select field to delete data !',
						buttons	: Ext.Msg.OK
					})
				} else {
					var ngno 	= rec[0].data.ngno;
					var ngname 	= rec[0].data.ngname;

					var win_del_ng;
					if(!win_del_ng){
						var form_del_ng = Ext.create('Ext.form.Panel',{
							layout		: {
								type		: 'vbox',
								align		: 'stretch'
							},
							border			: false,
							bodyPadding		: 20,
							bodyStyle		: 'background:url(img/banner.jpg) no-repeat top left',
							fieldDefaults	: {
								labelWidth		: 120,
								labelStyle		: 'font-weight:bold',
								msgTarget		: 'side',
								width			: 300
							},
							defaults		: {
								anchor	: '100%'
							},
							items			: [
								{
									xtype	: 'label',
									html	: '<p>Delete this data ?<br><br>NG NO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: '+ngno+'<br>NG Name : '+ngname+'</p>'
								},{	xtype	: 'hiddenfield',
									id		: 'fld_del_ngno',
									name	: 'fld_del_ngno',
									value	: ngno
								},{	xtype	: 'hiddenfield',
									id		: 'fld_del_ngname',
									name	: 'fld_del_ngname',
									value	: ngname
								}
							],
							buttons			: [
								{
									text	: 'DELETE',
									handler	: function(){
										var form = this.up('form').getForm();
										var popwindow = this.up('window');
										if(form.isValid()){
											form.submit({
												url		: 'resp/resp_del_ng.php',
												waitMsg	: 'deleting data',
												success	: function(form,action){
													Ext.Msg.show({
														title		:'Success - Delete Data',
														icon		: Ext.Msg.SUCCESS,
														msg			: action.result.msg,
														buttons		: Ext.Msg.OK
													});
													popwindow.close();
													ng_store.loadPage(1);
												},
												failure	: function(form,action){
													Ext.Msg.show({
														title		: 'Failure - Delete Data',
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
						win_del_ng = Ext.widget('window', {
							title			: 'DELETE DATA',
							width			: 400,
							minWidth		: 400,
							height			: 200,
							minHeight		: 200,
							modal			: true,
							constrain		: true,
							layout			: 'fit',
							animateTarget	: 'btn_ng_del',
							items			: form_del_ng,
							listeners		:{
								activate:function(){
									Ext.getCmp('btn_ng_add').disable();
									Ext.getCmp('btn_ng_refresh').disable();
									Ext.getCmp('btn_ng_del').disable();
								},
								 close:function(){
									Ext.getCmp('btn_ng_add').enable();
									Ext.getCmp('btn_ng_refresh').enable();
									Ext.getCmp('btn_ng_del').enable();
								}
							}
						});
					}
					win_del_ng.show();
				}
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
						store		: prcode_store,
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
											prcode_store.loadPage(1);
									}
								},{	xtype	: 'button',
									id		: 'btn_cp_add',
									iconCls	: 'add',
									text	: 'Add Problem',
									scale	: 'medium',
									handler	: input_cp
								},{	xtype	: 'button',
									id		: 'btn_cp_del',
									iconCls	: 'delete',
									text	: 'Delete Problem',
									scale	: 'medium',
									handler	: del_cp
								}
						],
						bbar		: Ext.create('Ext.PagingToolbar', {
							pageSize	: itemprcode,
							store		: prcode_store,
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

			function mch(){
				var win_mch;

				if (!win_mch){
					var form_mch = Ext.create('Ext.grid.Panel',{
						layout      : 'fit',
						id			: 'mch_data',
						name		: 'mch_data',
						renderTo	: 'section',
						frame		: false,
						store		: mch_store,
						width		: '100%',
						x			: 0,
						y			: 0,
						columnLines	: true,
						multiSelect	: true,
						viewConfig	: {
								stripeRows          : true,
								enableTextSelection : true
						},
						columns		: [
							{header: 'Machine No', 		dataIndex: 'mchno', width:80},
							{header: 'Machine Name', 	dataIndex: 'mchname', flex:1}
						],
						tbar	: [{xtype:'tbspacer',width:10},
								{	xtype	: 'button',
									id		: 'btn_mch_refresh',
									iconCls	: 'refresh',
									text 	: 'Refresh',
									tooltip	: 'Refresh',
									scale	: 'medium',
									handler : function (){
											mch_store.loadPage(1);
									}
								},{	xtype	: 'button',
									id		: 'btn_mch_add',
									iconCls	: 'add',
									text	: 'Add Item',
									scale	: 'medium',
									handler	: input_mch
								},{	xtype	: 'button',
									id		: 'btn_mch_del',
									iconCls	: 'delete',
									text	: 'Delete Item',
									scale	: 'medium',
									handler	: del_mch
								}
						],
						bbar		: Ext.create('Ext.PagingToolbar', {
							pageSize	: 10,
							store		: mch_store,
							displayInfo	: true,
							plugins		: Ext.create('Ext.ux.ProgressBarPager', {}),
							listeners	: {
									afterrender: function(cmp) {
										cmp.getComponent("refresh").hide();
									}
							}
						})
					});
					win_mch = Ext.widget('window',{
						title			: '<p style="color:#000">Form Input',
						width			: 500,
						minWidth		: 500,
						height			: 335,
						minHeight		: 335,
						modal			: false,
						constrain		: true,
						layout			: 'fit',
						border			: false,
						bodyBorder		: false,
						animateTarget	: 'btn_settings',
						items			: form_mch,
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
				win_mch.show();
			}

			function pcb(){
				var win_pcb;

				if (!win_pcb){
					var form_pcb = Ext.create('Ext.grid.Panel',{
						layout      : 'fit',
						id			: 'pcb_data',
						name		: 'pcb_data',
						renderTo	: 'section',
						frame		: false,
						store		: pcb_store,
						width		: '100%',
						x			: 0,
						y			: 0,
						columnLines	: true,
						multiSelect	: true,
						viewConfig	: {
								stripeRows          : true,
								enableTextSelection : true
						},
						columns		: [
							{header: 'PCB No', 		dataIndex: 'pcbno', width:80},
							{header: 'PCB Name', 	dataIndex: 'pcbname', flex:1}
						],
						tbar	: [{xtype:'tbspacer',width:10},
								{	xtype	: 'button',
									id		: 'btn_pcb_refresh',
									iconCls	: 'refresh',
									text 	: 'Refresh',
									tooltip	: 'Refresh',
									scale	: 'medium',
									handler : function (){
											pcb_store.loadPage(1);
									}
								},{	xtype	: 'button',
									id		: 'btn_pcb_add',
									iconCls	: 'add',
									text	: 'Add Item',
									scale	: 'medium',
									handler	: input_pcb
								},{	xtype	: 'button',
									id		: 'btn_pcb_del',
									iconCls	: 'delete',
									text	: 'Delete Item',
									scale	: 'medium',
									handler	: del_pcb
								}
						],
						bbar		: Ext.create('Ext.PagingToolbar', {
							pageSize	: 10,
							store		: pcb_store,
							displayInfo	: true,
							plugins		: Ext.create('Ext.ux.ProgressBarPager', {}),
							listeners	: {
									afterrender: function(cmp) {
										cmp.getComponent("refresh").hide();
									}
							}
						})
					});
					win_pcb = Ext.widget('window',{
						title			: '<p style="color:#000">Form Input',
						width			: 500,
						minWidth		: 500,
						height			: 335,
						minHeight		: 335,
						modal			: false,
						constrain		: true,
						layout			: 'fit',
						border			: false,
						bodyBorder		: false,
						animateTarget	: 'btn_settings',
						items			: form_pcb,
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
				win_pcb.show();
			}

			function ai(){
				var win_ai;

				if (!win_ai){
					var form_ai = Ext.create('Ext.grid.Panel',{
						layout      : 'fit',
						id			: 'ai_data',
						name		: 'ai_data',
						renderTo	: 'section',
						frame		: false,
						store		: ai_store,
						width		: '100%',
						x			: 0,
						y			: 0,
						columnLines	: true,
						multiSelect	: true,
						viewConfig	: {
								stripeRows          : true,
								enableTextSelection : true
						},
						columns		: [
							{header: 'AI No', 		dataIndex: 'aino', width:80},
							{header: 'AI Name', 	dataIndex: 'ainame', flex:1}
						],
						tbar	: [{xtype:'tbspacer',width:10},
								{	xtype	: 'button',
									id		: 'btn_ai_refresh',
									iconCls	: 'refresh',
									text 	: 'Refresh',
									tooltip	: 'Refresh',
									scale	: 'medium',
									handler : function (){
											ai_store.loadPage(1);
									}
								},{	xtype	: 'button',
									id		: 'btn_ai_add',
									iconCls	: 'add',
									text	: 'Add Problem',
									scale	: 'medium',
									handler	: input_ai
								},{	xtype	: 'button',
									id		: 'btn_ai_del',
									iconCls	: 'delete',
									text	: 'Delete Problem',
									scale	: 'medium',
									handler	: del_ai
								}
						],
						bbar		: Ext.create('Ext.PagingToolbar', {
							pageSize	: 10,
							store		: ai_store,
							displayInfo	: true,
							plugins		: Ext.create('Ext.ux.ProgressBarPager', {}),
							listeners	: {
									afterrender: function(cmp) {
										cmp.getComponent("refresh").hide();
									}
							}
						})
					});
					win_ai = Ext.widget('window',{
						title			: '<p style="color:#000">Form Input',
						width			: 500,
						minWidth		: 500,
						height			: 335,
						minHeight		: 335,
						modal			: false,
						constrain		: true,
						layout			: 'fit',
						border			: false,
						bodyBorder		: false,
						animateTarget	: 'btn_settings',
						items			: form_ai,
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
				win_ai.show();
			}

			function ng(){
				var win_ng;

				if (!win_ng){
					var form_ng = Ext.create('Ext.grid.Panel',{
						layout      : 'fit',
						id			: 'ng_data',
						name		: 'ng_data',
						renderTo	: 'section',
						frame		: false,
						store		: ng_store,
						width		: '100%',
						x			: 0,
						y			: 0,
						columnLines	: true,
						multiSelect	: true,
						viewConfig	: {
								stripeRows          : true,
								enableTextSelection : true
						},
						columns		: [
							{header: 'NG No', 		dataIndex: 'ngno', width:80},
							{header: 'NG Name', 	dataIndex: 'ngname', flex:1}
						],
						tbar	: [{xtype:'tbspacer',width:10},
								{	xtype	: 'button',
									id		: 'btn_ng_refresh',
									iconCls	: 'refresh',
									text 	: 'Refresh',
									tooltip	: 'Refresh',
									scale	: 'medium',
									handler : function (){
											ng_store.loadPage(1);
									}
								},{	xtype	: 'button',
									id		: 'btn_ng_add',
									iconCls	: 'add',
									text	: 'Add Problem',
									scale	: 'medium',
									handler	: input_ng
								},{	xtype	: 'button',
									id		: 'btn_ng_del',
									iconCls	: 'delete',
									text	: 'Delete Problem',
									scale	: 'medium',
									handler	: del_ng
								}
						],
						bbar		: Ext.create('Ext.PagingToolbar', {
							pageSize	: 10,
							store		: ng_store,
							displayInfo	: true,
							plugins		: Ext.create('Ext.ux.ProgressBarPager', {}),
							listeners	: {
									afterrender: function(cmp) {
										cmp.getComponent("refresh").hide();
									}
							}
						})
					});
					win_ng = Ext.widget('window',{
						title			: '<p style="color:#000">Form Input',
						width			: 500,
						minWidth		: 500,
						height			: 335,
						minHeight		: 335,
						modal			: false,
						constrain		: true,
						layout			: 'fit',
						border			: false,
						bodyBorder		: false,
						animateTarget	: 'btn_settings',
						items			: form_ng,
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
				win_ng.show();
			}

			//-----------------------------------------------------[-Update Quality-]
			function update() {
				var record = grid_data.store.getUpdatedRecords();
				if (record == "" || record == null){
					Ext.Msg.show({
						title		:'Message',
						icon		: Ext.Msg.ERROR,
						msg			: "You don't make any changes yet !",
						buttons		: Ext.Msg.OK
					});
				}
				else{
					Ext.Msg.confirm('Confirm', 'Are you sure want to update data ?', function(btn){
						if (btn == 'yes'){
							var record = grid_data.store.getUpdatedRecords();

							for (var i=0; i < record.length; i++) {
								//alert(record[i].data.id_item+' ## '+record[i].data.result);
								Ext.Ajax.request({
									url		: 'resp/resp_update.php',
									method	: 'POST',
									params	: 'inputid='+record[i].data.inputid+'&dateid='+record[i].data.dateid+'&group='+record[i].data.group+'&shift='+record[i].data.shift+'&mch='+record[i].data.mch+'&lotno='+record[i].data.lot_no+'&lotqty='+record[i].data.lot_qty+'&model='+record[i].data.model_name+'&stserial='+record[i].data.start_serial+'&pcb='+record[i].data.pcb_name+'&pwb='+record[i].data.pwb_no+'&smt='+record[i].data.smt+'&process='+record[i].data.process+'&ai='+record[i].data.ai+'&location='+record[i].data.loc+'&magazineno='+record[i].data.magazineno+'&ng='+record[i].data.ng+'&boardke='+record[i].data.boardke+'&boardqty='+record[i].data.boardqty+'&pointqty='+record[i].data.pointqty,
									success	: function(obj) {
										var resp = obj.responseText;
										if (resp != 0) {
											//data_store.proxy.setExtraParam('inputid', Ext.getCmp('ocl_no').getValue() );
											data_store.loadPage(1);
										} else {
											Ext.Msg.show({
												title		:'Edit Data',
												icon		: Ext.Msg.ERROR,
												msg			: resp,
												buttons		: Ext.Msg.OK
											});
										}
									}
								});
							}
						}
						else{
							//datastore2.proxy.setExtraParam('ocl_no', Ext.getCmp('ocl_no').getValue() );
							data_store.loadPage(1);
						}
					});
				}
			}

			//-----------------------------------------------------[-Input Rejection-]
			function rejection(){
				var rec = grid_data.getSelectionModel().getSelection();

				if (rec == 0){
					Ext.Msg.show({
						title	: 'Failure - Select Data',
						icon	: Ext.Msg.ERROR,
						msg		: 'Select field to input rejection !',
						buttons	: Ext.Msg.OK
					});
				} else {
					var win_rejection;
					var model = rec[0].data.model_name;
					var inputid = rec[0].data.inputid;
					var symptom = rec[0].data.smt;
					var location = rec[0].data.loc;
					var pcb_name = rec[0].data.pcb_name;
					var process = rec[0].data.process;
					var stserial = rec[0].data.start_serial;
					get_partno.proxy.setExtraParam('model_name', model);
					get_partno.proxy.setExtraParam('loc', location);
					get_partno.proxy.setExtraParam('pcb_name', pcb_name);
					get_partno.proxy.setExtraParam('process', process);
					get_partno.proxy.setExtraParam('start_serial', stserial);
					
					rejection_store.proxy.setExtraParam('inputid', inputid);
					rejection_store.loadPage(1);

					if(!win_rejection){
						get_partno.loadPage(1);
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
								//height		: 290,
								height		: 318,
								minHeight	: 318,
								frame		: false,
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
											// width: 430,
											items: [
												{	xtype			: 'textfield',
													margin			: '5 10 5 10',
													fieldLabel		: 'Symptom',
													id				: 'label_symptom',
													name			: 'label_symptom',
													labelSeparator	: ' ',
													readOnly		: true,
													width			: 360,
													value			: symptom
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
															id					: 'fld_part',
															name				: 'fld_part',
															// afterLabelTextTpl	: required,
															// allowBlank			: false,
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
														},
														// { 	xtype: 'combo',
														// 	fieldLabel: 'Combo Part No',
														// 	id					: 'fld_cbxpart',
														// 	name				: 'fld_cbxpart',
														// 	afterLabelTextTpl	: required,
														// 	allowBlank			: false,
														// 	store 				: get_partno,
														// 	queryMode 			: 'local',
														// 	displayField		: 'partno',
														// 	valueField			: 'partno',
														// 	listeners 			: {
														// 		specialkey: function(field, e) {
														// 			if (e.getKey() == e.ENTER) {
														// 				var reel = field.getValue().substr(0,15);
														// 				// alert(reel);
														// 				field.setValue(reel);
														// 			}
														// 		}
														// 		// change:function(field){
														// 		// 	field.setValue(field.getValue().toUpperCase());
														// 		// }
														// 	}
														// },
														{ fieldLabel			: 'Qty Select',
															id					: 'fld_selectqty',
															name				: 'fld_selectqty',
														   	maskRe				: /[0-9.,]/,
															labelSeparator		: ' '
														},{ fieldLabel			: 'Qty NG',
															id					: 'fld_repairqty',
															name				: 'fld_repairqty',
														   	maskRe				: /[0-9.,]/,
															labelSeparator		: ' '
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
														}
													]
												}
											]
										},{xtype:'tbspacer',width:10},{
											xtype: 'container',
											layout:	'vbox',
											// width: 430,
											items: [
												{	xtype			: 'textfield',
													margin			: '5 10 5 10',
													fieldLabel		: 'NG Location',
													id				: 'label_ngloc',
													name			: 'label_ngloc',
													labelSeparator	: ' ',
													readOnly		: true,
													width			: 360,
													value			: location
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
															value				: rec[0].data.inputid
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
														// change: function(field) {
														// 	var reel = substr(field.getValue(), 15);
														// 	var part = Ext.getCmp('fld_part').getValue();

														// 	if (reel != part) {
														// 		alert('wrong part !')
														// 	} else {

														// 	}
														// }
													}
												}
											]
										}
									],
									buttons		: [
										{
											text		: 'New',
											id			: 'add_rejection',
											iconCls		: 'add',
											scale		: 'medium',
											handler		: function() {
												this.up('form').getForm().reset();
												//ds_result.loadPage(1);
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
														params: 'inputcode='+0,
														success	: function(form, action) {
															Ext.Msg.show({
																title		:'Success - Input Data',
																icon		: Ext.Msg.SUCCESS,
																msg			: action.result.msg,
																buttons		: Ext.Msg.OK
															});
															form.reset();
															rejection_store.loadPage(1);
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
								collapsible	: false,
								region		: 'center',
								layout		: 'fit',
								autoScroll	: true,
								items		: [{
									xtype		: 'grid',
									// layout      : 'fit',
									id			: 'grid_rejection',
									name		: 'grid_rejection',
									store		: rejection_store,
									// height		: 218,
									//height		: 460,
									//width		: '100%',
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
										/*{header: 'ACTION' , columns : [
											{ 	xtype	:'actioncolumn',
											 	header	: 'EDIT',
												width	:50,
												align	: 'center',
												items	: [{
													icon	: 'icons/edit.png',  // Use a URL in the icon config
													tooltip	: 'Edit',
													//handler	: update_rejection
												}]
											},
											{ 	xtype	:'actioncolumn',
											 	header	: 'DEL',
												width	:50,
												align	: 'center',
												items	: [{
													icon	: 'icons/delete.png',  // Use a URL in the icon config
													tooltip	: 'Delete',
													//handler	: del
												}]
											}
										]},*/
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
											{ xtype: 'button', text: 'Update', iconCls	: 'edit', scale: 'medium', iconAlign: 'left', handler: update_rejection  }
										],
										listeners	: {
											afterrender: function(cmp) {
												cmp.getComponent("refresh").hide();
											}
										}
									})
								}]
							}]
						});

						win_rejection = Ext.widget('window',{
						title			: '<p style="color:#000">Form Follow Up',
						width			: 966,
						minWidth		: 966,
						height			: 600,
						minHeight		: 600,
						layout			: 'fit',
						animateTarget	: 'btn_add_reject',
						items			: panel_rejection,
						bodyStyle		: 'background:#008080',
						bodyBorder		: false,
						autoScroll		: true,
						modal			: false,
						constrain		: true,
						border			: false,
							listeners	: {
								activate:function(){
									Ext.getCmp('btn_input').disable();
									Ext.getCmp('btn_update').disable();
									Ext.getCmp('btn_settings').disable();
									Ext.getCmp('btn_add_reject').disable();
									//Ext.getCmp('btn_src').disable();
								},
								close:function(){
									Ext.getCmp('btn_input').enable();
									Ext.getCmp('btn_update').enable();
									Ext.getCmp('btn_settings').enable();
									Ext.getCmp('btn_add_reject').enable();
									//Ext.getCmp('btn_src').enable();
								}
							}
						});
					}
					win_rejection.show();
				}
			}

			//-----------------------------------------------------[-Download-]
			function download(){
				var win_download;

				if(!win_download){
					var form_download = Ext.create('Ext.form.Panel',{
						layout			: {
							type			: 'vbox',
							align			: 'stretch'
						},
						border			: false,
						bodyPadding		: 20,
						bodyStyle		: 'background:url(img/banner.jpg) no-repeat top left',
						fieldDefaults	: {
							labelWidth		: 120,
							labelStyle		: 'font-weight:bold',
							msgTarget		: 'side',
							width			: 300
						},
						defaults		: {
							anchor			: '100%'
						},
						items			: [
							{
								xtype				: 'datefield',
								fieldLabel			: 'Start Date',
								name				: 'startdt',
								id					: 'startdt',
								vtype				: 'daterange',
								endDateField		: 'enddt',
								format				: 'Y-m-d',
								afterLabelTextTpl	: required,
								allowBlank			: false,
								labelSeparator		: ' ',
							},{
								xtype				: 'datefield',
								fieldLabel			: 'End Date',
								name				: 'enddt',
								id					: 'enddt',
								vtype				: 'daterange',
								startDateField		: 'startdt',
								format				: 'Y-m-d',
								afterLabelTextTpl	: required,
								allowBlank			: false,
								labelSeparator		: ' ',
							},{
								xtype		: 'button',
								text		: 'Download',
								iconCls		: 'download',
								iconAlign	: 'top',
								id			: 'download',
								name		: 'download',
								scale		: 'medium',
								handler		: function(field) {
									var cv_startdt 	= Ext.Date.format(Ext.getCmp('startdt').getValue(), 'Y-m-d');
									var cv_enddt 	= Ext.Date.format(Ext.getCmp('enddt').getValue(), 'Y-m-d');
									window.open('resp/resp_download.php?startdt='+cv_startdt+'&enddt='+cv_enddt+'');
								}
							},{
								xtype		: 'label',
								html		: 'File will be saved into <b>(*.csv)</b>'
							}
						]
					});
					win_download = Ext.widget('window',{
						title			: '<p style="color:#000">Form Download',
						width			: 350,
						minWidth		: 350,
						height			: 200,
						minHeight		: 200,
						layout			: 'fit',
						animateTarget	: 'btn_download',
						items			: form_download,
						bodyStyle		: 'background:#008080',
						formBind		: true,
						autoScroll		: true,
						modal			: false,
						constrain		: true,
						border			: false,
						listeners		:{
							activate:function(){
								Ext.getCmp('btn_input').disable();
								Ext.getCmp('btn_update').disable();
								Ext.getCmp('btn_add_reject').disable();
								Ext.getCmp('btn_settings').disable();
								Ext.getCmp('btn_download').disable();
								//Ext.getCmp('btn_src').disable();
							},
							close:function(){
								Ext.getCmp('btn_input').enable();
								Ext.getCmp('btn_update').enable();
								Ext.getCmp('btn_add_reject').enable();
								Ext.getCmp('btn_settings').enable();
								Ext.getCmp('btn_download').enable();
								//Ext.getCmp('btn_src').enable();
							}
						}
					});
				}
				win_download.show();
			}

			//-----------------------------------------------------[-Delete Quality-]
			function del(){
				var rec = grid_data.getSelectionModel().getSelection();
				if(rec == 0){
					Ext.Msg.show({
						title	: 'Failure - Select Data',
						icon	: Ext.Msg.ERROR,
						msg		: 'Select field to delete data !',
						buttons	: Ext.Msg.OK
					})
				} else {
					var inputid 	= rec[0].data.inputid;
					var model_name 	= rec[0].data.model_name;

					var win_del;
					if(!win_del){
						var form_del = Ext.create('Ext.form.Panel',{
							layout		: {
								type		: 'vbox',
								align		: 'stretch'
							},
							border			: false,
							bodyPadding		: 20,
							bodyStyle		: 'background:url(img/banner.jpg) no-repeat top left',
							fieldDefaults	: {
								labelWidth		: 120,
								labelStyle		: 'font-weight:bold',
								msgTarget		: 'side',
								width			: 300
							},
							defaults		: {
								anchor	: '100%'
							},
							items			: [
								{	xtype	: 'label',
									html	: '<p>Delete this data ?<br><br>Model Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: '+model_name+'</p>'
								},{	xtype	: 'hiddenfield',
									id		: 'fld_del_model',
									name	: 'fld_del_model',
									value	: model_name
								},{	xtype	: 'hiddenfield',
									id		: 'fld_del_inputid',
									name	: 'fld_del_inputid',
									value	: inputid
								}
							],
							buttons			: [
								{
									text	: 'DELETE',
									handler		: function() {
										var form = this.up('form').getForm();
										var popwindow = this.up('window');
										if (form.isValid()) {
											form.submit({
												url		: 'resp/resp_del_prodctrl.php',
												waitMsg	: 'deleting data',
												success	: function(form, action) {
													Ext.Msg.show({
														title		:'Success - Delete Data',
														icon		: Ext.Msg.SUCCESS,
														msg			: action.result.msg,
														buttons		: Ext.Msg.OK
													});
													popwindow.close();
													data_store.loadPage(1);
													rejection_store.loadPage(1);
												},
												failure	: function(form, action) {
													Ext.Msg.show({
														title		:'Failure - Delete Data',
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
						win_del = Ext.widget('window', {
							title			: 'DELETE DATA',
							width			: 400,
							minWidth		: 400,
							height			: 200,
							minHeight		: 200,
							modal			: true,
							constrain		: true,
							layout			: 'fit',
							animateTarget	: 'btn_del',
							items			: form_del,
							listeners		:{
								activate:function(){
									Ext.getCmp('btn_input').disable();
									Ext.getCmp('btn_update').disable();
									Ext.getCmp('btn_del').disable();
									Ext.getCmp('btn_add_reject').disable();
									Ext.getCmp('btn_download').disable();
								},
								 close:function(){
									Ext.getCmp('btn_input').enable();
									Ext.getCmp('btn_update').enable();
									Ext.getCmp('btn_del').enable();
									Ext.getCmp('btn_add_reject').enable();
									Ext.getCmp('btn_download').enable();
								}
							}
						});
					}
					win_del.show();
				}
			}

			//-----------------------------------------------------[-Searc Quality-]
			function search(){
				var win_search;

				if(!win_search){
					var form_search = Ext.create('Ext.form.Panel',{
						layout			: {
							type			: 'vbox',
							align			: 'stretch'
						},
						border			: false,
						bodyPadding		: 20,
						bodyStyle		: 'background:url(img/banner.jpg) no-repeat top left',
						fieldDefaults	: {
							labelWidth		: 120,
							labelStyle		: 'font-weight:bold',
							msgTarget		: 'side',
							width			: 300
						},
						defaults		: {
							anchor			: '100%'
						},
						items			: [
							{
								xtype				: 'combobox',
								fieldLabel			: 'Machine Name',
								id					: 'src_mch',
								name				: 'src_mch',
								// afterLabelTextTpl	: required,
								labelSeparator		: ' ',
								queryMode			: 'local',
								store				: cbx_mch,
								displayField		: 'mchname',
								valueField			: 'mchno'
							},{
								xtype				: 'textfield',
								fieldLabel			: 'Model Name',
								id					: 'src_model',
								name				: 'src_model',
								// afterLabelTextTpl	: required,
								labelSeparator		: ' '
							},{
								xtype				: 'textfield',
								fieldLabel			: 'Start Serial',
								id					: 'src_stserial',
								name				: 'src_stserial',
								// afterLabelTextTpl	: required,
								labelSeparator		: ' ',
								maskRe				: /[0-9]/
							},
							{
								xtype		: 'button',
								text		: 'Search',
								id			: 'search',
								name		: 'search',
								scale		: 'medium',
								formBind	: true,
								handler		: function() {
									data_store.proxy.setExtraParam('src_mch', Ext.getCmp('src_mch').getValue());
									data_store.proxy.setExtraParam('src_model', Ext.getCmp('src_model').getValue());
									data_store.proxy.setExtraParam('src_stserial', Ext.getCmp('src_stserial').getValue());
									data_store.loadPage(1);
								}
							}
						]
					});
					win_search = Ext.widget('window',{
						title			: '<p style="color:#000">Search Data',
						width			: 350,
						minWidth		: 350,
						minHeight		: 185,
						layout			: 'fit',
						animateTarget	: 'btn_src',
						items			: form_search,
						bodyStyle		: 'background:#008080',
						formBind		: true,
						autoScroll		: true,
						modal			: false,
						constrain		: true,
						border			: false,
						listeners		:{
							activate:function(){
								// Ext.getCmp('btn_input').disable();
								// Ext.getCmp('btn_update').disable();
								// Ext.getCmp('btn_add_reject').disable();
								// Ext.getCmp('btn_settings').disable();
								// Ext.getCmp('btn_download').disable();
								Ext.getCmp('btn_src').disable();
							},
							close:function(){
								// Ext.getCmp('btn_input').enable();
								// Ext.getCmp('btn_update').enable();
								// Ext.getCmp('btn_add_reject').enable();
								// Ext.getCmp('btn_settings').enable();
								// Ext.getCmp('btn_download').enable();
								Ext.getCmp('btn_src').enable();
							}
						}
					});
				}
				win_search.show();
			}

			function search_serialno(){
				var win_search_serialno;

				if(!win_search_serialno){
					var form_search_serialno = Ext.create('Ext.form.Panel',{
						layout			: {
							type			: 'vbox',
							align			: 'stretch'
						},
						border			: false,
						bodyPadding		: 20,
						bodyStyle		: 'background:url(img/banner.jpg) no-repeat top left',
						fieldDefaults	: {
							labelWidth		: 120,
							labelStyle		: 'font-weight:bold',
							msgTarget		: 'side',
							width			: 300
						},
						defaults		: {
							anchor			: '100%'
						},
						items			: [
							{
								xtype				: 'textfield',
								fieldLabel			: 'Board ID',
								id					: 'src_boardid',
								name				: 'src_boardid',
								afterLabelTextTpl	: required,
								allowBlank			: false,
								labelSeparator		: ' ',
								listeners       : {
									afterrender : function(field) {
										field.focus(false, 1000);
									}
								}
							},{
								xtype		: 'button',
								text		: 'Search',
								id			: 'search_boardid',
								name		: 'search_boardid',
								scale		: 'medium',
								formBind	: true,
								handler		: function() {
									data_store.proxy.setExtraParam('src_boardid', Ext.getCmp('src_boardid').getValue());
									data_store.loadPage(1);
									this.up('window').close();
								}
							}
						]
					});
					win_search_serialno = Ext.create('Ext.window.Window',{
						title			: '<p style="color:#000">Search Data',
						width			: 350,
						minWidth		: 350,
						minHeight		: 130,
						layout			: 'fit',
						animateTarget	: 'btn_src_serialno',
						items			: form_search_serialno,
						bodyStyle		: 'background:#008080',
						formBind		: true,
						autoScroll		: true,
						modal			: false,
						constrain		: true,
						border			: false,
						//autoShow 		: true,
						defaultFocus 	: 'src_boardid', //(id textfield)

					});
				}
				win_search_serialno.show();
			}

			function update_rejection(){
				var record = Ext.getCmp('grid_rejection').store.getUpdatedRecords();
				if (record == "" || record == null){
					Ext.Msg.show({
						title		:'Message',
						icon		: Ext.Msg.ERROR,
						msg			: "You don't make any changes yet !",
						buttons		: Ext.Msg.OK
					});
				}
				else{
					Ext.Msg.confirm('Confirm', 'Are you sure want to update data ?', function(btn){
						if (btn == 'yes'){
							var record = Ext.getCmp('grid_rejection').store.getUpdatedRecords();

							for (var i=0; i < record.length; i++) {
								//alert(record[i].data.repairedby+' ## '+record[i].data.partno);
								Ext.Ajax.request({
									url		: 'resp/resp_update_rejection.php',
									method	: 'POST',
									//params	: 'rejectid='+record[i].data.rejectid+'&inputid='+record[i].data.inputid+'&qtyselect='+record[i].data.qtyselect+'&qtyng='+record[i].data.qtyng+'&partno='+record[i].data.partno+'&repairedby='+record[i].data.repairedby+'&howtorepair='+record[i].data.howtorepair+'&checkedby='+record[i].data.checkedby+'&fld_result='+record[i].data.fld_result+'&fld_desc='+record[i].data.fld_desc+'&pic='+record[i].data.pic,
									params	: {
										rejectid: record[i].data.rejectid,
										inputid: record[i].data.inputid,
										qtyselect: record[i].data.qtyselect,
										qtyng: record[i].data.qtyng,
										partno: record[i].data.partno,
										repairedby: record[i].data.repairedby,
										howtorepair: record[i].data.howtorepair,
										checkedby: record[i].data.checkedby,
										fld_result: record[i].data.fld_result,
										fld_desc: record[i].data.fld_desc,
										pic: record[i].data.pic,
										reelno: record[i].data.reelno
									},
									success	: function(obj) {
										var resp = obj.responseText;
										if (resp != 0) {
											rejection_store.loadPage(1);
										} else {
											Ext.Msg.show({
												title		:'Edit Data',
												icon		: Ext.Msg.ERROR,
												msg			: resp,
												buttons		: Ext.Msg.OK
											});
										}
									}
								});
							}
						}
						else{
							rejection_store.loadPage(1);
						}
					});
				}
			}
		});
        </script>
    </head>
    <body>
        <div id="section">

        </div>
    </body>
</html>
