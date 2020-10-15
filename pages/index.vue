<template>
	<div>
		<el-form inline>
			<el-form-item label="Tahun">
				<el-input type="number" v-model="tahun" placeholder="Tahun"></el-input>
			</el-form-item>
			<el-form-item label="Bulan">
				<el-select v-model="bulan" placeholder="Bulan">
					<el-option
						v-for="(m, i) in months"
						v-show="i > 0"
						:key="i"
						:value="i"
						:label="m"
					></el-option>
				</el-select>
			</el-form-item>
			<el-form-item>
				<el-button type="primary" @click="getData()">Tampilkan Data</el-button>
			</el-form-item>
		</el-form>

		<div class="d-flex flex-wrap justify-content-center align-items-center">
			<div
				class="border flex-grow-1 p-3"
				style="width: 300px"
				v-for="(report, index) in laporanBulanan"
				:key="index"
			>
				<MyChart :data="report.data" :title="report.jenis_sarana" />
			</div>
		</div>
	</div>
</template>

<script>
import moment from "moment";
import { mapState } from "vuex";

export default {
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
					this.laporanBulanan = r.data.map((d) => {
						d.data = [
							{
								name: "Terdaftar",
								y: d.terdaftar,
								color: "orange",
							},
							{
								name: "Dalam Pengerjaan",
								y: d.dalam_pengerjaan,
								color: "blue",
							},
							{
								name: "Selesai",
								y: d.selesai,
								sliced: true,
								selected: true,
								color: "green",
							},
							{
								name: "Belum Masuk",
								y: d.belum_masuk,
								color: "gray",
							},
						];

						return d;
					});

					console.log(this.laporanBulanan);
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
