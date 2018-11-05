<!doctype html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<title>IM Quality Report</title>
		<script>
			Ext.Loader.setConfig({enabled: true});
			Ext.Loader.setPath('Ext.ux', '../framework/extjs-4.2.2/examples/ux/');
			
			Ext.onReady(function(){
			Ext.QuickTips.init();
			Ext.define('Ext.chart.theme.myTheme', {
				 extend: 'Ext.chart.theme.Base',
				 constructor: function(config) {
					 this.callParent([Ext.apply({
						 colors:['#f44336', '#e91e63', '#9c27b0', '#673ab7', '#3f51b5', '#2196f3', '#03a9f4', '#00bcd4', '#009688', '#4caf50', '#8bc34a', '#cddc39', '#ffeb3b', '#ffc107', '#ff9800', '#ff5722', '#795548', '#9e9e9e', '#607d8b', '#505050']
					 }, config)]);
				 }
			 });
			/*
			Ext.define('Chart', {
				extend: 'Ext.data.Model',
				fields: ['line','problem1']
			});
			
			var chart_store = Ext.create('Ext.data.Store', {
				model: 'Chart',
				autoLoad: false,
				data: [
					{ symptom1: 29, symptom2: 31, symptom3: 100, symptom4: 29, symptom5: 29, data: 'SMT1' },
					{ symptom1: 45, symptom2: 32, symptom3: 100, symptom4: 45, symptom5: 45, data: 'SMT2' },
					{ symptom1: 40, symptom2: 30, symptom3: 100, symptom4: 40, symptom5: 40, data: 'SMT3' },
					{ symptom1: 51, symptom2: 25, symptom3: 100, symptom4: 51, symptom5: 51, data: 'SMT4' },
					{ symptom1: 70, symptom2: 20, symptom3: 100, symptom4: 70, symptom5: 70, data: 'SMT5' }
					
				]
			});*/

			
			Ext.define('Chart', {
				extend: 'Ext.data.Model',
				fields: ['line','Missing','Wrong Part','Wrong Polarity','Slanting','Shifting','Short', 'Dry joint', 'Floating', 'Over Bonding', 'Others', 'Lay Back', 'Chip Scatter', 'Poor Soldier', 'Manual IC', 'Over Solder', 'Tailing', 'Foreign Material', 'Korosi(Akame/Red Eye)', 'Slip Mounting', 'Part Missing']
			});
			var itemperpage = 25;
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
				}
			});
			
				var chartPanel = Ext.create('Ext.chart.Chart', {
					animate: true,
					shadow: true,
					store: chart_store,
					theme: 'myTheme',
					legend: {
						position: 'left'
					},
					axes: [{
						type: 'Numeric',
						position: 'left',
						fields: ['Missing','Wrong Part','Wrong Polarity','Slanting','Shifting','Short', 'Dry joint', 'Floating', 'Over Bonding', 'Others', 'Lay Back', 'Chip Scatter', 'Poor Soldier', 'Manual IC', 'Over Solder', 'Tailing', 'Foreign Material', 'Korosi(Akame/Red Eye)', 'Slip Mounting', 'Part Missing'],
						//title: 'SUM',
						grid: true
						//minimum: 0,
						//maximum: 200
					}, {
						type: 'Category',
						position: 'bottom',
						fields: ['line'],
						//title: 'Machine Name',
						label: {
							rotate: {
								degrees: 50
							}
						}
					}],
					series: [{
						type: 'column',
						axis: 'left',
						gutter: 180,
						xField: 'line',
						yField: ['Missing','Wrong Part','Wrong Polarity','Slanting','Shifting','Short', 'Dry joint', 'Floating', 'Over Bonding', 'Others', 'Lay Back', 'Chip Scatter', 'Poor Soldier', 'Manual IC', 'Over Solder', 'Tailing', 'Foreign Material', 'Korosi(Akame/Red Eye)', 'Slip Mounting', 'Part Missing'],
						stacked: true,
						tips: {
							trackMouse: true,
							renderer: function(storeItem, item) {
								this.setTitle(storeItem.get('line')+' : '+String(item.value[1]));
								//this.setTitle(storeItem.get('line'));
								//this.update(storeItem.get('Missing','Wrong Part','Wrong Polarity','Slanting','Shifting','Short'));
							}
						}/*
						renderer: function(sprite, record, attr, index, store) {
							var color = [1][2];
							return Ext.apply(attr, {
								fill: color
							});
						}*/
					}]
				});
			
				var panel = Ext.create('Ext.form.Panel', {
					width		: '100%',
					height		: 650,
					renderTo	: 'section',
					layout		: 'fit',
					items		: chartPanel,
					tbar		: [
						{
							xtype	: 'button',
							text	: 'Refresh',
							scale	: 'medium'
						},'-',
						{
							xtype	: 'datefield',
							fieldLabel : 'Select Date',
							labelWidth	: 50
						}
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