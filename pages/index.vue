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
				<el-tab-pane name="tab99" label="TSGO - Tidak Siap Guna Operasi">
					<Tsgo
						:bulan="bulan"
						:tahun="tahun"
						:page="1"
						:size="10000"
						order="tanggal_masuk"
						sort="descending"
					/>
				</el-tab-pane>
			</el-tabs>
		</el-card>

		<SlideShow
			:show="showSlideShow"
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
		...mapState(["listJenisPekerjaan", "listBulan"]),
	},
	data() {
		return {
			showSlideShow: true,
			bulan: new Date().getMonth() + 1,
			tahun: new Date().getFullYear(),
		};
	},
};
</script>
