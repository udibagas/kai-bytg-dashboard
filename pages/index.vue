<template>
	<div style="height: calc(100vh - 200px)">
		<el-card class="mb-3">
			<el-form inline>
				<el-form-item class="mb-0">
					<el-input
						type="number"
						v-model="tahun"
						placeholder="Tahun"
					></el-input>
				</el-form-item>
				<el-form-item class="mb-0">
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
				<el-form-item class="mb-0">
					<el-button type="primary" @click="showSlideShow = true"
						>Tampilkan Slide</el-button
					>
				</el-form-item>
			</el-form>
		</el-card>

		<el-card class="mb-3">
			<BarChart
				:tahun="tahun"
				:bulan="bulan"
				:title="`Program dan Realisasi Bulan ${listBulan[bulan]} Tahun ${tahun}`"
			/>
		</el-card>

		<el-card class="mb-3">
			<BarChart
				:tahun="tahun"
				:title="`Program dan Realisasi Tahun ${tahun} `"
			/>
		</el-card>

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
				data: this.parseData({ target, terdaftar, dalam_pengerjaan, selesai }),
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
						d.data = this.parseData(d);
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
					this.laporanTahunan.data = this.parseData(r.data);
				});
		},
		parseData(d) {
			return [
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
					y: d.target - d.dalam_pengerjaan - d.selesai - d.terdaftar,
					color: "gray",
				},
				// {
				// 	name: "Lebih",
				// 	y: d.selesai - d.target,
				// 	color: "red",
				// },
			].filter((d) => d.y > 0);
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
