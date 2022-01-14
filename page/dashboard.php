<script>
	Ext.Loader.setConfig({enabled: true});
	Ext.Loader.setPath('Ext.ux', '../framework/extjs-4.2.2/examples/ux/');

	Ext.onReady(function(){
		Ext.QuickTips.init();

		function color(val) {
			if (val == 0) {
				return '<span style="color:#009688;">-</span>';
			} else if (val > 0) {
				return '<span style="color:#f44336;"><b>' + val + '</b></span>';
			}
			return val;
		}

		var selectedRec = false,
        //performs the highlight of an item in the bar series
        highlightCompanyPriceBar = function(storeItem) {
            var name = storeItem.get('line'),
                series = chartPanel.series.get(0),
                i, items, l;

            series.highlight = true;
            series.unHighlightItem();
            series.cleanHighlights();
            for (i = 0, items = series.items, l = items.length; i < l; i++) {
                if (name == items[i].storeItem.get('line')) {
                    series.highlightItem(items[i]);
                    break;
                }
            }
            series.highlight = false;
        };

		Ext.define('Ext.chart.theme.myTheme', {
			extend: 'Ext.chart.theme.Base',
				constructor: function(config) {
			 		this.callParent([Ext.apply({
				 		//colors:['#f44336', '#e91e63', '#9c27b0', '#673ab7', '#3f51b5', '#2196f3', '#03a9f4', '#00bcd4', '#009688', '#4caf50', '#8bc34a', '#cddc39', '#ffeb3b', '#ffc107', '#ff9800', '#ff5722', '#795548', '#9e9e9e', '#607d8b', '#505050']
				 		colors:['#f44336', '#ffb300', '#ffeb3b', '#4caf50', '#2196f3', '#e91e63', '#795548', '#9e9e9e', '#607d8b', '#222222', '#37474f', '#4e342e', '#e65100', '#827717', '#1b5e20', '#880e4f', '#311b92', '#0d47a1', '#aeea00', '#efebe9']
			 		}, config)]);
			}
		});
		
		var chart_store = Ext.create('Ext.data.Store', {
			model	: 'Chart',
			fields: ['line', 'BROKEN', 'BUBBLE', 'CHIP SCATTER', 'CHIPPING','COLD JOINT','DIRTY','DRY JOINT','EXTRA PART','FLOATING','FOREIGN MATERIAL','LAYBACK','MISSING','NO SOLDER','OVER BONDING',
      					'PATTERN O/C','PATTERN S/C','POOR SOLDER','RESIST','RUSTY','SHIFTING','SLANTING','SLIP INSERT','SOLDER BALL','SOLDER SHORT','TOMBSTONE','WRONG PART','WRONG POLARITY','OTHERS','CLEANING BOARD','mchname'],
			autoLoad: false,
			proxy	: {
				type	: 'ajax',
				url		: 'json/json_summary.php',
				reader	: {
					type			: 'json',
					root			: 'rows'
				}
			},
			listeners: {
				beforeload: function(store, eOpts) {
					// Show loading animation before loading the store
					chartPanel.mask("Please Wait...");
				},
				load: function(store, eOpts) {
					// Stop loading animation after loading the store
					chartPanel.unmask();
				}
			}
		});

		//create a grid that will list the dataset items.
		var gridPanel = Ext.create('Ext.grid.Panel', {
			id 					: 'grid_problem',
			height			: 400,
			flex 				: 1,
			padding			: '0 5 5 5',
			store 			: chart_store,
			title 			:'Problem per Line',
			autoScroll 	: true,
			columnLines	: true,
			multiSelect	: true,
			viewConfig	: {
				stripeRows          : true,
				enableTextSelection : true
			},
			columns: [
				{ text: 'Line',				flex: 1, sortable: true, dataIndex: 'mchname' },
				{ text: 'BROKEN',			flex: 1, sortable: true, dataIndex: 'BROKEN',			align: 'center', renderer: color },
				{ text: 'BUBBLE',			flex: 1, sortable: true, dataIndex: 'BUBBLE',			align: 'center', renderer: color },
				{ text: 'CHIP SCATTER',		flex: 1, sortable: true, dataIndex: 'CHIP SCATTER',		align: 'center', renderer: color },
				{ text: 'CHIPPING',			flex: 1, sortable: true, dataIndex: 'CHIPPING',			align: 'center', renderer: color },
				{ text: 'COLD JOINT',		flex: 1, sortable: true, dataIndex: 'COLD JOINT',		align: 'center', renderer: color },
				{ text: 'DIRTY',			flex: 1, sortable: true, dataIndex: 'DIRTY',			align: 'center', renderer: color },
				{ text: 'DRY JOINT',		flex: 1, sortable: true, dataIndex: 'DRY JOINT',		align: 'center', renderer: color },
				{ text: 'EXTRA PART',		flex: 1, sortable: true, dataIndex: 'EXTRA PART',		align: 'center', renderer: color },
				{ text: 'FLOATING',			flex: 1, sortable: true, dataIndex: 'FLOATING',			align: 'center', renderer: color },
				{ text: 'FOREIGN MATERIAL',	flex: 1, sortable: true, dataIndex: 'FOREIGN MATERIAL',	align: 'center', renderer: color },
				{ text: 'LAYBACK',			flex: 1, sortable: true, dataIndex: 'LAYBACK',			align: 'center', renderer: color },
				{ text: 'MISSING',			flex: 1, sortable: true, dataIndex: 'MISSING',			align: 'center', renderer: color },
				{ text: 'NO SOLDER',		flex: 1, sortable: true, dataIndex: 'NO SOLDER',		align: 'center', renderer: color },
				{ text: 'OVER BONDING',		flex: 1, sortable: true, dataIndex: 'OVER BONDING',		align: 'center', renderer: color },
				{ text: 'PATTERN O/C',		flex: 1, sortable: true, dataIndex: 'PATTERN O/C',		align: 'center', renderer: color },
				{ text: 'PATTERN S/C',		flex: 1, sortable: true, dataIndex: 'PATTERN S/C',		align: 'center', renderer: color },
				{ text: 'POOR SOLDER',		flex: 1, sortable: true, dataIndex: 'POOR SOLDER',		align: 'center', renderer: color },
				{ text: 'RESIST',			flex: 1, sortable: true, dataIndex: 'RESIST',			align: 'center', renderer: color },
				{ text: 'RUSTY',			flex: 1, sortable: true, dataIndex: 'RUSTY',			align: 'center', renderer: color },
				{ text: 'SHIFTING',			flex: 1, sortable: true, dataIndex: 'SHIFTING',			align: 'center', renderer: color },
				{ text: 'SLANTING',			flex: 1, sortable: true, dataIndex: 'SLANTING',			align: 'center', renderer: color },
				{ text: 'SLIP INSERT',		flex: 1, sortable: true, dataIndex: 'SLIP INSERT',		align: 'center', renderer: color },
				{ text: 'SOLDER BALL',		flex: 1, sortable: true, dataIndex: 'SOLDER BALL',		align: 'center', renderer: color },
				{ text: 'SOLDER SHORT',		flex: 1, sortable: true, dataIndex: 'SOLDER SHORT',		align: 'center', renderer: color },
				{ text: 'TOMBSTONE',		flex: 1, sortable: true, dataIndex: 'TOMBSTONE',		align: 'center', renderer: color },
				{ text: 'WRONG PART',		flex: 1, sortable: true, dataIndex: 'WRONG PART',		align: 'center', renderer: color },
				{ text: 'WRONG POLARITY',	flex: 1, sortable: true, dataIndex: 'WRONG POLARITY',	align: 'center', renderer: color },
				{ text: 'OTHERS',			flex: 1, sortable: true, dataIndex: 'OTHERS',			align: 'center', renderer: color },
				{ text: 'CLEANING BOARD',	flex: 1, sortable: true, dataIndex: 'CLEANING BOARD',	align: 'center', renderer: color }
			],

//			listeners: {
//				selectionchange: function(model, records) {
//					var fields;
//					if (records[0]) {
//						selectedRec = records[0];
						/*if (!form) {
							form = this.up('panel').down('form').getForm();
							fields = form.getFields();
							fields.each(function(field){
								if (field.name != 'company') {
									field.setDisabled(false);
								}
							});
						} else {
							fields = form.getFields();
						}

						// prevent change events from firing
						form.suspendEvents();
						form.loadRecord(selectedRec);
						form.resumeEvents();*/
//						highlightCompanyPriceBar(selectedRec);
//					}
//				}
//			}
		});

		//----- Create stacked bar to be at the top -----//
		var chartPanel = Ext.create('Ext.chart.Chart', {
			height: 600,
			margin: '5 5 5 5',
			cls: 'x-panel-body-default',
			animate: true,
			shadow: true,
			store: chart_store,
			legend: {
				position: 'right',
				itemSpacing: 2
			},
			theme: 'myTheme',
			axes: [{
				type: 'Numeric',
				position: 'left',
				fields: ['BROKEN', 'BUBBLE', 'CHIP SCATTER', 'CHIPPING','COLD JOINT','DIRTY','DRY JOINT','EXTRA PART','FLOATING','FOREIGN MATERIAL','LAYBACK','MISSING',
						'NO SOLDER','OVER BONDING','PATTERN O/C','PATTERN S/C','POOR SOLDER','RESIST','RUSTY','SHIFTING','SLANTING','SLIP INSERT','SOLDER BALL','SOLDER SHORT','TOMBSTONE','WRONG PART','WRONG POLARITY','OTHERS','CLEANING BOARD'],
				grid: true
			}, {
				type: 'Category',
				position: 'bottom',
				fields: ['line'],
				label: {
					renderer: function(v) {
						return Ext.String.ellipsis(v, 20, false);
					},
					font: '10px Arial',
					rotate: {
						degrees: 270
					}
        		}
			}],
			series: [{
				type: 'column',
				axis: 'left',
				gutter: 80,
				highlightCfg: { fill: '#a2b5ca' },
				label: {
	             	contrast: true,
	             	display: 'insideStart',
	             	field: ['BROKEN', 'BUBBLE', 'CHIP SCATTER', 'CHIPPING','COLD JOINT','DIRTY','DRY JOINT','EXTRA PART','FLOATING','FOREIGN MATERIAL','LAYBACK','MISSING',
						'NO SOLDER','OVER BONDING','PATTERN O/C','PATTERN S/C','POOR SOLDER','RESIST','RUSTY','SHIFTING','SLANTING','SLIP INSERT','SOLDER BALL','SOLDER SHORT','TOMBSTONE','WRONG PART','WRONG POLARITY','OTHERS','CLEANING BOARD'],
							 //color: '#000',
	             	orientation: 'horizontal','text-anchor': 'top',
					renderer: function(value, label, storeItem, item, i, display, animate, index) {
	                	return String(value);
	            	}
         		},
				xField: 'line',
				yField: ['BROKEN', 'BUBBLE', 'CHIP SCATTER', 'CHIPPING','COLD JOINT','DIRTY','DRY JOINT','EXTRA PART','FLOATING','FOREIGN MATERIAL','LAYBACK','MISSING',
						'NO SOLDER','OVER BONDING','PATTERN O/C','PATTERN S/C','POOR SOLDER','RESIST','RUSTY','SHIFTING','SLANTING','SLIP INSERT','SOLDER BALL','SOLDER SHORT','TOMBSTONE','WRONG PART','WRONG POLARITY','OTHERS','CLEANING BOARD'],
				stacked: true
				// tips: {
					// trackMouse: true,
      				// height: 25,
					// renderer: function(storeItem, storeField, item) {
						// var x = chart_store.fields;
						// console.log(chartPanel.series.get(0));
						// var len = x.length;
						// for (var i = 0; i < len; i++) {
						//     var z = fields[i];
						//     //var fieldName = field.name;
						// }
						// //this.setTitle(storeItem.get('line')+' : '+String(item.value[1]));
						// this.setTitle(z.name);
					// }
				// }
				/*,
				listeners: {
					itemmouseup: function(item) {
						 var series = chartPanel.series.get(0);
						 gridPanel.getSelectionModel().select(Ext.Array.indexOf(series.items, item));
					}
				}*/
			}]
		});
		//-----------------------------------------------//

		/*
		 * Main Panel
		 */
		Ext.create('Ext.panel.Panel', {
			title		: 'Problem Category',
			//frame		: true,
			bodyPadding	: '0 15 5 0',
			//height		: 1000,
			renderTo	: 'section',
			fieldDefaults : {
				labelAlign	: 'left',
				msgTarget	: 'side'
			},

			layout	: {
				type	: 'vbox',
				align	: 'stretch'
			},
			items: [chartPanel,{
				xtype: 'container',
				layout: {type: 'hbox', align: 'stretch'},
				flex: 3,
				items: [gridPanel,/*{
					xtype: 'form',
					flex: 3,
					layout: {
						type: 'vbox',
						align:'stretch'
					},
					margin: '0 0 0 5',
					title: '',
					items: [],
					listeners: {
						// buffer so we don't refire while the user is still typing
						buffer: 200,
						change: function(field, newValue, oldValue, listener) {
							if (selectedRec && form) {
								if (newValue > field.maxValue) {
									field.setValue(field.maxValue);
								} else {
									if (form.isValid()) {
										form.updateRecord(selectedRec);
										updateRadarChart(selectedRec);
									}
								}
							}
						}
					}
				}*/]
			}],
			tbar: [
				{xtype:'tbspacer',width:10},
				{
					xtype               :'datefield',
					id                  : 'tgl_awal',
					name                : 'tgl_awal',
					fieldLabel          : 'TANGGAL',
					format							: 'Y-m-d',
					//afterLabelTextTpl   : required,
					labelSeparator      : ' ',
					allowBlank          : false,
					labelWidth					: 60,
					listeners           : {
						select  : function(){
							Ext.getCmp('tgl_akhir').enable();
						},
						change	: function(f,new_val) {
							Ext.getCmp('tgl_akhir').reset();
							var valdate = Ext.getCmp('tgl_awal').getValue();
							if( valdate == null ){
								Ext.getCmp('tgl_akhir').reset();
								Ext.getCmp('tgl_akhir').disable();
							}
							else{
								Ext.getCmp('tgl_akhir').setMinValue(Ext.getCmp('tgl_awal').getValue());
							}
						}
					}
				},{xtype:'label',text:'-'},
				{
					xtype               : 'datefield',
					id                  : 'tgl_akhir',
					name                : 'tgl_akhir',
					disabled            : true,
					format							: 'Y-m-d',
					//afterLabelTextTpl : required,
					labelSeparator      : ' ',
					allowBlank          : false,
					labelWidth          : 100,
					listeners			: {
						select	: function(){
							chart_store.proxy.setExtraParam('start_date',Ext.getCmp('tgl_awal').getValue());
							chart_store.proxy.setExtraParam('end_date',Ext.getCmp('tgl_akhir').getValue());
							chart_store.loadPage(1);
						}
					}
				}
			]
		});
	});
</script>