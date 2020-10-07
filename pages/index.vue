<template>
	<div style="height:calc(100vh - 100px)">
		<el-form inline>
			<el-form-item label="Tahun">
				<el-input type="number" v-model="tahun" placeholder="Tahun"></el-input>
			</el-form-item>
			<el-form-item label="Bulan">
				<el-select v-model="bulan" placeholder="Bulan">
					<el-option v-for="(m, i) in months" v-show="i > 0" :key="i" :value="i" :label="m"></el-option>
				</el-select>
			</el-form-item>
			<el-form-item>
				<el-button type="primary" @click="getData()">Tampilkan Data</el-button>
			</el-form-item>
		</el-form>
		<div class="d-flex flex-wrap justify-content-center align-items-center">
			<el-card
				class="m-2 flex-grow-1"
				style="width:250px"
				v-for="(report, index) in laporanBulanan"
				:key="index"
			>
				<div slot="header" class="text-center">{{report.jenis_sarana}}</div>
				<div style="width:150px;margin:auto;">
					<el-progress
						:width="150"
						type="circle"
						:percentage="Math.round(Number(report.realisasi) * 100 / Number(report.target))"
					></el-progress>
					<div class="mt-3 text-center text-bold">{{report.realisasi}} dari {{report.target}}</div>
				</div>
			</el-card>
		</div>
		<div class="d-flex flex-wrap justify-content-center align-items-center">
			<el-card class="m-2 flex-grow-1" style="width:250px">
				<div slot="header" class="text-center">PENCAPAIAN TARGET BULAN {{months[bulan].toUpperCase()}}</div>
				<div style="width:150px;margin:auto;">
					<el-progress
						:width="150"
						type="circle"
						:percentage="Math.round(totalTargetBulanan.realisasi * 100 / totalTargetBulanan.target)"
					></el-progress>
					<div
						class="mt-3 text-center text-bold"
					>{{totalTargetBulanan.realisasi}} dari {{totalTargetBulanan.target}}</div>
				</div>
			</el-card>
			<el-card class="m-2 flex-grow-1" style="width:250px">
				<div slot="header" class="text-center">PENCAPAIAN TARGET TAHUN {{tahun}}</div>
				<div style="width:150px;margin:auto;">
					<el-progress
						:width="150"
						type="circle"
						:percentage="Math.round(laporanTahunan.realisasi * 100 / laporanTahunan.target)"
					></el-progress>
					<div
						class="mt-3 text-center text-bold"
					>{{laporanTahunan.realisasi}} dari {{laporanTahunan.target}}</div>
				</div>
			</el-card>
		</div>

		<el-card class="mb-3" v-for="jk in listJenisPekerjaan" :key="jk.id" :bodyStyle="{padding: 0}">
			<div slot="header" class="text-center" style="font-size:18px;font-weight:bold;">{{jk.kode}}</div>
			<table class="table table-striped" style="margin:0">
				<thead>
					<tr>
						<th>No</th>
						<th>SARANA</th>
						<th>ORDER</th>
						<th>DIPO</th>
						<th>TGL MASUK</th>
						<th>TGL KELUAR</th>
						<th>%</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="(data, index) in tableData[jk.id]" :key="index">
						<td>{{index+1}}</td>
						<td>{{data.jenis_sarana}} {{data.nomor_sarana}}</td>
						<td>{{data.nomor}}</td>
						<td>{{data.dipo}}</td>
						<td>{{readableDate(data.tanggal_masuk)}}</td>
						<td>{{readableDate(data.tanggal_keluar)}}</td>
						<td>
							<el-progress :percentage="data.prosentase_pekerjaan"></el-progress>
						</td>
					</tr>
				</tbody>
			</table>
		</el-card>
	</div>
</template>

<script>
import { Chart } from "highcharts-vue";
import moment from "moment";
import { mapState } from "vuex";

export default {
	components: { highcharts: Chart },
	computed: {
		totalTargetBulanan() {
			const target = this.laporanBulanan.reduce(
				(total, current) => total + Number(current.target),
				0
			);

			const realisasi = this.laporanBulanan.reduce(
				(total, current) => total + Number(current.realisasi),
				0
			);

			return { target, realisasi };
		},
		...mapState(["listJenisPekerjaan"]),
	},
	data() {
		return {
			laporanBulanan: [],
			laporanTahunan: {
				target: 0,
				realisasi: 0,
			},
			bulan: new Date().getMonth() + 1,
			tahun: new Date().getFullYear(),
			jenisSarana: [],
			tableData: [],
			months: "-,Januari,Februari,Maret,April,Mei,Juni,Juli,Agustus,September,Oktober,November,Desember".split(
				","
			),
		};
	},
	methods: {
		bulanan() {
			this.$axios
				.get("/api/report/bulanan", {
					params: { tahun: this.tahun, bulan: this.bulan },
				})
				.then((r) => {
					this.laporanBulanan = r.data;
				});
		},
		tahunan() {
			this.$axios
				.get("/api/report/tahunan", {
					params: { tahun: this.tahun },
				})
				.then((r) => {
					this.laporanTahunan =
						r.data.length == 1 ? r.data[0] : { target: 0, realisasi: 0 };
				});
		},
		getOrderList(jenis_pekerjaan_id) {
			const params = {
				jenis_pekerjaan_id: [jenis_pekerjaan_id],
				tahun: this.tahun,
				bulan: this.bulan,
				pageSize: 1000,
				page: 1,
			};

			this.$axios.get("/api/order", { params }).then((r) => {
				this.tableData[jenis_pekerjaan_id] = r.data.data;
			});
		},
		getData() {
			this.bulanan();
			this.tahunan();
			this.listJenisPekerjaan.forEach((jp) => {
				this.getOrderList(jp.id);
			});
		},
		readableDate(date) {
			if (!date) return null;
			return moment(date).format("DD-MMM-YYYY");
		},
	},
	mounted() {
		this.getData();
	},
};
</script>
