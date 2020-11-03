<template>
	<el-dialog
		fullscreen
		center
		:visible.sync="show"
		:before-close="
			(done) => {
				$emit('close');
			}
		"
	>
		<div slot="title" style="background-color: #eaeceb" class="py-3">
			<img src="/logo.jpeg" alt />
			<div style="font-size: 24px">UPT BALAI YASA TEGAL</div>
		</div>
		<el-carousel height="calc(100vh - 210px)" trigger="click" :interval="30000">
			<el-carousel-item>
				<BarChart
					style="margin: calc((100vh - 210px - 450px) / 2) auto; width: 90%"
					:tahun="tahun"
					:bulan="bulan"
					:title="`Program dan Realisasi Bulan ${listBulan[bulan]} Tahun ${tahun}`"
				/>
			</el-carousel-item>
			<el-carousel-item>
				<BarChart
					style="margin: calc((100vh - 210px - 450px) / 2) auto; width: 90%"
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
				<Tsgo :bulan="bulan" :tahun="tahun" />
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
