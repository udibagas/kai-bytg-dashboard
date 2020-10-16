<template>
	<div style="height: calc(100vh - 200px)">
		<el-form inline>
			<el-form-item label="Tahun">
				<el-input type="number" v-model="tahun" placeholder="Tahun"></el-input>
			</el-form-item>
			<el-form-item label="Bulan">
				<el-select v-model="bulan" placeholder="Bulan">
					<el-option
						v-for="(m, i) in listBulan"
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
			<el-form-item>
				<el-button type="primary" @click="showSlideShow = true"
					>Tampilkan Slide</el-button
				>
			</el-form-item>
		</el-form>

		<div class="d-flex flex-wrap justify-content-center align-items-center">
			<div
				class="border flex-grow-1 p-3"
				style="width: 350px"
				v-for="(report, index) in laporanBulanan"
				:key="index"
			>
				<MyChart :data="report.data" :title="report.jenis_sarana" />
			</div>
			<div class="border flex-grow-1 p-3" style="width: 300px">
				<MyChart
					:data="totalBulanan.data"
					:title="`TOTAL BULAN ${listBulan[bulan].toUpperCase()}`"
				/>
			</div>
			<div class="border flex-grow-1 p-3" style="width: 300px">
				<MyChart :data="laporanTahunan.data" :title="`TOTAL TAHUN ${tahun}`" />
			</div>
		</div>

		<el-card class="mt-3">
			<el-tabs>
				<el-tab-pane
					v-for="(jp, i) in listJenisPekerjaan"
					:key="`table${i}`"
					:label="`${jp.kode} - ${jp.nama}`"
				>
					<OrderTable :jp="jp" :bulan="bulan" :tahun="tahun" />
				</el-tab-pane>
			</el-tabs>
		</el-card>

		<SlideShow
			:show="showSlideShow"
			:bulanan="laporanBulanan"
			:tahunan="laporanTahunan"
			:total="totalBulanan"
			:bulan="bulan"
			:tahun="tahun"
			:table="tableData"
			@close="showSlideShow = false"
		/>
	</div>
</template>

<script>
import moment from "moment";
import { mapState } from "vuex";

export default {
	computed: {
		totalBulanan() {
			const target = this.laporanBulanan.reduce(
				(total, current) => total + Number(current.target),
				0
			);

			const terdaftar = this.laporanBulanan.reduce(
				(total, current) => total + Number(current.terdaftar),
				0
			);

			const dalam_pengerjaan = this.laporanBulanan.reduce(
				(total, current) => total + Number(current.dalam_pengerjaan),
				0
			);

			const selesai = this.laporanBulanan.reduce(
				(total, current) => total + Number(current.selesai),
				0
			);

			const belum_masuk = target - terdaftar - dalam_pengerjaan - selesai;

			return {
				target,
				terdaftar,
				dalam_pengerjaan,
				selesai,
				data: [
					{
						name: "Terdaftar",
						y: terdaftar,
						color: "orange",
					},
					{
						name: "Dalam Pengerjaan",
						y: dalam_pengerjaan,
						color: "blue",
					},
					{
						name: "Selesai",
						y: selesai,
						sliced: true,
						selected: true,
						color: "green",
					},
					{
						name: "Belum Masuk",
						y: belum_masuk,
						color: "gray",
					},
				].filter((d) => d.y > 0),
			};
		},
		...mapState(["listJenisPekerjaan", "listBulan"]),
	},
	data() {
		return {
			laporanBulanan: [],
			showSlideShow: true,
			laporanTahunan: {
				target: 0,
				terdaftar: 0,
				dalam_pengerjaan: 0,
				selesai: 0,
			},
			bulan: new Date().getMonth() + 1,
			tahun: new Date().getFullYear(),
			jenisSarana: [],
			tableData: [],
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
						].filter((d) => d.y > 0);

						return d;
					});
				});
		},
		tahunan() {
			this.$axios
				.get("/api/report/tahunan", {
					params: { tahun: this.tahun },
				})
				.then((r) => {
					this.laporanTahunan = r.data;
					const belum_masuk =
						r.data.target -
						r.data.terdaftar -
						r.data.dalam_pengerjaan -
						r.data.selesai;
					this.laporanTahunan.data = [
						{
							name: "Terdaftar",
							y: r.data.terdaftar,
							color: "orange",
						},
						{
							name: "Dalam Pengerjaan",
							y: r.data.dalam_pengerjaan,
							color: "blue",
						},
						{
							name: "Selesai",
							y: r.data.selesai,
							sliced: true,
							selected: true,
							color: "green",
						},
						{
							name: "Belum Masuk",
							y: belum_masuk,
							color: "gray",
						},
					].filter((d) => d.y > 0);
				});
		},
		getData() {
			this.bulanan();
			this.tahunan();
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
