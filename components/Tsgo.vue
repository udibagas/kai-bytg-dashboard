<template>
	<table class="table table-striped">
		<thead>
			<tr>
				<th colspan="7" class="text-center bg-danger text-white">
					TSGO - Tidak Siap Guna Operasi ({{ total }})
				</th>
			</tr>
			<tr>
				<th>No</th>
				<th>SARANA</th>
				<th>ORDER</th>
				<th>DIPO</th>
				<th>TGL MASUK</th>
				<th>KETERANGAN</th>
				<th>%</th>
			</tr>
		</thead>
		<tbody>
			<tr v-for="(d, index) in tableData" :key="index">
				<td>{{ index + 1 }}</td>
				<td>{{ d.jenis_sarana }} {{ d.nomor_sarana }}</td>
				<td>{{ d.nomor }}</td>
				<td>{{ d.dipo }}</td>
				<td>{{ readableDate(d.tanggal_masuk) }}</td>
				<td>{{ d.keterangan }}</td>
				<td>
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
	props: ["tahun", "bulan"],
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
			total: 0,
		};
	},
	methods: {
		readableDate(date) {
			if (!date) return null;
			return moment(date).format("DD-MMM-YYYY");
		},
		getData() {
			const params = {
				status: [10],
				tahun: this.tahun,
				bulan: this.bulan,
				per_page: 10000,
				page: 1,
				sort: "updated_at",
				order: "descending",
			};

			this.$axios
				.get("/api/order", { params })
				.then((r) => {
					this.tableData = r.data.data;
					this.total = r.data.meta.total;
				})
				.catch((e) => (this.tableData = []));
		},
	},
	mounted() {
		this.getData();
	},
};
</script>
