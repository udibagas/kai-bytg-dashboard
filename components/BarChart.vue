<template>
	<div>
		<Chart :options="chartOptions" />
	</div>
</template>

<script>
import { Chart } from "highcharts-vue";

export default {
	props: ["data", "title", "tahun", "bulan"],
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

			this.$axios.get("/api/report/bulanan", { params }).then((r) => {
				this.chartOptions.xAxis.categories = r.data.map((d) => d.jenis_sarana);
				this.chartOptions.series = [
					{
						name: "Program",
						color: "#79acee",
						data: r.data.map((d) => Number(d.target)),
					},
					{
						name: "Realisasi",
						color: "#5bfe79",
						data: r.data.map((d) => Number(d.selesai)),
					},
					{
						name: "Proses",
						color: "#e55b00",
						data: r.data.map(
							(d) => Number(d.terdaftar) + Number(d.dalam_pengerjaan)
						),
					},
				];
			});
		},
		annualReport() {
			const params = { tahun: this.tahun };
			this.$axios.get("/api/annualReport", { params }).then((r) => {
				this.chartOptions.xAxis.categories = r.data.map((d) => d.bulan);
				this.chartOptions.series = [
					{
						name: "Program",
						color: "#79acee",
						data: r.data.map((d) => Number(d.program)),
					},
					{
						name: "Realisasi",
						color: "#5bfe79",
						data: r.data.map((d) => Number(d.realisasi)),
					},
					{
						name: "Proses",
						color: "#e55b00",
						data: r.data.map((d) => Number(d.proses)),
					},
				];
			});
		},
	},
	mounted() {
		if (this.bulan) {
			this.monthlyReport();
		} else {
			this.annualReport();
		}
	},
};
</script>
