<div id="section">
<script>
	Ext.Loader.setConfig({enabled: true});
	Ext.Loader.setPath('Ext.ux', '../extjs-4.2.2/examples/ux/');

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
		Ext.define('Chart', {
			extend: 'Ext.data.Model',
			fields: ['line', 'Missing', 'Wrong Part', 'Wrong Polarity', 'Slanting', 'Shifting', 'Short', 'Dry Joint', 'Floating', 'Over Bonding', 'Others', 'Lay Back', 'Chip Scatter', 'Poor Soldier', 'Manual IC', 'Over Solder', 'Tailing', 'Foreign Material', 'Korosi(Akame/Red Eye)', 'Slip Mounting', 'Part Chipping']
		});

		var chart_store = Ext.create('Ext.data.Store', {
			model	: 'Chart',
			autoLoad: true,
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
				{ text 	: 'Line',width 	: 120, sortable : true, dataIndex 	: 'line' },
				{ text 	: 'Missing',width 	: 60, sortable 	: true, dataIndex 	: 'Missing',align: 'center', renderer: color },
				{ text 	: 'Wrong Part',width 	: 70, sortable 	: true, dataIndex 	: 'Wrong Part',align: 'center', renderer: color },
				{ text 	: 'Wrong Polarity',width 	: 95, sortable 	: true, dataIndex 	: 'Wrong Polarity',align: 'center', renderer: color },
				{ text 	: 'Slanting',width 	: 60, sortable 	: true, dataIndex 	: 'Slanting',align: 'center', renderer: color },
				{ text  : 'Shifting',width  	: 60, sortable 	: true, dataIndex		: 'Shifting',align: 'center', renderer: color },
				{ text  : 'Short',width   : 50, sortable 	: true, dataIndex 	: 'Short',align: 'center', renderer: color },
				{ text  : 'Dry Joint',width   : 60, sortable 	: true, dataIndex 	: 'Dry Joint',align: 'center', renderer: color },
				{ text  : 'Floating',width   : 60, sortable 	: true, dataIndex 	: 'Floating',align: 'center', renderer: color },
				{ text  : 'Over Bonding',width   : 85, sortable 	: true, dataIndex 	: 'Over Bonding',align: 'center', renderer: color },
				{ text  : 'Others',width   : 50, sortable 	: true, dataIndex 	: 'Others',align: 'center', renderer: color },
				{ text  : 'Lay Back',width   : 60, sortable 	: true, dataIndex 	: 'Lay Back',align: 'center', renderer: color },
				{ text  : 'Chip Scatter',width   : 85, sortable 	: true, dataIndex 	: 'Chip Scatter',align: 'center', renderer: color },
				{ text  : 'Poor Soldier',width   : 85, sortable 	: true, dataIndex 	: 'Poor Soldier',align: 'center', renderer: color },
				{ text  : 'Manual IC',width   : 70, sortable 	: true, dataIndex 	: 'Manual IC',align: 'center', renderer: color },
				{ text  : 'Over Solder',width   : 85, sortable 	: true, dataIndex 	: 'Over Solder',align: 'center', renderer: color },
				{ text  : 'Tailing',width   : 60, sortable 	: true, dataIndex 	: 'Tailing',align: 'center', renderer: color },
				{ text  : 'Foreign Material',width   : 120, sortable : true, dataIndex 	: 'Foreign Material',align: 'center', renderer: color },
				{ text  : 'Korosi(Akame/Red Eye)',width 	: 120, sortable : true, dataIndex   : 'Korosi(Akame/Red Eye)',align: 'center', renderer: color },
				{ text  : 'Slip Mounting',width   : 70, sortable 	: true, dataIndex 	: 'Slip Mounting',align: 'center', renderer: color },
				{ text  : 'Part Chipping',width   : 70, sortable 	: true, dataIndex 	: 'Part Chipping',align: 'center', renderer: color }
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
				fields: ['Missing','Wrong Part','Wrong Polarity','Slanting','Shifting','Short', 'Dry Joint', 'Floating', 'Over Bonding', 'Others', 'Lay Back', 'Chip Scatter', 'Poor Soldier', 'Manual IC', 'Over Solder', 'Tailing', 'Foreign Material', 'Korosi(Akame/Red Eye)', 'Slip Mounting', 'Part Chipping'],
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
            },
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
	             	field: ['Missing','Wrong Part','Wrong Polarity','Slanting','Shifting','Short', 'Dry Joint', 'Floating', 'Over Bonding', 'Others', 'Lay Back', 'Chip Scatter', 'Poor Soldier', 'Manual IC', 'Over Solder', 'Tailing', 'Foreign Material', 'Korosi(Akame/Red Eye)', 'Slip Mounting', 'Part Chipping'],
							 //color: '#000',
	             	orientation: 'horizontal','text-anchor': 'top',
					renderer: function(value, label, storeItem, item, i, display, animate, index) {
	                	return String(value);
	            	}
         		},
				xField: 'line',
				yField: ['Missing','Wrong Part','Wrong Polarity','Slanting','Shifting','Short', 'Dry Joint', 'Floating', 'Over Bonding', 'Others', 'Lay Back', 'Chip Scatter', 'Poor Soldier', 'Manual IC', 'Over Solder', 'Tailing', 'Foreign Material', 'Korosi(Akame/Red Eye)', 'Slip Mounting', 'Part Chipping'],
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
</div>
