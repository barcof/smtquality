<!doctype html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<title>IM Quality Report</title>
		<script>
			Ext.Loader.setConfig({enabled: true});
			Ext.Loader.setPath('Ext.ux', '../extjs-4.2.2/examples/ux/');
			
			Ext.onReady(function(){
			Ext.QuickTips.init();
				
			var required = '<span style="color:red;font-weight:bold" data-qtip="Required">*</span>';
				
			Ext.define('Ext.chart.theme.myTheme', {
				 extend: 'Ext.chart.theme.Base',
				 constructor: function(config) {
					 this.callParent([Ext.apply({
						 colors:['#f44336', '#e91e63', '#9c27b0', '#673ab7', '#3f51b5', '#2196f3', '#03a9f4', '#00bcd4', '#009688', '#4caf50', '#8bc34a', '#cddc39', '#ffeb3b', '#ffc107', '#ff9800', '#ff5722', '#795548', '#9e9e9e', '#607d8b', '#505050']
					 }, config)]);
				 }
			 });  	
			Ext.define('Chart', {
				extend: 'Ext.data.Model',
				fields: ['line','Missing','Wrong Part','Wrong Polarity','Slanting','Shifting','Short', 'Dry joint', 'Floating', 'Over Bonding', 'Others', 'Lay Back', 'Chip Scatter', 'Poor Soldier', 'Manual IC', 'Over Solder', 'Tailing', 'Foreign Material', 'Korosi(Akame/Red Eye)', 'Slip Mounting', 'Part Missing']
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
						// You may want to get panel reference dynamically
						chartPanel.mask("Please Wait...");
					},
					load: function(store, eOpts) {
						// You may want to get panel reference dynamically
						chartPanel.unmask();
					}
				}
			});
			
				var chartPanel = Ext.create('Ext.chart.Chart', {
					animate: true,
					shadow: true,
					store: chart_store,
					legend: {
						position: 'right'
					},
					theme: 'myTheme',
					axes: [{
						type: 'Numeric',
						position: 'bottom',
						fields: ['Missing','Wrong Part','Wrong Polarity','Slanting','Shifting','Short', 'Dry joint', 'Floating', 'Over Bonding', 'Others', 'Lay Back', 'Chip Scatter', 'Poor Soldier', 'Manual IC', 'Over Solder', 'Tailing', 'Foreign Material', 'Korosi(Akame/Red Eye)', 'Slip Mounting', 'Part Missing'],
						//title: 'SUM',
						grid: true
						//minimum: 0,
						//maximum: 200
					}, {
						type: 'Category',
						position: 'left',
						fields: ['line'],
						//title: 'Machine Name',
						//label: {
						//	rotate: {
						//		degrees: 60
						//	}
						//}
					}],
					series: [{
						type: 'bar',
						axis: 'bottom',
						gutter: 50,
						xField: 'line',
						yField: ['Missing','Wrong Part','Wrong Polarity','Slanting','Shifting','Short', 'Dry joint', 'Floating', 'Over Bonding', 'Others', 'Lay Back', 'Chip Scatter', 'Poor Soldier', 'Manual IC', 'Over Solder', 'Tailing', 'Foreign Material', 'Korosi(Akame/Red Eye)', 'Slip Mounting', 'Part Missing'],
						stacked: true,
						tips: {
							trackMouse: true,
							renderer: function(storeItem, item) {
								this.setTitle(storeItem.get('line')+' : '+String(item.value[1]));
								//this.update(storeItem.get('Missing','Wrong Part','Wrong Polarity','Slanting','Shifting','Short'));
								//this.setTitle('Total : '+ String(item.value[1]));
							}
						}
					}]
				});
			
				var panel = Ext.create('Ext.panel.Panel', {
					width		: '100%',
					height		: 640,
					renderTo	: 'section',
					layout		: 'fit',
					items		: chartPanel,
					tbar		: [
						{xtype:'tbspacer',width:10},
						{
							xtype               :'datefield',
							id                  : 'tgl_awal',
							name                : 'tgl_awal',
							fieldLabel          : 'TANGGAL',
							format				: 'Y-m-d',
							afterLabelTextTpl   : required,
							labelSeparator      : ' ',
							allowBlank          : false,
							labelWidth			: 60,
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
							format				: 'Y-m-d',
							afterLabelTextTpl   : required,
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
						/*,'-',
						{
							xtype	: 'button',
							text	: 'Refresh',
							scale	: 'medium'
						}*/
					]
				});
				
				
			});
		</script>
	</head>	
	<body>
		<div id="section">
 
		</div>
	</body>
</html>