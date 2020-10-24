<template>
	<div>
		<Chart :options="chartOptions" />
	</div>
</template>

<script>
import { Chart } from "highcharts-vue";

export default {
	props: ["title", "tahun", "bulan"],
	components: { Chart },
	data() {
		return {
			chartOptions: {
				chart: {
					type: "column",
				},
				title: {
					text: this.title,
				},
				// subtitle: {
				// 	text: "",
				// },
				xAxis: {
					categories: [],
					crosshair: true,
				},
				yAxis: {
					min: 0,
					title: {
						text: "Jumlah",
					},
				},
				tooltip: {
					headerFormat:
						'<span style="font-size:10px">{point.key}</span><table>',
					pointFormat:
						'<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
						'<td style="padding:0"><b>{point.y}</b></td></tr>',
					footerFormat: "</table>",
					shared: true,
					useHTML: true,
				},
				plotOptions: {
					column: {
						pointPadding: 0.2,
						borderWidth: 0,
						dataLabels: {
							enabled: true,
						},
					},
				},
				credits: {
					enabled: false,
				},
				series: [],
			},
		};
	},
	methods: {
		monthlyReport() {
			const params = {
				tahun: this.tahun,
				bulan: this.bulan,
			};

			this.$axios.get("/api/monthlyReport", { params }).then((r) => {
				this.chartOptions.xAxis.categories = r.data.map((d) => {
					return d.jenis_sarana;
				});

				this.updateSeries(r.data);
			});
		},

		annualReport() {
			const params = { tahun: this.tahun };
			this.$axios.get("/api/annualReport", { params }).then((r) => {
				this.chartOptions.xAxis.categories = r.data.map((d) => d.bulan);
				this.updateSeries(r.data);
			});
		},

		updateSeries(data) {
			const program = data.map((d) => Number(d.program));
			const realisasi = data.map((d) => Number(d.realisasi));
			const proses = data.map((d) => Number(d.proses));

			this.chartOptions.series = [
				{
					name: `Program (${program.reduce((prev, curr) => prev + curr)})`,
					color: "#79acee",
					data: program,
				},
				{
					name: `Realisasi (${realisasi.reduce((prev, curr) => prev + curr)})`,
					color: "#5bfe79",
					data: realisasi,
				},
				{
					name: `Proses (${proses.reduce((prev, curr) => prev + curr)})`,
					color: "#e55b00",
					data: proses,
				},
			];
		},

		getData() {
			if (this.bulan) {
				this.monthlyReport();
			} else {
				this.annualReport();
			}
		},
	},
	mounted() {
		this.getData();
	},
	watch: {
		tahun(v) {
			this.getData();
		},
		bulan(v) {
			this.getData();
		},
		title(v) {
			this.chartOptions.title.text = v;
		},
	},
};
</script>
