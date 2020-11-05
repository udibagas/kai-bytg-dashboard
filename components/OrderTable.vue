<template>
	<table class="table table-striped">
		<thead>
			<tr>
				<th colspan="7" class="text-center bg-success text-white">
					{{ jp.kode }} - {{ jp.nama }}
				</th>
			</tr>
			<tr>
				<th>No</th>
				<th>SARANA</th>
				<th>ORDER</th>
				<th>DIPO</th>
				<th>TGL MASUK</th>
				<th>TGL KELUAR</th>
				<th v-if="jp.kode == 'PB'">%</th>
			</tr>
		</thead>
		<tbody>
			<tr v-for="(d, index) in tableData" :key="index">
				<td>{{ index + 1 }}</td>
				<td>{{ d.jenis_sarana }} {{ d.nomor_sarana }}</td>
				<td>{{ d.nomor }}</td>
				<td>{{ d.dipo }}</td>
				<td>{{ readableDate(d.tanggal_masuk) }}</td>
				<td>{{ readableDate(d.tanggal_keluar) }}</td>
				<td v-if="jp.kode == 'PB'">
					<el-progress :percentage="d.prosentase_pekerjaan"></el-progress>
				</td>
			</tr>
		</tbody>
	</table>
</template>

<script>
import moment from "moment";
import { mapState } from "vuex";
export default {
	props: ["jp", "tahun", "bulan"],
	watch: {
		bulan(v) {
			this.getData();
		},
		tahun(v) {
			this.getData();
		},
	},
	data() {
		return {
			tableData: [],
		};
	},
	methods: {
		readableDate(date) {
			if (!date) return null;
			return moment(date).format("DD-MMM-YYYY");
		},
		getData() {
			const params = {
				jenis_pekerjaan_id: [this.jp.id],
				status: this.jp.kode == "PB" ? null : [20], // kalau PB tampilkan semua, lainnya cuma yg sudah selesai
				tahun: this.tahun,
				bulan: this.bulan,
				per_page: 10000,
				page: 1,
				sort: "tanggal_masuk",
				order: "descending",
			};

			this.$axios
				.get("/api/order", { params })
				.then((r) => {
					this.tableData = r.data.data;
				})
				.catch((e) => (this.tableData = []));
		},
	},
	mounted() {
		this.getData();
	},
};
</script>
