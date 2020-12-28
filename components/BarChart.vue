<template>
	<div>
		<Chart :options="chartOptions" />
	</div>
</template>

<script>
import { Chart } from "highcharts-vue";
import moment from "moment";

export default {
	props: ["title", "tahun", "bulan"],
	components: { Chart },
	computed: {
		prosentaseRealisasi() {
			return ((this.totalRealisasi / this.totalProgram) * 100).toFixed(1);
		},
		prosentaseProses() {
			return ((this.totalProses / this.totalProgram) * 100).toFixed(1);
		},
	},
	data() {
		return {
			totalProgram: 0,
			totalRealisasi: 0,
			totalProses: 0,
			chartOptions: {
				chart: {
					type: "column",
					height: "440px",
				},
				title: {
					text: this.title,
				},
				subtitle: {
					text: moment().format("DD MMM YYYY"),
					style: {
						fontSize: "15px",
					},
				},
				legend: {
					margin: 30,
					itemStyle: {
						color: "blue",
						fontSize: "18px",
						fontWeight: "normal",
					},
				},
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
						pointPadding: 0,
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

			this.totalProgram = program.reduce((prev, curr) => prev + curr);
			this.totalRealisasi = realisasi.reduce((prev, curr) => prev + curr);
			this.totalProses = proses.reduce((prev, curr) => prev + curr);

			this.chartOptions.series = [
				{
					name: `Program (${this.totalProgram})`,
					color: "#79acee",
					data: program,
				},
				{
					name: `Realisasi (${this.totalRealisasi} / ${this.prosentaseRealisasi}%)`,
					color: "#5bfe79",
					data: realisasi,
				},
				{
					name: `Proses (${this.totalProses} / ${this.prosentaseProses}%)`,
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
