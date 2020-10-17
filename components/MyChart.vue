<template>
	<Chart :options="chartOptions" />
</template>

<script>
import { Chart } from "highcharts-vue";
export default {
	props: ["data", "title"],
	watch: {
		data(v) {
			this.chartOptions.series[0].data = v.filter((d) => d.y > 0);
		},
		title(v) {
			this.chartOptions.title.text = v;
		},
	},
	components: { Chart },
	data() {
		return {
			chartOptions: {
				chart: {
					plotBackgroundColor: null,
					plotBorderWidth: null,
					plotShadow: false,
					type: "pie",
				},
				title: {
					text: this.title,
				},
				tooltip: {
					pointFormat:
						"{series.name}: <b>{point.y} ({point.percentage:.1f}%)</b>",
					// pointFormat: "{series.name}: {point.y}",
				},
				accessibility: {
					point: {
						valueSuffix: "%",
					},
				},
				plotOptions: {
					pie: {
						allowPointSelect: true,
						cursor: "pointer",
						dataLabels: {
							enabled: true,
							format:
								"<b>{point.name}</b>:{point.y} ({point.percentage:.1f} %)",
						},
					},
				},
				series: [
					{
						name: "Jumlah",
						colorByPoint: true,
						data: this.data,
					},
				],
			},
		};
	},
};
</script>
