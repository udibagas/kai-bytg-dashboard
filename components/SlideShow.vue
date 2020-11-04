<template>
	<el-dialog
		fullscreen
		:visible.sync="show"
		:before-close="
			(done) => {
				$emit('close');
			}
		"
	>
		<div slot="title" style="background-color: #eaeceb" class="d-flex">
			<img src="/logo.jpeg" alt />
			<div
				style="font-size: 30px; line-height: 49px; font-weight: bold"
				class="flex-grow-1 text-center"
			>
				UPT BALAI YASA TEGAL
			</div>
		</div>
		<el-carousel height="calc(100vh - 210px)" trigger="click" :interval="30000">
			<el-carousel-item>
				<BarChart
					style="margin: calc((100vh - 210px - 440px) / 2) auto; width: 90%"
					:tahun="tahun"
					:bulan="bulan"
					:title="`Program dan Realisasi Bulan ${listBulan[bulan]} Tahun ${tahun}`"
				/>
			</el-carousel-item>
			<el-carousel-item>
				<BarChart
					style="margin: calc((100vh - 210px - 440px) / 2) auto; width: 90%"
					:tahun="tahun"
					:title="`Program dan Realisasi Tahun ${tahun} `"
				/>
			</el-carousel-item>
			<el-carousel-item
				v-for="(jp, i) in listJenisPekerjaan.filter(
					(p) => !!p.tampilkan_di_slide
				)"
				:key="`table${i}`"
			>
				<OrderTable :jp="jp" :bulan="bulan" :tahun="tahun" />
			</el-carousel-item>
			<el-carousel-item>
				<Tsgo :bulan="bulan" :tahun="tahun" :page="1" :size="10" />
			</el-carousel-item>
			<el-carousel-item>
				<Tsgo :bulan="bulan" :tahun="tahun" :page="2" :size="10" />
			</el-carousel-item>
		</el-carousel>
	</el-dialog>
</template>

<script>
import { mapState } from "vuex";

export default {
	props: ["show", "bulan", "tahun"],
	computed: {
		...mapState(["listBulan", "listJenisPekerjaan"]),
	},
	methods: {
		readableDate(date) {
			if (!date) return null;
			return moment(date).format("DD-MMM-YYYY");
		},
	},
};
</script>
