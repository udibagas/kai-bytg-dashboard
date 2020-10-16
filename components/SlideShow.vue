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
		<div slot="title" class="p-3 text-white bg-primary">
			PENCAPAIAN TARGET BULAN {{ listBulan[bulan].toUpperCase() }}
		</div>
		<el-carousel
			height="calc(100vh - 200px)"
			indicator-position="outside"
			trigger="click"
		>
			<el-carousel-item v-for="(r, index) in bulanan" :key="index">
				<MyChart :data="r.data" :title="r.jenis_sarana" />
			</el-carousel-item>
			<el-carousel-item>
				<MyChart
					:data="total.data"
					:title="`TOTAL BULAN ${listBulan[bulan].toUpperCase()}`"
				/>
			</el-carousel-item>
			<el-carousel-item>
				<MyChart :data="tahunan.data" :title="`TOTAL TAHUN ${tahun}`" />
			</el-carousel-item>
		</el-carousel>
	</el-dialog>
</template>

<script>
import { mapState } from "vuex";

export default {
	props: ["show", "bulanan", "total", "tahunan", "bulan", "tahun", "table"],
	computed: {
		...mapState(["listBulan"]),
	},
};
</script>

<style>
</style>
